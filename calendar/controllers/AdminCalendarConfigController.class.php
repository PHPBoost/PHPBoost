<?php
/*##################################################
 *                              AdminCalendarConfigController.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class AdminCalendarConfigController extends AdminModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	/**
	 * @var CalendarConfig
	 */
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['calendar.success.config'], E_USER_SUCCESS, 5));
		}
		
		//Display the form on the template
		$tpl->put('FORM', $this->form->display());
		
		//Display the generated page
		return new AdminCalendarDisplayResponse($tpl, $this->lang['calendar.titles.admin.config']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('calendar_common', 'calendar');
		$this->config = CalendarConfig::load();
	}
	
	private function build_form()
	{
		//Creation of a new form
		$form = new HTMLForm(__CLASS__);
		
		//Configuration fieldset
		$fieldset_config = new FormFieldsetHTML('configuration_fieldset', $this->lang['calendar.titles.admin.config']);
		$form->add_fieldset($fieldset_config);
		
		$fieldset_config->add_field(new FormFieldTextEditor('items_number_per_page', $this->lang['calendar.config.items_number_per_page'], $this->config->get_items_number_per_page(), 
			array('size' => 6, 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i')
		)));
		
		$fieldset_config->add_field(new FormFieldCheckbox('comments_enabled', $this->lang['calendar.config.comments_enabled'], $this->config->is_comment_enabled()));
		
		$fieldset_config->add_field(new FormFieldCheckbox('location_enabled', $this->lang['calendar.config.location_enabled'], $this->config->is_location_enabled()));
		
		//Authorizations fieldset
		$fieldset_authorizations = new FormFieldsetHTML('authorizations_fieldset', $this->lang['calendar.titles.admin.authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['calendar.config.authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['calendar.config.authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['calendar.config.authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->lang['calendar.config.authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);
		
		//Submit and reset buttons
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		
		if ($this->form->get_value('comments_enabled'))
			$this->config->enable_comments();
		else
			$this->config->disable_comments();
		
		if ($this->form->get_value('location_enabled'))
			$this->config->enable_location();
		else
			$this->config->disable_location();
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		CalendarConfig::save();
		CalendarService::get_categories_manager()->regenerate_cache();
	}
}
?>