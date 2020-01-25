<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 3.0 - 2010 04 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminMailConfigController extends AdminController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminConfigDisplayResponse($tpl, $this->lang['mail-config']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-config-common');
		$this->config = MailServiceConfig::load();
	}

	protected function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('general_config', $this->lang['mail-config.general_mail_config']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMailEditor('default_mail_sender', $this->lang['mail-config.default_mail_sender'], $this->config->get_default_mail_sender(),
			array(
				'required' => true,
				'description' => $this->lang['mail-config.default_mail_sender_explain']
			)
		));

		$fieldset->add_field(new FormFieldMailEditor('admin_addresses', $this->lang['mail-config.administrators_mails'], implode(',', $this->config->get_administrators_mails()),
			array(
				'required' => true, 'multiple' => true,
				'description' => $this->lang['mail-config.administrators_mails_explain']
			)
		));

		$fieldset->add_field(new FormFieldMultiLineTextEditor('mail_signature', $this->lang['mail-config.mail_signature'], $this->config->get_mail_signature(),
			array('description' => $this->lang['mail-config.mail_signature_explain'])
		));

		$smtp_enabled = $this->config->is_smtp_enabled();

		$fieldset = new FormFieldsetHTML('send_configuration', $this->lang['mail-config.send_protocol'], array('description' => $this->lang['mail-config.send_protocol_explain']));
		$form->add_fieldset($fieldset);
		$fieldset->add_field(new FormFieldCheckbox('use_smtp', $this->lang['mail-config.use_custom_smtp_configuration'], $smtp_enabled,
			array(
				'class' => 'custom-checkbox',
				'events' => array('click' => '
					if (HTMLForms.getField("use_smtp").getValue()) {
						HTMLForms.getFieldset("smtp_configuration").enable();
					} else {
						HTMLForms.getFieldset("smtp_configuration").disable();
					}'
				)
			)
		));


		$fieldset = new FormFieldsetHTML('smtp_configuration', $this->lang['mail-config.custom_smtp_configuration'], array('disabled' => !$smtp_enabled));

		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('smtp_host', $this->lang['mail-config.smtp_host'], $this->config->get_smtp_host(),
			array('required' => true, 'disabled' => !$smtp_enabled),
			array(new FormFieldConstraintRegex('`^[a-z0-9-]+(?:\.[a-z0-9-]+)*$`iu'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('smtp_port', $this->lang['mail-config.smtp_port'], $this->config->get_smtp_port(),
			array('min' => 1, 'max' => 65535, 'disabled' => !$smtp_enabled),
			array(new FormFieldConstraintIntegerRange(0, 65535))
		));

		$fieldset->add_field(new FormFieldTextEditor('smtp_login', $this->lang['mail-config.smtp_login'], $this->config->get_smtp_login(),
			array('disabled' => !$smtp_enabled),
			array()
		));

		$fieldset->add_field(new FormFieldPasswordEditor('smtp_password', $this->lang['mail-config.smtp_password'], $this->config->get_smtp_password(),
			array('disabled' => !$smtp_enabled)));

		$none_protocol_option = new FormFieldSelectChoiceOption($this->lang['mail-config.smtp_secure_protocol_none'], 'none');
		$ssl_protocol_option = new FormFieldSelectChoiceOption($this->lang['mail-config.smtp_secure_protocol_ssl'], 'ssl');
		$tls_protocol_option = new FormFieldSelectChoiceOption($this->lang['mail-config.smtp_secure_protocol_tls'], 'tls');
		$default_protocol_option = $none_protocol_option;
		switch ($this->config->get_smtp_protocol())
		{
			case 'ssl':
				$default_protocol_option = $ssl_protocol_option;
				break;
			case 'tls':
				$default_protocol_option = $tls_protocol_option;
				break;
			default:
				$default_protocol_option = $none_protocol_option;
		}
		$fieldset->add_field(new FormFieldSimpleSelectChoice('smtp_protocol', $this->lang['mail-config.smtp_secure_protocol'], $default_protocol_option,
			array($none_protocol_option, $tls_protocol_option, $ssl_protocol_option),
			array('disabled' => !$smtp_enabled)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	protected function save()
	{
		$this->config->set_default_mail_sender($this->form->get_value('default_mail_sender'));
		$this->config->set_administrators_mails(explode(',', $this->form->get_value('admin_addresses')));
		$this->config->set_mail_signature($this->form->get_value('mail_signature'));

		if ($this->form->get_value('use_smtp'))
		{
			$this->config->enable_smtp();

			$this->config->set_smtp_host($this->form->get_value('smtp_host'));
			$this->config->set_smtp_port($this->form->get_value('smtp_port'));
			$this->config->set_smtp_login($this->form->get_value('smtp_login'));
			$this->config->set_smtp_password($this->form->get_value('smtp_password'));
			$this->config->set_smtp_protocol($this->form->get_value('smtp_protocol')->get_raw_value());
		}
		else
		{
			$this->config->disable_smtp();
		}

		MailServiceConfig::save();
	}
}
?>
