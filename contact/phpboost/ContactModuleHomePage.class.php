<?php
/*##################################################
 *                           ContactModuleHomePage.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class ContactModuleHomePage implements ModuleHomePage
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
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	public function build_view()
	{
		$this->init();
		$this->build_form();

		$tpl = new FileTemplate('contact/ContactController.tpl');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$mail_success = $this->send_mail();

			$tpl->put_all(array(
				'C_SUBMITED' => true,
				'C_SUCCESS' => $mail_success,
				'L_SUCCESS_MAIL' => $this->lang['success_mail'],
				'L_ERROR_MAIL' => $this->lang['error_mail']
			));
		}

		$tpl->put('FORM', $this->form->display());
		
		return $tpl;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('contact_common', 'contact');
	}
	
	private function build_form()
	{
		$form = new HTMLForm('contact');

		$fieldset = new FormFieldsetHTML('send_a_mail', $this->lang['contact_mail']);
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldMailEditor('sender_mail', $this->lang['your_mail_address'], AppContext::get_current_user()->get_attribute('user_mail'),
			array('description' => $this->lang['your_mail_address_explain'])));

		$fieldset->add_field(new FormFieldTextEditor('subject', $this->lang['contact_subject'], '', array('description' => $this->lang['contact_subject_explain']), array(new FormFieldConstraintNotEmpty())));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('message', $this->lang['message'], '', array(), array(new FormFieldConstraintNotEmpty())));

		$config = ContactConfig::load();
		if ($config->is_captcha_enabled())
		{
			$captcha = new Captcha();
			$captcha->set_difficulty($config->get_captcha_difficulty_level());
			$fieldset->add_field(new FormFieldCaptcha('captcha', $captcha));
		}

		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function send_mail()
	{
		$mail = new Mail();
		$mail->set_sender($this->form->get_value('sender_mail'), 'user');
		$mail->set_subject($this->form->get_value('subject'));
		$mail->set_content($this->form->get_value('message'));

		$admin_mails = MailServiceConfig::load()->get_administrators_mails();
		foreach ($admin_mails as $mail_address)
		{
			$mail->add_recipient($mail_address);
		}

		return AppContext::get_mail_service()->try_to_send($mail);
	}
}
?>