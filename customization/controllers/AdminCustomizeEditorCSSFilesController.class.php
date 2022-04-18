<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 18
 * @since       PHPBoost 3.0 - 2011 09 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizeEditorCSSFilesController extends DefaultAdminModuleController
{
	private $templates_path = '/templates/';
	private $default_css_files_path = '/__default__/theme/';
	private $css_files_path = '/theme/';
	private $css_modules_files_path = '/modules/';

	public function execute(HTTPRequestCustom $request)
	{
		$id_theme = $request->get_value('id_theme', '');
		$id_module = '';
		$file_selected = $request->get_value('file_name', '');

		if (preg_match('`/`u', $file_selected))
		{
			$split = explode('/', $file_selected);
			$id_module = $split[0];
			$file_name = $split[1];
		}
		else
			$file_name = $file_selected;

		$css_file = new File(PATH_TO_ROOT . $this->templates_path . $id_theme . $this->css_files_path . '@' . $file_name);
		if ($css_file->exists())
			$file_name = '@' . $file_name;

		$this->build_form($id_theme, $id_module, $file_name, $file_selected);

		if (!empty($id_theme) && !empty($file_selected) && $this->submit_button->has_been_submited() && $this->form->validate())
			$this->save($id_theme, $id_module, $file_name);

		$this->view->put('CONTENT', $this->form->display());

		return new AdminCustomizationDisplayResponse($this->view, $this->lang['customization.editor.css.files']);
	}

	private function build_form($theme_selected, $module_selected, $file_name, $file_selected)
	{
		$form = new HTMLForm(__CLASS__);

		$theme_choise_fieldset = new FormFieldsetHTML('theme-choice', $this->lang['customization.interface.theme.choice']);
		$form->add_fieldset($theme_choise_fieldset);

		$theme_choise_fieldset->add_field(
			new FormFieldSimpleSelectChoice('select_theme', $this->lang['customization.interface.select.theme'], $theme_selected,
				$this->list_themes(),
				array(
					'class' => 'third-field',
					'events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_css_file()->rel() . '" + HTMLForms.getField(\'select_theme\').getValue()')
				)
			)
		);

		if (!empty($theme_selected))
		{
			$file_editor_fieldset = new FormFieldsetHTML('file-editor', $this->lang['customization.editor.css.files']);
			$form->add_fieldset($file_editor_fieldset);

			$file_editor_fieldset->add_field(
				new FormFieldSimpleSelectChoice('select_file', $this->lang['customization.editor.files.select'], $file_selected,
					$this->list_files($theme_selected),
					array(
						'class'       => 'third-field',
						'description' => $this->lang['customization.editor.files.select.clue'],
						'events'      => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_css_file($theme_selected)->rel() . '" + "/" + HTMLForms.getField(\'select_file\').getValue()')
					)
				)
			);

			if (!empty($file_selected))
			{
				if (!empty($module_selected) && $module_selected != '__default__')
				{
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);
					if (!$css_file->exists())
						$css_file = new File(PATH_TO_ROOT . '/' . $module_selected . '/templates/' . $file_name);
				}
				else
				{
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);
					
					if (!$css_file->exists())
						$css_file = new File(PATH_TO_ROOT . $this->templates_path . $this->default_css_files_path . $file_name);
				}

				if ($css_file->exists())
				{
					$file_editor_fieldset->add_field(new FormFieldMultiLineTextEditor('css_file', $this->lang['customization.editor.files.content'], TextHelper::htmlspecialchars($css_file->read()),
						array('rows' => 30, 'class' => "lined")
					));
				}
				
				$display_remove_override_button = false;
				if (!empty($module_selected) && $module_selected != '__default__')
				{
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);
					if ($css_file->exists())
						$display_remove_override_button = true;
				}
				else
				{
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);
					$default_css_file = new File(PATH_TO_ROOT . $this->templates_path . $this->default_css_files_path . $file_name);
					if ($file_name != '@import.css' && $css_file->exists() && $default_css_file->exists())
						$display_remove_override_button = true;
				}

				if ($display_remove_override_button)
				{
					$file_editor_fieldset->add_field(new FormFieldCheckbox('remove_override', $this->lang['customization.remove.override'], false,
						array('class' => 'third-field custom-checkbox')
					));
				}

				$file_editor_fieldset->add_field(new FormFieldHidden('id_theme', $theme_selected));
				$file_editor_fieldset->add_field(new FormFieldHidden('file_name', $file_selected));
			}
		}

		if (!empty($theme_selected) && !empty($file_selected))
		{
			$this->submit_button = new FormButtonDefaultSubmit();
			$form->add_button($this->submit_button);
			$form->add_button(new FormButtonReset());
		}

		$this->form = $form;
	}

	private function save($theme_selected, $module_selected, $file_name)
	{
		if ($this->form->get_value('remove_override'))
		{
			if (!empty($module_selected) && $module_selected != '__default__')
			{
				$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);
				if ($css_file->exists())
					$css_file->delete();

				$module_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected);
				if ($module_folder->exists() && !$module_folder->get_all_content())
					$module_folder->delete();

				$modules_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path);
				if ($modules_folder->exists() && !$modules_folder->get_all_content())
					$modules_folder->delete();
				
				$redirect_url = AdminCustomizeUrlBuilder::editor_css_file($theme_selected, $module_selected . '/' . $file_name);
			}
			else
			{
				$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);
				if ($css_file->exists())
				{
					$css_file->delete();
					$this->update_import_css($theme_selected, $theme_selected . $this->css_files_path . $file_name, '__default__' . $this->css_files_path . $file_name);
				}
				
				$redirect_url = AdminCustomizeUrlBuilder::editor_css_file($theme_selected, '__default__/' . str_replace('@', '', $file_name));
			}
		}
		else
		{
			if (!empty($module_selected) && $module_selected != '__default__')
			{
				$modules_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path);
				if (!$modules_folder->exists())
					$modules_folder->create();

				$module_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected);
				if (!$module_folder->exists())
					$module_folder->create();

				$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);

				if (!$css_file->exists())
					copy(PATH_TO_ROOT . '/' . $module_selected . '/templates/' . $file_name, PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);

				$redirect_url = AdminCustomizeUrlBuilder::editor_css_file($theme_selected, $module_selected . '/' . $file_name);
			}
			else
			{
				$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);

				if (!$css_file->exists())
				{
					copy(PATH_TO_ROOT . $this->templates_path . $this->default_css_files_path . $file_name, PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);
					$this->update_import_css($theme_selected, '__default__' . $this->css_files_path . $file_name, $theme_selected . $this->css_files_path . $file_name);
				}

				$redirect_url = AdminCustomizeUrlBuilder::editor_css_file($theme_selected, str_replace('@', '', $file_name));
			}

			$css_file->write(TextHelper::html_entity_decode($this->form->get_value('css_file')));
		}
		
		AppContext::get_response()->redirect($redirect_url, $this->lang['warning.process.success']);
	}

	private function list_themes()
	{
		$choices_list = array();
		$choices_list[] = new FormFieldSelectChoiceOption('--', '');
		foreach (ThemesManager::get_activated_themes_map() as $id => $value)
		{
			$choices_list[] = new FormFieldSelectChoiceOption($value->get_configuration()->get_name(), $id);
		}
		return $choices_list;
	}

	private function list_files($theme_selected)
	{
		$files = $theme_css_files_list = array();
		$files[] = new FormFieldSelectChoiceOption('--', '');

		// Selected theme CSS files
		$folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path);
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.css$`') as $file)
			{
				$theme_css_files_list[] = $file->get_name();
				$files[] = new FormFieldSelectChoiceOption($file->get_name(), str_replace('@', '', $file->get_name()));
			}
		}
		else
			$this->view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.page.unexists', 'warning-lang'), MessageHelper::WARNING));

		// __default__ CSS files
		$folder = new Folder(PATH_TO_ROOT . $this->templates_path . $this->default_css_files_path);
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.css$`') as $file)
			{
				if (!in_array($file->get_name(), $theme_css_files_list))
					$files[] = new FormFieldSelectChoiceOption($this->lang['customization.default.theme'] . ' : ' . $file->get_name(), '__default__/' . str_replace('@', '', $file->get_name()));
			}
		}
		
		// Modules CSS files
		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
		{
			$folder = new Folder(PATH_TO_ROOT . '/' . $module->get_id() . '/templates');
			if ($folder->exists())
			{
				foreach ($folder->get_files('`\.css$`') as $file)
				{
					$is_override = false;
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module->get_id() . '/' . $file->get_name());
					if ($css_file->exists())
						$is_override = true;
					
					$files[] = new FormFieldSelectChoiceOption(LangLoader::get_message('common.module', 'common-lang') . ' ' . ModulesManager::get_module($module->get_id())->get_configuration()->get_name() . ' : ' . $file->get_name() . ($is_override ? ' *' : ''), $module->get_id() . '/' . str_replace('@', '', $file->get_name()));
				}
			}
		}

		// User CSS files
		$folder = new Folder(PATH_TO_ROOT . '/user/templates');
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.css$`') as $file)
			{
				$is_override = false;
				$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . 'user/' . $file->get_name());
				if ($css_file->exists())
					$is_override = true;
				
				$files[] = new FormFieldSelectChoiceOption(LangLoader::get_message('user.users', 'user-lang') . ' : ' . $file->get_name() . ($is_override ? ' *' : ''), 'user/' . $file->get_name());
			}
		}

		return $files;
	}

	private function update_import_css($theme_selected, $old_file_path, $new_file_path)
	{
		$import_css_file_path = PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . '@import.css';
		$import_css_file = new File($import_css_file_path);
		if ($import_css_file->exists())
		{
			$content = str_replace($old_file_path, $new_file_path, $import_css_file->read());
			$import_css_file->erase();
			$import_css_file->write($content);
		}
	}
}
?>
