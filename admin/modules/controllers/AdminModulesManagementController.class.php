<?php
/*##################################################
 *                       AdminModulesManagementController.class.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminModulesManagementController extends AdminController
{
	private $lang;
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view();
		$this->save($request);
		
		return new AdminModulesDisplayResponse($this->view, $this->lang['modules.module_management']);
	}
	
	private function init()
	{
		$this->load_lang();
		$this->view = new FileTemplate('admin/modules/AdminModulesManagementController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-modules-common');
	}
	
	private function build_view()
	{
		$modules_activated = ModulesManager::get_activated_modules_map_sorted_by_localized_name();
		$modules_installed = ModulesManager::get_installed_modules_map_sorted_by_localized_name();

		foreach ($modules_installed as $module)
		{
			$configuration = $module->get_configuration();
			$author = $configuration->get_author();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();
			
			if (!in_array($module, $modules_activated))
			{
				$this->view->assign_block_vars('modules_not_activated', array(
					'C_AUTHOR' => !empty($author),
					'C_AUTHOR_WEBSITE' => !empty($author_website),
					'ID' => $module->get_id(),
					'NAME' => ucfirst($configuration->get_name()),
					'ICON' => $module->get_id(),
					'VERSION' => $module->get_installed_version(),
					'AUTHOR' => $author,
					'AUTHOR_EMAIL' => $author_email,
					'AUTHOR_WEBSITE' => $author_website,
					'DESCRIPTION' => $configuration->get_description(),
					'COMPATIBILITY' => $configuration->get_compatibility(),
					'PHP_VERSION' => $configuration->get_php_version(),
					'C_MODULE_ACTIVE' => $module->is_activated()
				));
			}
			else 
			{
				$this->view->assign_block_vars('modules_activated', array(
					'C_AUTHOR' => !empty($author),
					'C_AUTHOR_WEBSITE' => !empty($author_website),
					'ID' => $module->get_id(),
					'NAME' => ucfirst($configuration->get_name()),
					'ICON' => $module->get_id(),
					'VERSION' => $module->get_installed_version(),
					'AUTHOR' => $author,
					'AUTHOR_EMAIL' => $author_email,
					'AUTHOR_WEBSITE' => $author_website,
					'DESCRIPTION' => $configuration->get_description(),
					'COMPATIBILITY' => $configuration->get_compatibility(),
					'PHP_VERSION' => $configuration->get_php_version(),
					'C_MODULE_ACTIVE' => $module->is_activated()
				));
			}
		}
		
		$this->view->put_all(array(
			'C_MODULES_ACTIVATED' => count($modules_activated) > 0,
			'C_MODULES_NOT_ACTIVATED' => (count($modules_installed) - count($modules_activated)) > 0
		));
	}
	
	private function save(HTTPRequestCustom $request)
	{
		$installed_modules = ModulesManager::get_installed_modules_map();
		
		foreach ($installed_modules as $module)
		{
			if ($request->get_string('delete-' . $module->get_id(), ''))
			{
				AppContext::get_response()->redirect(AdminModulesUrlBuilder::delete_module($module->get_id()));
			}
		}
		
		if ($request->get_bool('update', false))
		{
			$errors = array();
			foreach ($installed_modules as $module)
			{
				$module_id = $module->get_id();
				$activated = $request->get_bool('activated-' . $module_id, false);
				$error = ModulesManager::update_module($module_id, $activated);
				
				if (!empty($error))
					$errors[$module->get_configuration()->get_name()] = $error;
			}
			
			if (empty($errors))
			{
				AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules());
			}
			else
			{
				foreach ($errors as $module_name => $error)
				{
					$this->view->assign_block_vars('errors', array(
						'MSG' => MessageHelper::display($module_name . ' : ' . $error, MessageHelper::WARNING)
					));
				}
			}
		}
	}
}
?>