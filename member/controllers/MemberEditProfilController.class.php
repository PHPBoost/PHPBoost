<?php
/*##################################################
 *                       MemberEditProfilController.class.php
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

class MemberEditProfilController extends AbstractController
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
		
		$user_id = AppContext::get_user()->get_attribute('user_id');
		$this->build_form($user_id);

		$tpl = new StringTemplate('# INCLUDE FORM #');

		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save($user_id);
		}

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('main');
	}
	
	private function build_form($user_id)
	{
		$form = new HTMLForm('member-edit-profile');
		
		$fieldset = new FormFieldsetHTML('edit-profile', $this->lang['profile_edition']);
		$form->add_fieldset($fieldset);
		
		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $user_id . "' " , __LINE__, __FILE__);

		$fieldset->add_field(new FormFieldTextEditor('login', $this->lang['pseudo'], $row['login'], array(
			'class' => 'text', 'maxlength' => 25, 'size' => 25, 'description' => $this->lang['pseudo_how']),
			array(new FormFieldConstraintLengthRange(3, 25))
		));		
		$fieldset->add_field(new FormFieldTextEditor('mail', $this->lang['mail'], $row['user_mail'], array(
			'class' => 'text', 'maxlength' => 255, 'description' => $this->lang['valid']),
		array(new FormFieldConstraintMailAddress())
		));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_fieldset($fieldset);
		$member_extended_field->set_user_id($user_id);
		MemberExtendedFieldsService::display_form_fields($member_extended_field);
		
		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}
	
	private function save($user_id)
	{
		MemberExtendedFieldsService::register_fields($this->form, $user_id);
		//$this->redirect();
	}
	
	private function redirect()
	{

	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['profile_edition']);
		return $response;
	}
}

?>