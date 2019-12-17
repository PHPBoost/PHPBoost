<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2012 01 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class AdminUninstallLangController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $lang_id;
	private $multiple = false;
	private $tpl;
	private $file;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

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

				AppContext::get_response()->redirect(AdminLangsUrlBuilder::list_installed_langs(), LangLoader::get_message('process.success', 'status-messages-common'));
			}

			$this->tpl->put('FORM', $this->form->display());

			return new AdminLangsDisplayResponse($this->tpl, $this->multiple ? $this->lang['langs.delete_lang_multiple'] : $this->lang['langs.delete_lang']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-langs-common');
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('uninstall_lang', $this->multiple ? $this->lang['langs.delete_lang_multiple'] : $this->lang['langs.delete_lang']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['langs.drop_files_multiple'] : $this->lang['langs.drop_files'], '0',
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

	private function uninstall($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->lang_id as $id)
			{
				LangsManager::uninstall($id, $drop_files);
			}
			$this->file->delete();
		}
		else
			LangsManager::uninstall($this->lang_id, $drop_files);
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
