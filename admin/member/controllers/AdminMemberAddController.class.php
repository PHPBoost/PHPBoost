<?php
/*##################################################
 *                       AdminMemberAddController.class.php
 *                            -------------------
 *   begin                : December 27, 2010
 *   copyright            : (C) 2010 K�vin MASSY
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

class AdminMemberAddController extends AdminController
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

			$tpl->put('MSG', MessageHelper::display($this->lang['members.add-member.success'], E_USER_SUCCESS, 4));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminMembersDisplayResponse($tpl, $this->lang['members.add-member']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-members-common');
	}

	private function build_form()
	{
		$form = new HTMLForm('member-add');
		
		$fieldset = new FormFieldsetHTML('add_member', $this->lang['members.add-member']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['members.pseudo'], '', array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'required' => true),
			array(new FormFieldConstraintLengthRange(3, 25), new FormFieldConstraintLoginExist())
		));		
		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['members.mail'], '', array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['members.valid'], 'required' => true),
			array(new FormFieldConstraintMailAddress(), new FormFieldConstraintMailExist())
		));
		
		$fieldset->add_field($password = new FormFieldPasswordEditor('password', $this->lang['members.password'], '', array('required' => true)));
		
		$fieldset->add_field($password_bis = new FormFieldPasswordEditor('password_bis', $this->lang['members.confirm-password'], '', array('required' => true)));
		
		$fieldset->add_field(new FormFieldRanks('rank', $this->lang['members.rank'], FormFieldRanks::MEMBER));
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_constraint(new FormConstraintFieldsEquality($password, $password_bis));
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function save()
	{
		$user_accounts_config = UserAccountsConfig::load();
		UserService::create(
			$this->form->get_value('login'), 
			KeyGenerator::string_hash($this->form->get_value('password')), 
			$this->form->get_value('rank')->get_raw_value(), 
			$this->form->get_value('mail'), 
			$user_accounts_config->get_default_theme(), 
			GeneralConfig::load()->get_site_timezone(), 
			$user_accounts_config->get_default_lang(), 
			ContentFormattingConfig::load()->get_default_editor(), 
			'0', 
			'', 
			'1'
		);
			
		StatsCache::invalidate();
	}
}
?>