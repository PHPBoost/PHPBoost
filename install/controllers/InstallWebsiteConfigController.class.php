<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 08 27
 * @since       PHPBoost 3.0 - 2010 10 03
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class InstallWebsiteConfigController extends InstallController
{
    private Template $view;

    private HTMLForm $form;
    private FormButtonSubmit $submit_button;

    private SecurityConfig $security_config;
    private ServerConfiguration $server_configuration;

    private array $distribution_config;

    /** Remote modules list fetched from dl.phpboost.com  [addon_id => info_array] */
    private array $remote_modules = [];

    /** Remote themes list fetched from dl.phpboost.com  [addon_id => info_array] */
    private array $remote_themes = [];

    /** Base URL of the addons server */
    const ADDONS_SERVER_URL = 'https://dl.phpboost.com';

    public function execute(HTTPRequestCustom $request)
    {
        parent::load_lang($request);
        $this->init();
        $this->fetch_remote_addons();
        $this->build_form();
        if ($this->submit_button->has_been_submited() && $this->form->validate()) {
            $this->handle_form($request);
        }
        return $this->create_response();
    }

    private function init()
    {
        $this->security_config      = SecurityConfig::load();
        $this->server_configuration = new ServerConfiguration();
        $this->distribution_config  = parse_ini_file(PATH_TO_ROOT . '/install/distribution.ini');
    }

    // ── Detect distribution type ──────────────────────────────────────────────

    /**
     * Returns 'pdk', 'full', or 'normale' depending on the active distribution.
     * The pdk distribution is identified by module_home_page='bugtracker'.
     * The full distribution has module_home_page different from 'pages'.
     */
    private function get_distribution_type(): string
    {
        $home_page = $this->distribution_config['module_home_page'] ?? 'pages';

        $pdk_path = PATH_TO_ROOT . '/install/distribution/distribution_pdk.ini';
        if (file_exists($pdk_path)) {
            $pdk_config = parse_ini_file($pdk_path);
            if (($pdk_config['module_home_page'] ?? '') === $home_page && $home_page === 'bugtracker') {
                return 'pdk';
            }
        }

        if ($home_page !== 'pages') {
            return 'full';
        }

        return 'normale';
    }

    // ── Remote addons fetching ────────────────────────────────────────────────

    /**
     * Fetches the modules.json and themes.json index files directly from
     * dl.phpboost.com/{VERSION}/modules/modules.json (and themes/).
     * Falls back to /dev/ if the versioned folder is unavailable.
     * Works without any DB connection (no GeneralConfig round-trip inside the helper).
     */
    private function fetch_remote_addons(): void
    {
        // Determine version from the kernel default (works even before DB is set up)
        $version = $this->get_phpboost_version();

        $this->remote_modules = $this->fetch_addons_index($version, 'modules', 'modules.json');
        $this->remote_themes  = $this->fetch_addons_index($version, 'themes',  'themes.json');
    }

    /**
     * Returns the PHPBoost major version string (e.g. '6.1').
     * Reads from GeneralConfig defaults — safe during install even without a DB.
     */
    private function get_phpboost_version(): string
    {
        try {
            return GeneralConfig::load()->get_phpboost_major_version();
        } catch (Exception $e) {
            // Last resort: read from the KernelSetup default
            return '6.1';
        }
    }

    /**
     * Downloads and parses an addon index JSON file.
     * Tries versioned URL first, then /dev/ as fallback.
     *
     * @param  string $version      e.g. '6.1'
     * @param  string $addon_folder 'modules' or 'themes'
     * @param  string $index_file   'modules.json' or 'themes.json'
     * @return array  Associative array keyed by addon_id, or [] on failure.
     */
    private function fetch_addons_index(string $version, string $addon_folder, string $index_file): array
    {
        $base = rtrim(self::ADDONS_SERVER_URL, '/');

        $candidates = [
            $base . '/' . $version . '/' . $addon_folder . '/' . $index_file,
            $base . '/dev/'         . $addon_folder . '/' . $index_file,
        ];

        foreach ($candidates as $url) {
            $raw = $this->http_get($url);
            if ($raw === false || $raw === '') {
                continue;
            }

            $data = json_decode($raw, true);
            if (!is_array($data) || empty($data)) {
                continue;
            }

            // Build an associative array keyed by addon_id
            $result = [];
            foreach ($data as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $id = $item['id'] ?? '';
                if ($id !== '') {
                    $result[$id] = $item;
                }
            }
            return $result;
        }
        return [];
    }

    /**
     * Lightweight HTTP GET using cURL, with file_get_contents as fallback.
     * Returns the response body, or false on error.
     */
    private function http_get(string $url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,            $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS,      5);
            curl_setopt($ch, CURLOPT_TIMEOUT,        15);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_USERAGENT,      'PHPBoost-Installer/' . $this->get_phpboost_version());
            curl_setopt($ch, CURLOPT_HTTPHEADER,     ['Accept: application/json']);
            $body = curl_exec($ch);
            $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (\PHP_VERSION_ID < 80100)
                curl_close($ch);
            if ($body !== false && $http >= 200 && $http < 300) {
                return $body;
            }
            return false;
        }

        // Fallback: file_get_contents (requires allow_url_fopen)
        if (ini_get('allow_url_fopen')) {
            $ctx = stream_context_create(['http' => [
                'timeout'          => 15,
                'ignore_errors'    => false,
                'user_agent'       => 'PHPBoost-Installer/' . $this->get_phpboost_version(),
            ]]);
            $body = @file_get_contents($url, false, $ctx);
            return ($body !== false) ? $body : false;
        }

        return false;
    }

    // ── Default / mandatory module depending on distribution ──────────────────

    /**
     * Returns the module id that is mandatory (checked + non-deselectable).
     * - 'pdk' distribution  → 'bugtracker'
     * - other distributions → 'pages'
     */
    private function get_mandatory_module(): string
    {
        return $this->get_distribution_type() === 'pdk' ? 'bugtracker' : 'pages';
    }

    // ── Form building ─────────────────────────────────────────────────────────

    private function build_form()
    {
        $this->form = new HTMLForm('website_form', '', false);

        // Tabs start
        $tabs_start = new FormFieldsetCapsTop('tabs_start');
        $tabs_start->set_css_class('tabs-container');
        $this->form->add_fieldset($tabs_start);

        // Tabs menu
        $fieldset_tabs_menu = new TabsNavFieldset('tabs_menu', '');
        $this->form->add_fieldset($fieldset_tabs_menu);

        $tabs = [
            new TabsNavElement($this->lang['install.website.yours'],          'website_form_your_site'),
            new TabsNavElement($this->lang['install.website.modules'],        'website_form_modules_choice',  '', '', '', 'bgc visitor'),
            new TabsNavElement($this->lang['install.website.themes'],         'website_form_themes_choice',   '', '', '', 'bgc member'),
            new TabsNavElement($this->lang['user.security'],                  'website_form_security_config', '', '', '', 'bgc warning'),
            new TabsNavElement($this->lang['install.website.captcha.config'], 'website_form_captcha_config',  '', '', '', 'bgc moderator'),
        ];

        $fieldset_tabs_menu->add_field(new TabsNavList('tabs_menu_module', $tabs));

        // Tabs content wrapper
        $caps_wrapper_top = new FormFieldsetCapsTop('content_start');
        $caps_wrapper_top->set_css_class('tabs-wrapper');
        $this->form->add_fieldset($caps_wrapper_top);

        // Tab: Your site
        $fieldset = new TabsContentFieldset('your_site', $this->lang['install.website.yours']);
            $this->form->add_fieldset($fieldset);

            $host = new FormFieldUrlEditor('host', $this->lang['install.website.host'], $this->current_server_host(),
                [
                    'description' => $this->lang['install.website.host.clue'],
                    'required'    => $this->lang['install.website.host.required'],
                ]
            );
            $host->add_event('change', $this->warning_if_not_equals($host, $this->lang['install.website.host.warning']));
            $fieldset->add_field($host);

            $path = new FormFieldTextEditor('path', $this->lang['install.website.path'], $this->current_server_path(),
                ['description' => $this->lang['install.website.path.clue']]
            );
            $path->add_event('change', $this->warning_if_not_equals($path, $this->lang['install.website.path.warning']));
            $fieldset->add_field($path);

            $fieldset->add_field(new FormFieldTextEditor('name', $this->lang['install.website.name'], '',
                ['required' => $this->lang['install.website.name.required']]
            ));

            $fieldset->add_field(new FormFieldTextEditor('slogan', $this->lang['install.website.slogan'], ''));

            $fieldset->add_field(new FormFieldMultiLineTextEditor('description', $this->lang['install.website.description'], '',
                [
                    'class'       => 'three-quarter-field',
                    'description' => $this->lang['install.website.description.clue'],
                ]
            ));

            $fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['install.website.timezone'], 'Europe/Paris',
                ['description' => $this->lang['install.website.timezone.clue']]
            ));

        // Tab: Security
        $fieldset = new TabsContentFieldset('security_config', $this->lang['user.security']);
            $this->form->add_fieldset($fieldset);

            $fieldset->add_field(new FormFieldNumberEditor('internal_password_min_length', $this->lang['user.password.min.length'], $this->security_config->get_internal_password_min_length(),
                ['min' => 6, 'max' => 30],
                [new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(6, 30)]
            ));

            $fieldset->add_field(new FormFieldSimpleSelectChoice('internal_password_strength', $this->lang['user.password.strength'], $this->security_config->get_internal_password_strength(),
                [
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.weak'],        SecurityConfig::PASSWORD_STRENGTH_WEAK),
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.medium'],      SecurityConfig::PASSWORD_STRENGTH_MEDIUM),
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.strong'],      SecurityConfig::PASSWORD_STRENGTH_STRONG),
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.very.strong'], SecurityConfig::PASSWORD_STRENGTH_VERY_STRONG),
                ]
            ));

            $fieldset->add_field(new FormFieldCheckbox('login_and_email_forbidden_in_password', $this->lang['user.password.forbidden.tag'], $this->security_config->are_login_and_email_forbidden_in_password(),
                ['class' => 'custom-checkbox']
            ));

        // Tab: Captcha
        if ($this->distribution_config['default_captcha']) {
            $fieldset = new TabsContentFieldset('captcha_config', $this->lang['install.website.captcha.config']);
            $this->form->add_fieldset($fieldset);

            $default_captcha = $this->distribution_config['default_captcha'];
            $default_captcha::display_config_form_fields($fieldset, $this->locale);
        }

        // Tab: Modules
        $this->build_modules_tab();

        // Tab: Themes
        $this->build_themes_tab();

        // Close tabs wrapper
        $tabs_wrapper_bottom = new FormFieldsetCapsBottom('content_end');
        $this->form->add_fieldset($tabs_wrapper_bottom);

        $tabs_end = new FormFieldsetCapsBottom('tabs_end');
        $this->form->add_fieldset($tabs_end);

        // Navigation buttons
        $action_fieldset = new FormFieldsetSubmit('actions', ['css_class' => 'fieldset-submit next-step']);
        $back            = new FormButtonLinkCssImg($this->lang['common.previous'], InstallUrlBuilder::database(), 'fa fa-arrow-left');
        $action_fieldset->add_element($back);
        $this->submit_button = new FormButtonSubmitCssImg($this->lang['common.next'], 'fa fa-arrow-right', 'website');
        $action_fieldset->add_element($this->submit_button);
        $this->form->add_fieldset($action_fieldset);
    }

    private function build_modules_tab(): void
    {
        $mandatory_module = $this->get_mandatory_module();
        $distribution     = $this->get_distribution_type();

        $fieldset = new TabsContentFieldset('modules_choice', $this->lang['install.website.modules']);
        $this->form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldSpacer('default_modules', '<h4>' . $this->lang['install.website.default.modules'] . '</h4>'));

        // Mandatory module (pages or bugtracker) — always checked, not removable
        $mandatory_label = $this->get_module_label($mandatory_module, $this->remote_modules[$mandatory_module] ?? []);
        $fieldset->add_field(new FormFieldCheckbox(
            'module_' . $mandatory_module,
            $mandatory_label,
            true,
            [
                'disabled' => true,
                'class' => 'addon-checkbox',
                'description' => 'pages'
            ]
        ));
        // Hidden field guarantees the value is submitted even when the checkbox is disabled
        $fieldset->add_field(new FormFieldHidden('module_' . $mandatory_module . '_forced', '1'));

        // Optional: connect module (checked by default) — not for pdk
        if ($distribution == 'pdk') {
            $checked = ['connect', 'GoogleMaps', 'sandbox', 'sitemap'];
        } else {
            $checked = ['connect', 'GoogleMaps', 'search', 'sitemap', 'SocialNetworks', 'UrlUpdater'];
        }

        // Other optional remote modules
        $skip = [$mandatory_module, 'bbcode', 'qaptcha'];

        $grouped_modules = [];
        foreach ($this->remote_modules as $module)
        {
            $locale = LangLoader::get_locale();
            $genre = $module['genre'][$locale];
            if (!isset($grouped_modules[$genre]))
            {
                $grouped_modules[$genre] = [];
            }
            $grouped_modules[$genre][] = $module;
        }

        ksort($grouped_modules);

        foreach ($grouped_modules as $genre => $modules)
        {
            $fieldset->add_field(new FormFieldSpacer(Url::encode_rewrite($genre), '<h4>' . $genre . '</h4>'));

            foreach ($modules as $module)
            {
                if (in_array($module['id'], $skip, true)) {
                    continue;
                }
                $label = $this->get_module_label($module['id'], $module);
                $fieldset->add_field(new FormFieldCheckbox(
                    'module_' . $module['id'],
                    $label,
                    in_array($module['id'], $checked, true) ? true : false,
                    [
                        'class' => 'addon-checkbox',
                        'description' => $module['id']
                    ]
                ));
            }
        }

        // If the remote list could not be fetched, show an informational note
        if (empty($this->remote_modules)) {
            $fieldset->add_field(new FormFieldHTML('modules_unavailable',
                '<p class="message-helper bgc notice">' . $this->lang['install.website.modules.unavailable'] . '</p>'
            ));
        }
    }

    private function build_themes_tab(): void
    {
        $fieldset = new TabsContentFieldset('themes_choice', $this->lang['install.website.themes']);
        $this->form->add_fieldset($fieldset);

        // Mandatory theme: base — always present in archive, always selected
        $base_note = ' <em>(' . $this->lang['common.default'] . ')</em>';
        $fieldset->add_field(new FormFieldCheckbox(
            'theme_base',
            'Base' . $base_note,
            true,
            ['disabled' => true, 'class' => 'addon-checkbox']
        ));
        $fieldset->add_field(new FormFieldHidden('theme_base_forced', '1'));

        // Other optional remote themes
        foreach ($this->remote_themes as $theme_id => $theme_info) {
            if ($theme_id === 'base') {
                continue;
            }
            $label = $this->get_theme_label($theme_id, $theme_info);
            $fieldset->add_field(new FormFieldCheckbox(
                'theme_' . $theme_id,
                $label,
                false,
                [
                    'class' => 'addon-checkbox',
                    'description' => $theme_id
                ]
            ));
        }

        if (empty($this->remote_themes)) {
            $fieldset->add_field(new FormFieldHTML('themes_unavailable',
                '<p class="message-helper bgc notice">' . $this->lang['install.website.themes.unavailable'] . '</p>'
            ));
        }
    }

    /**
     * Builds a display label for a module, combining its name and a short description.
     * Falls back to the raw addon_id if no remote metadata is available.
     */
    private function get_module_label(string $addon_id, array $info = []): string
    {
        if (empty($info)) {
            return TextHelper::ucfirst($addon_id);
        }

        $locale = $this->locale ?: 'french';
        $name   = AddonHelper::resolve_locale_field($info, 'name',        $locale, $addon_id);
        $desc   = AddonHelper::resolve_locale_field($info, 'description', $locale, '');

        $label = '<span>' . TextHelper::ucfirst($name);
        if ($addon_id === 'pages')
        {
            $label .= ' <em>(' . $this->lang['common.required'] . ')</em>';
        }
        $label .= '</span>';
        if ($desc)
        {
            $label .= '&nbsp; <div class="d-inline-block">';
                $label .= '<span class="modal-button --infos-' . $addon_id . '"><i class="fa fa-fw fa-circle-info" aria-hidden="true"></i><span>';
                $label .= '<div id="infos-' . $addon_id . '" class="modal modal-half">';
                    $label .= '<div class="modal-overlay close-modal" aria-label="' . $this->lang['common.close'] . '"></div>';
                    $label .= '<div class="modal-content">';
                        $label .= '<span class="error big hide-modal close-modal" aria-label="' . $this->lang['common.close'] . '"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>';
                        $label .= '<h4>' .TextHelper::ucfirst($name) . '</h4>';
                        $label .= $desc;
                    $label .= '</div>';
                $label .= '</div>';
            $label .= '</div>';
        }
        return $label;
    }

    /**
     * Builds a display label for an theme, combining its name and a short description.
     * Falls back to the raw addon_id if no remote metadata is available.
     */
    private function get_theme_label(string $addon_id, array $info = []): string
    {
        if (empty($info)) {
            return TextHelper::ucfirst($addon_id);
        }

        $locale = $this->locale ?: 'french';
        $name   = AddonHelper::resolve_locale_field($info, 'name',        $locale, $addon_id);
        $desc   = AddonHelper::resolve_locale_field($info, 'description', $locale, '');
        $version = $this->get_phpboost_version();
        $url = self::ADDONS_SERVER_URL . '/' . $version . '/';

        // Check if the URL is valid AND server responds
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $headers = @get_headers($url);
            if ($headers && strpos($headers[0], '200') !== false) {
                $url = self::ADDONS_SERVER_URL . '/' . $version;
            }
            else {
                $url = self::ADDONS_SERVER_URL . '/dev';
            }
        }
        $thumbnail_path = $this->remote_themes[$addon_id]['thumbnail'][0];
        $thumbnail = new File($url . '/themes/' . $addon_id . '/' . $thumbnail_path);

        $label = '<span>' . TextHelper::ucfirst($name);
        if ($addon_id === 'pages')
        {
            $label .= ' <em>(' . $this->lang['common.required'] . ')</em>';
        }
        $label .= '</span>';
        if ($desc)
        {
            $label .= '&nbsp; <div class="d-inline-block">';
                $label .= '<span class="modal-button --infos-' . $addon_id . '"><i class="fa fa-fw fa-circle-info" aria-hidden="true"></i><span>';
                $label .= '<div id="infos-' . $addon_id . '" class="modal modal-half">';
                    $label .= '<div class="modal-overlay close-modal" aria-label="' . $this->lang['common.close'] . '"></div>';
                    $label .= '<div class="modal-content">';
                        $label .= '<span class="error big hide-modal close-modal" aria-label="' . $this->lang['common.close'] . '"><i class="far fa-circle-xmark" aria-hidden="true"></i></span>';
                        $label .= '<h4>' .TextHelper::ucfirst($name) . '</h4>';
                        $label .= $desc;
                        $label .= '<div><br><img src="' . $thumbnail->get_path() . '" alt=""></div>';
                    $label .= '</div>';
                $label .= '</div>';
            $label .= '</div>';
        }
        return $label;
    }

    /** Form handling  */
    private function handle_form(HTTPRequestCustom $request)
    {
        $mandatory_module  = $this->get_mandatory_module();
        $distribution      = $this->get_distribution_type();
        $selected_modules  = [$mandatory_module]; // always included

        // connect: optional, checked by default, only for normale & full
        if ($distribution !== 'pdk') {
            if ($this->form->has_field('module_connect') && $this->form->get_value('module_connect')) {
                $selected_modules[] = 'connect';
            }
        }

        // Other optional remote modules
        $skip = [$mandatory_module, 'connect'];
        foreach ($this->remote_modules as $module_id => $module_info) {
            if (in_array($module_id, $skip, true)) {
                continue;
            }
            $field_id = 'module_' . $module_id;
            if ($this->form->has_field($field_id) && $this->form->get_value($field_id)) {
                $selected_modules[] = $module_id;
            }
        }

        // Selected themes
        $selected_themes = ['base'];
        foreach ($this->remote_themes as $theme_id => $theme_info) {
            if ($theme_id === 'base') {
                continue;
            }
            $field_id = 'theme_' . $theme_id;
            if ($this->form->has_field($field_id) && $this->form->get_value($field_id)) {
                $selected_themes[] = $theme_id;
            }
        }

        $installation_services = new InstallationServices($this->locale);
        $installation_services->configure_website(
            $this->form->get_value('host'),
            $this->form->get_value('path'),
            $this->form->get_value('name'),
            $this->form->get_value('slogan'),
            $this->form->get_value('description'),
            $this->form->get_value('timezone')->get_raw_value(),
            $selected_modules,
            $selected_themes
        );

        $this->security_config->set_internal_password_min_length($this->form->get_value('internal_password_min_length'));
        $this->security_config->set_internal_password_strength($this->form->get_value('internal_password_strength')->get_raw_value());

        if ($this->form->get_value('login_and_email_forbidden_in_password')) {
            $this->security_config->forbid_login_and_email_in_password();
        } else {
            $this->security_config->allow_login_and_email_in_password();
        }

        SecurityConfig::save();

        if ($request->get_is_https()) {
            $server_environment_config = ServerEnvironmentConfig::load();
            $server_environment_config->enable_redirection_https();
            ServerEnvironmentConfig::save();
        }

        $default_captcha = $this->distribution_config['default_captcha'];
        if ($default_captcha) {
            $default_captcha::save_config($this->form);
        }

        AppContext::get_response()->redirect(InstallUrlBuilder::admin());
    }

    // ── Utilities ─────────────────────────────────────────────────────────────

    private function current_server_host()
    {
        return Appcontext::get_request()->get_site_url();
    }

    private function current_server_path()
    {
        $server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
        if (!$server_path) {
            $server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        }
        $server_path        = trim(preg_replace('`/install$`u', '', dirname($server_path)));
        return $server_path = ($server_path == '/') ? '' : $server_path;
    }

    private function warning_if_not_equals(FormField $field, $message)
    {
        $tpl = new StringTemplate('
            var field = $FF(${escapejs(ID)});
            var value = ${escapejs(VALUE)};
            if (field.getValue()!=value && !confirm(${escapejs(MESSAGE)})) {
                field.setValue(value);
            }
        ');
        $tpl->put('ID', $field->get_id());
        $tpl->put('VALUE', $field->get_value());
        $tpl->put('MESSAGE', $message);
        return $tpl->render();
    }

    /**
     * @return InstallDisplayResponse
     */
    private function create_response()
    {
        $this->view = new FileTemplate('install/website.tpl');
        $this->view->put('WEBSITE_FORM', $this->form->display());
        $step_title            = $this->lang['install.website.config.title'];
        $default_captcha       = $this->distribution_config['default_captcha'];
        $additional_stylesheet = $default_captcha ? $default_captcha::get_css_stylesheet() : '';
        $response              = new InstallDisplayResponse(4, $step_title, $this->lang, $this->view, $additional_stylesheet);
        return $response;
    }
}
