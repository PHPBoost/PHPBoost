<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
 * @since       PHPBoost 3.0 - 2010 03 12
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxMailController extends ModuleController
{
	private $view;
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
		$this->check_authorizations();

		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$result = $this->send_mail();
			$this->view->put_all(array(
				'C_MAIL_SENT' => true,
				'C_SUCCESS' => empty($result),
				'ERROR' => $result
			));
		}

		$this->view->put('SMTP_FORM', $this->form->display());

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxMailController.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_form()
	{
		$this->form = new HTMLForm('smtp_config');

		$fieldset = new FormFieldsetHTML('mail_properties', $this->lang['mail.title']);
		$this->form->add_fieldset($fieldset);
		$sender_mail = new FormFieldMailEditor('sender_mail', $this->lang['mail.sender_mail'], '');
		$fieldset->add_field($sender_mail);

		$fieldset->add_field(new FormFieldTextEditor('sender_name', $this->lang['mail.sender_name'], '', array(), array(new FormFieldConstraintNotEmpty())));

		$recipient_mail = new FormFieldMailEditor('recipient_mail', $this->lang['mail.recipient_mail'], '');
		$fieldset->add_field($recipient_mail);

		$fieldset->add_field(new FormFieldTextEditor('recipient_name', $this->lang['mail.recipient_name'], '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldTextEditor('mail_subject', $this->lang['mail.subject'], '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldMultiLineTextEditor('mail_content', $this->lang['mail.content'], ''));

		$fieldset = new FormFieldsetHTML('send_configuration', $this->lang['mail.smtp_config'], array('description' => $this->lang['mail.smtp_config.explain']));
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('use_smtp', $this->lang['mail.use_smtp'], false,
			array('events' => array('click' => 'if ($FF("use_smtp").getValue()) { $FFS("smtp_configuration").enable(); } else { $FFS("smtp_configuration").disable(); }'))));


		$fieldset = new FormFieldsetHTML('smtp_configuration', $this->lang['mail.smtp_configuration'], array('disabled' => true));
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('smtp_host', $this->lang['mail.smtp.host'], '', array('disabled' => true), array(new FormFieldConstraintRegex('`^[a-z0-9-]+(?:\.[a-z0-9-]+)*$`iu'))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_port', $this->lang['mail.smtp.port'], 25, array('disabled' => true), array(new FormFieldConstraintIntegerRange(0, 65535))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_login', $this->lang['mail.smtp.login'], '', array('disabled' => true), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldPasswordEditor('smtp_password', $this->lang['mail.smtp.password'], '', array('disabled' => true)));

		$select_option = new FormFieldSelectChoiceOption($this->lang['mail.smtp.secure_protocol.none'], 'none');
		$fieldset->add_field(new FormFieldSimpleSelectChoice('secure_protocol', $this->lang['mail.smtp.secure_protocol'], $select_option, array($select_option, new FormFieldSelectChoiceOption($this->lang['mail.smtp.secure_protocol.tls'], 'tls'), new FormFieldSelectChoiceOption($this->lang['mail.smtp.secure_protocol.ssl'], 'ssl')), array('disabled' => true)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
		$this->form->add_constraint(new FormConstraintFieldsInequality($recipient_mail, $sender_mail));
	}

	private function send_mail()
	{
		if ($this->form->get_value('use_smtp'))
		{
			$configuration = new SMTPConfiguration();
			$configuration->set_host($this->form->get_value('smtp_host'));
			$configuration->set_port($this->form->get_value('smtp_port'));
			$configuration->set_login($this->form->get_value('smtp_login'));
			$configuration->set_password($this->form->get_value('smtp_password'));
			$configuration->set_auth_mode($this->form->get_value('secure_protocol')->get_raw_value());

			$mailer = new SMTPMailService($configuration);
		}
		else
		{
			$mailer = new DefaultMailService();
		}

		$mail = new Mail();
		$mail->add_recipient($this->form->get_value('recipient_mail'), $this->form->get_value('recipient_name'));
		$mail->set_sender($this->form->get_value('sender_mail'), $this->form->get_value('sender_name'));
		$mail->set_subject($this->form->get_value('mail_subject'));
		$mail->set_content($this->form->get_value('mail_content'));

		return $mailer->send($mail);
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
		$graphical_environment->set_page_title($this->lang['title.mail.sender'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.mail.sender'], SandboxUrlBuilder::mail()->rel());

		return $response;
	}
}
?>
