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
	
	private function build_view()
	{
		$installed_langs = LangsManager::get_installed_langs_map();
		foreach($installed_langs as $lang)
		{
			$configuration = $lang->get_configuration();
			$authorizations = $lang->get_authorizations();
			$default_lang = LangsManager::get_default_lang();

			$this->view->assign_block_vars('langs_installed', array(
				'C_IS_DEFAULT_LANG' => $lang->get_id() == $default_lang,
				'C_IS_ACTIVATED' => $lang->is_activated(),
				'ID' => $lang->get_id(),
				'NAME' => $configuration->get_name(),
				'VERSION' => $configuration->get_version(),
				'AUTHOR_NAME' => $configuration->get_author_name(),
				'AUTHOR_WEBSITE' => $configuration->get_author_link(),
				'AUTHOR_EMAIL' => $configuration->get_author_mail(),
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'AUTHORIZATIONS' => Authorizations::generate_select(Lang::ACCES_LANG, $authorizations, array(2 => true), $lang->get_id())
			));
		}
		
		$this->view->put_all(array(
			'L_DELETE' => LangLoader::get_message('delete','common'),
			'L_RESET' => LangLoader::get_message('reset','main'),
			'L_UPDATE' => LangLoader::get_message('update','main')
		));
	}
	
	public function save(HTTPRequestCustom $request)
	{
		$installed_langs = LangsManager::get_installed_langs_map();
		
		foreach($installed_langs as $lang)
		{
			if ($request->get_string('delete-' . $lang->get_id(), ''))
			{
				AppContext::get_response()->redirect(AdminLangsUrlBuilder::uninstall($lang->get_id()));
			}
		}
		
		if ($request->get_bool('update', false))
		{
			foreach ($installed_langs as $lang)
			{
				if ($lang->get_id() !== LangsManager::get_default_lang())
				{
					$id = $lang->get_id();
					$activated = $request->get_bool('activated-' . $id, false);
					$authorizations = Authorizations::auth_array_simple(Lang::ACCES_LANG, $id);
					LangsManager::change_informations($id, $activated, $authorizations);
				}
			}
			AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs());
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('admin-langs-common');
		$this->view = new FileTemplate('admin/langs/AdminLangsInstalledListController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>