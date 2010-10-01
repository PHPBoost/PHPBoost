<?php
/*##################################################
 *                       ViewProfilController.class.php
 *                            -------------------
 *   begin                : September 23, 2010 2009
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

class ViewProfilController extends AbstractController
{
	public function execute(HTTPRequest $request)
	{
		$this->lang = LangLoader::get('main');
		
		$view = new StringTemplate('# INCLUDE form #');

		$user_id = $request->get_getint('user_id', AppContext::get_user()->get_attribute('user_id'));

		$form = $this->build_form_view_profil($user_id);
	
		$view->add_lang($this->lang);

		$view->add_subtemplate('form', $form->display());
		return new SiteDisplayResponse($view);
	}
	
	private function build_form_view_profil($id)
	{
		$form = new HTMLForm('profil');
		
		$fieldset = new FormFieldsetHTML('profil', $this->lang['profile']);
		$form->add_fieldset($fieldset);
		
		$row = PersistenceContext::get_sql()->query_array(DB_TABLE_MEMBER, '*', "WHERE user_aprob = 1 AND user_id = '" . $id . "' " , __LINE__, __FILE__);

		$link_edit = '<a href="'. PATH_TO_ROOT .'/member/index?url=/profil/edit"><img src="../templates/'. get_utheme().'/images/'. get_ulang().'/edit.png" alt="'.$this->lang['profile_edition'].'" /></a>';
		$fieldset->add_field(new FormFieldFree('profile_edition', $this->lang['profile_edition'], $link_edit));
		
		$fieldset->add_field(new FormFieldFree('pseudo', $this->lang['pseudo'], $row['login']));
		
		$fieldset->add_field(new FormFieldFree('avatar', $this->lang['avatar'], $row['user_avatar']));
		
		$fieldset->add_field(new FormFieldFree('status', $this->lang['status'], ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $row['level'] : $this->lang['banned']));
		$fieldset->add_field(new FormFieldFree('groups', $this->lang['groups'], $row['user_groups']));
		$fieldset->add_field(new FormFieldFree('registered_on', $this->lang['registered_on'], gmdate_format('date_format_short', $row['timestamp'])));
		$fieldset->add_field(new FormFieldFree('nbr_msg', $this->lang['nbr_message'], $row['user_msg'] . '<br>' . '<a href="membermsg.php?id='.$id.'">'. $this->lang['member_msg_display'] .'</a>'));
		$fieldset->add_field(new FormFieldFree('last_connect', $this->lang['last_connect'], $row['last_connect']));
		$fieldset->add_field(new FormFieldFree('web_site', $this->lang['web_site'], $row['user_web']));
		$fieldset->add_field(new FormFieldFree('localisation', $this->lang['localisation'], $row['user_local']));
		$fieldset->add_field(new FormFieldFree('job', $this->lang['job'], $row['user_occupation']));
		
		$fieldset->add_field(new FormFieldFree('hobbies', $this->lang['hobbies'], $row['user_hobbies']));
		$fieldset->add_field(new FormFieldFree('age', $this->lang['age'], $row['user_born']));
		$fieldset->add_field(new FormFieldFree('user_sign', $this->lang['user_sign'], $row['user_sign']));
		
		$this->submit_welcome_button = new FormButtonDefaultSubmit();
		return $form;
	}

	
}

?>