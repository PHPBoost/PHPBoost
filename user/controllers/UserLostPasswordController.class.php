<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2022 12 09
 * @since       PHPBoost 3.0 - 2011 07 25
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserLostPasswordController extends AbstractController
{
	private $lang;
	private $view;
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

		$this->view->put('FORM', $this->form->display());

		return $this->build_response($this->view);
	}

	private function init()
	{
		$this->view = new StringTemplate('# INCLUDE MESSAGE_HELPER # # INCLUDE FORM #');
		$this->lang = LangLoader::get_all_langs();
	}

	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->lang['user.forgotten.password']);

		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldMailEditor('email', $this->lang['user.email'], '',
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
		$graphical_environment->set_page_title($this->lang['user.forgotten.password'], $this->lang['user.user']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['user.seo.forgotten.password']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(UserUrlBuilder::forget_password());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['user.user'], UserUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['user.forgotten.password'], UserUrlBuilder::forget_password()->rel());

		return $response;
	}

	private function send_email()
	{
		$change_password_pass = KeyGenerator::generate_key(15);
		$user = $this->get_user();

		PHPBoostAuthenticationMethod::update_auth_infos($user->get_id(), null, null, null, null, $change_password_pass);

		$general_config = GeneralConfig::load();
		$parameters = array(
			'pseudo'               => $user->get_display_name(),
			'host'                 => $general_config->get_complete_site_url(),
			'change_password_link' => UserUrlBuilder::change_password($change_password_pass)->absolute(),
			'signature'            => MailServiceConfig::load()->get_mail_signature()
		);
		$subject = $general_config->get_site_name() . ' : ' . $this->lang['user.forgotten.password'];
		$content = StringVars::replace_vars($this->lang['user.forgotten.password.email.content'], $parameters);
		AppContext::get_mail_service()->send_from_properties($user->get_email(), $subject, $content);

		$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['user.forgotten.password.success'], MessageHelper::SUCCESS));
	}

	private function get_user()
	{
		$email = $this->form->get_value('email');
		$user_id = UserService::user_exists('WHERE email=:email', array('email' => $email));

		if (!$user_id)
		{
			$controller = new UserErrorController($this->lang['warning.error'], $this->lang['user.forgotten.password.error'], MessageHelper::NOTICE);
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
