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
	private $view;
	
	/**
	 * @var SocialNetworksConfig
	 */
	private $config;
	private $server_configuration;
	private $social_networks;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->update_social_networks_order($request);
		
		$this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
		
		$social_networks_number = 0;
		$social_networks_order = array_unique(array_merge($this->config->get_social_networks_order(), array_keys($this->social_networks)));
		foreach ($social_networks_order as $id)
		{
			if (isset($this->social_networks[$id]))
			{
				$sn = new $this->social_networks[$id]();
				
				if ($sn->has_content_sharing_url())
				{
					$this->view->assign_block_vars('social_networks_list', array(
						'C_DISPLAY' => $this->config->is_content_sharing_enabled($id),
						'ID' => $id,
						'NAME' => $sn->get_name(),
						'ICON_NAME' => $sn->get_icon_name()
					));
					$social_networks_number++;
				}
			}
		}
		
		$this->view->put_all(array(
			'C_MORE_THAN_ONE_SOCIAL_NETWORK' => $social_networks_number > 1,
			'FORM' => $this->form->display()
		));
		
		$response = new AdminMenuDisplayResponse($this->view);
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
		$this->view = new FileTemplate('SocialNetworks/AdminSocialNetworksConfigController.tpl');
		$this->view->add_lang(LangLoader::get('admin-user-common'));
		
		$social_networks_list = new SocialNetworksList();
		$this->social_networks = $social_networks_list->get_social_networks_list();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('authentication_config', LangLoader::get_message('members.config-authentication', 'admin-user-common'));
		$form->add_fieldset($fieldset);
		
		if ($this->server_configuration->has_curl_library())
		{
			foreach ($this->social_networks as $id => $social_network)
			{
				$sn = new $social_network();
				
				if ($sn->has_authentication())
				{
					$fieldset->add_field(new FormFieldCheckbox($id . '_authentication_enabled', StringVars::replace_vars($this->lang['authentication.config.authentication-enabled'], array('name' => $sn->get_name())), $this->config->is_authentication_enabled($id),
						array('description' => StringVars::replace_vars($this->lang['authentication.config.authentication-enabled-explain'], array('identifiers_creation_url' => $sn->get_identifiers_creation_url(), 'callback_url' => UserUrlBuilder::connect($id)->absolute())), 'events' => array('click' => '
							if (HTMLForms.getField("' . $id . '_authentication_enabled").getValue()) { 
								HTMLForms.getField("' . $id . '_client_id").enable(); 
								HTMLForms.getField("' . $id . '_client_secret").enable(); 
							} else { 
								HTMLForms.getField("' . $id . '_client_id").disable(); 
								HTMLForms.getField("' . $id . '_client_secret").disable(); 
							}'
						)
					)));
					
					$fieldset->add_field(new FormFieldTextEditor($id . '_client_id', StringVars::replace_vars($this->lang['authentication.config.client-id'], array('name' => $sn->get_name())), $this->config->get_client_id($id), 
						array('required' => true, 'hidden' => !$this->config->is_authentication_enabled($id))
					));
					
					$fieldset->add_field(new FormFieldPasswordEditor($id . '_client_secret', StringVars::replace_vars($this->lang['authentication.config.client-secret'], array('name' => $sn->get_name())), $this->config->get_client_secret($id), 
						array('required' => true, 'hidden' => !$this->config->is_authentication_enabled($id))
					));
				}
			}
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
			$authentications_enabled = array();
			$client_ids = array();
			$client_secrets = array();
			
			foreach ($this->social_networks as $id => $social_network)
			{
				$sn = new $social_network();
				
				if ($sn->has_authentication())
				{
					if ($this->form->get_value($id . '_authentication_enabled'))
					{
						$authentications_enabled[] = $id;
						$client_ids[$id] = $this->form->get_value($id . '_client_id');
						$client_secrets[$id] = $this->form->get_value($id . '_client_secret');
					}
				}
			}
			
			$this->config->set_enabled_authentications($authentications_enabled);
			$this->config->set_client_ids($client_ids);
			$this->config->set_client_secrets($client_secrets);
			
			SocialNetworksConfig::save();
			
			foreach ($this->social_networks as $id => $social_network)
			{
				$sn = new $social_network();
				
				if ($sn->has_authentication())
				{
					$this->form->get_field_by_id($id . '_client_id')->set_hidden(!$this->config->is_authentication_enabled($id));
					$this->form->get_field_by_id($id . '_client_secret')->set_hidden(!$this->config->is_authentication_enabled($id));
				}
			}
		}
	}
	
	private function update_social_networks_order(HTTPRequestCustom $request)
	{
		if ($request->get_value('order_manage_submit', false))
		{
			$this->update_position($request);
			$this->view->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.position.update', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}
	}
	
	private function update_position(HTTPRequestCustom $request)
	{
		$sorted_social_networks = array();
		
		$social_networks_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($social_networks_list as $position => $tree)
		{
			$sorted_social_networks[] = $tree->id;
		}
		
		$this->config->set_social_networks_order($sorted_social_networks);
		
		SocialNetworksConfig::save();
	}
}
?>