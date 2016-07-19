<?php
/*##################################################
 *                              AdminCalendarConfigController.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
			$this->form->get_field_by_id('birthday_color')->set_hidden(!$this->config->is_members_birthday_enabled());
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		//Display the form on the template
		$tpl->put('FORM', $this->form->display());
		
		//Display the generated page
		return new AdminCalendarDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->config = CalendarConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		//Configuration
		$fieldset = new FormFieldsetHTML('configuration_fieldset', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldNumberEditor('items_number_per_page', $this->lang['calendar.config.items_number_per_page'], $this->config->get_items_number_per_page(), 
			array('min' => 1, 'max' => 50, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', LangLoader::get_message('config.comments_enabled', 'admin-common'), $this->config->are_comments_enabled()));
		
		$fieldset->add_field(new FormFieldColorPicker('event_color', $this->lang['calendar.config.event_color'], $this->config->get_event_color(),
			array(),
			array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('members_birthday_enabled', $this->lang['calendar.config.members_birthday_enabled'], $this->config->is_members_birthday_enabled(),
			array('events' => array('click' => '
				if (HTMLForms.getField("members_birthday_enabled").getValue()) {
					HTMLForms.getField("birthday_color").enable();
				} else {
					HTMLForms.getField("birthday_color").disable();
				}')
			)
		));
		
		$user_born_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_born');
		
		if (!empty($user_born_field) && !$user_born_field['display'])
		{
			$fieldset->add_field(new FormFieldHTML('user_born_disabled_msg', MessageHelper::display($this->lang['calendar.error.e_user_born_field_disabled'], MessageHelper::WARNING)->render()));
		}
		
		$fieldset->add_field(new FormFieldColorPicker('birthday_color', $this->lang['calendar.config.birthday_color'], $this->config->get_birthday_color(),
			array('hidden' => !$this->config->is_members_birthday_enabled()),
			array(new FormFieldConstraintRegex('`^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$`i'))
		));
		
		$common_lang = LangLoader::get('common');
		$fieldset = new FormFieldsetHTML('authorizations_fieldset', $common_lang['authorizations'],
			array('description' => LangLoader::get_message('config.authorizations.explain', 'admin-common'))
		);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.categories_management'], Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS)
		));
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset->add_field($auth_setter);
		
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
		
		$this->config->set_event_color($this->form->get_value('event_color'));
		
		if ($this->form->get_value('members_birthday_enabled'))
		{
			$this->config->enable_members_birthday();
			$this->config->set_birthday_color($this->form->get_value('birthday_color'));
		}
		else
			$this->config->disable_members_birthday();
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		CalendarConfig::save();
		CalendarService::get_categories_manager()->regenerate_cache();
		CalendarCurrentMonthEventsCache::invalidate();
	}
}
?>
