<?php
/*##################################################
 *                          AjaxUserAutoCompleteController.class.php
 *                            -------------------
 *   begin                : November 15, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AjaxUserAutoCompleteController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$suggestions = array();
 
		try {
			$result = PersistenceContext::get_querier()->select("SELECT display_name, level, groups FROM " . DB_TABLE_MEMBER . " WHERE display_name LIKE '" . str_replace('*', '%', $request->get_value('value', '')) . "%'");
			
			while($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['groups'], $row['level']);
				
				$profile_link = new LinkHTMLElement('', $row['display_name'], array('onclick' => 'return false;', 'style' => (!empty($user_group_color) ? 'color:' . $user_group_color : '')), UserService::get_level_class($row['level']));
				
				$suggestions[] = $profile_link->display();
			}
			$result->dispose();
		} catch (Exception $e) {
		}
		
		return new JSONResponse(array('suggestions' => $suggestions));
	}
}
?>