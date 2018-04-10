<?php
/*##################################################
 *		                         AdminSocialNetworksConfigController.class.php
 *                            -------------------
 *   begin                : January 21, 2018
 *   copyright            : (C) 2018 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class AdminSocialNetworksConfigController extends AdminModuleController
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
	 * @var SocialNetworksConfig
	 */
	private $config;
	private $server_configuration;
	
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
		
		$response = new AdminMenuDisplayResponse($tpl);
		$response->set_title($this->lang['module_name']);
		$response->add_link(LangLoader::get_message('configuration', 'admin-common'), DispatchManager::get_url('/SocialNetworks', '/config/'));
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['module_config_title'], $this->lang['module_name']);

		return $response;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'SocialNetworks');
		$this->config = SocialNetworksConfig::load();
		$this->server_configuration = new ServerConfiguration();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('authentication_config', LangLoader::get_message('members.config-authentication', 'admin-user-common'));
		$form->add_fieldset($fieldset);
		
		if ($this->server_configuration->has_curl_library())
		{
			$fieldset->add_field(new FormFieldCheckbox('facebook_auth_enabled', $this->lang['authentication.config.facebook-auth-enabled'], $this->config->is_facebook_auth_enabled(),
				array('description' => $this->lang['authentication.config.facebook-auth-enabled-explain'], 'events' => array('click' => '
					if (HTMLForms.getField("facebook_auth_enabled").getValue()) { 
						HTMLForms.getField("facebook_app_id").enable(); 
						HTMLForms.getField("facebook_app_key").enable(); 
					} else { 
						HTMLForms.getField("facebook_app_id").disable(); 
						HTMLForms.getField("facebook_app_key").disable(); 
					}'
				)
			)));
			
			$fieldset->add_field(new FormFieldTextEditor('facebook_app_id', $this->lang['authentication.config.facebook-app-id'], $this->config->get_facebook_app_id(), 
				array('required' => true, 'hidden' => !$this->config->is_facebook_auth_enabled())
			));
			
			$fieldset->add_field(new FormFieldPasswordEditor('facebook_app_key', $this->lang['authentication.config.facebook-app-key'], $this->config->get_facebook_app_key(), 
				array('required' => true, 'hidden' => !$this->config->is_facebook_auth_enabled())
			));
			
			$fieldset->add_field(new FormFieldCheckbox('google_auth_enabled', $this->lang['authentication.config.google-auth-enabled'], $this->config->is_google_auth_enabled(),
				array('description' => $this->lang['authentication.config.google-auth-enabled-explain'], 'events' => array('click' => '
					if (HTMLForms.getField("google_auth_enabled").getValue()) { 
						HTMLForms.getField("google_client_id").enable(); 
						HTMLForms.getField("google_client_secret").enable(); 
					} else { 
						HTMLForms.getField("google_client_id").disable(); 
						HTMLForms.getField("google_client_secret").disable(); 
					}'
				)
			)));
			
			$fieldset->add_field(new FormFieldTextEditor('google_client_id', $this->lang['authentication.config.google-client-id'], $this->config->get_google_client_id(), 
				array('required' => true, 'hidden' => !$this->config->is_google_auth_enabled())
			));
			
			$fieldset->add_field(new FormFieldPasswordEditor('google_client_secret', $this->lang['authentication.config.google-client-secret'], $this->config->get_google_client_secret(), 
				array('required' => true, 'hidden' => !$this->config->is_google_auth_enabled())
			));
			
			$fieldset->add_field(new FormFieldCheckbox('linkedin_auth_enabled', $this->lang['authentication.config.linkedin-auth-enabled'], $this->config->is_linkedin_auth_enabled(),
				array('description' => $this->lang['authentication.config.linkedin-auth-enabled-explain'], 'events' => array('click' => '
					if (HTMLForms.getField("linkedin_auth_enabled").getValue()) { 
						HTMLForms.getField("linkedin_client_id").enable(); 
						HTMLForms.getField("linkedin_client_secret").enable(); 
					} else { 
						HTMLForms.getField("linkedin_client_id").disable(); 
						HTMLForms.getField("linkedin_client_secret").disable(); 
					}'
				)
			)));
			
			$fieldset->add_field(new FormFieldTextEditor('linkedin_client_id', $this->lang['authentication.config.linkedin-client-id'], $this->config->get_linkedin_client_id(), 
				array('required' => true, 'hidden' => !$this->config->is_linkedin_auth_enabled())
			));
			
			$fieldset->add_field(new FormFieldPasswordEditor('linkedin_client_secret', $this->lang['authentication.config.linkedin-client-secret'], $this->config->get_linkedin_client_secret(), 
				array('required' => true, 'hidden' => !$this->config->is_linkedin_auth_enabled())
			));
		}
		else
		{
			$fieldset->add_field(new FormFieldFree('', '', MessageHelper::display($this->lang['authentication.config.curl_extension_disabled'], MessageHelper::WARNING)->render()));
		}
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function save()
	{
		if ($this->server_configuration->has_curl_library())
		{
			if ($this->form->get_value('facebook_auth_enabled'))
			{
				$this->config->enable_facebook_auth();
				$this->config->set_facebook_app_id($this->form->get_value('facebook_app_id'));
				$this->config->set_facebook_app_key($this->form->get_value('facebook_app_key'));
			}
			else
				$this->config->disable_facebook_auth();
			
			if ($this->form->get_value('google_auth_enabled'))
			{
				$this->config->enable_google_auth();
				$this->config->set_google_client_id($this->form->get_value('google_client_id'));
				$this->config->set_google_client_secret($this->form->get_value('google_client_secret'));
			}
			else
				$this->config->disable_google_auth();
			
			if ($this->form->get_value('linkedin_auth_enabled'))
			{
				$this->config->enable_linkedin_auth();
				$this->config->set_linkedin_client_id($this->form->get_value('linkedin_client_id'));
				$this->config->set_linkedin_client_secret($this->form->get_value('linkedin_client_secret'));
			}
			else
				$this->config->disable_linkedin_auth();
			
			SocialNetworksConfig::save();

			$this->form->get_field_by_id('facebook_app_id')->set_hidden(!$this->config->is_facebook_auth_enabled());
			$this->form->get_field_by_id('facebook_app_key')->set_hidden(!$this->config->is_facebook_auth_enabled());
			$this->form->get_field_by_id('google_client_id')->set_hidden(!$this->config->is_google_auth_enabled());
			$this->form->get_field_by_id('google_client_secret')->set_hidden(!$this->config->is_google_auth_enabled());
			$this->form->get_field_by_id('linkedin_client_id')->set_hidden(!$this->config->is_linkedin_auth_enabled());
			$this->form->get_field_by_id('linkedin_client_secret')->set_hidden(!$this->config->is_linkedin_auth_enabled());
		}
	}
}
?>