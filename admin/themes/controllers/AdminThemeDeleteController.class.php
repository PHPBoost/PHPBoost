<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2011 04 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminThemeDeleteController extends AdminController
{
	private $form;
	private $lang;
	private $submit_button;
	private $theme_id;
	private $multiple = false;
	private $tpl;
	private $file;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->theme_id = $request->get_value('id', null);

		if ($this->theme_id == 'delete_multiple')
		{
			$temporary_file = PATH_TO_ROOT . '/cache/themes_to_delete.txt';
			$this->file = new File($temporary_file);
			if ($this->file->exists())
			{
				$this->theme_id  = explode(',', $this->file->read());
				$this->multiple = true;
			}
		}

		if ($this->theme_exists())
		{
			$this->build_form();
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$drop_files = $this->form->get_value('drop_files')->get_raw_value();
				$this->delete_theme($drop_files);

				AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), LangLoader::get_message('process.success', 'status-messages-common'));
			}

			$this->tpl->put('FORM', $this->form->display());

			return new AdminThemesDisplayResponse($this->tpl, $this->multiple ? $this->lang['themes.delete_theme_multiple'] : $this->lang['themes.delete_theme']);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-themes-common');
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('delete_theme', $this->multiple ? $this->lang['themes.delete_theme_multiple'] : $this->lang['themes.delete_theme']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['themes.drop_files_multiple'] : $this->lang['themes.drop_files'], '0',
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

	private function delete_theme($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->theme_id as $id)
			{
				ThemesManager::uninstall($id, $drop_files);
			}
			$this->file->delete();
		}
		else
			ThemesManager::uninstall($this->theme_id, $drop_files);
	}

	private function theme_exists()
	{
		if ($this->theme_id == null)
		{
			return false;
		}

		if ($this->multiple)
		{
			$theme_exists = false;
			foreach ($this->theme_id as $id)
			{
				if (ThemesManager::get_theme_existed($id))
				{
					$theme_exists = true;
					break;
				}
			}
			return $theme_exists;
		}
		else
			return ThemesManager::get_theme_existed($this->theme_id);
	}
}
?>
