<?php
/*##################################################
 *                      AdminLangsInstalledListController.class.php
 *                            -------------------
 *   begin                : April 20, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
					$lang_ids[] = $lang_number;
				}
				$lang_number++;
			}
			AppContext::get_response()->redirect(AdminLangsUrlBuilder::uninstall(implode('--', $lang_ids)));
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