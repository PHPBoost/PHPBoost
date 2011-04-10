<?php
/*##################################################
 *		                   AdminNewsletterConfigController.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminNewsletterConfigController extends AdminController
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submitted() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['admin.success-saving-config'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminNewsletterDisplayResponse($tpl, $this->lang['streams.add']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}

	private function build_form()
	{
		$form = new HTMLForm('newsletter_admin');
		$newsletter_config = NewsletterConfig::load();

		$fieldset_config = new FormFieldsetHTML('configuration', $this->lang['newsletter.config']);
		$form->add_fieldset($fieldset_config);
		
		$fieldset_config->add_field(new FormFieldTextEditor('mail_sender', $this->lang['admin.mail-sender'], $newsletter_config->get_mail_sender(), array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['admin.mail-sender-explain']),
			array(new FormFieldConstraintMailAddress())
		));
		
		$fieldset_config->add_field(new FormFieldTextEditor('newsletter_name', $this->lang['admin.newsletter-name'], $newsletter_config->get_newsletter_name(), array(
			'class' => 'text', 'description' => $this->lang['admin.newsletter-name-explain'])
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['admin.newsletter-authorizations']);
		$form->add_fieldset($fieldset_authorizations);
		
		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['auth.read'], NewsletterConfig::AUTH_READ),
			new ActionAuthorization($this->lang['auth.subscribe'], NewsletterConfig::AUTH_SUBSCRIBE),
			new ActionAuthorization($this->lang['auth.subscribers-read'], NewsletterConfig::AUTH_READ_SUBSCRIBERS),
			new ActionAuthorization($this->lang['auth.subscribers-moderation'], NewsletterConfig::AUTH_MODERATION_SUBSCRIBERS),
			new ActionAuthorization($this->lang['auth.create-newsletter'], NewsletterConfig::AUTH_CREATE_NEWSLETTER),
			new ActionAuthorization($this->lang['auth.archives-read'], NewsletterConfig::AUTH_READ_ARCHIVES)
		));
		
		$auth_settings->build_from_auth_array($newsletter_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset_authorizations->add_field($auth_setter);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

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