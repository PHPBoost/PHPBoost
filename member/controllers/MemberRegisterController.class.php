<?php
/*##################################################
 *                       MemberRegisterController.class.php
 *                            -------------------
 *   begin                : September 18, 2010 2009
 *   copyright            : (C) 2010 Kévin MASSY
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

class MemberRegisterController extends AbstractController
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

		$tpl = new StringTemplate('# INCLUDE FORM #');

		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('main');
	}
	
	private function build_form()
	{
		$form = new HTMLForm('member-register');
		
		$fieldset = new FormFieldsetHTML('registration', $this->lang['register']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldHTML('validation_method', $this->warn_confirmation_register()));
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'description' => $this->lang['pseudo_how']),
			array(new FormFieldConstraintLengthRange(3, 25))
		));		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['mail'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['valid']),
		array(new FormFieldConstraintMailAddress())
		));
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['password'], '', array(
			'class' => 'text', 'maxlength' => 25, 'description' => $this->lang['password_how']),
		array(new FormFieldConstraintLengthRange(6, 12))
		));
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['confirm_password'], '', array(
			'class' => 'text', 'maxlength' => 25),
		array(new FormFieldConstraintLengthRange(6, 12))
		));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_fieldset($fieldset);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function warn_confirmation_register()
	{
		if (UserAccountsConfig::load()->get_member_accounts_validation_method() == 1)
		{
			return '<strong>'. $this->lang['activ_mbr_mail'] . '<strong>';
		}
		elseif (UserAccountsConfig::load()->get_member_accounts_validation_method() == 2)
		{
			return '<strong>'. $this->lang['activ_mbr_admin'] . '<strong>';
		}
	}
	
	private function save()
	{
		
		//$this->redirect();
	}
	
	private function redirect()
	{

	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['register']);
		return $response;
	}
}

?>