<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 13
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModulesManagementController extends DefaultAdminController
{
	protected function get_template_to_use()
	{
        return new FileTemplate('admin/modules/AdminModulesManagementController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();
		$this->save($request);

		return new AdminModulesDisplayResponse($this->view, $this->lang['addon.modules.management']);
	}

	private function build_view()
	{
		$phpboost_version = GeneralConfig::load()->get_phpboost_major_version();
		$installed_modules = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
		$module_number = 1;
		foreach ($installed_modules as $module)
		{
			$configuration = $module->get_configuration();
			$author_email = $configuration->get_author_email();
			$author_website = $configuration->get_author_website();
			$documentation = $configuration->get_documentation();
			$fa_icon = $configuration->get_fa_icon();
			$hexa_icon = $configuration->get_hexa_icon();
			$thumbnail = new File(PATH_TO_ROOT . '/' . $module->get_id() . '/' . $module->get_id() . '.png');

			$this->view->assign_block_vars('modules_installed', array(
				'C_THUMBNAIL'          => $thumbnail->exists(),
				'C_FA_ICON' 	       => !empty($fa_icon),
				'C_HEXA_ICON' 	       => !empty($hexa_icon),
				'C_AUTHOR_EMAIL'       => !empty($author_email),
				'C_AUTHOR_WEBSITE'     => !empty($author_website),
				'C_COMPATIBLE'         => $configuration->get_addon_type() == 'module' && $configuration->get_compatibility() == $phpboost_version,
				'C_COMPATIBLE_ADDON'   => $configuration->get_addon_type() == 'module',
				'C_COMPATIBLE_VERSION' => $configuration->get_compatibility() == $phpboost_version,
				'C_IS_ACTIVATED'       => $module->is_activated(),
				'C_DOCUMENTATION'      => !empty($documentation),

				'MODULE_NUMBER'  => $module_number,
				'MODULE_ID'      => $module->get_id(),
				'MODULE_NAME'    => TextHelper::ucfirst($configuration->get_name()),
				'CREATION_DATE'  => $configuration->get_creation_date(),
				'LAST_UPDATE'    => $configuration->get_last_update(),
				'VERSION'        => $module->get_installed_version(),
				'AUTHOR'         => $configuration->get_author(),
				'AUTHOR_EMAIL'   => $author_email,
				'AUTHOR_WEBSITE' => $author_website,
				'DESCRIPTION'    => $configuration->get_description(),
				'COMPATIBILITY'  => $configuration->get_compatibility(),
				'PHP_VERSION'    => $configuration->get_php_version(),
				'FA_ICON' 		 => $fa_icon,
				'HEXA_ICON' 	 => $hexa_icon,

				'U_DOCUMENTATION' => $documentation
			));

			$module_number++;
		}

		$installed_modules_number = count($installed_modules);
		$this->view->put_all(array(
			'C_SEVERAL_MODULES_INSTALLED' => $installed_modules_number > 1,
			'MODULES_NUMBER' => $installed_modules_number
		));
	}

	private function save(HTTPRequestCustom $request)
	{
		$installed_modules = ModulesManager::get_installed_modules_map_sorted_by_localized_name();
		$errors = array();

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

			$number_ids = count($module_ids);
			if ($number_ids > 1)
			{
				$temporary_file = PATH_TO_ROOT . '/cache/modules_to_delete.txt';
				$file = new File($temporary_file);
				$file->write(implode(',', $module_ids));
				$id = 'delete_multiple';
			}
			else
				$id = $number_ids ? $module_ids[0] : '';

			if ($number_ids)
				AppContext::get_response()->redirect(AdminModulesUrlBuilder::delete_module($id));
		}
		elseif ($request->get_string('activate-selected-modules', false) || $request->get_string('deactivate-selected-modules', false))
		{
			$activated = 0;
			if ($request->get_string('activate-selected-modules', false))
				$activated = 1;

			$module_number = 1;
			$modified_modules = 0;

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
				AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), $this->lang['warning.process.success']);
			}
			else
			{
				$this->init();
				$this->build_view();
				foreach ($errors as $module_name => $error)
				{
					$this->view->assign_block_vars('errors', array(
						'MESSAGE_HELPER' => MessageHelper::display($module_name . ' : ' . $error, MessageHelper::WARNING)
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
						$this->view->put('MESSAGE_HELPER', MessageHelper::display('<b>' . $module->get_configuration()->get_name() . '</b> : ' . $error, MessageHelper::WARNING));
					else
						AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), $this->lang['warning.process.success']);
				}
				else if ($request->get_string('disable-' . $module->get_id(), ''))
				{
					$error = ModulesManager::update_module($module->get_id(), 0);

					if (!empty($error))
						$this->view->put('MESSAGE_HELPER', MessageHelper::display('<b>' . $module->get_configuration()->get_name() . '</b> : ' . $error, MessageHelper::WARNING));
					else
						AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), $this->lang['warning.process.success']);
				}
			}
		}
	}
}
?>
