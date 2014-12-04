<?php
/*##################################################
 *                   AdminShoutboxConfigController.class.php
 *                            -------------------
 *   begin                : October 14, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class AdminShoutboxConfigController extends AdminModuleController
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
	 * @var ShoutboxConfig
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
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());

		return new AdminShoutboxDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'shoutbox');
		$this->config = ShoutboxConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin-common'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('items_number_per_page', $this->lang['config.items_number_per_page'], $this->config->get_items_number_per_page(),
			array('maxlength' => 3, 'size' => 3, 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('refresh_delay', $this->lang['config.refresh_delay'], $this->config->get_refresh_delay()/60000,
			array('maxlength' => 3, 'size' => 3, 'description' => $this->lang['config.refresh_delay.explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('date_displayed', $this->lang['config.date_displayed'], $this->config->is_date_displayed()));
		
		$fieldset->add_field(new FormFieldTextEditor('max_messages_number', $this->lang['config.max_messages_number'], $this->config->get_max_messages_number(),
			array('maxlength' => 3, 'size' => 3, 'description' => $this->lang['config.max_messages_number.explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldMultipleSelectChoice('forbidden_formatting_tags', LangLoader::get_message('config.forbidden-tags', 'admin-common'), $this->config->get_forbidden_formatting_tags(),
			$this->generate_forbidden_formatting_tags_option(), array('size' => 10)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('max_links_number_per_message', $this->lang['config.max_links_number_per_message'], $this->config->get_max_links_number_per_message(),
			array('maxlength' => 3, 'size' => 3, 'description' => $this->lang['config.max_links_number_per_message.explain'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$common_lang = LangLoader::get('common');
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $common_lang['authorizations']);
		
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($common_lang['authorizations.read'], ShoutboxAuthorizationsService::READ_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.write'], ShoutboxAuthorizationsService::WRITE_AUTHORIZATIONS),
			new ActionAuthorization($common_lang['authorizations.moderation'], ShoutboxAuthorizationsService::MODERATION_AUTHORIZATIONS)
		));
		
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field($auth_setter);  
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_items_number_per_page($this->form->get_value('items_number_per_page'));
		$this->config->set_refresh_delay($this->form->get_value('refresh_delay') * 60000);
		
		if ($this->form->get_value('date_displayed'))
			$this->config->display_date();
		else
			$this->config->hide_date();
		
		$this->config->set_max_messages_number($this->form->get_value('max_messages_number'));
		
		$forbidden_formatting_tags = array();
		foreach ($this->form->get_value('forbidden_formatting_tags') as $field => $option)
		{
			$forbidden_formatting_tags[] = $option->get_raw_value();
		}
		
	 	$this->config->set_forbidden_formatting_tags($forbidden_formatting_tags);
		
		$this->config->set_max_links_number_per_message($this->form->get_value('max_links_number_per_message'));
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		ShoutboxConfig::save();
	}
	
	private function generate_forbidden_formatting_tags_option()
	{
		$options = array();
		$available_tags = AppContext::get_content_formatting_service()->get_available_tags();
		foreach ($available_tags as $identifier => $name)
		{
			$options[] = new FormFieldSelectChoiceOption($name, $identifier);
		}
		return $options;
	}
}
?>
