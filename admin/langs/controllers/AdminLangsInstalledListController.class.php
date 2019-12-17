<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2011 04 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminLangsInstalledListController extends AdminController
{
	private $lang;
	private $view;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();
		$this->save($request);

		return new AdminLangsDisplayResponse($this->view, $this->lang['langs.installed_langs']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-langs-common');
		$this->view = new FileTemplate('admin/langs/AdminLangsInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$installed_langs = LangsManager::get_installed_langs_map_sorted_by_localized_name();
		$selected_lang_number = 0;
		$lang_number = 1;
		foreach($installed_langs as $lang)
		{
			$configuration = $lang->get_configuration();
			$authorizations = $lang->get_authorizations();
			$author_email = $configuration->get_author_mail();
			$author_website = $configuration->get_author_link();

			$this->view->assign_block_vars('langs_installed', array(
				'C_AUTHOR_EMAIL' => !empty($author_email),
				'C_AUTHOR_WEBSITE' => !empty($author_website),
				'C_COMPATIBLE' => $configuration->get_compatibility() == $phpboost_version,
				'C_IS_DEFAULT_LANG' => $lang->get_id() == LangsManager::get_default_lang(),
				'C_IS_ACTIVATED' => $lang->is_activated(),
				'C_HAS_PICTURE' => $configuration->has_picture(),
				'LANG_NUMBER' => $lang_number,
				'ID' => $lang->get_id(),
				'PICTURE_URL' => $configuration->get_picture_url()->rel(),
				'NAME' => $configuration->get_name(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR' => $configuration->get_author_name(),
				'AUTHOR_EMAIL' => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Lang::ACCES_LANG, $authorizations, array(2 => true), $lang->get_id())
			));
			if ($lang->get_id() == LangsManager::get_default_lang())
				$selected_lang_number = $lang_number;

			$lang_number++;
		}

		$installed_langs_number = count($installed_langs);
		$this->view->put_all(array(
			'C_MORE_THAN_ONE_LANG_INSTALLED' => $installed_langs_number > 1,
			'LANGS_NUMBER' => $installed_langs_number,
			'DEFAULT_LANG_NUMBER' => $selected_lang_number
		));
	}

	public function save(HTTPRequestCustom $request)
	{
		$installed_langs = LangsManager::get_installed_langs_map_sorted_by_localized_name();

		if ($request->get_string('delete-selected-langs', false))
		{
			$lang_ids = array();
			$lang_number = 1;
			foreach ($installed_langs as $lang)
			{
				if ($request->get_value('delete-checkbox-' . $lang_number, 'off') == 'on')
				{
					$lang_ids[] = $lang->get_id();
				}
				$lang_number++;
			}
			
			$number_ids = count($lang_ids);
			if ($number_ids > 1)
			{
				$temporary_file = PATH_TO_ROOT . '/cache/langs_to_delete.txt';
				$file = new File($temporary_file);
				$file->write(implode(',', $lang_ids));
				$id = 'delete_multiple';
			}
			else
				$id = $number_ids ? $lang_ids[0] : '';
			
			if ($number_ids)
				AppContext::get_response()->redirect(AdminLangsUrlBuilder::uninstall($id));
		}
		elseif ($request->get_string('activate-selected-langs', false) || $request->get_string('deactivate-selected-langs', false))
		{
			$activated = 0;
			if ($request->get_string('activate-selected-langs', false))
				$activated = 1;

			$lang_number = 1;
			foreach ($installed_langs as $lang)
			{
				if ($lang->get_id() !== LangsManager::get_default_lang() && ($request->get_value('delete-checkbox-' . $Lang_number, 'off') == 'on') )
				{
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $lang->get_id());
					LangsManager::change_informations($lang->get_id(), $activated, $authorizations);
				}
				$lang_number++;
			}
			AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
		else
		{
			foreach($installed_langs as $lang)
			{
				if ($request->get_string('default-' . $lang->get_id(), ''))
				{
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $lang->get_id());
					LangsManager::change_informations($lang->get_id(), 1, $authorizations);

					$user_accounts_config = UserAccountsConfig::load();
					$user_accounts_config->set_default_lang($lang->get_id());
					UserAccountsConfig::save();

					AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
				}
				else if ($request->get_string('delete-' . $lang->get_id(), ''))
				{
					AppContext::get_response()->redirect(AdminLangsUrlBuilder::uninstall($lang->get_id()));
				}
				else if ($request->get_string('enable-' . $lang->get_id(), ''))
				{
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $lang->get_id());
					LangsManager::change_informations($lang->get_id(), 1, $authorizations);

					AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
				}
				else if ($request->get_string('disable-' . $lang->get_id(), ''))
				{
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $lang->get_id());
					LangsManager::change_informations($lang->get_id(), 0, $authorizations);

					AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
				}
			}
		}

		if ($request->get_bool('update', false))
		{
			foreach ($installed_langs as $lang)
			{
				if ($lang->get_id() !== LangsManager::get_default_lang())
				{

					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $lang->get_id());
					LangsManager::change_informations($lang->get_id(), $lang->is_activated(), $authorizations);
				}
			}
			AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
		}
	}
}
?>
