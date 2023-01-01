<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 18
 * @since       PHPBoost 4.1 - 2015 10 08
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizeEditorTPLFilesController extends DefaultAdminModuleController
{
	private $templates_path = '/templates/';
	private $default_tpl_files_path = '/__default__/';
	private $tpl_files_path = '/';
	private $tpl_modules_files_path = '/modules/';

	public function execute(HTTPRequestCustom $request)
	{
		$id_theme = $request->get_value('id_theme', '');
		$id_module = '';
		$file_selected = $request->get_value('file_name', '');

		if (preg_match('`/`u', $file_selected))
		{
			$split = explode('/', $file_selected);
			$id_module = $split[0];
			$file_name = $split[1] . '.tpl';
		}
		else
			$file_name = $file_selected . '.tpl';

		$this->build_form($id_theme, $id_module, $file_name, $file_selected);

		if (!empty($id_theme) && !empty($file_selected) && $this->submit_button->has_been_submited() && $this->form->validate())
			$this->save($id_theme, $id_module, $file_name);

		$this->view->put('CONTENT', $this->form->display());

		return new AdminCustomizationDisplayResponse($this->view, $this->lang['customization.editor.tpl.files']);
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
					'class'  => 'third-field',
					'events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_tpl_file()->rel() . '" + HTMLForms.getField(\'select_theme\').getValue()')
				)
			)
		);

		if (!empty($theme_selected))
		{
			$file_editor_fieldset = new FormFieldsetHTML('file-editor', $this->lang['customization.editor.tpl.files']);
			$form->add_fieldset($file_editor_fieldset);

			$file_editor_fieldset->add_field(
				new FormFieldSimpleSelectChoice('select_file', $this->lang['customization.editor.files.select'], $file_selected,
					$this->list_files($theme_selected),
					array(
						'class'       => 'third-field',
						'description' => $this->lang['customization.editor.files.select.clue'],
						'events'      => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_tpl_file($theme_selected)->rel() . '" + "/" + HTMLForms.getField(\'select_file\').getValue()')
					)
				)
			);

			if (!empty($file_selected))
			{
				if (!empty($module_selected) && $module_selected != '__default__')
				{
					$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected . '/' . $file_name);
					if (!$tpl_file->exists())
						$tpl_file = new File(PATH_TO_ROOT . '/' . $module_selected . '/templates/' . $file_name);
				}
				else
				{
					$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_files_path . $file_name);
					
					if (!$tpl_file->exists())
						$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $this->default_tpl_files_path . $file_name);
				}

				if ($tpl_file->exists())
				{
					$file_editor_fieldset->add_field(new FormFieldMultiLineTextEditor('tpl_file', $this->lang['customization.editor.files.content'], TextHelper::htmlspecialchars($tpl_file->read()),
						array('rows' => 30, 'class' => "lined")
					));
				}
				
				$display_remove_override_button = false;
				if (!empty($module_selected) && $module_selected != '__default__')
				{
					$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected . '/' . $file_name);
					if ($tpl_file->exists())
						$display_remove_override_button = true;
				}
				else
				{
					$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_files_path . $file_name);
					$default_tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $this->default_tpl_files_path . $file_name);
					if ($tpl_file->exists() && $default_tpl_file->exists())
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
				$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected . '/' . $file_name);
				if ($tpl_file->exists())
					$tpl_file->delete();

				$module_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected);
				if ($module_folder->exists() && !$module_folder->get_all_content())
					$module_folder->delete();

				$modules_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path);
				if ($modules_folder->exists() && !$modules_folder->get_all_content())
					$modules_folder->delete();
				
				$redirect_url = AdminCustomizeUrlBuilder::editor_tpl_file($theme_selected, $module_selected . '/' . str_replace('.tpl' , '', $file_name));
			}
			else
			{
				$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_files_path . $file_name);
				if ($tpl_file->exists())
					$tpl_file->delete();
				
				$redirect_url = AdminCustomizeUrlBuilder::editor_tpl_file($theme_selected, '__default__/' . str_replace('.tpl' , '', $file_name));
			}
		}
		else
		{
			if (!empty($module_selected) && $module_selected != '__default__')
			{
				$modules_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path);
				if (!$modules_folder->exists())
					$modules_folder->create();

				$module_folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected);
				if (!$module_folder->exists())
					$module_folder->create();

				$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected . '/' . $file_name);

				if (!$tpl_file->exists())
					copy(PATH_TO_ROOT . '/' . $module_selected . '/templates/' . $file_name, PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module_selected . '/' . $file_name);

				$redirect_url = AdminCustomizeUrlBuilder::editor_tpl_file($theme_selected, $module_selected . '/' . str_replace('.tpl' , '', $file_name));
			}
			else
			{
				$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_files_path . $file_name);

				if (!$tpl_file->exists())
				{
					copy(PATH_TO_ROOT . $this->templates_path . $this->default_tpl_files_path . $file_name, PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_files_path . $file_name);
				}

				$redirect_url = AdminCustomizeUrlBuilder::editor_tpl_file($theme_selected, str_replace('.tpl' , '', $file_name));
			}

			$tpl_file->write(TextHelper::html_entity_decode($this->form->get_value('tpl_file')));
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
		$files = $theme_tpl_files_list = array();
		$files[] = new FormFieldSelectChoiceOption('--', '');

		// Selected theme TPL files
		$folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_files_path);
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.tpl$`') as $file)
			{
				$theme_tpl_files_list[] = $file->get_name();
				$files[] = new FormFieldSelectChoiceOption($file->get_name(), $file->get_name_without_extension());
			}
		}
		else
			$this->view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.page.unexists', 'warning-lang'), MessageHelper::WARNING));

		// __default__ TPL files
		$folder = new Folder(PATH_TO_ROOT . $this->templates_path . $this->default_tpl_files_path);
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.tpl$`') as $file)
			{
				if (!in_array($file->get_name(), $theme_tpl_files_list))
					$files[] = new FormFieldSelectChoiceOption($this->lang['customization.default.theme'] . ' : ' . $file->get_name(), '__default__/' . $file->get_name_without_extension());
			}
		}

		// Modules TPL files
		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
		{
			$folder = new Folder(PATH_TO_ROOT . '/' . $module->get_id() . '/templates');
			if ($folder->exists())
			{
				foreach ($folder->get_files('`\.tpl$`') as $file)
				{
					$is_override = false;
					$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . $module->get_id() . '/' . $file->get_name());
					if ($tpl_file->exists())
						$is_override = true;
					
					$files[] = new FormFieldSelectChoiceOption(LangLoader::get_message('common.module', 'common-lang') . ' ' . ModulesManager::get_module($module->get_id())->get_configuration()->get_name() . ' : ' . $file->get_name() . ($is_override ? ' *' : ''), $module->get_id() . '/' . $file->get_name_without_extension());
				}
			}
		}

		// User TPL files
		$folder = new Folder(PATH_TO_ROOT . '/user/templates');
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.tpl$`') as $file)
			{
				$is_override = false;
				$tpl_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->tpl_modules_files_path . 'user/' . $file->get_name());
				if ($tpl_file->exists())
					$is_override = true;
				
				$files[] = new FormFieldSelectChoiceOption(LangLoader::get_message('user.users', 'user-lang') . ' : ' . $file->get_name() . ($is_override ? ' *' : ''), 'user/' . $file->get_name_without_extension());
			}
		}

		return $files;
	}
}
?>
