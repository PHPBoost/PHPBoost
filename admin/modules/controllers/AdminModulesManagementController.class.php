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
		$this->lang = LangLoader::get('admin-modules-common');
		$this->view = new FileTemplate('admin/modules/AdminModulesManagementController.tpl');
		$this->view->add_lang($this->lang);
	}
	
	private function build_view()
	{
		$installed_modules = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
		$module_number = 1;
		foreach ($installed_modules as $module)
		{
			$configuration = $module->get_configuration();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();
			$documentation = $configuration->get_documentation();
			
			$this->view->assign_block_vars('modules_installed', array(
				'C_AUTHOR_EMAIL' => !empty($author_email),
				'C_AUTHOR_WEBSITE' => !empty($author_website),
				'C_IS_ACTIVATED' => $module->is_activated(),
				'MODULE_NUMBER' => $module_number,
				'ID' => $module->get_id(),
				'NAME' => TextHelper::ucfirst($configuration->get_name()),
				'ICON' => $module->get_id(),
				'VERSION' => $module->get_installed_version(),
				'AUTHOR' => $configuration->get_author(),
				'AUTHOR_EMAIL' => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION' => $configuration->get_description(),
				'COMPATIBILITY' => $configuration->get_compatibility(),
				'PHP_VERSION' => $configuration->get_php_version(),
				'C_DOCUMENTATION' => !empty($documentation),
				'L_DOCUMENTATION' => $documentation
			));
			
			$module_number++;
		}
		
		$installed_modules_number = count($installed_modules);
		$this->view->put_all(array(
			'C_MORE_THAN_ONE_MODULE_INSTALLED' => $installed_modules_number > 1,
			'MODULES_NUMBER' => $installed_modules_number
		));
	}
	
	private function save(HTTPRequestCustom $request)
	{
		$installed_modules = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
		
		if ($request->get_string('delete-selected-modules', false))
		{
			$module_ids = array();
			$module_number = 1;
			foreach ($installed_modules as $module)
			{
				if ($request->get_value('delete-checkbox-' . $module_number, 'off') == 'on')
				{
					$module_ids[] = $module->get_id();
				}
				$module_number++;
			}
			AppContext::get_response()->redirect(AdminModulesUrlBuilder::delete_module(implode('---', $module_ids)));
		}
		elseif ($request->get_string('activate-selected-modules', false) || $request->get_string('deactivate-selected-modules', false))
		{
			$activated = 0;
			if ($request->get_string('activate-selected-modules', false))
				$activated = 1;
			
			$module_number = 1;
			$modified_modules = 0;
			$errors = array();
			
			foreach ($installed_modules as $module)
			{
				if ($request->get_value('delete-checkbox-' . $module_number, 'off') == 'on')
				{
					$error = ModulesManager::update_module($module->get_id(), $activated);
					$modified_modules++;
				}
				$module_number++;
				if (!empty($error))
					$errors[$module->get_configuration()->get_name()] = $error;
			}
			
			if ($modified_modules && empty($errors))
			{
				AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), LangLoader::get_message('process.success', 'status-messages-common'));
			}
			else
			{
				$this->init();
				$this->build_view();
				foreach ($errors as $module_name => $error)
				{
					$this->view->assign_block_vars('errors', array(
						'MSG' => MessageHelper::display($module_name . ' : ' . $error, MessageHelper::WARNING)
					));
				}
			}
		}
		else
		{
			foreach($installed_modules as $module)
			{
				if ($request->get_string('delete-' . $module->get_id(), ''))
				{
					AppContext::get_response()->redirect(AdminModulesUrlBuilder::delete_module($module->get_id()));
				}
				else if ($request->get_string('enable-' . $module->get_id(), ''))
				{
					$error = ModulesManager::update_module($module->get_id(), 1);
					
					if (!empty($error))
						$errors[$module->get_configuration()->get_name()] = $error;
					else
						AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), LangLoader::get_message('process.success', 'status-messages-common'));
				}
				else if ($request->get_string('disable-' . $module->get_id(), ''))
				{
					$error = ModulesManager::update_module($module->get_id(), 0);
					
					if (!empty($error))
						$errors[$module->get_configuration()->get_name()] = $error;
					else
						AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), LangLoader::get_message('process.success', 'status-messages-common'));
				}
			}
		}
	}
}
?>