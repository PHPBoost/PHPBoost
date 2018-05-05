<?php
/*##################################################
 *                          AjaxSearchUserAutoCompleteController.class.php
 *                            -------------------
 *   begin                : June 26, 2013
 *   copyright            : (C) 2013 j1.seth
 *   email                : j1.seth@phpboost.com
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

class AjaxSearchUserAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$lang = LangLoader::get('common');
		$is_admin = AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
		$number_admins = UserService::count_admin_members();
		$suggestions = array();
 
		try {
			$result = PersistenceContext::get_querier()->select("SELECT user_id, display_name, level, groups FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE '" . str_replace('*', '%', $request->get_value('value', '')) . "%'");
			
			while($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['groups'], $row['level']);
				
				$suggestion = '';
				
				if ($is_admin)
				{
					$edit_link = new LinkHTMLElement(UserUrlBuilder::edit_profile($row['user_id']), '', array('title' => $lang['edit']), 'fa fa-edit');
					
					if ($row['level'] != User::ADMIN_LEVEL || ($row['level'] == User::ADMIN_LEVEL && $number_admins > 1))
						$delete_link = new LinkHTMLElement(AdminMembersUrlBuilder::delete($row['user_id']), '', array('title' => $lang['delete'], 'data-confirmation' => 'delete-element'), 'fa fa-delete');
					else
						$delete_link = new LinkHTMLElement('', '', array('title' => $lang['delete'], 'onclick' => 'return false;'), 'fa fa-delete icon-disabled');
					
					$suggestion .= $edit_link->display() . '&nbsp;' . $delete_link->display() . '&nbsp;';
				}
				
				$profile_link = new LinkHTMLElement(UserUrlBuilder::profile($row['user_id'])->rel(), $row['display_name'], array('style' => (!empty($user_group_color) ? 'color:' . $user_group_color : '')), UserService::get_level_class($row['level']));
				
				$suggestion .= $profile_link->display();
				
				$suggestions[] = $suggestion;
			}
			$result->dispose();
		} catch (Exception $e) {
		}
		
		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>