<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 01 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

				$this->view->assign_block_vars('social_networks_list', array(
					'C_MOBILE_ONLY' => $sn->is_mobile_only(),
					'C_DESKTOP_ONLY' => $sn->is_desktop_only(),
					'C_SHARING_CONTENT' => $sn->has_content_sharing_url() || $sn->has_mobile_content_sharing_url(),
					'C_DISPLAY' => $this->config->is_content_sharing_enabled($id),
					'ID' => $id,
					'NAME' => $sn->get_name(),
					'ICON_NAME' => $sn->get_icon_name(),
					'CSS_CLASS' => $sn->get_css_class()
				));
				$social_networks_number++;
			}
		}

		$this->view->put_all(array(
			'C_MORE_THAN_ONE_SOCIAL_NETWORK' => $social_networks_number > 1,
			'FORM' => $this->form->display()
		));

		$response = new AdminMenuDisplayResponse($this->view);
		$response->set_title($this->lang['module_name']);
		$response->add_link(LangLoader::get_message('configuration', 'admin-common'), DispatchManager::get_url('/SocialNetworks', '/admin/'));
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
		$this->view->add_lang($this->lang);

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
					if ($sn->authentication_identifiers_needed())
					{
						$fieldset->add_field(new FormFieldCheckbox($id . '_authentication_enabled', StringVars::replace_vars($this->lang['authentication.config.authentication-enabled'],
							array('name' => $sn->get_name())), $this->config->is_authentication_enabled($id),
								array(
									'class' => 'third-field custom-checkbox',
									'description' => StringVars::replace_vars(($sn->authentication_client_secret_needed() ? $this->lang['authentication.config.authentication-enabled-explain'] : $this->lang['authentication.config.authentication-enabled-explain.key-only']
								),
								array(
									'identifiers_creation_url' => $sn->get_identifiers_creation_url())) . ($sn->callback_url_needed() ? StringVars::replace_vars($this->lang['authentication.config.authentication-enabled-explain.callback-url'],
									array('callback_url' => UserUrlBuilder::connect($id)->absolute())) : ''), 'events' => array('click' => '
										if (HTMLForms.getField("' . $id . '_authentication_enabled").getValue()) {
											HTMLForms.getField("' . $id . '_client_id").enable();
											' . ($sn->authentication_client_secret_needed() ? 'HTMLForms.getField("' . $id . '_client_secret").enable();
										' : '') . '} else {
											HTMLForms.getField("' . $id . '_client_id").disable();
											' . ($sn->authentication_client_secret_needed() ? 'HTMLForms.getField("' . $id . '_client_secret").disable();
										' : '') . '}'
									)
								)
							)
						);

						$fieldset->add_field(new FormFieldTextEditor($id . '_client_id', StringVars::replace_vars($this->lang['authentication.config.client-id'], array('name' => $sn->get_name())), $this->config->get_client_id($id),
							array('class' => 'third-field top-field', 'required' => true, 'hidden' => !$this->config->is_authentication_enabled($id))
						));

						if ($sn->authentication_client_secret_needed())
						{
							$fieldset->add_field(new FormFieldPasswordEditor($id . '_client_secret', StringVars::replace_vars($this->lang['authentication.config.client-secret'], array('name' => $sn->get_name())), $this->config->get_client_secret($id),
								array('class' => 'third-field top-field', 'required' => true, 'hidden' => !$this->config->is_authentication_enabled($id))
							));
						}

						$fieldset->add_field(new FormFieldFree($id . '_separator', '', ''));
					}
					else
					{
						$fieldset->add_field(new FormFieldCheckbox($id . '_authentication_enabled', StringVars::replace_vars($this->lang['authentication.config.authentication-enabled'], array('name' => $sn->get_name())), $this->config->is_authentication_enabled($id),
							array('class' => 'custom-checkbox', 'description' => $this->lang['authentication.config.no-identifier-needed'])
						));
					}
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
						if ($sn->authentication_identifiers_needed())
						{
							$client_ids[$id] = $this->form->get_value($id . '_client_id');

							if ($sn->authentication_client_secret_needed())
								$client_secrets[$id] = $this->form->get_value($id . '_client_secret');
						}
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

				if ($sn->has_authentication() && $sn->authentication_identifiers_needed())
				{
					$this->form->get_field_by_id($id . '_client_id')->set_hidden(!$this->config->is_authentication_enabled($id));

					if ($sn->authentication_client_secret_needed())
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
