<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 03 13
 * @since       PHPBoost 3.0 - 2012 01 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminUninstallLangController extends DefaultAdminController
{
	private $lang_id;
	private $multiple = false;
	private $file;

	public function execute(HTTPRequestCustom $request)
	{
		$this->lang_id = $request->get_value('id', null);

		if ($this->lang_id == 'delete_multiple')
		{
			$temporary_file = PATH_TO_ROOT . '/cache/langs_to_delete.txt';
			$this->file = new File($temporary_file);
			if ($this->file->exists())
			{
				$this->lang_id  = explode(',', $this->file->read());
				$this->multiple = true;
			}
		}

		if ($this->lang_exists())
		{
			$this->build_form();
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$this->uninstall($this->form->get_value('drop_files')->get_raw_value());

				AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), $this->lang['warning.process.success']);
			}

			$this->view->put('CONTENT', $this->form->display());

			return new AdminLangsDisplayResponse($this->view, $this->multiple ? $this->lang['addon.langs.delete.multiple'] : $this->lang['addon.langs.delete']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('uninstall_lang', $this->multiple ? $this->lang['addon.langs.delete.multiple'] : $this->lang['addon.langs.delete']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['addon.langs.drop.multiple'] : $this->lang['addon.langs.drop'], '0',
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

	private function uninstall($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->lang_id as $id)
			{
				$lang = LangsManager::get_lang($id);
				HooksService::execute_hook_typed_action('uninstall', 'lang', $id, array_merge(array('title' => $lang->get_configuration()->get_name(), $lang->get_configuration()->get_properties())));
                LangsManager::uninstall($id, $drop_files);
            }
			$this->file->delete();
		}
		else
		{
			$lang = LangsManager::get_lang($this->lang_id);
            HooksService::execute_hook_typed_action('uninstall', 'lang', $this->lang_id, array_merge(array('title' => $lang->get_configuration()->get_name(), $lang->get_configuration()->get_properties())));
            LangsManager::uninstall($this->lang_id, $drop_files);
		}
	}

	private function lang_exists()
	{
		if ($this->lang_id == null)
		{
			return false;
		}
		if ($this->multiple)
		{
			$lang_exists = false;
			foreach ($this->lang_id as $id)
			{
				if (LangsManager::get_lang_existed($id))
				{
					$lang_exists = true;
					break;
				}
			}
			return $lang_exists;
		}
		else
			return LangsManager::get_lang_existed($this->lang_id);
	}
}

?>
