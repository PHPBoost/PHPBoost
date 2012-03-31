<?php
/*##################################################
 *                       AdminCustomizeEditorCSSFilesController.class.php
 *                            -------------------
 *   begin                : September 26, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class AdminCustomizeEditorCSSFilesController extends AdminController
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
	
	private $css_file;
	
	private $templates_path = '/templates/';
	private $css_files_path = '/theme/';

	public function execute(HTTPRequest $request)
	{
		$this->load_lang();
		
		$id_theme = $request->get_value('id_theme', '');
		$file_name = $request->get_value('file_name', '');

		$this->build_form($id_theme, $file_name);
		
		if (!empty($id_theme) && !empty($file_name))
		{
			if ($this->submit_button->has_been_submited() && $this->form->validate())
			{
				$this->save();
				$tpl->put('MSG', MessageHelper::display($this->lang['customization.editor.css-file.success'], E_USER_SUCCESS, 4));
			}
		}

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.editor.css-files']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-customization-common');
	}
	
	private function build_form($theme_selected, $file_selected)
	{
		$form = new HTMLForm('file-editor');
		
		$theme_choise_fieldset = new FormFieldsetHTML('theme-choise', $this->lang['customization.interface.theme-choise']);
		$form->add_fieldset($theme_choise_fieldset);
		
		$theme_choise_fieldset->add_field(
			new FormFieldSimpleSelectChoice('select_theme', $this->lang['customization.interface.select-theme'], $theme_selected,
				$this->list_themes(), 
				array('events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_file()->absolute() . '" + HTMLForms.getField(\'select_theme\').getValue()'))
			)
		);
		
		if (!empty($theme_selected))
		{
			$file_editor_fieldset = new FormFieldsetHTML('file-editor', $this->lang['customization.editor.css-files']);
			$form->add_fieldset($file_editor_fieldset);
		
			$file_editor_fieldset->add_field(
				new FormFieldSimpleSelectChoice('select_file', $this->lang['customization.editor.css-files.select'], $file_selected,
					$this->list_files($theme_selected), 
					array('events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::editor_file($theme_selected)->absolute() . '" + "/" + HTMLForms.getField(\'select_file\').getValue()'))
				)
			);
			
			if (!empty($file_selected))
			{
				$this->css_file = new File(PATH_TO_ROOT . $this->templates_path . $theme_selected . $this->css_files_path . $file_selected);
				$file_editor_fieldset->add_field(new FormFieldMultiLineTextEditor('css_file', $this->lang['customization.editor.css-files.content'], $this->css_file->read(),
					array('rows' => 30)
				));
			}
		}
		
		if (!empty($theme_selected) && !empty($file_selected))
		{
			$form->add_button(new FormButtonReset());
			$this->submit_button = new FormButtonDefaultSubmit();
			$form->add_button($this->submit_button);
		}
		
		$this->form = $form;
	}

	private function save()
	{
		$this->css_file->write($this->form->get_value('css_file'));
		$this->css_file->close();
	}
	
	private function list_themes()
	{
		$choices_list = array();
		$choices_list[] = new FormFieldSelectChoiceOption('--', '');
		foreach (ThemeManager::get_activated_themes_map() as $id => $value) 
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
		foreach ($folder->get_files('`\.css$`') as $file)
		{
			$files[] = new FormFieldSelectChoiceOption($file->get_name(), $file->get_name());
		}
		return $files;
	}
}
?>