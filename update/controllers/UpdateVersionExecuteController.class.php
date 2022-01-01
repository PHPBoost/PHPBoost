<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2012 03 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UpdateVersionExecuteController extends UpdateController
{
	private $submit;

	private $general_config;
	private $user_accounts_config;

	private $default_module_changed = false;
	private $default_theme_changed = false;
	private $default_lang_changed = false;

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);

		$this->init();

		$view = new FileTemplate('update/execute.tpl');
		$this->display_incompatible_elements_messages($view);
		$this->add_navigation($view);
		if ($this->submit->has_been_submited())
		{
			$this->handle_form();
		}
		return $this->create_response($view);
	}

	private function init()
	{
		$this->general_config = GeneralConfig::load();
		$this->user_accounts_config = UserAccountsConfig::load();
	}

	private function display_incompatible_elements_messages(Template $view)
	{
		$incompatible_modules = $this->get_incompatible_modules_list();

		if ($incompatible_modules)
		{
			$message = StringVars::replace_vars(count($incompatible_modules) > 1 ? $this->lang['step.execute.incompatible_modules'] : $this->lang['step.execute.incompatible_module'], array('modules' => '<b>' . implode('</b>, <b>', $incompatible_modules) . '</b>'));

			if ($this->default_module_changed)
			{
				if (ModulesManager::is_module_installed('news') && ModulesManager::get_module('news')->get_configuration()->get_compatibility() == UpdateServices::NEW_KERNEL_VERSION)
					$new_default = 'news';
				else if (ModulesManager::is_module_installed('articles') && ModulesManager::get_module('articles')->get_configuration()->get_compatibility() == UpdateServices::NEW_KERNEL_VERSION)
					$new_default = 'articles';
				else
					$new_default = 'forum';

				$message .= StringVars::replace_vars($this->lang['step.execute.incompatible_module.default'], array('old_default' => ModulesManager::get_module($this->general_config->get_module_home_page())->get_configuration()->get_name(), 'new_default' => ModulesManager::get_module($new_default)->get_configuration()->get_name()));
			}

			$view->put('INCOMPATIBLE_MODULES', MessageHelper::display($message, MessageHelper::WARNING));
		}

		$incompatible_themes = $this->get_incompatible_themes_list();

		if ($incompatible_themes)
		{
			$message = StringVars::replace_vars(count($incompatible_themes) > 1 ? $this->lang['step.execute.incompatible_themes'] : $this->lang['step.execute.incompatible_theme'], array('themes' => '<b>' . implode('</b>, <b>', $incompatible_themes) . '</b>'));

			if ($this->default_theme_changed)
			{
				$message .= StringVars::replace_vars($this->lang['step.execute.incompatible_theme.default'], array('old_default' => ThemesManager::get_theme($this->user_accounts_config->get_default_theme())->get_configuration()->get_name(), 'new_default' => 'Base'));
			}

			$view->put('INCOMPATIBLE_THEMES', MessageHelper::display($message, MessageHelper::WARNING));
		}

		$incompatible_langs = $this->get_incompatible_langs_list();

		if ($incompatible_langs)
		{
			$message = StringVars::replace_vars(count($incompatible_langs) > 1 ? $this->lang['step.execute.incompatible_langs'] : $this->lang['step.execute.incompatible_lang'], array('langs' => '<b>' . implode('</b>, <b>', $incompatible_langs) . '</b>'));

			if ($this->default_lang_changed)
			{
				$message .= StringVars::replace_vars($this->lang['step.execute.incompatible_lang.default'], array('old_default' => LangsManager::get_lang($this->user_accounts_config->get_default_lang())->get_configuration()->get_name(), 'new_default' => LangsManager::get_lang(LangLoader::get_locale())->get_configuration()->get_name()));
			}

			$view->put('INCOMPATIBLE_LANGS', MessageHelper::display($message, MessageHelper::WARNING));
		}
	}

	private function get_incompatible_modules_list()
	{
		$list = array();

		foreach (ModulesManager::get_installed_modules_map() as $module)
		{
			if ($module->get_configuration()->get_compatibility() != UpdateServices::NEW_KERNEL_VERSION)
			{
				$list[] = $module->get_configuration()->get_name();
				if ($this->general_config->get_module_home_page() == $module->get_id())
					$this->default_module_changed = true;
			}
		}

		return $list;
	}

	private function get_incompatible_themes_list()
	{
		$list = array();

		foreach (ThemesManager::get_installed_themes_map() as $theme)
		{
			if ($theme->get_configuration()->get_compatibility() != UpdateServices::NEW_KERNEL_VERSION)
			{
				$list[] = $theme->get_configuration()->get_name();
				if ($this->user_accounts_config->get_default_theme() == $theme->get_id())
					$this->default_theme_changed = true;
			}
		}

		return $list;
	}

	private function get_incompatible_langs_list()
	{
		$list = array();

		foreach (LangsManager::get_installed_langs_map() as $lang)
		{
			if ($lang->get_configuration()->get_compatibility() != UpdateServices::NEW_KERNEL_VERSION)
			{
				$list[] = $lang->get_configuration()->get_name();
				if ($this->user_accounts_config->get_default_lang() == $lang->get_id())
					$this->default_lang_changed = true;
			}
		}

		return $list;
	}

	private function handle_form()
	{
		$service = new UpdateServices();
		$service->execute();
		AppContext::get_response()->redirect(UpdateUrlBuilder::finish());
	}

	/**
	 * @param Template $view
	 * @return UpdateDisplayResponse
	 */
	private function create_response(Template $view)
	{
		$step_title = $this->lang['step.execute.title'];
		$response = new UpdateDisplayResponse(4, $step_title, $view);
		return $response;
	}

	private function add_navigation(Template $view)
	{
		$server_configuration = new ServerConfiguration();
		if (UpdateServices::database_config_file_checked())
		{
			if ($server_configuration->is_php_compatible() && PHPBoostFoldersPermissions::validate() && $server_configuration->has_mbstring_library())
				$back_url = UpdateUrlBuilder::introduction();
			else
				$back_url = UpdateUrlBuilder::server_configuration();
		}
		else
			$back_url = UpdateUrlBuilder::database();

		$form = new HTMLForm('continueForm', '', false);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], $back_url, 'fa fa-arrow-left');
		$action_fieldset->add_element($back);
		$refresh = new FormButtonLinkCssImg(LangLoader::get_message('form.refresh', 'form-lang'), UpdateUrlBuilder::update()->rel(), 'fa fa-sync');
		$action_fieldset->add_element($refresh);
		$this->submit = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'finish', 'jQuery(\'#update-in-progress-container\').show();');
		$action_fieldset->add_element($this->submit);
		$form->add_fieldset($action_fieldset);
		$view->put('SERVER_FORM', $form->display());
	}
}
?>
