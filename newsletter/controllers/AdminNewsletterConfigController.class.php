<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 11 18
 * @since   	PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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

		$fieldset_config = new FormFieldsetHTMLHeading('configuration', LangLoader::get_message('configuration', 'admin'));
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
			new MemberDisabledActionAuthorization($this->lang['auth.subscribers-moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_SUBSCRIBERS),
			new VisitorDisabledActionAuthorization($this->lang['auth.create-newsletter'], NewsletterAuthorizationsService::AUTH_CREATE_NEWSLETTERS),
			new ActionAuthorization($this->lang['auth.archives-read'], NewsletterAuthorizationsService::AUTH_READ_ARCHIVES),
			new MemberDisabledActionAuthorization($this->lang['auth.archives-moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_ARCHIVES),
			new MemberDisabledActionAuthorization($this->lang['auth.manage-streams'], NewsletterAuthorizationsService::AUTH_MANAGE_STREAMS)
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
