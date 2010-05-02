<?php
/*##################################################
 *                       SandboxMailController.class.php
 *                            -------------------
 *   begin                : March 12, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SandboxMailController extends ModuleController
{
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
		$view = new StringTemplate('# IF C_MAIL_SENT # # IF C_SUCCESS # <div class="success">The mail has been sent</div>
			# ELSE # <div class="error">{ERROR}</div> # ENDIF # # ENDIF #
			<h1>SMTP</h1> # INCLUDE SMTP_FORM #');

		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$result = $this->send_mail();
			$view->assign_vars(array(
				'C_MAIL_SENT' => true,
				'C_SUCCESS' => empty($result),
				'ERROR' => $result
			));
		}
		$view->add_subtemplate('SMTP_FORM', $this->form->display());
		return new SiteDisplayResponse($view);
	}

	private function build_form()
	{
		$this->form = new HTMLForm('smtp_config');

		$fieldset = new FormFieldsetHTML('mail_properties', 'Mail');
		$this->form->add_fieldset($fieldset);
		$sender_mail = new FormFieldMailEditor('sender_mail', 'Sender mail', '');
		$fieldset->add_field($sender_mail);

		$fieldset->add_field(new FormFieldTextEditor('sender_name', 'Sender name', '', array(), array(new FormFieldConstraintNotEmpty())));

		$recipient_mail = new FormFieldMailEditor('recipient_mail', 'Recipient mail', '');
		$fieldset->add_field($recipient_mail);

		$fieldset->add_field(new FormFieldTextEditor('recipient_name', 'Recipient name', '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldTextEditor('mail_subject', 'Mail subject', '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldMultiLineTextEditor('mail_content', 'Content', ''));

		$fieldset = new FormFieldsetHTML('send_configuration', 'SMTP configuration', array('description' => 'If you want to use a direct SMTP connection to send the mail, check the box.'));
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('use_smtp', 'Use SMTP', false,
			array('events' => array('click' => 'if ($FF("use_smtp").getValue()) { $FFS("smtp_configuration").enable(); } else { $FFS("smtp_configuration").disable(); }'))));


		$fieldset = new FormFieldsetHTML('smtp_configuration', 'Send configuration', array('disabled' => true));
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('smtp_host', 'SMTP host', '', array('disabled' => true), array(new FormFieldConstraintRegex('`^[a-z0-9-]+(?:\.[a-z0-9-]+)*$`i'))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_port', 'SMTP port', 25, array('disabled' => true), array(new FormFieldConstraintIntegerRange(0, 65535))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_login', 'SMTP login', '', array('disabled' => true), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldPasswordEditor('smtp_password', 'SMTP password', '', array('disabled' => true)));

		$select_option = new FormFieldSelectChoiceOption('None', 'none');
		$fieldset->add_field(new FormFieldSelectChoice('secure_protocol', 'Secure protocol', $select_option, array($select_option, new FormFieldSelectChoiceOption('TLS', 'tls'), new FormFieldSelectChoiceOption('SSL', 'ssl')), array('disabled' => true)));

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
}
?>
