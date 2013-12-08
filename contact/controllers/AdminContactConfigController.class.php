<?php
/*##################################################
 *                       AdminContactConfigController.class.php
 *                            -------------------
 *   begin                : March 1, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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

class AdminContactConfigController extends AdminController
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
	private $common_lang;
	
	/**
	 * @var GuestbookConfig
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
			$tpl->put('MSG', MessageHelper::display($this->common_lang['message.success.config'], E_USER_SUCCESS, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return new AdminContactDisplayResponse($tpl, $this->lang['module_config_title']);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'contact');
		$this->common_lang = LangLoader::get('common');
		$this->config = ContactConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['admin.config.title'], $this->config->get_title(), array(
			'class' => 'text', 'size' => 40, 'required' => true)
		));
		
		$fieldset->add_field(new FormFieldCheckbox('enable_informations', $this->lang['admin.config.display_informations_bloc'], $this->config->are_informations_enabled(),
			array('description' => $this->lang['admin.config.informations.explain'], 'events' => array('click' => '
				if (HTMLForms.getField("enable_informations").getValue()) {
					HTMLForms.getField("informations_position").enable();
					HTMLForms.getField("informations").enable();
				} else {
					HTMLForms.getField("informations_position").disable();
					HTMLForms.getField("informations").disable();
				}'))
		));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('informations_position', $this->lang['admin.config.informations_position'], $this->config->get_informations_position(),
			array(
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_left'], ContactConfig::LEFT),
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_top'], ContactConfig::TOP),
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_right'], ContactConfig::RIGHT),
				new FormFieldSelectChoiceOption($this->lang['admin.config.informations.position_bottom'], ContactConfig::BOTTOM),
			),
			array('hidden' => !$this->config->are_informations_enabled())
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('informations', $this->lang['admin.config.informations_content'], 
			FormatingHelper::unparse($this->config->get_informations()), 
			array('class' => 'text', 'rows' => 8, 'cols' => 47, 'hidden' => !$this->config->are_informations_enabled())
		));
		
		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->common_lang['authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['admin.authorizations.read'], ContactAuthorizationsService::READ_AUTHORIZATIONS),
		));
		
		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$fieldset_authorizations->add_field(new FormFieldAuthorizationsSetter('authorizations', $auth_settings));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		$this->config->set_title($this->form->get_value('title'));
		
		if ($this->form->get_value('enable_informations'))
		{
			$this->config->enable_informations();
			$this->config->set_informations($this->form->get_value('informations'));
			$this->config->set_informations_position($this->form->get_value('informations_position')->get_raw_value());
		}
		else
			$this->config->disable_informations();
		
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		
		ContactConfig::save();
	}
}
?>
