<?php
/*##################################################
 *		                   AdminNewsletterConfigController.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminNewsletterConfigController extends AdminModuleController
{
	private $lang;
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;

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

		return new AdminNewsletterDisplayResponse($tpl, LangLoader::get_message('configuration', 'admin'));
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		$newsletter_config = NewsletterConfig::load();

		$fieldset_config = new FormFieldsetHTML('configuration', LangLoader::get_message('configuration', 'admin'));
		$form->add_fieldset($fieldset_config);
		
		$fieldset_config->add_field(new FormFieldMailEditor('mail_sender', $this->lang['admin.mail-sender'], $newsletter_config->get_mail_sender(),
			array('description' => $this->lang['admin.mail-sender-explain'], 'required' => true)
		));
		
		$fieldset_config->add_field(new FormFieldTextEditor('newsletter_name', $this->lang['admin.newsletter-name'], $newsletter_config->get_newsletter_name(),
			array('maxlength' => 255, 'description' => $this->lang['admin.newsletter-name-explain'], 'required' => true)
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['admin.newsletter-authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['auth.read'], NewsletterAuthorizationsService::AUTH_READ),
			new ActionAuthorization($this->lang['auth.subscribe'], NewsletterAuthorizationsService::AUTH_SUBSCRIBE),
			new ActionAuthorization($this->lang['auth.subscribers-read'], NewsletterAuthorizationsService::AUTH_READ_SUBSCRIBERS),
			new ActionAuthorization($this->lang['auth.subscribers-moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_SUBSCRIBERS),
			new ActionAuthorization($this->lang['auth.create-newsletter'], NewsletterAuthorizationsService::AUTH_CREATE_NEWSLETTERS),
			new ActionAuthorization($this->lang['auth.archives-read'], NewsletterAuthorizationsService::AUTH_READ_ARCHIVES),
			new ActionAuthorization($this->lang['auth.archives-moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_ARCHIVES),
			new ActionAuthorization($this->lang['auth.manage-streams'], NewsletterAuthorizationsService::AUTH_MANAGE_STREAMS)
		));
		
		$auth_settings->build_from_auth_array($newsletter_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset_authorizations->add_field($auth_setter);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$newsletter_config = NewsletterConfig::load();
		$newsletter_config->set_mail_sender($this->form->get_value('mail_sender'));
		$newsletter_config->set_newsletter_name($this->form->get_value('newsletter_name'));
		$newsletter_config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		NewsletterConfig::save();
	}
}
?>