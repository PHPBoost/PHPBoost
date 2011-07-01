<?php
/*##################################################
 *                       AdminGeneralConfigController.class.php
 *                            -------------------
 *   begin                : July 1, 2011
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

class AdminGeneralConfigController extends AdminController
{
	private $lang;
	private $general_config;
	private $graphical_environment_config;
	private $user_accounts_config;
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
		$this->load_lang();
		$this->load_config();
		
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			$tpl->put('MSG', MessageHelper::display($this->lang['general-config.success'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminConfigDisplayResponse($tpl, $this->lang['general-config']);
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
		
		$fieldset->add_field(new FormFieldTextEditor('name_site', $this->lang['general-config.site_name'], $this->general_config->get_site_name(), array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('site_description', $this->lang['general-config.site_description'], $this->general_config->get_site_description(),
			array('rows' => 4, 'description' => $this->lang['general-config.site_description-explain'])
		));
		
		$fieldset->add_field(new FormFieldShortMultiLineTextEditor('site_keywords', $this->lang['general-config.site_keywords'], $this->general_config->get_site_keywords(),
			array('rows' => 4, 'description' => $this->lang['general-config.site_keywords-explain'])
		));
		
		$fieldset->add_field(new FormFieldCheckbox('visit_counter', $this->lang['general-config.visit_counter'], $this->graphical_environment_config->is_visit_counter_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('page_bench', $this->lang['general-config.page_bench'], $this->graphical_environment_config->is_page_bench_enabled(), 
		array('description' => $this->lang['general-config.page_bench-explain'])));
		
		$fieldset->add_field(new FormFieldCheckbox('display_theme_author', $this->lang['general-config.display_theme_author'], $this->graphical_environment_config->get_display_theme_author(), 
		array('description' => $this->lang['general-config.display_theme_author-explain'])));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}
	
	private function save()
	{
	
	}
}
?>