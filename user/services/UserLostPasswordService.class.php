<?php
/*##################################################
 *                       UserLostPasswordService.class.php
 *                            -------------------
 *   begin                : October 07, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

class UserLostPasswordService
{
	const LOST_PASSWORD_BY_EMAIL = 'email';
	const LOST_PASSWORD_BY_LOGIN = 'login';

	private static $querier;

	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}

	public static function get_email_by_login($login)
	{
		return self::$querier->get_column_value(DB_TABLE_MEMBER, 'user_mail', 'WHERE login = :login', array('login' => $login));
	}
}
?>