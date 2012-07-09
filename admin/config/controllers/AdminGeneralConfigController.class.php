<?php
/*##################################################
 *                       AdminGeneralConfigController.class.php
 *                            -------------------
 *   begin                : August 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminGeneralConfigController extends AdminController
{
	private $lang;
	private $general_config;
	private $graphical_environment_config;
	private $user_accounts_config;
	private $tpl;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->clear_cache();
			AppContext::get_response()->redirect(AdminConfigUrlBuilder::general_config());
		}

		$this->tpl->put('FORM', $this->form->display());

		return new AdminConfigDisplayResponse($this->tpl, $this->lang['general-config']);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE FORM #');

		$this->load_lang();
		$this->tpl->add_lang($this->lang);

		$this->load_config();
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}

	private function load_config()
	{
		$this->general_config = GeneralConfig::load();
		$this->graphical_environment_config = GraphicalEnvironmentConfig::load();
		$this->user_accounts_config = UserAccountsConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm('general-config');

		$fieldset = new FormFieldsetHTML('general-config', $this->lang['general-config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('site_name', $this->lang['general-config.site_name'], $this->general_config->get_site_name(),
			array('class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true)
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('site_description', $this->lang['general-config.site_description'], $this->general_config->get_site_description(),
			array('rows' => 4, 'description' => $this->lang['general-config.site_description-explain'])
		));

		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('site_keywords', $this->lang['general-config.site_keywords'], $this->general_config->get_site_keywords(),
			array('rows' => 4, 'description' => $this->lang['general-config.site_keywords-explain'])
		));

		$fieldset->add_field(new FormFieldLangsSelect('default_language', $this->lang['general-config.default_language'], 
			$this->user_accounts_config->get_default_lang(), 
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldThemesSelect('default_theme', $this->lang['general-config.default_theme'], $this->user_accounts_config->get_default_theme(),
			array('required' => true, 'events' => array('change' => $this->construct_javascript_picture_theme() .
			' var theme_id = HTMLForms.getField("default_theme").getValue();
			document.images[\'img_theme\'].src = theme[theme_id];'))
		));
		
		$fieldset->add_field(new FormFieldFree('picture_theme', $this->lang['general-config.theme_picture'],
			'<a href="'. $this->get_picture_theme() .'" rel="lightbox">
				<img id="img_theme" src="'. $this->get_picture_theme() .'" alt="" style="max-height:180px;max-width:180px;" /></br>
				('. $this->lang['general-config.theme_preview_click'] .')
			</a>'
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('start_page', $this->lang['general-config.start_page'], $this->general_config->get_module_home_page(), $this->list_modules_home_page(),
			array('required' => false, 'events' => array('change' => 
				'if (HTMLForms.getField("start_page").getValue() == "other") {
					HTMLForms.getField("other_start_page").enable();
				} else {
					HTMLForms.getField("other_start_page").disable();	
				}'
			))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('other_start_page', $this->lang['general-config.other_start_page'], $this->general_config->get_other_home_page(),
			array('class' => 'text', 'required' => false, 'hidden' => $this->general_config->get_other_home_page() == '')
		));

		$fieldset->add_field(new FormFieldCheckbox('visit_counter', $this->lang['general-config.visit_counter'], $this->graphical_environment_config->is_visit_counter_enabled()));

		$fieldset->add_field(new FormFieldCheckbox('page_bench', $this->lang['general-config.page_bench'], $this->graphical_environment_config->is_page_bench_enabled(),
			array('description' => $this->lang['general-config.page_bench-explain'])
		));

		$fieldset->add_field(new FormFieldCheckbox('display_theme_author', $this->lang['general-config.display_theme_author'], $this->graphical_environment_config->get_display_theme_author(),
			array('description' => $this->lang['general-config.display_theme_author-explain'])
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->general_config->set_site_name($this->form->get_value('site_name'));
		$this->general_config->set_site_description($this->form->get_value('site_description'));
		$this->general_config->set_site_keywords($this->form->get_value('site_keywords'));

		$other_start_page = $this->form->get_value('other_start_page');

		$module_home_page = $this->form->get_value('start_page')->get_raw_value();
		if ($module_home_page !== 'other')
		{
			$this->general_config->set_module_home_page($module_home_page);
			$this->general_config->set_other_home_page('');
		}
		else
		{
			$this->general_config->set_other_home_page($this->form->get_value('other_start_page'));
		}

		GeneralConfig::save();

		$this->graphical_environment_config->set_visit_counter_enabled($this->form->get_value('visit_counter'));
		$this->graphical_environment_config->set_page_bench_enabled($this->form->get_value('page_bench'));
		$this->graphical_environment_config->set_display_theme_author($this->form->get_value('display_theme_author'));
		GraphicalEnvironmentConfig::save();

		$this->user_accounts_config->set_default_lang($this->form->get_value('default_language')->get_raw_value());
		$this->user_accounts_config->set_default_theme($this->form->get_value('default_theme')->get_raw_value());
		UserAccountsConfig::save();
	}
	
	private function construct_javascript_picture_theme()
    {
    	$text = 'var theme = new Array;' . "\n";
    	foreach (ThemeManager::get_activated_themes_map() as $theme)
    	{
   			$text .= 'theme["' . $theme->get_id() . '"] = "' . $this->get_picture_theme($theme->get_id()) . '";' . "\n";
   		}
    	return $text;
    }
    
	private function get_picture_theme($theme_id = null)
    {
        $theme_id = $theme_id !== null ? $theme_id : $this->user_accounts_config->get_default_theme();
        $pictures = ThemeManager::get_theme($theme_id)->get_configuration()->get_pictures();
    	return Url::to_rel('/templates/' . $theme_id . '/' . $pictures[0]);
    }

	private function list_modules_home_page()
	{
		$options = array();
		$options[] = new FormFieldSelectChoiceOption($this->lang['general-config.other_start_page'], 'other');
		$providers = AppContext::get_extension_provider_service()->get_providers(HomePageExtensionPoint::EXTENSION_POINT);
		foreach ($providers as $id => $provider)
		{
			$module = ModulesManager::get_module($id);
			$configuration = $module->get_configuration();
			$options[] = new FormFieldSelectChoiceOption($configuration->get_name(), $id);
		}
		
		if (empty($options))
		{
			$options[] = new FormFieldSelectChoiceOption($this->lang['no_module_starteable'], '');
		}

		return $options;
	}
	
	private function clear_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
}
?>