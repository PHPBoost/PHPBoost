<?php
/*##################################################
 *                         ContactController.class.php
 *                            -------------------
 *   begin                : May 2, 2010
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

class ContactController extends ModuleController
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
	private $config;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view();
		
		return $this->generate_response();
	}
	
	public function build_view()
	{
		$this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			if ($this->send_mail())
			{
				$this->view->put('MSG', MessageHelper::display($this->lang['message.success_mail'] . ($this->config->is_sender_acknowledgment_enabled() ? ' ' . $this->lang['message.acknowledgment'] : ''), MessageHelper::SUCCESS));
				$this->view->put('C_MAIL_SENT', true);
			}
			else
				$this->view->put('MSG', MessageHelper::display($this->lang['message.error_mail'], MessageHelper::ERROR, 5));
		}
		
		$this->view->put('FORM', $this->form->display());
		
		$this->view->put_all(array(
			'C_INFORMATIONS_LEFT' => $this->config->are_informations_enabled() && $this->config->are_informations_left(),
			'C_INFORMATIONS_TOP' => $this->config->are_informations_enabled() && $this->config->are_informations_top(),
			'C_INFORMATIONS_RIGHT' => $this->config->are_informations_enabled() && $this->config->are_informations_right(),
			'C_INFORMATIONS_BOTTOM' => $this->config->are_informations_enabled() && $this->config->are_informations_bottom(),
			'C_INFORMATIONS_SIDE' => $this->config->are_informations_enabled() && ($this->config->are_informations_left() || $this->config->are_informations_right()),
			'INFORMATIONS' => FormatingHelper::second_parse($this->config->get_informations()),
		));
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'contact');
		$this->view = new FileTemplate('contact/ContactController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = ContactConfig::load();
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('send_a_mail', $this->config->get_title());
		$form->add_fieldset($fieldset);
		
		foreach($this->config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);
			
			if ($field->is_displayed() && $field->is_authorized())
			{
				if ($field->get_field_name() == 'f_sender_mail')
					$field->set_default_value(AppContext::get_current_user()->get_email());
				$field->set_fieldset($fieldset);
				ContactFieldsService::display_field($field);
			}
		}
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function send_mail()
	{
		$message = '';
		$current_user = AppContext::get_current_user();
		
		$fields = $this->config->get_fields();
		$recipients_field_id = $this->config->get_field_id_by_name('f_recipients');
		$recipients_field = new ContactField();
		$recipients_field->set_properties($fields[$recipients_field_id]);
		$recipients = $recipients_field->get_possible_values();
		$recipients['admins']['email'] = implode(';', MailServiceConfig::load()->get_administrators_mails());
		
		$subject_field_id = $this->config->get_field_id_by_name('f_subject');
		$subject_field = new ContactField();
		$subject_field->set_properties($fields[$subject_field_id]);
		$subjects = $subject_field->get_possible_values();
		
		if ($subject_field->get_field_type() == 'ContactShortTextField')
			$subject = $this->form->get_value('f_subject');
		else
			$subject = $this->form->get_value('f_subject')->get_raw_value();
		
		$display_message_title = false;
		if ($this->config->is_tracking_number_enabled())
		{
			$now = new Date();
			
			$tracking_number = $this->config->get_last_tracking_number();
			$tracking_number++;
			$message .= $this->lang['contact.tracking_number'] . ' : ' . ($this->config->is_date_in_tracking_number_enabled() ? $now->get_year() . $now->get_month() . $now->get_day() . '-' : '') . $tracking_number . '

';
			$this->config->set_last_tracking_number($tracking_number);
			ContactConfig::save();
			
			$subject = '[' . $tracking_number . '] ' . $subject;
			
			$display_message_title = true;
		}
		
		foreach($this->config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);
			
			if ($field->is_displayed() && $field->is_authorized() && $field->is_deletable())
			{
				try{
					$value = ContactFieldsService::get_value($this->form, $field);
						$message .= $field->get_name() . ': ' . $value . '

';
				} catch(Exception $e) {
					throw new Exception($e->getMessage());
				}
				
				$display_message_title = true;
			}
		}
		
		if ($display_message_title)
			$message .= $this->lang['contact.form.message'] . ':
';
		
		$message .= $this->form->get_value('f_message');
		
		$mail = new Mail();
		$mail->set_sender(MailServiceConfig::load()->get_default_mail_sender(), $this->lang['module_title']);
		$mail->set_reply_to($this->form->get_value('f_sender_mail'), $current_user->get_display_name());
		$mail->set_subject($subject);
		$mail->set_content(TextHelper::html_entity_decode($message));
		
		if ($recipients_field->is_displayed())
		{
			if (in_array($recipients_field->get_field_type(), array('ContactSimpleSelectField', 'ContactSimpleChoiceField')))
				$recipients_mails = explode(';', $recipients[$this->form->get_value('f_recipients')->get_raw_value()]['email']);
			else
			{
				$selected_recipients = $this->form->get_value('f_recipients');
				$recipients_mails = array();
				foreach ($selected_recipients as $recipient)
				{
					$mails = explode(';', $recipients[$recipient->get_id()]['email']);
					foreach ($mails as $m)
					{
						$recipients_mails[] = $m;
					}
				}
			}
			
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		else if ($subject_field->get_field_type() != 'ContactShortTextField')
		{
			$recipient = $this->form->get_value('f_subject')->get_raw_value() ? $subjects[$this->form->get_value('f_subject')->get_raw_value()]['recipient'] : MailServiceConfig::load()->get_default_mail_sender() . ';' . Mail::SENDER_ADMIN;
			$recipients_mails = explode(';', $recipients[$recipient]['email']);
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		else
		{
			$recipients_mails = explode(';', $recipients['admins']['email']);
			foreach ($recipients_mails as $mail_address)
			{
				$mail->add_recipient($mail_address);
			}
		}
		
		$mail_service = AppContext::get_mail_service();
		
		if ($this->config->is_sender_acknowledgment_enabled())
		{
			$acknowledgment = new Mail();
			$acknowledgment->set_sender(MailServiceConfig::load()->get_default_mail_sender(), Mail::SENDER_ADMIN);
			$acknowledgment->set_subject('[' . $this->lang['contact.acknowledgment_title'] . '] ' . $subject);
			$acknowledgment->set_content($this->lang['contact.acknowledgment'] . $message);
			$acknowledgment->add_recipient($this->form->get_value('f_sender_mail'));
			
			return $mail_service->try_to_send($mail) && $mail_service->try_to_send($acknowledgment);
		}
		
		return $mail_service->try_to_send($mail);
	}
	
	private function check_authorizations()
	{
		if (!ContactAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], ContactUrlBuilder::home());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ContactUrlBuilder::home());
		
		return $response;
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
