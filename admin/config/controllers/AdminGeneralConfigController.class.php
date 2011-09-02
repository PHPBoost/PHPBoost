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
	private $admin_lang;
	private $main_lang;
	private $themes_lang;
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->clear_cache();

			$this->tpl->put('MSG', MessageHelper::display($this->lang['general-config.success'], E_USER_SUCCESS, 4));
		}

		$this->tpl->put('FORM', $this->form->display());

		return new AdminConfigDisplayResponse($this->tpl, $this->lang['general-config']);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('<script type="text/javascript" src="{PATH_TO_ROOT}/kernel/lib/js/lightbox/lightbox.js"></script>
			# INCLUDE MSG # # INCLUDE FORM #');

		$this->load_lang();
		$this->tpl->add_lang($this->lang);

		$this->load_config();
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
		$this->admin_lang = LangLoader::get('admin');
		$this->main_lang = LangLoader::get('main');
		$this->themes_lang = LangLoader::get('admin-themes-common');
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

		//Site name
		$fieldset->add_field(new FormFieldTextEditor('site_name', $this->lang['general-config.site_name'], $this->general_config->get_site_name(),
			array('class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true)
		));

		//Site description
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('site_description', $this->lang['general-config.site_description'], $this->general_config->get_site_description(),
			array('rows' => 4, 'description' => $this->lang['general-config.site_description-explain'])
		));

		//Site keywords
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('site_keywords', $this->lang['general-config.site_keywords'], $this->general_config->get_site_keywords(),
			array('rows' => 4, 'description' => $this->lang['general-config.site_keywords-explain'])
		));

		//Default language
		$fieldset->add_field(new FormFieldSimpleSelectChoice('default_language', $this->admin_lang['default_language'], $this->user_accounts_config->get_default_lang(), $this->list_langs(),
			array('required' => true)
		));

		//Default theme
		$fieldset->add_field(new FormFieldSimpleSelectChoice('default_theme', $this->admin_lang['default_theme'], $this->user_accounts_config->get_default_theme(), $this->list_themes(),
			array('required' => true, 'events' => array('change' => 'document.images[\'img_theme\'].src = "'. PATH_TO_ROOT .'/templates/" +
			HTMLForms.getField(\'default_theme\').getValue() + "/theme/images/theme.jpg"'))
		));

		//Picture of the default theme
		$fieldset->add_field(new FormFieldFree('picture_theme', $this->themes_lang['themes.view_real_preview'],
			'<a href="'. PATH_TO_ROOT .'/templates/'. $this->user_accounts_config->get_default_theme() .'/theme/images/theme.jpg" 
			rel="lightbox" title="' . $this->user_accounts_config->get_default_theme() . '">
				<img id="img_theme" src="'. PATH_TO_ROOT .'/templates/'. $this->user_accounts_config->get_default_theme() .'/theme/images/theme.jpg" 
				alt="" style="vertical-align:top;max-height:180px;max-width:180px;" />
			</a>'
			));

		//Link of the default theme picture
		$fieldset->add_field(new FormFieldFree('link_picture_theme', $this->main_lang['preview'],
			'<a href="'. PATH_TO_ROOT .'/templates/'. $this->user_accounts_config->get_default_theme() .'/theme/images/theme.jpg" 
			rel="lightbox" title="' . $this->user_accounts_config->get_default_theme() . '">'
			. $this->themes_lang['themes.view_real_preview'] .'</a>'
		));

		//Default start page
		$fieldset->add_field(new FormFieldSimpleSelectChoice('start_page', $this->admin_lang['start_page'], $this->general_config->get_home_page(), $this->list_modules_installed(),
			array('required' => false)
		));

		//Alternative start page
		$fieldset->add_field(new FormFieldTextEditor('other_start_page', $this->admin_lang['other_start_page'], $this->general_config->get_home_page(),
			array('class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => false)
		));

		//Enabling visit counter
		$fieldset->add_field(new FormFieldCheckbox('visit_counter', $this->lang['general-config.visit_counter'], $this->graphical_environment_config->is_visit_counter_enabled()));

		//Enabling page bench
		$fieldset->add_field(new FormFieldCheckbox('page_bench', $this->lang['general-config.page_bench'], $this->graphical_environment_config->is_page_bench_enabled(),
			array('description' => $this->lang['general-config.page_bench-explain'])
		));

		//Enabling display of theme author
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
		//Save all configurations
		$this->general_config->set_site_name($this->form->get_value('site_name'));
		$this->general_config->set_site_description($this->form->get_value('site_description'));
		$this->general_config->set_site_keywords($this->form->get_value('site_keywords'));

		$start_page = $this->form->get_value('start_page')->get_raw_value();

		if (!empty($start_page))
		{
			$this->general_config->set_home_page($this->form->get_value('start_page')->get_raw_value());
		}
		else
		{
			$this->general_config->set_home_page($this->form->get_value('other_start_page'));
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

	private function list_langs()
	{
		//List languages for select form input
		$choices_list = array();
		$langs_cache = LangsCache::load();
		foreach($langs_cache->get_installed_langs() as $lang => $properties)
		{
			if ($properties['auth'] == -1)
			{
				$info_lang = load_ini_file(PATH_TO_ROOT .'/lang/', $lang);
				$choices_list[] = new FormFieldSelectChoiceOption($info_lang['name'], $lang);
			}

		}
		return $choices_list;
	}

	private function list_themes()
	{
		//List themes for select form input
		$choices_list = array();
		foreach (ThemeManager::get_activated_themes_map() as $id => $value)
		{
			$choices_list[] = new FormFieldSelectChoiceOption($value->get_configuration()->get_name(), $id);
		}
		return $choices_list;
	}

	private function list_modules_installed()
	{
		//List modules installed's start page for select form input
		$start_page = array();
		$i = 0;
		foreach (ModulesManager::get_activated_modules_ids_list() as $name)
		{
			$module_configuration = ModuleConfigurationManager::get($name);

			if ($module_configuration->get_home_page())
			{
				$get_home_page = '/' . $name . '/' . $module_configuration->get_home_page();
				$start_page[] = new FormFieldSelectChoiceOption($module_configuration->get_name(), $get_home_page);
				$i++;
			}
		}

		if ($i == 0)
		{
			$start_page[] = new FormFieldSelectChoiceOption($this->lang['no_module_starteable'], '');
		}
		return $start_page;
			
	}

	private function clear_cache()
	{
		AppContext::get_cache_service()->clear_cache();
	}
}
?>