<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 25
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();

		$view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 5));
		}

		$view->put('FORM', $this->form->display());

		return new DefaultAdminDisplayResponse($view);
	}

	private function init()
	{
		$this->lang = array_merge(
			LangLoader::get('form-lang'),
			LangLoader::get('common', 'newsletter')
		);
		$this->config = NewsletterConfig::load();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset_config = new FormFieldsetHTML('configuration', StringVars::replace_vars(LangLoader::get_message('form.module.title', 'form-lang'), array('module_name' => self::get_module()->get_configuration()->get_name())));
		$form->add_fieldset($fieldset_config);

		$fieldset_config->add_field(new FormFieldMailEditor('mail_sender', $this->lang['newsletter.email.sender'], $this->config->get_mail_sender(),
			array('class' => 'third-field', 'description' => $this->lang['newsletter.email.sender.clue'], 'required' => true)
		));

		$fieldset_config->add_field(new FormFieldTextEditor('newsletter_name', $this->lang['newsletter.name'], $this->config->get_newsletter_name(),
			array('class' => 'third-field', 'maxlength' => 255, 'description' => $this->lang['newsletter.name.clue'], 'required' => true)
		));

		$fieldset_config->add_field(new FormFieldNumberEditor('streams_number', $this->lang['newsletter.streams.per.page'], $this->config->get_streams_number_per_page(),
			array('class' => 'third-field', 'min' => 1, 'max' => 50)
		));

		$fieldset_config->add_field(new FormFieldRichTextEditor('default_content', $this->lang['newsletter.default.content'], $this->config->get_default_content(),
			array('rows' => 8, 'cols' => 47, 'description' => $this->lang['newsletter.content.clue'])
		));

		$fieldset_authorizations = new FormFieldsetHTML('authorizations', $this->lang['form.authorizations']);
		$form->add_fieldset($fieldset_authorizations);

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['newsletter.authorizations.streams.read'], NewsletterAuthorizationsService::AUTH_READ),
			new ActionAuthorization($this->lang['newsletter.authorizations.streams.subscribe'], NewsletterAuthorizationsService::AUTH_SUBSCRIBE),
			new ActionAuthorization($this->lang['newsletter.authorizations.subscribers.read'], NewsletterAuthorizationsService::AUTH_READ_SUBSCRIBERS),
			new MemberDisabledActionAuthorization($this->lang['newsletter.authorizations.subscribers.moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_SUBSCRIBERS),
			new VisitorDisabledActionAuthorization($this->lang['newsletter.authorizations.item.write'], NewsletterAuthorizationsService::AUTH_CREATE_NEWSLETTERS),
			new MemberDisabledActionAuthorization($this->lang['newsletter.authorizations.streams.manage'], NewsletterAuthorizationsService::AUTH_MANAGE_STREAMS),
			new ActionAuthorization($this->lang['newsletter.authorizations.archives.manage'], NewsletterAuthorizationsService::AUTH_READ_ARCHIVES),
			new MemberDisabledActionAuthorization($this->lang['newsletter.authorizations.archives.moderation'], NewsletterAuthorizationsService::AUTH_MODERATION_ARCHIVES),
		));

		$auth_settings->build_from_auth_array($this->config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset_authorizations->add_field($auth_setter);

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function save()
	{
		$this->config->set_mail_sender($this->form->get_value('mail_sender'));
		$this->config->set_newsletter_name($this->form->get_value('newsletter_name'));
		$this->config->set_streams_number_per_page($this->form->get_value('streams_number'));
		$this->config->set_default_content($this->form->get_value('default_content'));
		$this->config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		NewsletterConfig::save();
	}
}
?>
