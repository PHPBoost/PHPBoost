<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 23
 * @since       PHPBoost 3.0 - 2011 04 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminThemeDeleteController extends DefaultAdminController
{
	private $theme_id;
	private $multiple = false;
	private $file;

	public function execute(HTTPRequestCustom $request)
	{
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
			$this->check_requested_theme();
			$this->build_form();
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$drop_files = $this->form->get_value('drop_files')->get_raw_value();
				$this->delete_theme($drop_files);

				AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['warning.process.success']);
			}

			if (!$this->multiple)
			{
				$theme_childs_list = ThemesManager::get_theme_childs_list($this->theme_id);
				if ($theme_childs_list)
				{
					$requested_theme_name = ThemesManager::get_theme($this->theme_id)->get_configuration()->get_name();
					$theme_childs_list_names = array();
					foreach ($theme_childs_list as $id)
					{
						$theme_childs_list_names[] = ThemesManager::get_theme($id)->get_configuration()->get_name();
					}

					if (count($theme_childs_list_names) > 1)
						$warning_message = StringVars::replace_vars($this->lang['addon.themes.warning.childs.list'], array('themes_names' => implode('</b>, <b>', $theme_childs_list_names), 'name' => $requested_theme_name));
					else
						$warning_message = StringVars::replace_vars($this->lang['addon.themes.warning.child'], array('theme_name' => $theme_childs_list_names[0], 'name' => $requested_theme_name));

					$this->view->put('MESSAGE_HELPER', MessageHelper::display($warning_message, MessageHelper::WARNING));
				}
			}

			$this->view->put('CONTENT', $this->form->display());

			return new AdminThemesDisplayResponse($this->view, $this->multiple ? $this->lang['addon.themes.delete.multiple'] : $this->lang['addon.themes.delete']);
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

		$fieldset = new FormFieldsetHTML('delete_theme', $this->multiple ? $this->lang['addon.themes.delete.multiple'] : $this->lang['addon.themes.delete']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRadioChoice('drop_files', $this->multiple ? $this->lang['addon.themes.drop.multiple'] : $this->lang['addon.themes.drop'], '0',
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

	private function check_requested_theme()
	{
		$try_to_delete_default = $try_to_delete_default_parent = false;
		$default_theme_parent = ThemesManager::get_theme(ThemesManager::get_default_theme())->get_configuration()->get_parent_theme();
		if ($default_theme_parent != '__default__')
		{
			$default_theme_parent_name = ThemesManager::get_theme($default_theme_parent)->get_configuration()->get_name();
			$default_theme_name = ThemesManager::get_theme(ThemesManager::get_default_theme())->get_configuration()->get_name();
			$default_theme_parent_error = StringVars::replace_vars($this->lang['addon.themes.default.parent.theme'], array('name' => $default_theme_parent_name, 'default_theme' => $default_theme_name));
		}

		if ($this->multiple)
		{
			foreach ($this->theme_id as $id)
			{
				if ($id == ThemesManager::get_default_theme())
					$try_to_delete_default = true;
				else if ($id == $default_theme_parent)
					$try_to_delete_default_parent = true;
			}
		}
		else
		{
			if ($this->theme_id == ThemesManager::get_default_theme())
				$try_to_delete_default = true;
			else if ($this->theme_id == $default_theme_parent)
				$try_to_delete_default_parent = true;
		}

		if ($try_to_delete_default)
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $this->lang['addon.themes.warning.default'], MessageHelper::WARNING);
		else if ($default_theme_parent != '__default__' && $try_to_delete_default_parent)
			AppContext::get_response()->redirect(AdminThemeUrlBuilder::list_installed_theme(), $default_theme_parent_error, MessageHelper::WARNING);
	}

	private function delete_theme($drop_files)
	{
		if ($this->multiple)
		{
			foreach ($this->theme_id as $id)
			{
				$theme = ThemesManager::get_theme($id);
				ThemesManager::uninstall($id, $drop_files);
				HooksService::execute_hook_typed_action('uninstall', 'theme', $id, array_merge(array('title' => $theme->get_configuration()->get_name(), $theme->get_configuration()->get_properties())));
			}
			$this->file->delete();
		}
		else
			$theme = ThemesManager::get_theme($this->theme_id);
			ThemesManager::uninstall($this->theme_id, $drop_files);
			HooksService::execute_hook_typed_action('uninstall', 'theme', $this->theme_id, array_merge(array('title' => $theme->get_configuration()->get_name(), $theme->get_configuration()->get_properties())));
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
