<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 16
 * @since       PHPBoost 3.0 - 2011 07 25
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UserLostPasswordController extends AbstractController
{
	private $lang;
	private $tpl;
	private $form;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->send_email();
		}

		$this->tpl->put('FORM', $this->form->display());

		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		$fieldset = new FormFieldsetHTMLHeading('fieldset', $this->lang['forget-password']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMailEditor('email', $this->lang['email'], '',
			array('required' => true)
		));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['forget-password'], $this->lang['user']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['seo.user.forget-password']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::forget_password());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['forget-password'], UserUrlBuilder::forget_password()->rel());

		return $response;
	}

	private function send_email()
	{
		$change_password_pass = KeyGenerator::generate_key(15);
		$user = $this->get_user();

		PHPBoostAuthenticationMethod::update_auth_infos($user->get_id(), null, null, null, null, $change_password_pass);

		$general_config = GeneralConfig::load();
		$parameters = array(
			'pseudo' => $user->get_display_name(),
			'host' => $general_config->get_complete_site_url(),
			'change_password_link' => UserUrlBuilder::change_password($change_password_pass)->absolute(),
			'signature' => MailServiceConfig::load()->get_mail_signature()
		);
		$subject = $general_config->get_site_name() . ' : ' . $this->lang['forget-password'];
		$content = StringVars::replace_vars($this->lang['forget-password.mail.content'], $parameters);
		AppContext::get_mail_service()->send_from_properties($user->get_email(), $subject, $content);

		$this->tpl->put('MSG', MessageHelper::display($this->lang['forget-password.success'], MessageHelper::SUCCESS));
	}

	private function get_user()
	{
		$email = $this->form->get_value('email');
		$user_id = UserService::user_exists('WHERE email=:email', array('email' => $email));

		if (!$user_id)
		{
			$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['forget-password.error'], MessageHelper::NOTICE);
			DispatchManager::redirect($controller);
		}
		else
		{
			return UserService::get_user($user_id);
		}
	}

	public function get_right_controller_regarding_authorizations()
	{
		if (AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}
}
?>
