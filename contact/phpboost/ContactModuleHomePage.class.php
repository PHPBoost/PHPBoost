<?php
/*##################################################
 *                           ContactModuleHomePage.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
	private $config;
	
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
			if ($this->send_mail())
				$tpl->put('MSG', MessageHelper::display($this->lang['success_mail'], E_USER_SUCCESS, 5));
			else
				$tpl->put('MSG', MessageHelper::display($this->lang['error_mail'], E_USER_ERROR, 5));
		}
		
		$tpl->put('FORM', $this->form->display());
		
		$tpl->put_all(array(
			'C_INFORMATIONS_LEFT' => $this->config->is_informations_enabled() && $this->config->is_informations_left(),
			'C_INFORMATIONS_TOP' => $this->config->is_informations_enabled() && $this->config->is_informations_top(),
			'C_INFORMATIONS_RIGHT' => $this->config->is_informations_enabled() && $this->config->is_informations_right(),
			'C_INFORMATIONS_BOTTOM' => $this->config->is_informations_enabled() && $this->config->is_informations_bottom(),
			'C_INFORMATIONS_SIDE' => $this->config->is_informations_enabled() && ($this->config->is_informations_left() || $this->config->is_informations_right()),
			'INFORMATIONS' => FormatingHelper::second_parse($this->config->get_informations()),
		));
		
		return $tpl;
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('contact_common', 'contact');
		$this->config = ContactConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm('contact');
		
		$fieldset = new FormFieldsetHTML('send_a_mail', $this->config->get_title());
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldMailEditor('sender_mail', $this->lang['your_mail_address'], AppContext::get_current_user()->get_attribute('user_mail'),
			array('description' => $this->lang['your_mail_address_explain'], 'required' => true)));
		
		$extended_field = new MemberExtendedField();
		$extended_field->set_fieldset($fieldset);
		ContactExtendedFieldsService::display_form_fields($extended_field);
		
		if ($this->config->is_subject_field_displayed())
		{
			if ($this->config->is_subject_field_text())
				$fieldset->add_field(new FormFieldTextEditor('subject', $this->lang['contact_subject'], $this->config->get_subject_field_default_value(), array('description' => $this->lang['contact_subject_explain'], 'required' => $this->config->is_subject_field_mandatory())));
			else
			{
				$fieldset->add_field(new FormFieldSimpleSelectChoice('subject', $this->lang['contact_subject'], $this->config->get_subject_field_default_value(), $this->get_array_select_subject(), array('required' => $this->config->is_subject_field_mandatory())));
			}
		}
		
		$fieldset->add_field(new FormFieldMultiLineTextEditor('message', $this->lang['message'], '', array('required' => true)));
		
		$fieldset->add_field(new FormFieldCaptcha('captcha'));
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function get_array_select_subject()
	{
		$subject_select = array();
		$possible_values = explode('|', $this->config->get_subject_field_possible_values());
		
		if (!$this->config->get_subject_field_default_value())
			$subject_select[] = new FormFieldSelectChoiceOption('', '');
		
		foreach ($possible_values as $value)
		{
			$subject_select[] = new FormFieldSelectChoiceOption($value, $value);
		}
		return $subject_select;
	}

	private function send_mail()
	{
		$message = $subject = '';
		
		if ($this->config->is_subject_field_displayed())
		{
			if ($this->config->is_subject_field_text())
				$subject = $this->form->get_value('subject');
			else
				$subject = $this->form->get_value('subject')->get_raw_value();
		}
		else
			$subject = $this->config->get_subject_field_default_value();
		
		$extended_fields_displayed = ContactExtendedFieldsDatabaseService::extended_fields_displayed();
		if($extended_fields_displayed)
		{
			$extended_fields_cache = ContactExtendedFieldsCache::load()->get_extended_fields();
			foreach($extended_fields_cache as $id => $extended_field)
			{
				if($extended_field['display'] == 1)
				{
					$member_extended_field = new MemberExtendedField();
					$member_extended_field->set_field_type($extended_field['field_type']);
					$member_extended_field->set_field_name($extended_field['field_name']);

					try{
						$value = MemberExtendedFieldsFactory::return_value($this->form, $member_extended_field);

						$authorizations=$extended_field['auth'];
						if(AppContext::get_current_user()->check_auth($authorizations, ExtendedField::READ_EDIT_AND_ADD_AUTHORIZATION))
						{
							$message .= $extended_field['name'] . ': ' . $value . '
';
						}
					} catch(MemberExtendedFieldErrorsMessageException $e) {
						throw new MemberExtendedFieldErrorsMessageException($e->getMessage());
					}
				}
			}
			$message .= '
' . $this->lang['message'] . ':
';
		}
		
		$message .= $this->form->get_value('message');
		
		$mail = new Mail();
		$mail->set_sender($this->form->get_value('sender_mail'), 'user');
		$mail->set_subject($subject);
		$mail->set_content($message);

		$admin_mails = MailServiceConfig::load()->get_administrators_mails();
		foreach ($admin_mails as $mail_address)
		{
			$mail->add_recipient($mail_address);
		}

		return AppContext::get_mail_service()->try_to_send($mail);
	}
}
?>