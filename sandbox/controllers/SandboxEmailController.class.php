<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 3.0 - 2010 03 12
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxEmailController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxEmailController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$result = $this->send_email();
			$this->view->put_all(array(
				'C_EMAIL_SENT' => true,
				'C_SUCCESS' => empty($result),
				'ERROR' => $result
			));
		}

		$this->view->put_all(array(
			'SMTP_FORM'       => $this->form->display(),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));

		return $this->generate_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('smtp_config');

		$fieldset = new FormFieldsetHTML('email_properties', $this->lang['sandbox.email.title']);
		$this->form->add_fieldset($fieldset);
		$sender_email = new FormFieldMailEditor('sender_email', $this->lang['sandbox.email.sender.email'], '');
		$fieldset->add_field($sender_email);

		$fieldset->add_field(new FormFieldTextEditor('sender_name', $this->lang['sandbox.email.sender.name'], '', array(), array(new FormFieldConstraintNotEmpty())));

		$recipient_email = new FormFieldMailEditor('recipient_email', $this->lang['sandbox.email.recipient.email'], '');
		$fieldset->add_field($recipient_email);

		$fieldset->add_field(new FormFieldTextEditor('recipient_name', $this->lang['sandbox.email.recipient.name'], '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldTextEditor('email_subject', $this->lang['sandbox.email.subject'], '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldMultiLineTextEditor('email_content', $this->lang['sandbox.email.content'], ''));

		$fieldset = new FormFieldsetHTML('send_configuration', $this->lang['sandbox.email.smtp.config'], array('description' => $this->lang['sandbox.email.smtp.config.clue']));
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('use_smtp', $this->lang['sandbox.email.use.smtp'], false,
			array('events' => array('click' => 'if ($FF("use_smtp").getValue()) { $FFS("smtp_configuration").enable(); } else { $FFS("smtp_configuration").disable(); }'))));


		$fieldset = new FormFieldsetHTML('smtp_configuration', $this->lang['sandbox.email.smtp.configuration'], array('disabled' => true));
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('smtp_host', $this->lang['sandbox.email.smtp.host'], '', array('disabled' => true), array(new FormFieldConstraintRegex('`^[a-z0-9-]+(?:\.[a-z0-9-]+)*$`iu'))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_port', $this->lang['sandbox.email.smtp.port'], 25, array('disabled' => true), array(new FormFieldConstraintIntegerRange(0, 65535))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_login', $this->lang['sandbox.email.smtp.login'], '', array('disabled' => true), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldPasswordEditor('smtp_password', $this->lang['sandbox.email.smtp.password'], '', array('disabled' => true)));

		$select_option = new FormFieldSelectChoiceOption($this->lang['sandbox.email.smtp.secure.protocol.none'], 'none');
		$fieldset->add_field(new FormFieldSimpleSelectChoice('secure_protocol', $this->lang['sandbox.email.smtp.secure.protocol'], $select_option, array($select_option, new FormFieldSelectChoiceOption($this->lang['sandbox.email.smtp.secure.protocol.tls'], 'tls'), new FormFieldSelectChoiceOption($this->lang['sandbox.email.smtp.secure.protocol.ssl'], 'ssl')), array('disabled' => true)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_constraint(new FormConstraintFieldsInequality($recipient_email, $sender_email));
	}

	private function send_email()
	{
		if ($this->form->get_value('use_smtp'))
		{
			$configuration = new SMTPConfiguration();
			$configuration->set_host($this->form->get_value('smtp_host'));
			$configuration->set_port($this->form->get_value('smtp_port'));
			$configuration->set_login($this->form->get_value('smtp_login'));
			$configuration->set_password($this->form->get_value('smtp_password'));
			$configuration->set_auth_mode($this->form->get_value('secure_protocol')->get_raw_value());

			$emailer = new SMTPMailService($configuration);
		}
		else
		{
			$emailer = new DefaultMailService();
		}

		$email = new Mail();
		$email->add_recipient($this->form->get_value('recipient_email'), $this->form->get_value('recipient_name'));
		$email->set_sender($this->form->get_value('sender_email'), $this->form->get_value('sender_name'));
		$email->set_subject($this->form->get_value('email_subject'));
		$email->set_content($this->form->get_value('email_content'));

		return $emailer->send($email);
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.email'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.email'], SandboxUrlBuilder::email()->rel());

		return $response;
	}
}
?>
