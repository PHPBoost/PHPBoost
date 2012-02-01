<?php
/*##################################################
 *                       UserChangeLostPasswordController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

class UserChangeLostPasswordController extends AbstractController
{
	private $lang;
	private $tpl;
	private $form;
	private $submit_button;

	public function execute(HTTPRequest $request)
	{
		$this->init();

		$change_password_pass = $request->get_getstring('key','');
		if (empty($change_password_pass) || !UserLostPasswordService::change_password_pass_exists($change_password_pass))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		
		$this->build_form();
		
		if($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->change_password($change_password_pass, $this->form->get_value('password'));
		}
		
		$this->tpl->put('FORM', $this->form->display());
		
		return $this->build_response($this->tpl, $change_password_pass);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->lang = LangLoader::get('user-common');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		$form = new HTMLForm('change_password_form');
		
		$fieldset = new FormFieldsetHTML('fieldset', $this->lang['change-password']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password.new'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password.explain'], 'required' => true),
			array(new FormFieldConstraintLength(6))
		));
			
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['password.confirm'], '', array(
			'class' => 'text', 'maxlength' => 25, 'required' => true),
			array(new FormFieldConstraintLength(6))
		));
			
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
			
		$this->form = $form;
	}

	private function change_password($change_password_pass, $password)
	{
		$password = KeyGenerator::string_hash($password);
		$user_id = UserLostPasswordService::get_user_id_by_change_password_pass($change_password_pass);
		UserService::change_password($user_id, $password);
		UserLostPasswordService::clear_activation_key($user_id);
		UserLostPasswordService::connect_user($user_id, $password);
	}
	
	private function build_response(View $view, $key)
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['change-password']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['change-password'], UserUrlBuilder::change_password($key)->absolute());
		return $response->display($view);
	}
	
	public function get_right_controller_regarding_authorizations()
	{
		if (AppContext::get_current_user()->check_level(MEMBER_LEVEL))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}
		return $this;
	}
}
?>