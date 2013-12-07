<?php
/*##################################################
 *                              AdminCalendarConfigController.class.php
 *                            -------------------
 *   begin                : November 20, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
	private $admin_common_lang;
	
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
			$tpl->put('MSG', MessageHelper::display($this->admin_common_lang['message.success.config'], E_USER_SUCCESS, 5));
		}
		
		
		//Display the form on the template
		$tpl->put('FORM', $this->form->display());
		
		//Display the generated page
		return new AdminCalendarDisplayResponse($tpl, LangLoader::get_message('configuration', 'admin'));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->admin_common_lang = LangLoader::get('admin-common');
		$this->config = CalendarConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		//Configuration
		$fieldset = new FormFieldsetHTML('configuration_fieldset', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('items_number_per_page', $this->lang['calendar.config.items_number_per_page'], $this->config->get_items_number_per_page(), 
			array('size' => 3, 'maxlength' => 2, 'required' => true),
			array(new FormFieldConstraintIntegerRange(1, 50))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('comments_enabled', $this->admin_common_lang['admin.config.comments_enabled'], $this->config->are_comments_enabled()));
		
		$fieldset->add_field(new FormFieldCheckbox('members_birthday_enabled', $this->lang['calendar.config.members_birthday_enabled'], $this->config->is_members_birthday_enabled(),
			array('events' => array('click' => '
				if (HTMLForms.getField("members_birthday_enabled").getValue()) {
					HTMLForms.getField("birthday_color").enable();
				} else {
					HTMLForms.getField("birthday_color").disable();
				}')
			)
		));
		
		$fieldset->add_field(new FormFieldColorPicker('birthday_color', $this->lang['calendar.config.birthday_color'], $this->config->get_birthday_color(),
			array('hidden' => !$this->config->is_members_birthday_enabled())
		));
		
		//Authorizations
		$fieldset = new FormFieldsetHTML('authorizations_fieldset', $this->admin_common_lang['authorizations']);
		$form->add_fieldset($fieldset);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->admin_common_lang['authorizations.read'], Category::READ_AUTHORIZATIONS),
			new ActionAuthorization($this->admin_common_lang['authorizations.write'], Category::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($this->admin_common_lang['authorizations.contribution'], Category::CONTRIBUTION_AUTHORIZATIONS),
			new ActionAuthorization($this->admin_common_lang['authorizations.moderation'], Category::MODERATION_AUTHORIZATIONS),
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
