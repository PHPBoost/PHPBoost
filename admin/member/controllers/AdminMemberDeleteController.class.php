<?php
/*##################################################
 *                      AdminMemberDeleteController.class.php
 *                            -------------------
 *   begin                : February 28, 2010
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

class AdminMemberDeleteController extends AdminController
{
	public function execute(HTTPRequest $request)
	{
		$user_id = $request->get_int('id', null);
		
		if (!UserService::user_exists_by_id($user_id) && $user_id !== null)
		{
			if (self::verificate_number_admin_user() > 1)
			{
				UserService::delete_account($user_id);
				
				StatsCache::invalidate();
				
				// TODO replace for new delete user function
				throw new Exception('Ok !');
			}
			else
			{
				throw new Exception('Is the last admin !');
			}
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_member();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private static function verificate_number_admin_user()
	{
		return PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE user_aprob = 1 AND level = 1");
	}
}
?>