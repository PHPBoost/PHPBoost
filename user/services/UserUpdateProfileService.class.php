<?php
/*##################################################
 *                       UserUpdateProfileService.class.php
 *                            -------------------
 *   begin                : October 09, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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
 
class UserUpdateProfileService
{
	private static $querier;
	
	public static function __static()
	{
		self::$querier = PersistenceContext::get_querier();
	}
	
	public static function update($user_id, $email, $locale, $timezone, $theme, $editor, $show_email)
	{
		self::$querier->update(DB_TABLE_MEMBER, array(
			'user_mail' => $email,
			'user_lang' => $locale,
			'user_theme' => $theme,
			'user_timezone' => $timezone,
			'user_editor' => $editor,
			'user_show_mail' => $show_email,
		), 'WHERE user_id = :user_id', array('user_id' => $user_id));
	}
 	
	public static function get_password($user_id)
	{
		return self::$querier->get_column_value(DB_TABLE_MEMBER, 'password', 'WHERE user_id = :user_id', array('user_id' => $user_id));
	}
}
?>