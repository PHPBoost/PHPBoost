<?php
/*##################################################
 *                       AdminCustomizeInterfaceController.class.php
 *                            -------------------
 *   begin                : August 30, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class AdminCustomizeInterfaceController extends AdminController
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
	
	private $config;

	public function execute(HTTPRequest $request)
	{
		$this->load_lang();
		$this->load_config();
		
		$theme = $request->get_value('theme', 'all');
		
		if ($theme !== 'all' && !ThemeManager::get_theme_existed($theme))
		{
			AppContext::get_response()->redirect(AdminCustomizeUrlBuilder::customize_interface()->absolute());
		}
		
		$this->build_form($theme);

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$header_logo = $this->form->get_value('header_logo', null);
			
			if($header_logo !== null)
			{
				$file_type = new FileType(new File($header_logo->get_name()));
				if ($file_type->is_picture())
				{
					$this->save($header_logo, $theme);
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'errors-common'), E_USER_SUCCESS, 4));
				}
				else
				{
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.error', 'errors-common'), E_USER_ERROR, 4));
				}
			}
			elseif ($this->form->get_value('use_default_logo'))
			{
				$this->delete_pictures_saved($theme);
				$tpl->put('MSG', MessageHelper::display($this->lang['customization.interface.success'], E_USER_SUCCESS, 4));
			}
			else
			{
				$tpl->put('MSG', MessageHelper::display($this->lang['customization.interface.logo.error'], E_USER_ERROR, 4));
			}
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.interface']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-customization-common');
	}
	
	private function load_config()
	{
		$this->config = CustomizationConfig::load();
	}

	private function build_form($theme_selected)
	{
		$form = new HTMLForm('customize-interface');
		
		$theme_choise_fieldset = new FormFieldsetHTML('theme-choise', $this->lang['customization.interface.theme-choise']);
		$form->add_fieldset($theme_choise_fieldset);
		
		$theme_choise_fieldset->add_field(
			new FormFieldSimpleSelectChoice('select_theme', $this->lang['customization.interface.select-theme'], $theme_selected,
				$this->list_themes(), 
				array('events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::customize_interface()->absolute() . '" + HTMLForms.getField(\'select_theme\').getValue()'))
			)
		);
		
		$customize_interface_fieldset = new FormFieldsetHTML('customize_interface', $this->lang['customization.interface']);
		$form->add_fieldset($customize_interface_fieldset);
		
		$header_logo_path = $this->get_header_logo_path($theme_selected);
		if (!empty($header_logo_path))
		{
			$header_logo_file = new File(PATH_TO_ROOT . $header_logo_path);

			if ($header_logo_file->exists())
			{
				$picture = '<img src="' . $header_logo_file->get_path() . '">';
				$customize_interface_fieldset->add_field(new FormFieldFree('current_logo', $this->lang['customization.interface.logo.current'], $picture));
			}
			else
			{
				$customize_interface_fieldset->add_field(new FormFieldFree('current_logo', $this->lang['customization.interface.logo.current'], $this->lang['customization.interface.logo.current.erased']));
			}
		}
		else 
		{
			$customize_interface_fieldset->add_field(new FormFieldFree('current_logo', $this->lang['customization.interface.logo.current'], $this->lang['customization.interface.logo.current.null']));
		}

		$customize_interface_fieldset->add_field(new FormFieldFilePicker('header_logo', $this->lang['customization.interface.logo.current.change']));
		
		$customize_interface_fieldset->add_field(new FormFieldCheckbox('use_default_logo', $this->lang['customization.interface.logo.use-default'], FormFieldCheckbox::UNCHECKED));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save($header_logo, $theme_selected)
	{
		$save_destination = new File(PATH_TO_ROOT . '/images/customization/' . $theme_selected . '_'. $header_logo->get_name());
		$header_logo->save($save_destination);
		
		$this->delete_older($theme_selected);
		
		if ($theme_selected !== 'all')
		{
			$theme = ThemeManager::get_theme($theme_selected);
			$customize_interface = $theme->get_customize_interface();
			$customize_interface->set_header_logo_path($save_destination->get_path_from_root());
			ThemeManager::change_customize_interface($theme_selected, $customize_interface);
			
			if ($this->config->get_header_logo_path_all_themes() !== null)
			{
				$this->config->remove_header_logo_path_all_themes();
			}
		}
		else
		{
			foreach (ThemeManager::get_activated_themes_map() as $id => $theme) 
			{
				$customize_interface = $theme->get_customize_interface();
				$customize_interface->set_header_logo_path($save_destination->get_path_from_root());
				ThemeManager::change_customize_interface($id, $customize_interface);
			}
					
			$this->config->set_header_logo_path_all_themes($save_destination->get_path_from_root());
			CustomizationConfig::save();			
		}
	}
	
	private function delete_pictures_saved($theme_selected)
	{
		$this->delete_older($theme_selected);
		
		if ($theme_selected !== 'all')
		{
			$theme = ThemeManager::get_theme($theme_selected);
			$customize_interface = $theme->get_customize_interface();
			$customize_interface->remove_header_logo_path();
			ThemeManager::change_customize_interface($theme_selected, $customize_interface);
		}
		else
		{
			foreach (ThemeManager::get_activated_themes_map() as $id => $theme) 
			{
				$customize_interface = $theme->get_customize_interface();
				$customize_interface->remove_header_logo_path();
				ThemeManager::change_customize_interface($id, $customize_interface);
			}
			
			$this->config->remove_header_logo_path_all_themes();
			CustomizationConfig::save();			
		}
	}
	
	private function list_themes()
	{
		$choices_list = array();
		$choices_list[] = new FormFieldSelectChoiceOption($this->lang['customization.interface.all-themes'], 'all');
		foreach (ThemeManager::get_activated_themes_map() as $id => $value) 
		{
			$choices_list[] = new FormFieldSelectChoiceOption($value->get_configuration()->get_name(), $id);
		}
		return $choices_list;
	}
	
	private function get_header_logo_path($theme)
	{
		if ($theme == 'all')
		{
			return $this->config->get_header_logo_path_all_themes();
		}
		else
		{
			$theme = ThemeManager::get_theme($theme);
			$customize_interface = $theme->get_customize_interface();
			return $customize_interface->get_header_logo_path();
		}
	}
	
	private function delete_older($theme)
	{
		$file = new File(PATH_TO_ROOT . '/' . $this->get_header_logo_path($theme));
		if ($file->exists())
		{
			$file->delete();
		}
	}
}
?>