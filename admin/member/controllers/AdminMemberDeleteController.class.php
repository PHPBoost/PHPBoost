<?php
/*##################################################
 *                      AdminMemberDeleteController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class AdminMemberDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$user_id = $request->get_int('id', null);
		
		$lang = LangLoader::get('errors-common');
		try
		{
			UserService::delete_account('WHERE user_id=:user_id', array('user_id' => $user_id));
		}
		catch (RowNotFoundException $ex) {
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
		
		$upload = new Uploads();
		$upload->Empty_folder_member($user_id);
		
		StatsCache::invalidate();
		
		AppContext::get_response()->redirect(PATH_TO_ROOT . '/admin/admin_members.php');
	}
}
?>