<?php
/*##################################################
 *                           AdminLoginController.class.php
 *                            -------------------
 *   begin                : December 14 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class LoginController extends AbstractController
{
	private $lang;

	/**
	 * @var HTTPRequest
	 */
	private $request;

	/**
	 * HTMLForm
	 */
	private $form;

	/**
	 * FormFieldset
	 */
	private $fieldset;

	/**
	 * FormButtonSubmit
	 */
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$this->request = $request;
		$this->lang = LangLoader::get('login');
		$this->build_form();
		if ($this->submit_button->has_been_submitted() && $this->form->validate())
		{
			$username = $this->form->get_value('username');
			$password = $this->form->get_value('password');
			$autoconnect = $this->form->get_value('autoconnect');
			$this->authenticate($username, $password, $autoconnect);
		}
		return $this->build_view();
	}

	private function authenticate($username, $password, $autoconnect)
	{
		$authentication = new PHPBoostAuthenticationMethod($username, $password);
		if ($authentication->authenticate($autoconnect))
		{
			$session = AppContext::get_session();
			if ($session != null)
			{
				Session::delete($session);
			}
			AppContext::set_session(Session::create($authentication->get_user_id(), $autoconnect));
			AppContext::get_response()->redirect($this->request->get_value('redirect', '/'));
		}
		else
		{
			$this->build_error_message($authentication);
		}
	}

	private function build_view()
	{
		$view = new FileTemplate('member/LoginController.tpl');
		$view->put_all(array(
			'SITE_NAME' => GeneralConfig::load()->get_site_name(),
			'LOGIN_FORM' => $this->form->display()
		));
		return new SiteNodisplayResponse($view);
	}

	private function build_error_message(PHPBoostAuthenticationMethod $authentication)
	{
		$error_msg = '';
		if (!$authentication->has_user_been_found())
		{
			$error_msg = $this->lang['user.notFound'];
		}
		else
		{
			$remaining_attempts = $authentication->get_remaining_attemps();
			if ($remaining_attempts > 0)
			{
				$error_msg = StringVars::replace_vars($this->lang['flood_block'], array('remaining_tries' => $remaining_attempts));
			}
			else
			{
				$error_msg = $this->lang['flood_max'];
			}
		}
		$error = new FormFieldLabel('<span class="errorMsg">' . $error_msg . '</span>');
		$this->fieldset->add_field($error);
	}

	/**
	 * @return HTMLForm
	 */
	private function build_form()
	{
		$this->form = new HTMLForm('loginForm', $this->build_target());
		$this->form->set_css_class('fieldset_content');

		$this->fieldset = new FormFieldsetHTML('loginFieldset', $this->lang['login.form']);
		$login = new FormFieldTextEditor('username', $this->lang['login'], $this->lang['login'], array('required' => true));
		$this->fieldset->add_field($login);
		$password = new FormFieldPasswordEditor('password', $this->lang['password'], $this->lang['password'], array('required' => true));
		$this->fieldset->add_field($password);
		$autoconnect = new FormFieldCheckbox('autoconnect', $this->lang['autoconnect'], false);
		$this->fieldset->add_field($autoconnect);

		$this->form->add_fieldset($this->fieldset);

		$this->submit_button = new FormButtonSubmit($this->lang['connect'], 'authenticate');
		$this->form->add_button($this->submit_button);
	}

	private function build_target()
	{
		$redirect_url = $this->request->get_value('redirect', '/');
		return DispatchManager::get_url('/member/index.php', '/login?redirect=' . urlencode($redirect_url));
	}
}
?>