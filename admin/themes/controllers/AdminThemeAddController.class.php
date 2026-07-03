<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 06
 * @since       PHPBoost 3.0 - 2011 04 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemeAddController extends DefaultAdminController
{
    protected function get_template_to_use(): FileTemplate
    {
        return new FileTemplate('admin/themes/AdminThemeAddController.tpl');
    }

    public function execute(HTTPRequestCustom $request): AdminThemesDisplayResponse
    {
        $addons_selected = $addons_success = 0;

        // ── Remote install (github / website) ──────────────────────────────
        $source = $request->get_poststring('remote_source', '');
        if ($source === 'github' || $source === 'website')
        {
            AppContext::get_session()->csrf_post_protect();
            $addon_ids = $request->get_postarray('addon_ids', []);
            foreach ($addon_ids as $raw_id)
            {
                $addon_id = preg_replace('/[^A-Za-z0-9_-]/', '', $raw_id);
                if (empty($addon_id))
                    continue;
                $addons_selected++;
                if ($source === 'github')
                    $result = $this->install_theme_from_github(
                        $addon_id,
                        $request->get_poststring('gh_owner', ''),
                        $request->get_poststring('gh_repo',  ''),
                        $request->get_poststring('gh_dir',   '')
                    );
                else
                    $result = $this->install_theme_from_website(
                        $addon_id,
                        $request->get_poststring('ws_url', ''),
                        $request->get_poststring('ws_dir', '')
                    );
                if ($result)
                    $addons_success++;
            }
            if ($addons_selected > 0 && $addons_selected === $addons_success)
                $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
            elseif ($addons_selected > 0)
                $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.error'], MessageHelper::WARNING, -1));
        }

        // ── Local install (server tab) ──────────────────────────────────────
        $theme_number = 1;
        foreach ($this->get_not_installed_themes() as $theme)
        {
            if ($request->get_string('add-' . $theme->get_id(), false) || ($request->get_string('add-selected-themes', false) && $request->get_value('add-checkbox-' . $theme_number, 'off') == 'on'))
            {
                $authorizations = Authorizations::auth_array_simple(Theme::ACCES_THEME, $theme->get_id());
                $this->install_theme($theme->get_id(), $authorizations);
            }
            $theme_number++;
        }

        $this->upload_form();

        if ($this->submit_button->has_been_submited() && $this->form->validate())
            $this->upload_theme();

        $this->build_view($request);

        $this->view->put('UPLOAD_FORM', $this->form->display());

        return new AdminThemesDisplayResponse($this->view, $this->lang['addon.themes.add']);
    }

    private function build_view(HTTPRequestCustom $request): void
    {
        $phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
        $not_installed_themes = $this->get_not_installed_themes();
        $theme_number = 1;
        foreach ($not_installed_themes as $theme)
        {
            $configuration = $theme->get_configuration();
            $theme_has_parent = $configuration->get_parent_theme() != '' && $configuration->get_parent_theme() != '__default__';

            $author_email = $configuration->get_author_mail();
            $author_website = $configuration->get_author_link();
            $pictures = $configuration->get_pictures();

            $this->view->assign_block_vars('themes_not_installed',
            [
                'C_AUTHOR_EMAIL'       => !empty($author_email),
                'C_AUTHOR_WEBSITE'     => !empty($author_website),
                'C_COMPATIBLE'         => $configuration->get_addon_type() == 'theme' && $configuration->get_compatibility() == $phpboost_version && ($theme_has_parent ? ThemesManager::get_theme_existed($configuration->get_parent_theme()) : true),
                'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'theme',
                'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,
                'C_PARENT_THEME'       => $theme_has_parent,
                'C_PARENT_COMPATIBLE'  => $theme_has_parent ? ThemesManager::get_theme_existed($configuration->get_parent_theme()) : true,
                'C_THUMBNAIL'          => count($pictures) > 0,

                'ADDON_NUMBER'   => $theme_number,
                'ADDON_ID'       => $theme->get_id(),
                'ADDON_NAME'     => $configuration->get_name(),
                'CREATION_DATE'  => $configuration->get_creation_date(),
                'LAST_UPDATE'    => $configuration->get_last_update(),
                'VERSION'        => $configuration->get_version(),
                'AUTHOR'         => $configuration->get_author_name(),
                'AUTHOR_EMAIL'   => $author_email,
                'AUTHOR_WEBSITE' => $author_website,
                'DESCRIPTION'    => $configuration->get_description() !== '' ? $configuration->get_description() : $this->lang['common.unspecified'],
                'COMPATIBILITY'  => $configuration->get_compatibility(),
                'AUTHORIZATIONS' => Authorizations::generate_select(Theme::ACCES_THEME, ['r-1' => 1, 'r0' => 1, 'r1' => 1], [2 => true], $theme->get_id()),
                'HTML_VERSION'   => $configuration->get_html_version() !== '' ? $configuration->get_html_version() : $this->lang['common.unspecified'],
                'CSS_VERSION'    => $configuration->get_css_version() !== '' ? $configuration->get_css_version() : $this->lang['common.unspecified'],
                'MAIN_COLOR'     => $configuration->get_main_color() !== '' ? $configuration->get_main_color() : $this->lang['common.unspecified'],
                'WIDTH'          => $configuration->get_variable_width() ? $this->lang['addon.themes.variable.width'] : $configuration->get_width(),
                'PARENT_THEME'   => $theme_has_parent ? (ThemesManager::get_theme_existed($configuration->get_parent_theme()) ? ThemesManager::get_theme($configuration->get_parent_theme())->get_configuration()->get_name() : $configuration->get_parent_theme()) : '',

                'U_MAIN_THUMBNAIL'     => count($pictures) > 0 ? Url::to_rel('/templates/' . $theme->get_id() . '/' . current($pictures)) : '',
                'L_PARENT_COMPATIBLE'  => StringVars::replace_vars($this->lang['addon.themes.parent.theme.not.installed'], ['id_parent' => $configuration->get_parent_theme()]),
            ]);

            if (count($pictures) > 0)
            {
                unset($pictures[0]);
                foreach ($pictures as $picture)
                {
                    $url = '/templates/' . (!preg_match('/\/default\//', $picture) ? $theme->get_id() . '/' : '') . $picture;
                    $this->view->assign_block_vars('themes_not_installed.pictures',
                    [
                        'URL' => Url::to_rel($url)
                    ]);
                }
            }
            $theme_number++;
        }

        $not_installed_themes_number = count($not_installed_themes);
        $this->view->put_all([
            'C_SEVERAL_THEMES_AVAILABLE' => $not_installed_themes_number > 1,
            'C_THEME_AVAILABLE'          => $not_installed_themes_number > 0,
            'THEMES_NUMBER'              => $not_installed_themes_number
        ]);

        $installed_addon_ids = [];
        foreach (ThemesManager::get_installed_themes_map_sorted_by_localized_name() as $addon)
        {
            $installed_addon_ids[] = $addon->get_id();
        };


        // ── Defaults & active source (GitHub) ──
        $addons_config = AddonsConfig::load();
        $github_repos  = $addons_config->get_themes_repo();

        $default_gh_owner = isset($github_repos[0]['owner'])      ? $github_repos[0]['owner']      : '';
        $default_gh_repo  = isset($github_repos[0]['repository']) ? $github_repos[0]['repository'] : '';
        $default_gh_dir   = isset($github_repos[0]['directory'])  ? $github_repos[0]['directory']  : '';

        $active_gh_owner = $request->get_getstring('gh_owner', $default_gh_owner);
        $active_gh_repo  = $request->get_getstring('gh_repo',  $default_gh_repo);
        $active_gh_dir   = $request->get_getstring('gh_dir',   $default_gh_dir);

        foreach ($github_repos as $repo)
        {
            $this->view->assign_block_vars('github_repos', [
                'C_SELECTED' => $repo['owner'] === $active_gh_owner && $repo['repository'] === $active_gh_repo,
                'LABEL'      => $repo['owner'] . '/' . $repo['repository'],
                'OWNER'      => $repo['owner'],
                'REPO'       => $repo['repository'],
                'DIR'        => $repo['directory'],
            ]);
        }

        // ── Defaults & active source (Website) ──
        $website_servers = $addons_config->get_addons_server();

        $default_ws_url = isset($website_servers[0]['url'])       ? $website_servers[0]['url']       : '';
        $default_ws_dir = isset($website_servers[0]['directory']) ? $website_servers[0]['directory'] : '';

        $active_ws_url = $request->get_getstring('ws_url', $default_ws_url);
        $active_ws_dir = $request->get_getstring('ws_dir', $default_ws_dir);

        foreach ($website_servers as $server)
        {
            $this->view->assign_block_vars('website_servers', [
                'C_SELECTED' => $server['url'] === $active_ws_url,
                'LABEL'      => $server['website'] . ' (' . $server['url'] . ')',
                'URL'        => $server['url'],
                'DIR'        => $server['directory'],
            ]);
        }

        $this->view->put_all([
            'C_GITHUB_HAS_REPOS'    => count($github_repos) > 1,
            'GITHUB_DEFAULT_OWNER'  => $default_gh_owner,
            'GITHUB_DEFAULT_REPO'   => $default_gh_repo,
            'GITHUB_DEFAULT_DIR'    => $default_gh_dir,
            'GITHUB_ACTIVE_OWNER'   => $active_gh_owner,
            'GITHUB_ACTIVE_REPO'    => $active_gh_repo,
            'GITHUB_ACTIVE_DIR'     => $active_gh_dir,
            'C_WEBSITE_HAS_SERVERS' => count($website_servers) > 1,
            'WEBSITE_DEFAULT_URL'   => $default_ws_url,
            'WEBSITE_DEFAULT_DIR'   => $default_ws_dir,
            'WEBSITE_ACTIVE_URL'    => $active_ws_url,
            'WEBSITE_ACTIVE_DIR'    => $active_ws_dir,
            'U_AJAX_INSTALL'        => AdminThemeUrlBuilder::ajax_install()->rel(),
            'U_CURRENT_PAGE'        => AdminThemeUrlBuilder::add_theme()->rel(),
        ]);

        // ── GitHub list (server-side, from cache) ──
        $github_token = $addons_config->get_github_token();

        if (!empty($github_token))
        {
            if (!empty($active_gh_owner) && !empty($active_gh_repo))
            {
                $branch = AddonRemoteHelper::resolve_github_branch($active_gh_owner, $active_gh_repo, $phpboost_version, $github_token);
                $gh_index = AddonRemoteHelper::fetch_github_index_json(
                    $active_gh_owner, $active_gh_repo, $active_gh_dir,
                    $branch, $github_token, 'themes.json'
                );

                if (is_array($gh_index))
                {
                    $locale   = AppContext::get_current_user()->get_locale();
                    $path     = trim($active_gh_dir, '/');
                    $gh_grouped = [];

                    foreach ($gh_index as $entry)
                    {
                        if (($entry['addon_type'] ?? '') !== 'theme')
                            continue;
                        $addon_id = $entry['id'] ?? '';
                        if (empty($addon_id))
                            continue;

                        $name  = $this->resolve_locale_field($entry, 'name',        $locale, $addon_id);
                        $desc  = $this->resolve_locale_field($entry, 'description', $locale, '');

                        $compatible       = ($entry['compatibility'] ?? '') === $phpboost_version;
                        $addon_compatible = ($entry['addon_type'] ?? '') === 'theme';

                        $gh_grouped[] = [
                            'addon_id'         => $addon_id,
                            'name'             => TextHelper::ucfirst($name),
                            'compatibility'    => $entry['compatibility'] ?? '',
                            'version'          => $entry['version']       ?? '',
                            'author'           => $entry['author']        ?? '',
                            'author_mail'      => $entry['author_mail']   ?? '',
                            'author_website'   => $entry['author_website'] ?? '',
                            'description'      => $desc,
                            'creation_date'    => $entry['creation_date'] ?? '',
                            'last_update'      => $entry['last_update']   ?? '',
                            'php_version'      => $entry['php_version']   ?? '',
                            'fa_icon'          => $entry['fa_icon']   ?? '',
                            'hexa_icon'        => $entry['hexa_icon'] ?? '',
                            'thumbnail'        => $entry['thumbnail'] ?? '',
                            'compatible'       => $compatible,
                            'addon_compatible' => $addon_compatible,
                            'installed'        => ThemesManager::get_theme_existed($addon_id),
                            'repo_url'         => 'https://github.com/' . $active_gh_owner . '/' . $active_gh_repo . '/tree/' . $branch . '/' . ($path !== '' ? $path . '/' : '') . $addon_id,
                        ];
                    }

                    ksort($gh_grouped);
                    $gh_number = 1;
                    $gh_total  = array_sum(array_map('count', $gh_grouped));
                    $gh_addon_ids = [];

                    foreach ($gh_grouped as $m)
                    {
                        $this->view->assign_block_vars('github_addons', [
                            'C_THUMBNAIL'          => !empty($m['thumbnail']),
                            'C_FA_ICON'            => !empty($m['fa_icon']),
                            'C_HEXA_ICON'          => !empty($m['hexa_icon']),
                            'C_AUTHOR_EMAIL'       => !empty($m['author_mail']),
                            'C_AUTHOR_WEBSITE'     => !empty($m['author_website']),
                            'C_COMPATIBLE'         => $m['compatible'] && $m['addon_compatible'],
                            'C_COMPATIBLE_ADDON'   => $m['addon_compatible'],
                            'C_COMPATIBLE_VERSION' => $m['compatible'],
                            'C_IS_INSTALLED'       => $m['installed'],
                            'ADDON_NUMBER'   => $gh_number,
                            'ADDON_ID'       => $m['addon_id'],
                            'ADDON_NAME'     => $m['name'],
                            'COMPATIBILITY'  => $m['compatibility'],
                            'VERSION'        => $m['version'],
                            'AUTHOR'         => $m['author'],
                            'AUTHOR_EMAIL'   => $m['author_mail'],
                            'AUTHOR_WEBSITE' => $m['author_website'],
                            'DESCRIPTION'    => $m['description'],
                            'CREATION_DATE'  => $m['creation_date'],
                            'LAST_UPDATE'    => $m['last_update'],
                            'FA_ICON'        => $m['fa_icon'],
                            'HEXA_ICON'      => $m['hexa_icon'],
                            'THUMBNAIL_URL'  => 'https://raw.githubusercontent.com/' . $active_gh_owner . '/' . $active_gh_repo . '/' . $branch . '/' . $m['addon_id'] . '/' . $m['thumbnail'][0],
                            'U_REPO'         => $m['repo_url'],
                        ]);
                        $gh_addon_ids[] = $m['addon_id'];
                        $gh_number++;
                    }

                    $this->view->put_all([
                        'C_ALL_GH_INSTALLED'      => count(array_diff($gh_addon_ids, $installed_addon_ids)) == 0,
                        'C_GITHUB_ADDONS'         => $gh_total > 0,
                        'C_SEVERAL_GITHUB_ADDONS' => $gh_total > 1,
                        'GITHUB_ADDONS_NUMBER'    => $gh_total,
                    ]);
                }
            }
        }
        elseif (AppContext::get_request()->get_is_localhost())
        {
            $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['addon.github.token.missing'], MessageHelper::ERROR, -1));
        }

        // ── Website list (server-side, from cache) ──
        if (!empty($active_ws_url))
        {
            $locale = AppContext::get_current_user()->get_locale();
            list($base_url, $ws_index) = AddonRemoteHelper::fetch_website_index($active_ws_url, $active_ws_dir, $phpboost_version, 'themes');

            if (!empty($ws_index))
            {
                $ws_grouped = [];
                foreach ($ws_index as $item)
                {
                    $addon_id = $item['id'] ?? '';
                    if (empty($addon_id) || ($item['addon_type'] ?? '') !== 'theme')
                        continue;

                    $name  = $this->resolve_locale_field($item, 'name',        $locale, $addon_id);
                    $desc  = $this->resolve_locale_field($item, 'description', $locale, '');

                    $compatible       = ($item['compatibility'] ?? '') === $phpboost_version;
                    $addon_compatible = ($item['addon_type'] ?? '') === 'theme';

                    $ws_grouped[] = [
                        'addon_id'         => $addon_id,
                        'name'             => TextHelper::ucfirst($name),
                        'compatibility'    => $item['compatibility'] ?? '',
                        'version'          => $item['version']       ?? '',
                        'author'           => $item['author']        ?? '',
                        'author_mail'      => $item['author_mail']   ?? '',
                        'author_website'   => $item['author_website'] ?? '',
                        'description'      => $desc,
                        'creation_date'    => $item['creation_date'] ?? '',
                        'last_update'      => $item['last_update']   ?? '',
                        'thumbnail'        => $item['thumbnail'] ?? '',
                        'compatible'       => $compatible,
                        'addon_compatible' => $addon_compatible,
                        'installed'        => ThemesManager::get_theme_existed($addon_id),
                    ];
                }

                ksort($ws_grouped);
                $ws_number = 1;
                $ws_total  = array_sum(array_map('count', $ws_grouped));
                $ws_addon_ids = [];

                foreach ($ws_grouped as $m)
                {
                    $this->view->assign_block_vars('website_addons', [
                        'C_THUMBNAIL'          => !empty($m['thumbnail']),
                        'C_AUTHOR_EMAIL'       => !empty($m['author_mail']),
                        'C_AUTHOR_WEBSITE'     => !empty($m['author_website']),
                        'C_COMPATIBLE'         => $m['compatible'] && $m['addon_compatible'],
                        'C_COMPATIBLE_ADDON'   => $m['addon_compatible'],
                        'C_COMPATIBLE_VERSION' => $m['compatible'],
                        'C_IS_INSTALLED'       => $m['installed'],
                        'ADDON_NUMBER'   => $ws_number,
                        'ADDON_ID'       => $m['addon_id'],
                        'ADDON_NAME'     => $m['name'],
                        'COMPATIBILITY'  => $m['compatibility'],
                        'VERSION'        => $m['version'],
                        'AUTHOR'         => $m['author'],
                        'AUTHOR_EMAIL'   => $m['author_mail'],
                        'AUTHOR_WEBSITE' => $m['author_website'],
                        'DESCRIPTION'    => $m['description'],
                        'CREATION_DATE'  => $m['creation_date'],
                        'LAST_UPDATE'    => $m['last_update'],
                        'THUMBNAIL_URL'  => $base_url . '/' . $m['addon_id'] . '/' . $m['thumbnail'][0],
                    ]);
                    $ws_addon_ids[] = $m['addon_id'];
                    $ws_number++;
                }

                $this->view->put_all([
                    'C_ALL_WS_INSTALLED'       => count(array_diff($ws_addon_ids, $installed_addon_ids)) == 0,
                    'C_WEBSITE_ADDONS'         => $ws_total > 0,
                    'C_SEVERAL_WEBSITE_ADDONS' => $ws_total > 1,
                    'WEBSITE_ADDONS_NUMBER'    => $ws_total,
                ]);
            }
        }
    }

    private function get_not_installed_themes(): array
    {
        $themes_not_installed = [];
        $folder_containing_phpboost_themes = new Folder(PATH_TO_ROOT . '/templates/');
        foreach ($folder_containing_phpboost_themes->get_folders() as $folder)
        {
            $folder_name = $folder->get_name();
            if ($folder_name != '__default__' && $folder->get_files('/config\.ini/') && !ThemesManager::get_theme_existed($folder_name))
            {
                try {
                    $themes_not_installed[$folder_name] = new Theme($folder_name);
                } catch (IOException $ex) {
                    continue;
                }
            }
        }

        usort($themes_not_installed, [self::class, 'callback_sort_themes_by_name']);

        return $themes_not_installed;
    }

    private function resolve_locale_field(array $entry, string $field, string $locale, string $default): string
    {
        return AddonHelper::resolve_locale_field($entry, $field, $locale, $default);
    }

    private static function callback_sort_themes_by_name(Theme $theme1, Theme $theme2): int
    {
        if (TextHelper::strtolower($theme1->get_configuration()->get_name()) > TextHelper::strtolower($theme2->get_configuration()->get_name()))
        {
            return 1;
        }
        return -1;
    }

    private function install_theme_from_github(string $addon_id, string $owner, string $repo, string $subdir): bool
    {
        $result = AddonHelper::install_from_github(
            $addon_id, $owner, $repo, $subdir,
            PATH_TO_ROOT . '/templates/',
            'addon.themes.already.installed',
            [ThemesManager::class, 'get_theme_existed'],
            function($id) { $this->install_theme($id, ['r-1' => 1, 'r0' => 1, 'r1' => 1]); return ['success' => ThemesManager::get_error() === null, 'msg' => '']; }
        );
        return $result['success'];
    }

    private function install_theme_from_website(string $addon_id, string $server_url, string $server_dir): bool
    {
        $result = AddonHelper::install_from_website(
            $addon_id, $server_url, $server_dir, 'themes',
            PATH_TO_ROOT . '/templates/',
            'addon.themes.already.installed',
            [ThemesManager::class, 'get_theme_existed'],
            function($id) { $this->install_theme($id, ['r-1' => 1, 'r0' => 1, 'r1' => 1]); return ['success' => ThemesManager::get_error() === null, 'msg' => '']; }
        );
        return $result['success'];
    }

    private function install_theme(string $id_theme, array $authorizations = []): void
    {
        ThemesManager::install($id_theme, $authorizations);
        $error = ThemesManager::get_error();
        if ($error !== null)
        {
            $this->view->put('MESSAGE_HELPER', MessageHelper::display($error, MessageHelper::WARNING));
        }
        else
        {
            $theme = ThemesManager::get_theme($id_theme);
            HooksService::execute_hook_typed_action('install', 'theme', $id_theme, array_merge(['title' => $theme->get_configuration()->get_name(), 'url' => AdminThemeUrlBuilder::list_installed_theme()->rel()], $theme->get_configuration()->get_properties()));
            $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
        }
    }

    private function upload_form(): void
    {
        $form = new HTMLForm('upload_theme', '', false);

        $fieldset = new FormFieldsetHTML('upload', $this->lang['addon.themes.upload']);
        $form->add_fieldset($fieldset);

        $fieldset->set_description(MessageHelper::display($this->lang['addon.themes.warning.install'], MessageHelper::NOTICE)->render());

        $fieldset->add_field(new FormFieldFilePicker('file', MessageHelper::display(StringVars::replace_vars($this->lang['addon.upload.clue'], ['max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()), 'addon' => $this->lang['addon.themes.directory']]), MessageHelper::QUESTION)->render(),
        [
            'class' => 'full-field',
            'authorized_extensions' => 'gz|zip'
        ]));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);

        $this->form = $form;
    }

    private function upload_theme(): void
    {
        $folder_phpboost_themes = PATH_TO_ROOT . '/templates/';

        if (!is_writable($folder_phpboost_themes))
            $is_writable = @chmod($folder_phpboost_themes, 0755);
        else
            $is_writable = true;

        if ($is_writable)
        {
            $uploaded_file = $this->form->get_value('file');
            if ($uploaded_file !== null)
            {
                $upload = new Upload($folder_phpboost_themes);

                if ($upload->file('upload_theme_file', '`([A-Za-z0-9-_]+)\.(gz|zip)+$`iu'))
                {
                    $archive = $folder_phpboost_themes . $upload->get_filename();

                    if ($upload->get_extension() == 'gz')
                        $archive_content = $this->list_tar_gz_content($archive);
                    else
                        $archive_content = $this->list_zip_content($archive);

                    $theme_name = TextHelper::substr($upload->get_filename(), 0, TextHelper::strpos($upload->get_filename(), '.'));
                    $valid_archive = true;
                    $archive_root_content = [];
                    $required_files = ['/config.ini', '/theme/@import.css'];
                    $forbidden_files = ['index.php', 'admin-lang.php'];
                    foreach ($archive_content as $element)
                    {
                        if (TextHelper::strpos($element['filename'], $theme_name) === 0)
                        {
                            $element['filename'] = str_replace($theme_name . '/', '', $element['filename']);
                            $archive_root_content[0] = ['filename' => $theme_name, 'folder' => 1];
                        }
                        if (TextHelper::substr($element['filename'], -1) == '/')
                        {
                            $element['filename'] = TextHelper::substr($element['filename'], 0, -1);
                        }
                        if (TextHelper::substr_count($element['filename'], '/') == 0)
                        {
                            $archive_root_content[] = ['filename' => $element['filename'], 'folder' => ((isset($element['folder']) && $element['folder'] == 1) || (isset($element['typeflag']) && $element['typeflag'] == 5))];
                        }
                        if (isset($archive_root_content[0]))
                        {
                            $name_in_archive = str_replace($archive_root_content[0]['filename'] . '/', '/', $element['filename']);

                            if (in_array($name_in_archive, $required_files))
                            {
                                unset($required_files[array_search($name_in_archive, $required_files)]);
                            }
                            else if (in_array('/' . $name_in_archive, $required_files))
                            {
                                unset($required_files[array_search('/' . $name_in_archive, $required_files)]);
                            }

                            if (in_array($name_in_archive, $forbidden_files) || in_array('/' . $name_in_archive, $forbidden_files))
                            {
                                $valid_archive = false;
                            }
                        }
                    }

                    if (isset($archive_root_content[0]) && !empty($archive_root_content[0]) && $valid_archive)
                    {
                        if ($archive_root_content[0]['folder'] && empty($required_files))
                        {
                            $theme_id = $archive_root_content[0]['filename'];
                            if (!ThemesManager::get_theme_existed($theme_id))
                            {
                                if ($upload->get_extension() == 'gz')
                                {
                                    $this->extract_tar_gz($archive, $folder_phpboost_themes);
                                }
                                else
                                {
                                    $this->extract_zip($archive, $folder_phpboost_themes);
                                }

                                $this->install_theme($theme_id, ['r-1' => 1, 'r0' => 1, 'r1' => 1]);
                            }
                            else
                            {
                                $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.element.already.exists'], MessageHelper::WARNING));
                            }
                        }
                        else
                        {
                            $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.invalid.archive.content'], MessageHelper::WARNING));
                        }
                    }
                    else
                    {
                        $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.invalid.archive.content'], MessageHelper::WARNING));
                    }

                    $uploaded_file = new File($archive);
                    $uploaded_file->delete();
                }
                else
                {
                    $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.file.invalid.format'], MessageHelper::WARNING));
                }
            }
            else
            {
                $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.file.upload.error'], MessageHelper::WARNING));
            }
        }
    }

    private function list_zip_content(string $archive): array
    {
        return AddonHelper::list_zip_content($archive);
    }

    private function extract_zip(string $archive, string $destination): void
    {
        AddonHelper::extract_zip($archive, $destination);
    }

    private function list_tar_gz_content(string $archive): array
    {
        return AddonHelper::list_tar_gz_content($archive);
    }

    private function extract_tar_gz(string $archive, string $destination): void
    {
        AddonHelper::extract_tar_gz($archive, $destination);
    }

}