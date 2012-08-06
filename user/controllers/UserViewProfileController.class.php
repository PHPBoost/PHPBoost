<?php
/*##################################################
 *                       UserViewProfileController.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserViewProfileController extends AbstractController
{
	private $lang;
	private $form;
	private $user;
	private $tpl;
	private $submit_button;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$user_id = $request->get_getint('user_id', $this->user->get_attribute('user_id'));
		if (!UserService::user_exists('WHERE user_id=:user_id', array('user_id' => $user_id)))
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$this->build_form($user_id);

		$this->tpl->put('FORM', $this->form->display());

		return $this->build_response($this->tpl, $user_id);
	}

	private function init()
	{
		$this->lang = LangLoader::get('user-common');
		$this->tpl = new StringTemplate('# INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
		$this->user = AppContext::get_current_user();
	}
	
	private function build_form($user_id)
	{
		$form = new HTMLForm('member-view-profile');

		$fieldset = new FormFieldsetHTML('profile', $this->lang['profile']);
		$form->add_fieldset($fieldset);
		
		$user_informations = $this->get_user_informations($user_id);
		
		if ($this->user->check_level(User::ADMIN_LEVEL))
		{
			$link_edit = '<a href="'. AdminMembersUrlBuilder::edit($user_id)->absolute() .'">
			<img src="' . TPL_PATH_TO_ROOT . '/templates/'. get_utheme().'/images/'. get_ulang().'/edit.png" alt="'.$this->lang['profile.edit'].'" /></a>';
			$fieldset->add_field(new FormFieldFree('profile_edit', $this->lang['profile.edit'], $link_edit));
		}

		$fieldset->add_field(new FormFieldFree('pseudo', $this->lang['pseudo'], $user_informations['login']));
		
		$fieldset->add_field(new FormFieldFree('level', $this->lang['level'], $this->get_level_lang($user_informations)));

		$fieldset->add_field(new FormFieldFree('groups', $this->lang['groups'], $this->build_groups($user_informations['user_groups'])));
		$fieldset->add_field(new FormFieldFree('registered_on', $this->lang['registration_date'], gmdate_format('date_format_short', $user_informations['timestamp'])));
		$fieldset->add_field(new FormFieldFree('nbr_msg', $this->lang['number-messages'], $user_informations['user_msg'] . '<br>' . '<a href="' . UserUrlBuilder::messages($user_id)->absolute() . '">'. $this->lang['messages'] .'</a>'));
		$fieldset->add_field(new FormFieldFree('last_connect', $this->lang['last_connection'], gmdate_format('date_format_short', $user_informations['last_connect'])));
		
		if (!$this->same_user_view_profile($user_id))
		{
			$link_mp = '<a href="'. UserUrlBuilder::personnal_message($user_id)->absolute() .'">
			<img src="' . TPL_PATH_TO_ROOT . '/templates/'. get_utheme().'/images/'. get_ulang().'/pm.png" alt="'.$this->lang['private_message'].'" /></a>';
			$fieldset->add_field(new FormFieldFree('private_message', $this->lang['private_message'], $link_mp));
		}
		
		$member_extended_field = new MemberExtendedField();
		$member_extended_field->set_template($form);
		$member_extended_field->set_user_id($user_id);
		MemberExtendedFieldsService::display_profile_fields($member_extended_field);
		
		$this->form = $form;
	}

	private function get_user_informations($user_id)
	{
		return PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER, array('*'), "WHERE user_aprob = 1 AND user_id = '" . $user_id . "' ");
	}
	
	private function same_user_view_profile($user_id)
	{
		return $user_id == $this->user->get_attribute('user_id');
	}
	
	private function get_level_lang($user_informations)
	{
		if ($user_informations['user_warning'] < '100' || (time() - $user_informations['user_ban']) < 0)
		{
			return UserService::get_level_lang($user_informations['level']);
		}
		return $this->lang['banned'];
	}
	
	private function build_groups($user_groups)
	{
		$groups_cache = GroupsCache::load();
		$user_groups_html = '';
		$user_groups = explode('|', $user_groups);
		foreach ($user_groups as $key => $group_id)
		{
			if ($group_id > 0)
			{
				if ($groups_cache->group_exists($group_id))
				{
					$group = $groups_cache->get_group($group_id);
					$group_image = !empty($group['img']) ? '<img src="'. TPL_PATH_TO_ROOT .'/images/group/' . $group['img'] . '" alt="' . $group['name'] . '" title="' . $group['name'] . '" class="valign_middle" />' : $group['name'];
					$user_groups_html .= '<li><a href="' . UserUrlBuilder::group($group_id)->absolute() . '">' . $group_image . '</a></li>';
				}
			}
		}
		return !empty($user_groups_html) ? '<ul style="list-style-type:none;">' . $user_groups_html . '</ul>' : $this->lang['user'];
	}
	
	private function build_response(View $view, $user_id)
	{
		$response = new UserDisplayResponse();
		$response->set_page_title($this->lang['profile']);
		$response->add_breadcrumb($this->lang['user'], UserUrlBuilder::users()->absolute());
		$response->add_breadcrumb($this->lang['profile'], UserUrlBuilder::profile($user_id)->absolute());
		return $response->display($view);
	}
}
?>