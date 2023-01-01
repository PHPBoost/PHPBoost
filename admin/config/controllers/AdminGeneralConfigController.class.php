<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 15
 * @since       PHPBoost 3.0 - 2011 08 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminGeneralConfigController extends DefaultAdminController
{
	private $general_config;
	private $graphical_environment_config;
	private $user_accounts_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->load_config();
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->form->get_field_by_id('other_start_page')->set_hidden($this->general_config->get_module_home_page() != 'other');
			$this->form->get_field_by_id('picture_theme')->set_value('<a href="'. $this->get_picture_theme() .'" data-lightbox="theme" data-rel="lightcase:collection" id="preview_theme">
				<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="' . $this->lang['configuration.theme.picture'] . '" class="admin-theme-img" /><br />
				('. $this->lang['configuration.theme.preview'] .')
			</a>');
			$this->clear_cache();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.config'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminConfigDisplayResponse($this->view, $this->lang['configuration.general']);
	}

	private function load_config()
	{
		$this->general_config = GeneralConfig::load();
		$this->graphical_environment_config = GraphicalEnvironmentConfig::load();
		$this->user_accounts_config = UserAccountsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('general_configuration', $this->lang['configuration.general']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('site_name', $this->lang['configuration.site.name'], $this->general_config->get_site_name(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldTextEditor('site_slogan', $this->lang['configuration.site.slogan'], $this->general_config->get_site_slogan(),
			array('class' => 'half-field')
		));

		$fieldset->add_field(new FormFieldLangsSelect('default_language', $this->lang['configuration.default.language'],
			$this->user_accounts_config->get_default_lang(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('site_description', $this->lang['configuration.site.description'], $this->general_config->get_site_description(),
			array(
				'rows' => 4, 'class' => 'full-field',
				'description' => $this->lang['configuration.site.description.clue']
			)
		));

		$fieldset->add_field(new FormFieldThemesSelect('default_theme', $this->lang['configuration.default.theme'], $this->user_accounts_config->get_default_theme(),
			array(
				'required' => true, 'class' => 'top-field',
				'events' => array('change' => $this->construct_javascript_picture_theme() . '
					var theme_id = HTMLForms.getField("default_theme").getValue();
					jQuery(\'#img_theme\').attr(\'src\', theme[theme_id]);
					jQuery(\'#preview_theme\').attr(\'href\', theme[theme_id]);'
				)
			)
		));

		$fieldset->add_field(new FormFieldFree('picture_theme', $this->lang['configuration.theme.picture'],
			'<a href="'. $this->get_picture_theme() .'" data-lightbox="theme" data-rel="lightcase:collection" id="preview_theme">
				<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="' . $this->lang['configuration.theme.picture'] . '" class="admin-theme-img" /><br />
				('. $this->lang['configuration.theme.preview'] .')
			</a>'
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('start_page', $this->lang['configuration.start.page'], $this->general_config->get_module_home_page(), $this->list_modules_home_page(),
			array(
				'required' => false, 'class' => 'top-field',
				'events' => array('change' => '
					if (HTMLForms.getField("start_page").getValue() == "other") {
						HTMLForms.getField("other_start_page").enable();
					} else {
						HTMLForms.getField("other_start_page").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('other_start_page', $this->lang['configuration.other.start.page'], $this->general_config->get_other_home_page(),
			array(
				'class' => 'top-field', 'required' => false,
				'hidden' => $this->general_config->get_module_home_page() != 'other'
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('visit_counter', $this->lang['configuration.visit.counter'], $this->graphical_environment_config->is_visit_counter_enabled(),
			array('class' => 'third-field custom-checkbox')
		));

		$fieldset->add_field(new FormFieldCheckbox('page_bench', $this->lang['configuration.page.bench'], $this->graphical_environment_config->is_page_bench_enabled(),
			array(
				'class' => 'third-field custom-checkbox',
				'description' => $this->lang['configuration.page.bench.clue']
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('display_theme_author', $this->lang['configuration.display.theme.author'], $this->graphical_environment_config->get_display_theme_author(),
			array(
				'class' => 'third-field custom-checkbox',
				'description' => $this->lang['configuration.display.theme.author.clue']
			)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->general_config->set_site_name($this->form->get_value('site_name'));
		$this->general_config->set_site_slogan($this->form->get_value('site_slogan'));
		$this->general_config->set_site_description($this->form->get_value('site_description'));

		$module_home_page = $this->form->get_value('start_page')->get_raw_value();
		$this->general_config->set_module_home_page($module_home_page);
		if ($module_home_page == 'other')
			$this->general_config->set_other_home_page($this->form->get_value('other_start_page'));
		else
			$this->general_config->set_other_home_page('');

		GeneralConfig::save();

		$this->graphical_environment_config->set_visit_counter_enabled($this->form->get_value('visit_counter'));
		$this->graphical_environment_config->set_page_bench_enabled($this->form->get_value('page_bench'));
		$this->graphical_environment_config->set_display_theme_author($this->form->get_value('display_theme_author'));
		GraphicalEnvironmentConfig::save();

		$this->user_accounts_config->set_default_lang($this->form->get_value('default_language')->get_raw_value());
		$this->user_accounts_config->set_default_theme($this->form->get_value('default_theme')->get_raw_value());
		UserAccountsConfig::save();

		HooksService::execute_hook_action('edit_config', 'kernel', array('title' => $this->lang['configuration.general'], 'url' => AdminConfigUrlBuilder::general_config()->rel()));
	}

	private function construct_javascript_picture_theme()
	{
		$text = 'var theme = new Array;' . "\n";
		$activated_themes = ThemesManager::get_activated_themes_map();
		foreach ($activated_themes as $theme)
		{
			$text .= 'theme["' . $theme->get_id() . '"] = "' . $this->get_picture_theme($theme->get_id()) . '";' . "\n";
		}
		return $text;
	}

	private function get_picture_theme($theme_id = null)
	{
		$theme_id = $theme_id !== null ? $theme_id : $this->user_accounts_config->get_default_theme();
		$picture = ThemesManager::get_theme($theme_id)->get_configuration()->get_first_picture();
		return Url::to_rel('/templates/' . $theme_id . '/' . $picture);
	}

	private function list_modules_home_page()
	{
		$options = array(new FormFieldSelectChoiceOption($this->lang['configuration.other.start.page'], 'other'));

		$installed_modules = ModulesManager::get_activated_modules_map_sorted_by_localized_name();

		foreach ($installed_modules as $id => $module)
		{
			if ((bool)$module->get_configuration()->get_home_page())
			{
				$options[] = new FormFieldSelectChoiceOption($module->get_configuration()->get_name(), $module->get_id());
			}
		}

		if (empty($options))
		{
			$options[] = new FormFieldSelectChoiceOption($this->lang['configuration.no.module.startable'], '');
		}

		return $options;
	}

	private function clear_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
}
?>
