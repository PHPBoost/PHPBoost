<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 10 03
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class InstallWebsiteConfigController extends InstallController
{
    /**
     * @var Template
     */
    private $view;

    /**
     * @var HTMLForm
     */
    private $form;
    /**
     * @var FormButtonSubmit
     */
    private $submit_button;

    private $security_config;
    private $server_configuration;

    /**
     * @var mixed[string] Distribution configuration
     */
    private $distribution_config;

    public function execute(HTTPRequestCustom $request)
    {
        parent::load_lang($request);
        $this->init();
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

    private function build_form()
    {
        $this->form = new HTMLForm('websiteForm', '', false);
                // Tabs start
        $tabs_start = new FormFieldsetCapsTop('tabs_start');
        $tabs_start->set_css_class('tabs-container');
        $this->form->add_fieldset($tabs_start);

        // Tabs menu
		$fieldset_tabs_menu = new TabsNavFieldset('tabs_menu', '');
		$this->form->add_fieldset($fieldset_tabs_menu);

        $fieldset_tabs_menu->add_field(new TabsNavList('tabs_menu_module',
            [
                new TabsNavElement($this->lang['install.website.yours'], 'websiteForm_yourSite', ),
                new TabsNavElement($this->lang['user.security'], 'websiteForm_security_config', '', '', '', 'bgc warning'),
                new TabsNavElement($this->lang['install.website.captcha.config'], 'websiteForm_captcha_config', '', '', '', 'bgc moderator'),
            ]
        ));

        // Tabs content
        $caps_wrapper_top = new FormFieldsetCapsTop('content_start');
        $caps_wrapper_top->set_css_class('tabs-wrapper');
        $this->form->add_fieldset($caps_wrapper_top);

        $fieldset = new TabsContentFieldset('yourSite', $this->lang['install.website.yours']);
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
                    'class' => 'three-quarter-field',
                    'description' => $this->lang['install.website.description.clue']
                ]
            ));

            $fieldset->add_field(new FormFieldTimezone('timezone', $this->lang['install.website.timezone'], 'Europe/Paris',
                ['description' => $this->lang['install.website.timezone.clue']]
            ));

        $fieldset = new TabsContentFieldset('security_config', $this->lang['user.security']);
            $this->form->add_fieldset($fieldset);

            $fieldset->add_field(new FormFieldNumberEditor('internal_password_min_length', $this->lang['user.password.min.length'], $this->security_config->get_internal_password_min_length(),
                ['min' => 6, 'max' => 30],
                [new FormFieldConstraintRegex('`^[0-9]+$`iu'), new FormFieldConstraintIntegerRange(6, 30)]
            ));

            $fieldset->add_field(new FormFieldSimpleSelectChoice('internal_password_strength', $this->lang['user.password.strength'], $this->security_config->get_internal_password_strength(),
                [
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.weak'], SecurityConfig::PASSWORD_STRENGTH_WEAK),
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.medium'], SecurityConfig::PASSWORD_STRENGTH_MEDIUM),
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.strong'], SecurityConfig::PASSWORD_STRENGTH_STRONG),
                    new FormFieldSelectChoiceOption($this->lang['user.password.strength.very.strong'], SecurityConfig::PASSWORD_STRENGTH_VERY_STRONG),
                ]
            ));

            $fieldset->add_field(new FormFieldCheckbox('login_and_email_forbidden_in_password', $this->lang['user.password.forbidden.tag'], $this->security_config->are_login_and_email_forbidden_in_password(),
                ['class' => 'custom-checkbox']
            ));

        if ($this->distribution_config['default_captcha']) {
            $fieldset = new TabsContentFieldset('captcha_config', $this->lang['install.website.captcha.config']);
            $this->form->add_fieldset($fieldset);

            $default_captcha = $this->distribution_config['default_captcha'];
            $default_captcha::display_config_form_fields($fieldset, $this->locale);
        }

        $tabs_wrapper_bottom = new FormFieldsetCapsBottom('content_end');
        $this->form->add_fieldset($tabs_wrapper_bottom);

        $tabs_end = new FormFieldsetCapsBottom('tabs_end');
        $this->form->add_fieldset($tabs_end);

        $action_fieldset = new FormFieldsetSubmit('actions', ['css_class' => 'fieldset-submit next-step']);
        $back            = new FormButtonLinkCssImg($this->lang['common.previous'], InstallUrlBuilder::database(), 'fa fa-arrow-left');
        $action_fieldset->add_element($back);
        $this->submit_button = new FormButtonSubmitCssImg($this->lang['common.next'], 'fa fa-arrow-right', 'website');
        $action_fieldset->add_element($this->submit_button);
        $this->form->add_fieldset($action_fieldset);
    }

    private function handle_form(HTTPRequestCustom $request)
    {
        $installation_services = new InstallationServices($this->locale);
        $installation_services->configure_website(
            $this->form->get_value('host'), $this->form->get_value('path'),
            $this->form->get_value('name'), $this->form->get_value('slogan'), $this->form->get_value('description'),
            $this->form->get_value('timezone')->get_raw_value());

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
        $tpl = new StringTemplate('var field = $FF(${escapejs(ID)});
var value = ${escapejs(VALUE)};
if (field.getValue()!=value && !confirm(${escapejs(MESSAGE)})){field.setValue(value);}');
        $tpl->put('ID', $field->get_id());
        $tpl->put('VALUE', $field->get_value());
        $tpl->put('MESSAGE', $message);
        return $tpl->render();
    }

    /**
     * @param Template $view
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
