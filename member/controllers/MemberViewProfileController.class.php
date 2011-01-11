<?php
/*##################################################
 *                       MemberViewProfileController.class.php
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

class MemberViewProfileController extends AbstractController
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

		$user_id = $request->get_getint('user_id', AppContext::get_user()->get_attribute('user_id'));
		
		if ($this->user_exist($user_id))
		{
			$this->build_form($user_id);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}

		$tpl = new StringTemplate('# INCLUDE FORM #');

		$tpl->add_lang($this->lang);

		$tpl->put('FORM', $this->form->display());

		return $this->build_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('main');
	}
	
	private function build_form($user_id)
	{
		$form = new HTMLForm('member-view-profile');

		$fieldset = new FormFieldsetHTML('profile', $this->lang['profile']);
		$form->add_fieldset($fieldset);
		
		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $user_id . "' " , __LINE__, __FILE__);

		$link_edit = '<a href="'. PATH_TO_ROOT .'/member/?url=/profil/edit"><img src="../templates/'. get_utheme().'/images/'. get_ulang().'/edit.png" alt="'.$this->lang['profile_edition'].'" /></a>';
		$fieldset->add_field(new FormFieldFree('profile_edition', $this->lang['profile_edition'], $link_edit));
		
		$fieldset->add_field(new FormFieldFree('pseudo', $this->lang['pseudo'], $row['login']));
		
		$fieldset->add_field(new FormFieldFree('avatar', $this->lang['avatar'], $row['user_avatar']));
		
		$fieldset->add_field(new FormFieldFree('status', $this->lang['status'], ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $row['level'] : $this->lang['banned']));
		$fieldset->add_field(new FormFieldFree('groups', $this->lang['groups'], $row['user_groups']));
		$fieldset->add_field(new FormFieldFree('registered_on', $this->lang['registered_on'], gmdate_format('date_format_short', $row['timestamp'])));
		$fieldset->add_field(new FormFieldFree('nbr_msg', $this->lang['nbr_message'], $row['user_msg'] . '<br>' . '<a href="membermsg.php?id='.$user_id.'">'. $this->lang['member_msg_display'] .'</a>'));
		$fieldset->add_field(new FormFieldFree('last_connect', $this->lang['last_connect'], gmdate_format('date_format_short', $row['last_connect'])));
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id(1);
		MemberExtendedFieldsService::display_profile_fields($member_extended_field);
		
		$this->form = $form;
	}

	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$env = $response->get_graphical_environment();
		$env->set_page_title($this->lang['profile']);
		return $response;
	}
	
	private function user_exist($user_id)
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_id = '" . $user_id . "'") > 0 ? true : false;
	}
}

?>