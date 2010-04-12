<?php
/*##################################################
 *                       AdminMailConfigController.class.php
 *                            -------------------
 *   begin                : April 12, 2010
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

class AdminMailConfigController extends AdminController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonDefaultSubmit
	 */
	private $submit_button;
	
	private $lang;

	public function __construct()
	{
		$this->load_lang();
	}

	private function load_lang()
	{
		$this->lang = LangLoader::get('admin-config-common');
	}

	public function execute(HTTPRequest $request)
	{
		$view = new StringTemplate('# IF C_SUBMIT #
			<div class="success" id="mail_config_saved_success">Configuration saved</div>
			<script type="text/javascript"><!--
			window.setTimeout(function() { Effect.Fade("mail_config_saved_success"); }, 5000);
			--></script>
			# ENDIF #
			<script type="text/javascript"><!--
			function show_smtp_config()
			{
				Effect.Appear("mail_sending_config_smtp_configuration_fieldset");
				HTMLForms.getField("mail_sending_config_smtp_host").enable();
				HTMLForms.getField("mail_sending_config_smtp_port").enable();
				HTMLForms.getField("mail_sending_config_smtp_login").enable();
				HTMLForms.getField("mail_sending_config_smtp_password").enable();
				HTMLForms.getField("mail_sending_config_secure_protocol").enable();
			}
			
			function hide_smtp_config()
			{
				Effect.Fade("mail_sending_config_smtp_configuration_fieldset");
				HTMLForms.getField("mail_sending_config_smtp_host").disable();
				HTMLForms.getField("mail_sending_config_smtp_port").disable();
				HTMLForms.getField("mail_sending_config_smtp_login").disable();
				HTMLForms.getField("mail_sending_config_smtp_password").disable();
				HTMLForms.getField("mail_sending_config_secure_protocol").disable();
			}
			--></script>
			# INCLUDE SMTP_FORM #');

		$this->build_form();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$view->assign_vars(array(
				'C_SUBMIT' => true
			));
		}
		$view->add_subtemplate('SMTP_FORM', $this->form->display());
		return new AdminConfigDisplayResponse($view);
	}

	private function build_form()
	{
		$this->form = new HTMLForm('mail_sending_config');
		
		$fieldset = new FormFieldsetHTML('general_config', $this->lang['general_mail_config']);
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldMailEditor('default_mail_sender', $this->lang['default_mail_sender'], '', array('required' => true, 'description' => $this->lang['default_mail_sender_explain'])));
		$fieldset->add_field(new FormFieldTextEditor('admin_addresses', $this->lang['administrators_mails'], '', array('required' => true, 'description' => $this->lang['administrators_mails_explain'])));
		$fieldset->add_field(new FormFieldMultiLineTextEditor('mail_signature', $this->lang['mail_signature'], '', array('description' => $this->lang['mail_signature_explain'])));

		$fieldset = new FormFieldsetHTML('send_configuration', $this->lang['send_protocol'], array('description' => $this->lang['send_protocol_explain']));
		$this->form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('use_smtp', $this->lang['use_custom_smtp_configuration'], false,
			array('events' => array('click' => 'if ($F("mail_sending_config_use_smtp") == "on") { show_smtp_config(); } else { hide_smtp_config(); }'))));


		$fieldset = new FormFieldsetHTML('smtp_configuration', $this->lang['custom_smtp_configuration'], array('hidden' => true));
		
		$this->form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('smtp_host', $this->lang['smtp_host'], '', array('disabled' => true), array(new FormFieldConstraintRegex('`^[a-z0-9-]+(?:\.[a-z0-9-]+)*$`i'))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_port', $this->lang['smtp_port'], 25, array('disabled' => true), array(new FormFieldConstraintIntegerRange(0, 65535))));
		$fieldset->add_field(new FormFieldTextEditor('smtp_login', $this->lang['smtp_login'], '', array('disabled' => true), array(new FormFieldConstraintNotEmpty())));
		$fieldset->add_field(new FormFieldPasswordEditor('smtp_password', $this->lang['smtp_password'], '', array('disabled' => true)));

		$select_option = new FormFieldSelectChoiceOption($this->lang['smtp_secure_protocol_none'], 'none');
		$fieldset->add_field(new FormFieldSelectChoice('secure_protocol', $this->lang['smtp_secure_protocol'], $select_option, array($select_option, new FormFieldSelectChoiceOption($this->lang['smtp_secure_protocol_tls'], 'tls'), new FormFieldSelectChoiceOption($this->lang['smtp_secure_protocol_ssl'], 'ssl')), array('disabled' => true)));

		$this->submit_button = new FormButtonDefaultSubmit();
		$this->form->add_button($this->submit_button);
	}
}
?>
