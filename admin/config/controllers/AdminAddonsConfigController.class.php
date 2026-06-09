<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 06 04
 * @since       PHPBoost 6.1 - 2026 03 07
*/

class AdminAddonsConfigController extends DefaultAdminController
{
	private $configuration;
    /** @var FormButtonDefaultSubmit */
    private $refresh_button;
    /** @var HTMLForm */
    private $refresh_form;

    public function execute(HTTPRequestCustom $request): AdminAddonsConfigDisplayResponse
    {
		$this->init();
		$this->build_form();
		$this->build_refresh_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
        }

		if ($this->refresh_button->has_been_submited() && $this->refresh_form->validate())
		{
            $cache_folder = new Folder(PATH_TO_ROOT . '/cache/addons');
			$this->delete_files($cache_folder, '`^\.|.*\.log|apc.php|debug.php`iu');
            AddonRemoteHelper::build_addons_caches(true);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 4));
        }

        $this->view = new StringTemplate('# INCLUDE CONTENT ## INCLUDE REFRESH_CONTENT #');
		$this->view->put('CONTENT', $this->form->display());
		$this->view->put('REFRESH_CONTENT', $this->refresh_form->display());

		return new AdminAddonsConfigDisplayResponse($this->view, $this->lang['form.configuration']);
    }

    public function init()
    {
        $this->configuration = AddonsConfig::load();
    }

    private function build_form()
    {
        $form = new HTMLForm(self::class);

        if (AppContext::get_request()->get_is_localhost())
        {
            $fieldset = new FormFieldsetHTML('github', $this->lang['addon.github.configuration']);
            $form->add_fieldset($fieldset);

            $fieldset->add_field(new FormFieldTextEditor('github_token', $this->lang['addon.github.token'], $this->configuration->get_github_token()));

            $fieldset->add_field(new FormFieldAddonsRepositories('modules_repos', $this->lang['addon.modules.repos.add'], $this->configuration->get_modules_repo(), 
                ['class' => 'full-field', 'addon_type' => 'modules']
            ));

            $fieldset->add_field(new FormFieldAddonsRepositories('themes_repos', $this->lang['addon.themes.repos.add'], $this->configuration->get_themes_repo(), 
                ['class' => 'full-field', 'addon_type' => 'themes']
            ));

            $fieldset->add_field(new FormFieldAddonsRepositories('langs_repos', $this->lang['addon.langs.repos.add'], $this->configuration->get_langs_repo(), 
                ['class' => 'full-field', 'addon_type' => 'langs']
            ));
        }

        $server_fieldset = new FormFieldsetHTML('addon_server', $this->lang['addon.servers.configuration']);
        $server_fieldset->set_description($this->lang['addon.servers.configuration.clue']);
        $form->add_fieldset($server_fieldset);

        $server_fieldset->add_field(new FormFieldAddonsServers('addons_server', $this->lang['addon.servers.add'], $this->configuration->get_addons_server(), 
            ['class' => 'full-field']
        ));

        $this->submit_button = new FormButtonDefaultSubmit();
        $form->add_button($this->submit_button);

        $this->form = $form;
    }

    private function build_refresh_form()
    {
        $form = new HTMLForm('refresh_addons_cache', '', false);
        $cache_fieldset = new FormFieldsetHTML('addon_cache', $this->lang['addon.caches.configuration']);
        $cache_fieldset->set_description($this->lang['addon.caches.configuration.clue']);
        $form->add_fieldset($cache_fieldset);
        $this->refresh_button = new FormButtonDefaultSubmit($this->lang['addon.refresh.cache']);
        $form->add_button($this->refresh_button);

        $this->refresh_form = $form;
    }

    private function save()
    {
        if (AppContext::get_request()->get_is_localhost())
        {
            $this->configuration->set_github_token($this->form->get_field_by_id('github_token')->get_value());
            $this->configuration->set_modules_repo($this->form->get_field_by_id('modules_repos')->get_value());
            $this->configuration->set_themes_repo($this->form->get_field_by_id('themes_repos')->get_value());
            $this->configuration->set_langs_repo($this->form->get_field_by_id('langs_repos')->get_value());
        }
        $this->configuration->set_addons_server($this->form->get_field_by_id('addons_server')->get_value());
        AddonsConfig::save();
    }

	private function delete_files(Folder $folder, $regex = '')
	{
		$files_to_delete = $folder->get_files($regex, true);
		foreach ($files_to_delete as $file)
		{
			if ($file->exists())
				$file->delete();
		}
	}
}
?>
