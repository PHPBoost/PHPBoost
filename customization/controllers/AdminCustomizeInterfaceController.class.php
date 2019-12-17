<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 08 02
 * @since       PHPBoost 3.0 - 2011 09 26
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCustomizeInterfaceController extends AdminModuleController
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

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_lang();
		$this->load_config();

		$theme = $request->get_value('theme', 'all');

		if ($theme !== 'all' && !ThemesManager::get_theme_existed($theme))
		{
			AppContext::get_response()->redirect(AdminCustomizeUrlBuilder::customize_interface());
		}

		$this->build_form($theme);

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$header_logo = $this->form->get_value('header_logo', null);

			if ($header_logo !== null)
			{
				$file_type = new FileType(new File($header_logo->get_name()));
				if ($file_type->is_picture())
				{
					$this->save($header_logo, $theme);
					$header_logo_path = $this->get_header_logo_path($theme);
					$header_logo_file = new File(PATH_TO_ROOT . $header_logo_path);
					$picture = '<img src="' . Url::to_rel($header_logo_file->get_path()) . '" alt="' . $this->lang['customization.interface.logo.current'] . '" />';
					$this->form->get_field_by_id('current_logo')->set_value($picture);
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
				}
				else
				{
					$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('form.invalid_picture', 'status-messages-common'), MessageHelper::ERROR, 4));
				}
			}
			elseif ($this->form->get_value('use_default_logo'))
			{
				$this->delete_pictures_saved($theme);
				$this->form->get_field_by_id('current_logo')->set_value($this->lang['customization.interface.logo.current.null']);
				$this->form->get_field_by_id('use_default_logo')->set_value(FormFieldCheckbox::UNCHECKED);
				$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
			}
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCustomizationDisplayResponse($tpl, $this->lang['customization.interface']);
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('common', 'customization');
	}

	private function load_config()
	{
		$this->config = CustomizationConfig::load();
	}

	private function build_form($theme_selected)
	{
		$form = new HTMLForm(__CLASS__);

		$theme_choise_fieldset = new FormFieldsetHTML('theme-choice', $this->lang['customization.interface.theme-choice']);
		$form->add_fieldset($theme_choise_fieldset);

		$theme_choise_fieldset->add_field(
			new FormFieldSimpleSelectChoice('select_theme', $this->lang['customization.interface.select-theme'], $theme_selected,
				$this->list_themes(),
				array('class' => 'third-field', 'events' => array('change' => 'document.location.href = "' . AdminCustomizeUrlBuilder::customize_interface()->rel() . '" + HTMLForms.getField(\'select_theme\').getValue()'))
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
				$picture = '<img src="' . Url::to_rel($header_logo_file->get_path()) . '" alt="' . $this->lang['customization.interface.logo.current'] . '" />';
				$customize_interface_fieldset->add_field(new FormFieldFree('current_logo', $this->lang['customization.interface.logo.current'], $picture,
					array('class' => 'third-field top-field')
				));
			}
			else
			{
				$customize_interface_fieldset->add_field(new FormFieldFree('current_logo', $this->lang['customization.interface.logo.current'], '<span class="text-strong error">' . $this->lang['customization.interface.logo.current.erased'] .'</span>',
					array('class' => 'third-field top-field')
				));
			}
		}
		else
		{
			$customize_interface_fieldset->add_field(new FormFieldFree('current_logo', $this->lang['customization.interface.logo.current'], $this->lang['customization.interface.logo.current.null'],
				array('class' => 'third-field top-field')
			));
		}

		$customize_interface_fieldset->add_field(new FormFieldFilePicker('header_logo', $this->lang['customization.interface.logo.current.change'],
			array('class' => 'third-field', 'description' => $this->lang['customization.interface.logo.current.change-explain']),
			array(new FormFieldConstraintPictureFile())
		));

		$customize_interface_fieldset->add_field(new FormFieldCheckbox('use_default_logo', $this->lang['customization.interface.logo.use-default'], FormFieldCheckbox::UNCHECKED,
			array('class' => 'third-field top-field custom-checkbox')
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save($header_logo, $theme_selected)
	{
		$save_destination = new File(PATH_TO_ROOT . '/images/customization/' . $theme_selected . '_'. $header_logo->get_name());
		$header_logo->save($save_destination);

		$this->delete_older($theme_selected);

		if ($theme_selected !== 'all')
		{
			$theme = ThemesManager::get_theme($theme_selected);
			$customize_interface = $theme->get_customize_interface();
			$customize_interface->set_header_logo_path($save_destination->get_path_from_root());
			ThemesManager::change_customize_interface($theme_selected, $customize_interface);

			if ($this->config->get_header_logo_path_all_themes() !== null)
			{
				$this->config->remove_header_logo_path_all_themes();
			}
		}
		else
		{
			foreach (ThemesManager::get_activated_themes_map() as $id => $theme)
			{
				$customize_interface = $theme->get_customize_interface();
				$customize_interface->set_header_logo_path($save_destination->get_path_from_root());
				ThemesManager::change_customize_interface($id, $customize_interface);
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
			$theme = ThemesManager::get_theme($theme_selected);
			$customize_interface = $theme->get_customize_interface();
			$customize_interface->remove_header_logo_path();
			ThemesManager::change_customize_interface($theme_selected, $customize_interface);
		}
		else
		{
			foreach (ThemesManager::get_activated_themes_map() as $id => $theme)
			{
				$customize_interface = $theme->get_customize_interface();
				$customize_interface->remove_header_logo_path();
				ThemesManager::change_customize_interface($id, $customize_interface);
			}

			$this->config->remove_header_logo_path_all_themes();
			CustomizationConfig::save();
		}
	}

	private function list_themes()
	{
		$choices_list = array();
		$choices_list[] = new FormFieldSelectChoiceOption($this->lang['customization.interface.all-themes'], 'all');
		foreach (ThemesManager::get_activated_themes_map() as $id => $value)
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
			$theme = ThemesManager::get_theme($theme);
			$customize_interface = $theme->get_customize_interface();
			return $customize_interface->get_header_logo_path();
		}
	}

	private function delete_older($theme)
	{
		$logo = $this->get_header_logo_path($theme);
		if (!empty($logo))
		{
			$file = new File(PATH_TO_ROOT . '/' . $logo);
			if ($file->exists())
			{
				$file->delete();
			}
		}
	}
}
?>
