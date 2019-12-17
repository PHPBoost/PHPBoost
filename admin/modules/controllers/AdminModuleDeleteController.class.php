<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2011 09 20
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class AdminModuleDeleteController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $module_id;
	private $multiple = false;
	private $error = '';
	private $tpl;
	private $file;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

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
					AppContext::get_response()->redirect(AdminModulesUrlBuilder::list_installed_modules(), LangLoader::get_message('process.success', 'status-messages-common'));
				else
					$this->tpl->put('MSG', $this->error);
			}
			$this->tpl->put('FORM', $this->form->display());
			return new AdminModulesDisplayResponse($this->tpl, $this->multiple ? $this->lang['modules.delete_module_multiple'] : $this->lang['modules.delete_module']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-modules-common');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
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

		$fieldset = new FormFieldsetHTMLHeading('delete_module', $this->multiple ? $this->lang['modules.delete_module_multiple'] : $this->lang['modules.delete_module']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['modules.drop_files_multiple'] : $this->lang['modules.drop_files'], '0',
			array(
				new FormFieldRadioChoiceOption(LangLoader::get_message('yes', 'common'), '1'),
				new FormFieldRadioChoiceOption(LangLoader::get_message('no', 'common'), '0')
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
				$this->error_check(ModulesManager::uninstall_module($id, $drop_files));
			}
			$this->file->delete();
		}
		else
			$this->error_check(ModulesManager::uninstall_module($this->module_id, $drop_files));
	}

	private function error_check($error)
	{
		if (is_int($error))
		{
			switch($error)
			{
				case ModulesManager::MODULE_FILES_COULD_NOT_BE_DROPPED:
					$this->error = MessageHelper::display(LangLoader::get_message('files_del_failed', 'main'), MessageHelper::WARNING, 10);
					break;
				case ModulesManager::NOT_INSTALLED_MODULE:
					$this->error = MessageHelper::display($this->lang['modules.not_installed_module'], MessageHelper::WARNING, 10);
					break;
				case ModulesManager::MODULE_UNINSTALLED:
					break;
				default:
					$this->error = MessageHelper::display(LangLoader::get_message('process.error', 'status-messages-common'), MessageHelper::WARNING, 10);
			}
		}
		else
		{
			$this->error = MessageHelper::display($error, MessageHelper::WARNING, 10);
		}
	}
}
?>
