<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 23
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminModuleDeleteController extends DefaultAdminController
{
	private $module_id;
	private $multiple = false;
	private $error = '';
	private $file;

	public function execute(HTTPRequestCustom $request)
	{
		$this->module_id = $request->get_value('id', null);

		if ($this->module_id == 'delete_multiple')
		{
			$temporary_file = PATH_TO_ROOT . '/cache/modules_to_delete.txt';
			$this->file = new File($temporary_file);
			if ($this->file->exists())
			{
				$this->module_id  = explode(',', $this->file->read());
				$this->multiple = true;
			}
		}

		if ($this->module_exists())
		{
			$this->build_form();

			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$drop_files = $this->form->get_value('drop_files')->get_raw_value();
				$this->delete_module($drop_files);

				if (!$this->error)
					AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), $this->lang['warning.process.success']);
				else
					$this->view->put('MESSAGE_HELPER', $this->error);
			}
			$this->view->put('CONTENT', $this->form->display());
			return new AdminModulesDisplayResponse($this->view, $this->multiple ? $this->lang['addon.modules.delete.multiple'] : $this->lang['addon.modules.delete']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function module_exists()
	{
		if ($this->module_id == null)
		{
			return false;
		}

		if ($this->multiple)
		{
			$module_exists = false;
			foreach ($this->module_id as $id)
			{
				if (ModulesManager::is_module_installed($id))
				{
					$module_exists = true;
					break;
				}
			}
			return $module_exists;
		}
		else
			return ModulesManager::is_module_installed($this->module_id);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('delete_module', $this->multiple ? $this->lang['addon.modules.delete.multiple'] : $this->lang['addon.modules.delete']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['addon.modules.drop.multiple'] : $this->lang['addon.modules.drop'], '0',
			array(
				new FormFieldRadioChoiceOption($this->lang['common.yes'], '1'),
				new FormFieldRadioChoiceOption($this->lang['common.no'], '0')
			),
			array('class' => 'inline-radio custom-radio')
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function delete_module($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->module_id as $id)
			{
				$module = ModulesManager::get_module($id);
				$this->error_check(ModulesManager::uninstall_module($id, $drop_files));
				if (!$this->error)
					HooksService::execute_hook_typed_action('uninstall', 'module', $id, array_merge(array('title' => $module->get_configuration()->get_name(), $module->get_configuration()->get_properties())));
			}
			$this->file->delete();
		}
		else
		{
			$module = ModulesManager::get_module($this->module_id);
			$this->error_check(ModulesManager::uninstall_module($this->module_id, $drop_files));
			if (!$this->error)
				HooksService::execute_hook_typed_action('uninstall', 'module', $this->module_id, array_merge(array('title' => $module->get_configuration()->get_name(), $module->get_configuration()->get_properties())));
		}
	}

	private function error_check($error)
	{
		if (is_int($error))
		{
			switch($error)
			{
				case ModulesManager::MODULE_FILES_COULD_NOT_BE_DROPPED:
					$this->error = MessageHelper::display($this->lang['warning.files.del.failed'], MessageHelper::WARNING, 10);
					break;
				case ModulesManager::NOT_INSTALLED_MODULE:
					$this->error = MessageHelper::display($this->lang['addon.modules.not.installed'], MessageHelper::WARNING, 10);
					break;
				case ModulesManager::MODULE_UNINSTALLED:
					break;
				default:
					$this->error = MessageHelper::display($this->lang['warning.process.error'], MessageHelper::WARNING, 10);
			}
		}
		else
		{
			$this->error = MessageHelper::display($error, MessageHelper::WARNING, 10);
		}
	}
}
?>
