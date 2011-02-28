<?php
/*##################################################
 *		                   AdminNewsletterConfigController.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class AdminNewsletterConfigController extends AdminController
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

	public function execute(HTTPRequest $request)
	{
		$this->init();
		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$tpl->put('MSG', MessageHelper::display($this->lang['success_saving_config'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('newsletter_common', 'newsletter');
	}

	private function build_form()
	{
		$form = new HTMLForm('newsletter_admin');
		$newsletter_config = NewsletterConfig::load();

		$fieldset = new FormFieldsetHTML('configuration', $this->lang['newsletter_config']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('mail_sender', $this->lang['mail_sender'], $newsletter_config->get_mail_sender(), array(
			'class' => 'text', 'maxlength' => 25)
		));
		
		$fieldset->add_field(new FormFieldTextEditor('newsletter_name', $this->lang['newsletter_name'], $newsletter_config->get_newsletter_name(), array(
			'class' => 'text', 'maxlength' => 25)
		));

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['auth.archive-read'], NewsletterConfig::AUTH_READ_ARCHIVE), 
			new ActionAuthorization($this->lang['auth.register-newsletter'], NewsletterConfig::AUTH_REGISTER_NEWSLETTER), 
			new ActionAuthorization($this->lang['auth.moderation-archive'], NewsletterConfig::AUTH_MODERATION_ARCHIVE))
		);
		$auth_settings->build_from_auth_array($newsletter_config->get_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('authorizations', $auth_settings);
		$fieldset->add_field($auth_setter);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$newsletter_config = NewsletterConfig::load();
		$newsletter_config->set_mail_sender($this->form->get_value('mail_sender'));
		$newsletter_config->set_newsletter_name($this->form->get_value('newsletter_name'));
		$newsletter_config->set_authorizations($this->form->get_value('authorizations')->build_auth_array());
		NewsletterConfig::save();
	}

	private function build_response(View $view)
	{
		$response = new AdminMenuDisplayResponse($view);
		$response->set_title($this->lang['title_newsletter']);
		$response->add_link($this->lang['newsletter_admin'], DispatchManager::get_url('/newsletter/index.php', '/config'), '/newsletter/newsletter.png');
		$response->add_link($this->lang['newsletter_archive'], DispatchManager::get_url('/newsletter/index.php', '/archive'), '/newsletter/newsletter.png');
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['title_newsletter_admin']);
		return $response;
	}
}

?>