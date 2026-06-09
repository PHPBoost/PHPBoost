<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 06
 * @since       PHPBoost 3.0 - 2011 04 20
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      xela <xela@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminLangAddController extends DefaultAdminController
{
    protected function get_template_to_use(): FileTemplate
    {
        return new FileTemplate('admin/langs/AdminLangAddController.tpl');
    }

    public function execute(HTTPRequestCustom $request): AdminLangsDisplayResponse
    {
        // ── Remote install (github / website) ──────────────────────────────
        $source = $request->get_poststring('remote_source', '');
        if ($source === 'github' || $source === 'website')
        {
            AppContext::get_session()->csrf_post_protect();
            $addon_ids = $request->get_postarray('addon_ids', []);
            $selected = $success = 0;
            foreach ($addon_ids as $raw_id)
            {
                $addon_id = preg_replace('/[^A-Za-z0-9_-]/', '', $raw_id);
                if (empty($addon_id))
                    continue;
                $selected++;
                if ($source === 'github')
                    $ok = $this->install_lang_from_github(
                        $addon_id,
                        $request->get_poststring('gh_owner', ''),
                        $request->get_poststring('gh_repo',  ''),
                        $request->get_poststring('gh_dir',   '')
                    );
                else
                    $ok = $this->install_lang_from_website(
                        $addon_id,
                        $request->get_poststring('ws_url', ''),
                        $request->get_poststring('ws_dir', '')
                    );
                if ($ok) $success++;
            }
            if ($selected > 0 && $selected === $success)
                $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
            elseif ($selected > 0)
                $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.error'], MessageHelper::WARNING, -1));
        }

        $this->save($request);
        $this->upload_form();

        if ($this->submit_button->has_been_submited() && $this->form->validate())
            $this->upload();

        $this->build_view($request);

        $this->view->put('CONTENT', $this->form->display());

        return new AdminLangsDisplayResponse($this->view, $this->lang['addon.langs.add']);
    }

    private function build_view(HTTPRequestCustom $request): void
    {
        $phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
        $not_installed_langs = $this->get_not_installed_langs();
        $lang_number = 1;
        foreach ($not_installed_langs as $lang)
        {
            $configuration = $lang->get_configuration();
            $author_email = $configuration->get_author_mail();
            $author_website = $configuration->get_author_link();

            $this->view->assign_block_vars('langs_not_installed', [
                'C_AUTHOR_EMAIL'       => !empty($author_email),
                'C_AUTHOR_WEBSITE'     => !empty($author_website),
                'C_COMPATIBLE'         => $configuration->get_addon_type() == 'lang' && $configuration->get_compatibility() == $phpboost_version,
                'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'lang',
                'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,
                'C_HAS_THUMBNAIL'      => $configuration->has_picture(),

                'LANG_NUMBER'    => $lang_number,
                'ID'             => $lang->get_id(),
                'NAME'           => $configuration->get_name(),
                'VERSION'        => $configuration->get_version(),
                'AUTHOR'         => $configuration->get_author_name(),
                'AUTHOR_EMAIL'   => $author_email,
                'AUTHOR_WEBSITE' => $author_website,
                'COMPATIBILITY'  => $configuration->get_compatibility(),
                'AUTHORIZATIONS' => Authorizations::generate_select(Lang::ACCES_LANG, ['r-1' => 1, 'r0' => 1, 'r1' => 1], [2 => true], $lang->get_id()),

                'U_THUMBNAIL' => $configuration->get_picture_url()->rel(),
            ]);
            $lang_number++;
        }
        $not_installed_langs_number = count($not_installed_langs);
        $this->view->put_all([
            'C_SEVERAL_LANGS_AVAILABLE' => $not_installed_langs_number > 1,
            'C_LANG_AVAILABLE'          => $not_installed_langs_number > 0,

            'LANGS_NUMBER' => $not_installed_langs_number
        ]);

        // ── Defaults & active source (GitHub) ──
        $addons_config = AddonsConfig::load();
        $github_repos  = $addons_config->get_langs_repo();

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
            'U_AJAX_INSTALL'        => AdminLangsUrlBuilder::ajax_install()->rel(),
            'U_CURRENT_PAGE'        => AdminLangsUrlBuilder::install()->rel(),
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
                    $branch, $github_token, 'langs.json'
                );

                if (is_array($gh_index))
                {
                    $locale   = AppContext::get_current_user()->get_locale();
                    $raw_base = 'https://raw.githubusercontent.com/' . $active_gh_owner . '/' . $active_gh_repo . '/' . $branch . '/';
                    $path     = trim($active_gh_dir, '/');
                    $gh_grouped = [];

                    foreach ($gh_index as $entry)
                    {
                        if (($entry['addon_type'] ?? '') !== 'lang')
                            continue;
                        $addon_id = $entry['id'] ?? '';
                        if (empty($addon_id))
                            continue;

                        $compatible       = ($entry['compatibility'] ?? '') === $phpboost_version;
                        $addon_compatible = ($entry['addon_type'] ?? '') === 'lang';

                        $gh_grouped[] = [
                            'addon_id'         => $addon_id,
                            'name'             => $entry['name'] ?? '',
                            'compatibility'    => $entry['compatibility'] ?? '',
                            'version'          => $entry['version']       ?? '',
                            'author'           => $entry['author']        ?? '',
                            'author_mail'      => $entry['author_mail']   ?? '',
                            'author_website'   => $entry['author_website'] ?? '',
                            'creation_date'    => $entry['creation_date'] ?? '',
                            'last_update'      => $entry['last_update']   ?? '',
                            'php_version'      => $entry['php_version']   ?? '',
                            'identifier'       => $entry['identifier']   ?? '',
                            'compatible'       => $compatible,
                            'addon_compatible' => $addon_compatible,
                            'installed'        => LangsManager::get_lang_existed($addon_id),
                            'repo_url'         => 'https://github.com/' . $active_gh_owner . '/' . $active_gh_repo . '/tree/' . $branch . '/' . ($path !== '' ? $path . '/' : '') . $addon_id,
                        ];
                    }

                    ksort($gh_grouped);
                    $gh_number = 1;
                    $gh_total  = array_sum(array_map('count', $gh_grouped));

                    foreach ($gh_grouped as $m)
                    {
                        $this->view->assign_block_vars('github_addons', [
                            'C_HAS_IDENTIFIER'     => !empty($m['identifier']),
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
                            'CREATION_DATE'  => $m['creation_date'],
                            'LAST_UPDATE'    => $m['last_update'],
                            'U_REPO'         => $m['repo_url'],
                            'U_IDENTIFIER'   => TPL_PATH_TO_ROOT . '/images/stats/countries/' . $m['identifier'] . '.png',
                        ]);
                        $gh_number++;
                    }

                    $this->view->put_all([
                        'C_GITHUB_ADDONS'         => $gh_total > 0,
                        'C_SEVERAL_GITHUB_ADDONS' => $gh_total > 1,
                        'GITHUB_ADDONS_NUMBER'     => $gh_total,
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
            list($base_url, $ws_index) = AddonRemoteHelper::fetch_website_index($active_ws_url, $active_ws_dir, $phpboost_version, 'langs');

            if (!empty($ws_index))
            {
                $ws_grouped = [];
                foreach ($ws_index as $item)
                {
                    $addon_id = $item['id'] ?? '';
                    if (empty($addon_id) || ($item['addon_type'] ?? '') !== 'lang')
                        continue;

                    $compatible       = ($item['compatibility'] ?? '') === $phpboost_version;
                    $addon_compatible = ($item['addon_type'] ?? '') === 'lang';

                    $ws_grouped[] = [
                        'addon_id'         => $addon_id,
                        'name'             => $item['name'] ?? '',
                        'compatibility'    => $item['compatibility'] ?? '',
                        'version'          => $item['version']       ?? '',
                        'author'           => $item['author']        ?? '',
                        'author_mail'      => $item['author_mail']   ?? '',
                        'author_website'   => $item['author_website'] ?? '',
                        'creation_date'    => $item['creation_date'] ?? '',
                        'last_update'      => $item['last_update']   ?? '',
                        'identifier'       => $item['identifier']    ?? '',
                        'compatible'       => $compatible,
                        'addon_compatible' => $addon_compatible,
                        'installed'        => LangsManager::get_lang_existed($addon_id),
                    ];
                }

                ksort($ws_grouped);
                $ws_number = 1;
                $ws_total  = array_sum(array_map('count', $ws_grouped));

                foreach ($ws_grouped as $m)
                {
                    $this->view->assign_block_vars('website_addons', [
                        'C_HAS_IDENTIFIER'     => !empty($m['identifier']),
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
                        'CREATION_DATE'  => $m['creation_date'],
                        'LAST_UPDATE'    => $m['last_update'],
                        'U_IDENTIFIER'   => TPL_PATH_TO_ROOT . '/images/stats/countries/' . $m['identifier'] . '.png',
                    ]);
                    $ws_number++;
                }

                $this->view->put_all([
                    'C_WEBSITE_ADDONS'         => $ws_total > 0,
                    'C_SEVERAL_WEBSITE_ADDONS' => $ws_total > 1,
                    'WEBSITE_ADDONS_NUMBER'     => $ws_total,
                ]);
            }
        }
    }

    private function get_not_installed_langs(): array
    {
        $langs_not_installed = [];
        $folder_containing_phpboost_langs = new Folder(PATH_TO_ROOT . '/lang/');
        foreach ($folder_containing_phpboost_langs->get_folders() as $folder)
        {
            $folder_name = $folder->get_name();
            if ($folder->get_files('/config\.ini/') && !LangsManager::get_lang_existed($folder_name))
            {
                try {
                    $langs_not_installed[$folder_name] = new Lang($folder_name);
                } catch (IOException $ex) {
                    continue;
                }
            }
        }

        usort($langs_not_installed, [self::class, 'callback_sort_langs_by_name']);

        return $langs_not_installed;
    }

    private static function callback_sort_langs_by_name(Lang $lang1, Lang $lang2): int
    {
        if (TextHelper::strtolower($lang1->get_configuration()->get_name()) > TextHelper::strtolower($lang2->get_configuration()->get_name()))
        {
            return 1;
        }
        return -1;
    }

    private function save(HTTPRequestCustom $request): void
    {
        $lang_number = 1;
        foreach ($this->get_not_installed_langs() as $lang)
        {
            if ($request->get_string('add-' . $lang->get_id(), false) || ($request->get_string('add-selected-langs', false) && $request->get_value('add-checkbox-' . $lang_number, 'off') == 'on'))
            {
                $authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $lang->get_id());
                $this->install_lang($lang->get_id(), $authorizations);
            }
            $lang_number++;
        }
    }

    private function install_lang_from_github(string $addon_id, string $owner, string $repo, string $subdir): bool
    {
        $result = AddonHelper::install_from_github(
            $addon_id, $owner, $repo, $subdir,
            PATH_TO_ROOT . '/lang/',
            'addon.langs.already.installed',
            [LangsManager::class, 'get_lang_existed'],
            function($id) { $this->install_lang($id, ['r-1' => 1, 'r0' => 1, 'r1' => 1]); return ['success' => LangsManager::get_error() === null, 'msg' => '']; }
        );
        return $result['success'];
    }

    private function install_lang_from_website(string $addon_id, string $server_url, string $server_dir): bool
    {
        $result = AddonHelper::install_from_website(
            $addon_id, $server_url, $server_dir, 'langs',
            PATH_TO_ROOT . '/lang/',
            'addon.langs.already.installed',
            [LangsManager::class, 'get_lang_existed'],
            function($id) { $this->install_lang($id, ['r-1' => 1, 'r0' => 1, 'r1' => 1]); return ['success' => LangsManager::get_error() === null, 'msg' => '']; }
        );
        return $result['success'];
    }

    private function install_lang(string $id_lang, array $authorizations = []): void
    {
        LangsManager::install($id_lang, $authorizations);
        $error = LangsManager::get_error();
        if ($error !== null)
        {
            $this->view->put('MESSAGE_HELPER', MessageHelper::display($error, MessageHelper::WARNING, 10));
        }
        else
        {
            $lang = LangsManager::get_lang($id_lang);
            HooksService::execute_hook_typed_action('install', 'lang', $id_lang, array_merge(['title' => $lang->get_configuration()->get_name(), 'url' => AdminLangsUrlBuilder::list_installed_langs()->rel()], $lang->get_configuration()->get_properties()));
            $this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 10));
        }
    }

    private function upload_form(): void
    {
        $form = new HTMLForm('upload_lang', '', false);

        $fieldset = new FormFieldsetHTML('upload', $this->lang['addon.langs.upload']);
        $form->add_fieldset($fieldset);

        $fieldset->set_description(MessageHelper::display($this->lang['addon.langs.warning.install'], MessageHelper::NOTICE)->render());

        $fieldset->add_field(new FormFieldFilePicker('file', MessageHelper::display(StringVars::replace_vars($this->lang['addon.upload.clue'], ['max_size' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()), 'addon' => $this->lang['addon.langs.directory']]), MessageHelper::QUESTION)->render(),
            ['class' => 'full-field', 'authorized_extensions' => 'gz|zip']
        ));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);

        $this->form = $form;
    }

    private function upload(): void
    {
        $folder_phpboost_langs = PATH_TO_ROOT . '/lang/';
        if (!is_writable($folder_phpboost_langs))
            $is_writable = @chmod($folder_phpboost_langs, 0755);
        else
            $is_writable = true;

        if ($is_writable)
        {
            $uploaded_file = $this->form->get_value('file');
            if ($uploaded_file !== null)
            {
                $upload = new Upload($folder_phpboost_langs);
                $upload->disableContentCheck();
                if ($upload->file('upload_lang_file', '`([A-Za-z0-9-_])+\.(gz|zip)+$`iu'))
                {
                    $archive = $folder_phpboost_langs . $upload->get_filename();

                    if ($upload->get_extension() == 'gz')
                        $archive_content = $this->list_tar_gz_content($archive);
                    else
                        $archive_content = $this->list_zip_content($archive);

                    $lang_name = TextHelper::substr($upload->get_filename(), 0, TextHelper::strpos($upload->get_filename(), '.'));
                    $valid_archive = true;
                    $archive_root_content = [];
                    $required_files = ['/config.ini', '/admin-lang.php', '/addon-lang.php', '/common-lang.php'];
                    $forbidden_files = ['theme/@import.css', 'index.php'];
                    foreach ($archive_content as $element)
                    {
                        if (TextHelper::strpos($element['filename'], $lang_name) === 0)
                        {
                            $element['filename'] = str_replace($lang_name . '/', '', $element['filename']);
                            $archive_root_content[0] = ['filename' => $lang_name, 'folder' => 1];
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
                        $lang_id = $archive_root_content[0]['filename'];
                        if (!LangsManager::get_lang_existed($lang_id))
                        {
                            if ($upload->get_extension() == 'gz')
                            {
                                $this->extract_tar_gz($archive, $folder_phpboost_langs);
                            }
                            else
                            {
                                $this->extract_zip($archive, $folder_phpboost_langs);
                            }

                            $this->install_lang($lang_id, ['r-1' => 1, 'r0' => 1, 'r1' => 1]);
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