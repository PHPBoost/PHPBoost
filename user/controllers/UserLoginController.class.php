<?php
/*##################################################
 *                           UserLoginController.class.php
 *                            -------------------
 *   begin                : April 05, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class UserLoginController extends AbstractController
{
	private $view;
	private $lang;
	private $request;
	private $form;
	private $fieldset;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->build_error_message();
		$this->build_form();
		$this->init_vars_template();
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$username = $this->form->get_value('login');
			$password = $this->form->get_value('password');
			$autoconnect = $this->form->get_value('autoconnect');
			$this->authenticate($username, $password, $autoconnect);
		}
		
		return $this->build_view();
	}
	
	private function init(HTTPRequestCustom $request)
	{
		$this->request = $request;
		$this->view = new FileTemplate('user/UserLoginController.tpl');
		$this->lang = LangLoader::get('user-common');
		$this->view->add_lang($this->lang);
	}
	
	private function init_vars_template()
	{
		$this->view->put_all(array(
			'U_REGISTER' => UserUrlBuilder::registration()->absolute(),
			'U_FORGET_PASSWORD' => UserUrlBuilder::forget_password()->absolute(),
			'L_FORGET_PASSWORD' => $this->lang['forget-password'],
			'LOGIN_FORM' => $this->form->display(),
		));
	}

	private function authenticate($login, $password, $autoconnect)
	{
		AppContext::get_session()->connect($login, $password, $autoconnect);
	}
	
	private function build_error_message()
	{
		$lang = LangLoader::get('main');
		$errors_lang = LangLoader::get('errors');
		
		$error_type = $this->request->get_string('error_type', '');
		$error_value = $this->request->get_string('error_value', '');
		switch ($error_type) {
			case 'flood':
				if (!empty($error_value))
				{
					$flood = ($error_value > 0 && $error_value <= 5) ? $error_value : 0;
					$this->display_error_message(sprintf($errors_lang['e_test_connect'], $flood));
				}
				else
				{
					$this->display_error_message($errors_lang['e_nomore_test_connect']);
				}		
			break;
			case 'not_enabled':
				$this->display_error_message($errors_lang['e_unactiv_member']);				
			break;
			case 'wrong_password':
				$this->display_error_message($errors_lang['e_wrong_password']);
			break;
			case 'banned':
				if (!empty($error_value))
				{
					$delay = $error_value;
					if ($delay > 0)
					{
						if ($delay < 60)
							$message = $delay . ' ' . (($delay > 1) ? $lang['minutes'] : $lang['minute']);
						elseif ($delay < 1440)
						{
							$delay_ban = NumberHelper::round($delay/60, 0);
							$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['hours'] : $lang['hour']);
						}
						elseif ($delay < 10080)
						{
							$delay_ban = NumberHelper::round($delay/1440, 0);
							$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['days'] : $lang['day']);
						}
						elseif ($delay < 43200)
						{
							$delay_ban = NumberHelper::round($delay/10080, 0);
							$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['weeks'] : $lang['week']);
						}
						elseif ($delay < 525600)
						{
							$delay_ban = NumberHelper::round($delay/43200, 0);
							$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['months'] : $lang['month']);
						}
						else
						{
							$delay_ban = NumberHelper::round($delay/525600, 0);
							$message = $delay_ban . ' ' . (($delay_ban > 1) ? $lang['years'] : $lang['year']);
						}
						$message = $errors_lang['e_member_ban'] . ' ' . $message;
					}
					$this->display_error_message($message);
				}
				else
				{
					$this->display_error_message($errors_lang['e_member_ban_w']);
				}
			break;
			case 'unexisting':
				$this->display_error_message($errors_lang['e_unexist_member']);				
			break;
		}
	}
	
	private function display_error_message($message, $error_type = 'notice')
	{
		$this->view->put('ERROR_MESSAGE', MessageHelper::display($message, $error_type));
	}

	private function build_view()
	{
		$response = new UserSiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['connect']);
		return $response;
	}
	
	/**
	 * @return HTMLForm
	 */
	private function build_form()
	{
		$this->form = new HTMLForm('loginForm');
		$this->form->set_css_class('fieldset_content');

		$this->fieldset = new FormFieldsetHTML('loginFieldset', $this->lang['connect']);
		$login = new FormFieldTextEditor('login', $this->lang['pseudo'], '', array('required' => true));
		$this->fieldset->add_field($login);
		$password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array('required' => true));
		$this->fieldset->add_field($password);
		$autoconnect = new FormFieldCheckbox('autoconnect', $this->lang['autoconnect'], true);
		$this->fieldset->add_field($autoconnect);

		$this->form->add_fieldset($this->fieldset);

		$this->submit_button = new FormButtonSubmit($this->lang['connect'], 'authenticate');
		$this->form->add_button($this->submit_button);
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