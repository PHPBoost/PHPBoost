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

		$fieldset = new FormFieldsetHTML('Mail');
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldMailEditor('sender_mail', 'Sender mail', ''));
		$fieldset->add_field(new FormFieldTextEditor('sender_name', 'Sender name', '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldMailEditor('recipient_mail', 'Recipient mail', ''));
		$fieldset->add_field(new FormFieldTextEditor('recipient_name', 'Recipient name', '', array(), array(new FormFieldConstraintNotEmpty()))); 
		$fieldset->add_field(new FormFieldTextEditor('mail_subject', 'Mail subject', '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldMultiLineTextEditor('mail_content', 'Content', ''));
		
		$fieldset = new FormFieldsetHTML('SMTP configuration');
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldTextEditor('smtp_host', 'SMTP host', '', array(), array(new FormFieldConstraintRegex('`^[a-z0-9-]+(?:\.[a-z0-9-]+)*$`i'))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_port', 'SMTP port', 25, array(), array(new FormFieldConstraintIntegerRange(0, 65535))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_login', 'SMTP login', '', array(), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldPasswordEditor('smtp_password', 'SMTP password', ''));
		
		$select_option = new FormFieldSelectChoiceOption('None', 'none');
		$fieldset->add_field(new FormFieldSelectChoice('secure_protocol', 'Secure protocol', $select_option, array($select_option, new FormFieldSelectChoiceOption('TLS', 'tls'), new FormFieldSelectChoiceOption('SSL', 'ssl'))));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
	}
	
	private function send_mail()
	{
		$configuration = new SMTPConfiguration();
		$configuration->set_host($this->form->get_value('smtp_host'));
		$configuration->set_port($this->form->get_value('smtp_port'));
		$configuration->set_login($this->form->get_value('smtp_login'));
		$configuration->set_password($this->form->get_value('smtp_password'));
		$configuration->set_auth_mode($this->form->get_value('secure_protocol')->get_raw_value());
				
		$mailer = new SMTPMailSender($configuration);
		
		$mail = new Mail();
		$mail->add_recipient($this->form->get_value('recipient_mail'), $this->form->get_value('recipient_name'));
		$mail->set_sender($this->form->get_value('sender_mail'), $this->form->get_value('sender_name'));
		$mail->set_subject($this->form->get_value('mail_subject'));
		$mail->set_content($this->form->get_value('mail_content'));
		
		return $mailer->send($mail);
	}
}
?>
