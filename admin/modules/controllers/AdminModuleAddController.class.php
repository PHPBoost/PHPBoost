<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 06
 * @since       PHPBoost 3.0 - 2011 09 20
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      janus57 <janus57@janus57.fr>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModuleAddController extends DefaultAdminController
{
    /** @var HTMLForm */
    private $refresh_form;
    /** @var FormButtonDefaultSubmit */
    private $refresh_button;

    protected function get_template_to_use(): FileTemplate
    {
        return new FileTemplate('admin/modules/AdminModuleAddController.tpl');
    }

    public function execute(HTTPRequestCustom $request): AdminModulesDisplayResponse
    {
        $message_success = $message_warning = '';
        $modules_selected = $modules_success = 0;

        // ── Remote install (github / website) ──────────────────────────────
        $source = $request->get_poststring('remote_source', '');
        if ($source === 'github' || $source === 'website')
        {
            AppContext::get_session()->csrf_post_protect();
            $addon_ids = $request->get_postarray('addon_ids', []);
            foreach ($addon_ids as $raw_id)
            {
                $addon_id = preg_replace('/[^A-Za-z0-9_-]/', '', $raw_id);
                if (empty($addon_id)) continue;
                $modules_selected++;
                if ($source === 'github')
                    $result = $this->install_from_github(
                        $addon_id,
                        $request->get_poststring('gh_owner', ''),
                        $request->get_poststring('gh_repo',  ''),
                        $request->get_poststring('gh_dir',   '')
                    );
                else
                    $result = $this->install_from_website(
                        $addon_id,
                        $request->get_poststring('ws_url', ''),
                        $request->get_poststring('ws_dir', '')
                    );
                if ($result['success'])
                {
                    $modules_success++;
                    $message_success .= '<b>' . $addon_id . '</b> : ' . $result['msg'] . '<br />';
                }
                else
                    $message_warning .= '<b>' . $addon_id . '</b> : ' . $result['msg'] . '<br />';
            }
        }

        // ── Local install (server tab) ──────────────────────────────────────
        $module_number = 1;
        ClassLoader::generate_classlist(true);
        foreach ($this->get_modules_not_installed() as $name => $module)
        {
            if ($request->get_string('add-' . $module->get_id(), false) || ($request->get_string('add-selected-modules', false) && $request->get_value('add-checkbox-' . $module_number, 'off') == 'on'))
            {
                $modules_selected++;

                $result = $this->install_module($module->get_id());

                if ($result['success'])
                {
                    $modules_success++;
                    $message_success .= '<b>' . $module->get_configuration()->get_name() . '</b> : ' . $result['msg'] . '<br />';
                }
                else
                {
                    $message_warning .= '<b>' . $module->get_configuration()->get_name() . '</b> : ' . $result['msg'] . '<br />';
                }
            }

            $module_number++;
        }

        if ($modules_selected > 0 && $modules_selected == $modules_success)
        {
            $this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
        }
        else
        {
            if ($message_warning)
            {
                $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($message_warning, MessageHelper::WARNING, -1));
            }
            if ($message_success)
            {
                $this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($message_success, MessageHelper::SUCCESS, 10));
            }
        }

        $this->refresh_form();
        $this->upload_form();

        if ($this->submit_button->has_been_submited() && $this->form->validate())
        {
            $this->upload_module();
        }

        if ($this->refresh_button->has_been_submited())
        {
            AddonRemoteHelper::clear_all_index_caches();
            AddonRemoteHelper::build_addons_caches(true);
        }

        $this->build_view($request);

        $this->view->put_all([
            // 'REFRESH' => $this->refresh_form->display(),
            'CONTENT' => $this->form->display()
        ]);

        return new AdminModulesDisplayResponse($this->view, $this->lang['addon.modules.add']);
    }

    private function upload_form(): void
    {
        $form = new HTMLForm('upload_module', '', false);

        $fieldset = new FormFieldsetHTML('upload', '');
        $form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldFilePicker('file', MessageHelper::display(StringVars::replace_vars($this->lang['addon.upload.clue'], ['max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()),]), MessageHelper::QUESTION)->render(),
            ['class' => 'full-field', 'authorized_extensions' => 'gz|zip']
        ));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);

        $this->form = $form;
    }

    private function refresh_form(): void
    {
        $form = new HTMLForm('refresh_module', '', false);
        $form->set_css_class('refresh-form options');

        $this->refresh_button = new FormButtonDefaultSubmit('Refresh Caches Files');
        $form->add_button($this->refresh_button);

        $this->refresh_form = $form;
    }

    private function build_view(HTTPRequestCustom $request): void
    {
        $phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
        $modules_not_installed = $this->get_modules_not_installed();

        $module_order = [];
        $order = 1;
        foreach ($modules_not_installed as $module)
        {
            $module_order[$module->get_id()] = $order;
            $order++;
        }

        $grouped_modules = [];
        foreach ($modules_not_installed as $module)
        {
            $genre = $module->get_configuration()->get_genre();
            if (!isset($grouped_modules[$genre]))
            {
                $grouped_modules[$genre] = [];
            }
            $grouped_modules[$genre][] = $module;
        }

        ksort($grouped_modules);

        foreach ($grouped_modules as $genre => $modules)
        {
            $this->view->assign_block_vars('genres', [
                'GENRE_NAME' => $genre
            ]);

            foreach ($modules as $id => $module)
            {
                $configuration = $module->get_configuration();
                $author_email = $configuration->get_author_email();
                $author_website = $configuration->get_author_website();

                $fa_icon = $configuration->get_fa_icon();
                $hexa_icon = $configuration->get_hexa_icon();
                $thumbnail = new File(PATH_TO_ROOT . '/' . $module->get_id() . '/' . $module->get_id() . '.png');

                $this->view->assign_block_vars('genres.server_modules', [
                    'C_THUMBNAIL'          => $thumbnail->exists(),
                    'C_FA_ICON'           => !empty($fa_icon),
                    'C_HEXA_ICON'         => !empty($hexa_icon),
                    'C_AUTHOR_EMAIL'       => !empty($author_email),
                    'C_AUTHOR_WEBSITE'     => !empty($author_website),
                    'C_COMPATIBLE'         => $configuration->get_addon_type() == 'module' && $configuration->get_compatibility() == $phpboost_version,
                    'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'module',
                    'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,

                    'MODULE_NUMBER'  => $module_order[$module->get_id()],
                    'MODULE_ID'      => $module->get_id(),
                    'MODULE_NAME'    => TextHelper::ucfirst($configuration->get_name()),
                    'CREATION_DATE'  => $configuration->get_creation_date(),
                    'LAST_UPDATE'    => $configuration->get_last_update(),
                    'VERSION'        => $configuration->get_version(),
                    'AUTHOR'         => $configuration->get_author(),
                    'AUTHOR_EMAIL'   => $author_email,
                    'AUTHOR_WEBSITE' => $author_website,
                    'DESCRIPTION'    => $configuration->get_description(),
                    'COMPATIBILITY'  => $configuration->get_compatibility(),
                    'PHP_VERSION'    => $configuration->get_php_version(),
                    'FA_ICON'        => $fa_icon,
                    'HEXA_ICON'      => $hexa_icon,
                ]);
            }
        }

        $not_installed_modules_number = count($modules_not_installed);
        $this->view->put_all([
            'C_SEVERAL_MODULES_AVAILABLE' => $not_installed_modules_number > 1,
            'C_MODULE_AVAILABLE'          => $not_installed_modules_number > 0,

            'MODULES_NUMBER'              => $not_installed_modules_number,

            'U_AJAX_GITHUB_LIST'  => AdminModulesUrlBuilder::ajax_github_list()->rel(),
            'U_AJAX_WEBSITE_LIST' => AdminModulesUrlBuilder::ajax_website_list()->rel(),
            'U_AJAX_INSTALL'      => AdminModulesUrlBuilder::ajax_install()->rel(),
        ]);

        $installed_addon_ids = [];
        foreach (ModulesManager::get_installed_modules_map_sorted_by_localized_name() as $addon)
        {
            $installed_addon_ids[] = $addon->get_id();
        };

        // Populate github repos selector
        $addons_config = AddonsConfig::load();
        $github_repos  = $addons_config->get_modules_repo();

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

        // Populate website servers selector
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
            'U_CURRENT_PAGE'        => AdminModulesUrlBuilder::add_module()->rel(),
        ]);

        // ---- GitHub modules list — built server-side from the cached index ----
        $github_token = $addons_config->get_github_token();

        if (!empty($github_token))
        {
            if (!empty($active_gh_owner) && !empty($active_gh_repo))
            {
                $branch = AddonRemoteHelper::resolve_github_branch($active_gh_owner, $active_gh_repo, $phpboost_version, $github_token);
                $gh_index = AddonRemoteHelper::fetch_github_index_json(
                    $active_gh_owner, $active_gh_repo, $active_gh_dir,
                    $branch, $github_token, 'modules.json'
                );

                if (is_array($gh_index))
                {
                    $locale   = AppContext::get_current_user()->get_locale();
                    $path     = trim($active_gh_dir, '/');

                    $gh_grouped = [];
                    foreach ($gh_index as $entry)
                    {
                        if (!isset($entry['addon_type']) || $entry['addon_type'] !== 'module')
                            continue;

                        $addon_id = $entry['id'] ?? '';
                        if (empty($addon_id))
                            continue;

                        $name  = $this->resolve_locale_field($entry, 'name',        $locale, $addon_id);
                        $desc  = $this->resolve_locale_field($entry, 'description', $locale, '');
                        $genre = $this->resolve_locale_field($entry, 'genre',       $locale, '');

                        $thumbnail = !empty($entry['thumbnail']) ? $entry['thumbnail'] : '';

                        $compatible       = ($entry['compatibility'] ?? '') === $phpboost_version;
                        $addon_compatible = ($entry['addon_type'] ?? '') === 'module';

                        if (!isset($gh_grouped[$genre]))
                            $gh_grouped[$genre] = [];

                        $gh_grouped[$genre][] = [
                            'addon_id'         => $addon_id,
                            'name'             => TextHelper::ucfirst($name),
                            'genre'            => $genre,
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
                            'thumbnail'        => $thumbnail,
                            'compatible'       => $compatible,
                            'addon_compatible' => $addon_compatible,
                            'installed'        => ModulesManager::is_module_installed($addon_id),
                            'repo_url'         => 'https://github.com/' . $active_gh_owner . '/' . $active_gh_repo . '/tree/' . $branch . '/' . ($path !== '' ? $path . '/' : '') . $addon_id,
                        ];
                    }

                    ksort($gh_grouped);

                    $gh_module_number = 1;
                    $gh_total = array_sum(array_map('count', $gh_grouped));
                    $gh_addon_ids = [];

                    foreach ($gh_grouped as $genre => $gh_modules)
                    {
                        usort($gh_modules, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });

                        $displayed_modules = array_filter($gh_modules, function($m) use ($installed_addon_ids) {
                            return !in_array($m['addon_id'], $installed_addon_ids);
                        });

                        $has_modules_to_display = !empty($displayed_modules);

                        $this->view->assign_block_vars('github_genres', [
                            'C_DISPLAY_GH_GENRE' => $has_modules_to_display,
                            'GENRE_NAME' => $genre
                        ]);

                        foreach ($gh_modules as $m)
                        {
                            $this->view->assign_block_vars('github_genres.github_modules', [
                                'C_THUMBNAIL'          => !empty($m['thumbnail']),
                                'C_FA_ICON'            => !empty($m['fa_icon']),
                                'C_HEXA_ICON'          => !empty($m['hexa_icon']),
                                'C_AUTHOR_EMAIL'       => !empty($m['author_mail']),
                                'C_AUTHOR_WEBSITE'     => !empty($m['author_website']),
                                'C_COMPATIBLE'         => $m['compatible'] && $m['addon_compatible'],
                                'C_COMPATIBLE_ADDON'   => $m['addon_compatible'],
                                'C_COMPATIBLE_VERSION' => $m['compatible'],
                                'C_IS_INSTALLED'       => $m['installed'],

                                'MODULE_NUMBER'  => $gh_module_number,
                                'MODULE_ID'      => $m['addon_id'],
                                'MODULE_NAME'    => $m['name'],
                                'GENRE_NAME'     => $m['genre'],
                                'COMPATIBILITY'  => $m['compatibility'],
                                'VERSION'        => $m['version'],
                                'AUTHOR'         => $m['author'],
                                'AUTHOR_EMAIL'   => $m['author_mail'],
                                'AUTHOR_WEBSITE' => $m['author_website'],
                                'DESCRIPTION'    => $m['description'],
                                'CREATION_DATE'  => $m['creation_date'],
                                'LAST_UPDATE'    => $m['last_update'],
                                'PHP_VERSION'    => $m['php_version'],
                                'FA_ICON'        => $m['fa_icon'],
                                'HEXA_ICON'      => $m['hexa_icon'],
                                'THUMBNAIL_URL'  => $m['thumbnail'] ?? '',
                                'U_REPO'         => $m['repo_url'],
                            ]);
                            $gh_addon_ids[] = $m['addon_id'];
                            $gh_module_number++;
                        }
                    }

                    $this->view->put_all([
                        'C_ALL_GH_INSTALLED'       => count(array_diff($gh_addon_ids, $installed_addon_ids)) == 0,
                        'C_GITHUB_MODULES'         => $gh_total > 0,
                        'C_SEVERAL_GITHUB_MODULES' => $gh_total > 1,
                        'GITHUB_MODULES_NUMBER'    => $gh_total,
                    ]);
                }
                else
                {
                    $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['addon.github.bad.token'], MessageHelper::ERROR, -1));
                }
            }
        }
        elseif (AppContext::get_request()->get_is_localhost())
        {
            $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['addon.github.token.missing'], MessageHelper::ERROR, -1));
        }

        // ---- Website modules list — built server-side from the cached index ----
        if (!empty($active_ws_url))
        {
            $phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
            $locale = AppContext::get_current_user()->get_locale();

            list($base_url, $ws_index) = AddonRemoteHelper::fetch_website_index($active_ws_url, $active_ws_dir, $phpboost_version, 'modules');

            if (!empty($ws_index))
            {
                $ws_grouped = [];
                foreach ($ws_index as $item)
                {
                    $addon_id = $item['id'] ?? '';
                    if (empty($addon_id) || ($item['addon_type'] ?? '') !== 'module')
                        continue;

                    $name  = $this->resolve_locale_field($item, 'name',        $locale, $addon_id);
                    $genre = $this->resolve_locale_field($item, 'genre',       $locale, '');
                    $desc  = $this->resolve_locale_field($item, 'description', $locale, '');

                    $thumbnail = !empty($item['thumbnail']) ? $base_url . '/' . $addon_id . '/' . $item['thumbnail'] : '';
                    $fa_icon   = $item['fa_icon']  ?? '';
                    $hexa_icon = $item['hexa_icon'] ?? '';

                    $compatible       = ($item['compatibility'] ?? '') === $phpboost_version;
                    $addon_compatible = ($item['addon_type'] ?? '') === 'module';

                    if (!isset($ws_grouped[$genre]))
                        $ws_grouped[$genre] = [];

                    $ws_grouped[$genre][] = [
                        'addon_id'         => $addon_id,
                        'name'             => TextHelper::ucfirst($name),
                        'genre'            => $genre,
                        'compatibility'    => $item['compatibility'] ?? '',
                        'version'          => $item['version']       ?? '',
                        'author'           => $item['author']        ?? '',
                        'author_mail'      => $item['author_mail']   ?? '',
                        'author_website'   => $item['author_website'] ?? '',
                        'description'      => $desc,
                        'creation_date'    => $item['creation_date'] ?? '',
                        'last_update'      => $item['last_update']   ?? '',
                        'php_version'      => $item['php_version']   ?? '',
                        'fa_icon'          => $fa_icon,
                        'hexa_icon'        => $hexa_icon,
                        'thumbnail'        => $thumbnail,
                        'compatible'       => $compatible,
                        'addon_compatible' => $addon_compatible,
                        'installed'        => ModulesManager::is_module_installed($addon_id),
                    ];
                }

                ksort($ws_grouped);

                $ws_module_number = 1;
                $ws_total = array_sum(array_map('count', $ws_grouped));
                $ws_addon_ids = [];

                foreach ($ws_grouped as $genre => $ws_modules)
                {
                    usort($ws_modules, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });

                    $displayed_modules = array_filter($ws_modules, function($m) use ($installed_addon_ids) {
                        return !in_array($m['addon_id'], $installed_addon_ids);
                    });

                    $has_modules_to_display = !empty($displayed_modules);

                    $this->view->assign_block_vars('website_genres', [
                        'C_DISPLAY_WS_GENRE' => $has_modules_to_display,
                        'GENRE_NAME' => $genre
                    ]);

                    foreach ($ws_modules as $m)
                    {
                        $this->view->assign_block_vars('website_genres.website_modules', [
                            'C_THUMBNAIL'          => !empty($m['thumbnail']),
                            'C_FA_ICON'            => !empty($m['fa_icon']),
                            'C_HEXA_ICON'          => !empty($m['hexa_icon']),
                            'C_AUTHOR_EMAIL'       => !empty($m['author_mail']),
                            'C_AUTHOR_WEBSITE'     => !empty($m['author_website']),
                            'C_COMPATIBLE'         => $m['compatible'] && $m['addon_compatible'],
                            'C_COMPATIBLE_ADDON'   => $m['addon_compatible'],
                            'C_COMPATIBLE_VERSION' => $m['compatible'],
                            'C_IS_INSTALLED'       => $m['installed'],

                            'MODULE_NUMBER'  => $ws_module_number,
                            'MODULE_ID'      => $m['addon_id'],
                            'MODULE_NAME'    => $m['name'],
                            'GENRE_NAME'     => $m['genre'],
                            'COMPATIBILITY'  => $m['compatibility'],
                            'VERSION'        => $m['version'],
                            'AUTHOR'         => $m['author'],
                            'AUTHOR_EMAIL'   => $m['author_mail'],
                            'AUTHOR_WEBSITE' => $m['author_website'],
                            'DESCRIPTION'    => $m['description'],
                            'CREATION_DATE'  => $m['creation_date'],
                            'LAST_UPDATE'    => $m['last_update'],
                            'PHP_VERSION'    => $m['php_version'],
                            'FA_ICON'        => $m['fa_icon'],
                            'HEXA_ICON'      => $m['hexa_icon'],
                            'THUMBNAIL_URL'  => $m['thumbnail'],
                        ]);
                        $ws_addon_ids[] = $m['addon_id'];
                        $ws_module_number++;
                    }
                }

                $this->view->put_all([
                    'C_ALL_WS_INSTALLED'        => count(array_diff($ws_addon_ids, $installed_addon_ids)) == 0,
                    'C_WEBSITE_MODULES'         => $ws_total > 0,
                    'C_SEVERAL_WEBSITE_MODULES' => $ws_total > 1,
                    'WEBSITE_MODULES_NUMBER'    => $ws_total,
                ]);
            }
        }
    }

    private function get_modules_not_installed(): array
    {
        $modules_not_installed = [];

        $modules_folder = new Folder(PATH_TO_ROOT . '/modules');
        if ($modules_folder->exists())
        {
            foreach ($modules_folder->get_folders() as $folder)
            {
                $folder_name = $folder->get_name();
                if ($folder->get_files('/config\.ini/') && !ModulesManager::is_module_installed($folder_name))
                {
                    try {
                        $modules_not_installed[$folder_name] = new Module($folder_name);
                    } catch (IOException $ex) {
                        continue;
                    }
                }
            }
        }

        $root_folder = new Folder(PATH_TO_ROOT);
        foreach ($root_folder->get_folders() as $folder)
        {
            $folder_name = $folder->get_name();
            if (isset($modules_not_installed[$folder_name]) || $folder_name === 'modules')
                continue;

            if ($folder->get_files('/config\.ini/') && !ModulesManager::is_module_installed($folder_name))
            {
                try {
                    $modules_not_installed[$folder_name] = new Module($folder_name);
                } catch (IOException $ex) {
                    continue;
                }
            }
        }

        usort($modules_not_installed, [self::class, 'callback_sort_modules_by_name']);

        return $modules_not_installed;
    }


    /**
     * Resolves a multilingual field from the GitHub index JSON.
     * The field can be a simple string or an array ['fr' => ..., 'english' => ...].
     */
    private function resolve_locale_field(array $entry, string $field, string $locale, string $default): string
    {
        return AddonHelper::resolve_locale_field($entry, $field, $locale, $default);
    }

    private static function callback_sort_modules_by_name(Module $module1, Module $module2): int
    {
        if (TextHelper::strtolower($module1->get_configuration()->get_name()) > TextHelper::strtolower($module2->get_configuration()->get_name()))
        {
            return 1;
        }
        return -1;
    }

    private function install_from_github(string $addon_id, string $owner, string $repo, string $subdir): array
    {
        return AddonHelper::install_from_github(
            $addon_id, $owner, $repo, $subdir,
            PATH_TO_ROOT . '/modules/',
            'addon.modules.already.installed',
            [ModulesManager::class, 'is_module_installed'],
            function($id) { return $this->install_module($id); }
        );
    }

    private function install_from_website(string $addon_id, string $server_url, string $server_dir): array
    {
        return AddonHelper::install_from_website(
            $addon_id, $server_url, $server_dir, 'modules',
            PATH_TO_ROOT . '/modules/',
            'addon.modules.already.installed',
            [ModulesManager::class, 'is_module_installed'],
            function($id) { return $this->install_module($id); }
        );
    }

    private function install_module(string $module_id): array
    {
        switch (ModulesManager::install_module($module_id))
        {
            case ModulesManager::CONFIG_CONFLICT:
                return ['success' => false, 'msg' => $this->lang['addon.modules.config.conflict']];
            case ModulesManager::UNEXISTING_MODULE:
                return ['success' => false, 'msg' => $this->lang['warning.element.unexists']];
            case ModulesManager::MODULE_ALREADY_INSTALLED:
                return ['success' => false, 'msg' => $this->lang['addon.modules.already.installed']];
            case ModulesManager::PHP_VERSION_CONFLICT:
                return ['success' => false, 'msg' => $this->lang['warning.misfit.php']];
            case ModulesManager::PHPBOOST_VERSION_CONFLICT:
                return ['success' => false, 'msg' => $this->lang['warning.misfit.phpboost']];
            case ModulesManager::MODULE_INSTALLED:
            default:
                $module = ModulesManager::get_module($module_id);
                HooksService::execute_hook_typed_action('install', 'module', $module_id, array_merge(['title' => $module->get_configuration()->get_name(), 'url' => AdminModulesUrlBuilder::list_installed_modules()->rel()], $module->get_configuration()->get_properties()));
                return ['success' => true, 'msg' => $this->lang['warning.process.success']];
        }
    }

    private function upload_module(): void
    {
        $modules_folder = PATH_TO_ROOT . '/modules/';
        if (!is_writable($modules_folder))
            $is_writable = @chmod($modules_folder, 0755);
        else
            $is_writable = true;

        if ($is_writable)
        {
            $uploaded_file = $this->form->get_value('file');
            if ($uploaded_file !== null)
            {
                $upload = new Upload($modules_folder);
                $upload->disableContentCheck();
                if ($upload->file('upload_module_file', '`([A-Za-z0-9-_])+\.(gz|zip)+$`iu'))
                {
                    $archive = $modules_folder . $upload->get_filename();

                    if ($upload->get_extension() == 'gz')
                        $archive_content = $this->list_tar_gz_content($archive);
                    else
                        $archive_content = $this->list_zip_content($archive);

                    $module_name = TextHelper::substr($upload->get_filename(), 0, TextHelper::strpos($upload->get_filename(), '.'));
                    $valid_archive = true;
                    $archive_root_content = [];
                    $required_files = ['/config.ini', '/' . AppContext::get_current_user()->get_locale() . '/desc.ini/'];
                    $forbidden_files = ['theme/@import.css', 'admin-lang.php'];
                    foreach ($archive_content as $element)
                    {
                        if (TextHelper::strpos($element['filename'], $module_name) === 0)
                        {
                            $element['filename'] = str_replace($module_name . '/', '', $element['filename']);
                            $archive_root_content[0] = ['filename' => $module_name, 'folder' => 1];
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

                    if ($archive_root_content[0]['folder'] && empty($required_files) && $valid_archive)
                    {
                        $module_id = $archive_root_content[0]['filename'];
                        if (!ModulesManager::is_module_installed($module_id))
                        {
                            if ($upload->get_extension() == 'gz')
                            {
                                $this->extract_tar_gz($archive, $modules_folder);
                            }
                            else
                            {
                                $this->extract_zip($archive, $modules_folder);
                            }

                            $result = $this->install_module($module_id);

                            if ($result['success'])
                            {
                                $this->view->put('MESSAGE_HELPER_SUCCESS', MessageHelper::display($result['msg'], MessageHelper::SUCCESS, 10));
                            }
                            else
                            {
                                $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($result['msg'], MessageHelper::WARNING));
                            }
                        }
                        else
                        {
                            $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.element.already.exists'], MessageHelper::WARNING));
                        }
                    }
                    else
                    {
                        $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.invalid.archive.content'], MessageHelper::WARNING));
                    }

                    $uploaded_file = new File($archive);
                    $uploaded_file->delete();
                }
                else
                {
                    $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.file.invalid.format'], MessageHelper::WARNING));
                }
            }
            else
            {
                $this->view->put('MESSAGE_HELPER_WARNING', MessageHelper::display($this->lang['warning.file.upload.error'], MessageHelper::WARNING));
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