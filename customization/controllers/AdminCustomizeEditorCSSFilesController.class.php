<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 26
 * @since       PHPBoost 3.0 - 2011 09 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizeEditorCSSFilesController extends AdminModuleController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	private $view;

	private $templates_path = '/templates/';
	private $css_files_path = '/theme/';
	private $css_modules_files_path = '/modules/';

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

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

		if (!empty($id_theme) && !empty($file_selected))
		{
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$this->save($id_theme, $id_module, $file_name);
				$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
			}
		}

		$this->view->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($this->view, $this->lang['customization.editor.css.files']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'customization');
		$this->view = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->view->add_lang($this->lang);
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
						'class' => 'third-field',
						'events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_css_file($theme_selected)->rel() . '" + "/" + HTMLForms.getField(\'select_file\').getValue()')
					)
				)
			);

			if (!empty($file_selected))
			{
				if (!empty($module_selected))
				{
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);
					if (!$css_file->exists())
						$css_file = new File(PATH_TO_ROOT . '/' . $module_selected . '/templates/' . $file_name);

					$file_editor_fieldset->add_field(new FormFieldHidden('id_theme', $theme_selected));
					$file_editor_fieldset->add_field(new FormFieldHidden('file_name', $file_selected));
				}
				else
				{
					$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);
				}

				if ($css_file->exists())
				{
					$file_editor_fieldset->add_field(new FormFieldMultiLineTextEditor('css_file', $this->lang['customization.editor.files.content'], TextHelper::htmlspecialchars($css_file->read()),
						array('rows' => 30, 'class' => "lined")
					));
				}
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
		$modules_folder = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path);

		if (!$modules_folder->exists())
			mkdir(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path);

		$module_folder = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected);
		if (!$module_folder->exists())
			mkdir(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected);

		if ($module_selected)
			$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);
		else
			$css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_name);

		if (!$css_file->exists())
			copy(PATH_TO_ROOT . '/' . $module_selected . '/templates/' . $file_name, PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_modules_files_path . $module_selected . '/' . $file_name);

		$css_file->write(TextHelper::html_entity_decode($this->form->get_value('css_file')));
		$css_file->close();
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
		$files = array();
		$files[] = new FormFieldSelectChoiceOption('--', '');

		$folder = new Folder(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path);
		if ($folder->exists())
		{
			foreach ($folder->get_files('`\.css$`') as $file)
			{
				$files[] = new FormFieldSelectChoiceOption($file->get_name(), str_replace('@', '', $file->get_name()));
			}
		}
		else
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('warning.page.unexists', 'warning-lang'), MessageHelper::WARNING));

		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
		{
			$folder = new Folder(PATH_TO_ROOT . '/' . $module->get_id() . '/templates');
			if ($folder->exists())
			{
				foreach ($folder->get_files('`\.css$`') as $file)
				{
					$files[] = new FormFieldSelectChoiceOption(LangLoader::get_message('common.module', 'common-lang') . ' ' . ModulesManager::get_module($module->get_id())->get_configuration()->get_name() . ' : ' . $file->get_name(), $module->get_id() . '/' . str_replace('@', '', $file->get_name()));
				}
			}
		}

		return $files;
	}
}
?>
