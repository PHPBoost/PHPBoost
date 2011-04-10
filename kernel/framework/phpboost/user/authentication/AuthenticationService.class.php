<?php
/*##################################################
 *                            AuthenticationService.class.php
 *                            -------------------
 *   begin                : November 28, 2010
 *   copyright            : (C) 2010 loic rouchon
 *   email                : horn@phpboost.com
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

/**
 * @author Loic Rouchon <horn@phpboost.com>
 * @desc This class manages the authentication mecanism. Several authentication methods could be used.
 * If the authentication by the selected method successful, the user session is started.
 *
 * @package {@package}
 */
class AuthenticationService
{
	/**
	 * @desc Tries to authenticate the user using the given authentication method.
	 * @param AuthenticationMethod $method the authentication method to use
	 * @param bool $autoconnect If true, an autoconnect cookie will be created
	 * @return bool true, if authentication has been performed successfully
	 */
	public static function authenticate(AuthenticationMethod $method, $autoconnect = false)
	{
		$result = $method->authenticate();
		if ($result)
		{
			Session::delete();
			$session_data = Session::create($user_id, autoconnect);
			AppContext::set_session($session_data);
		}
		return $result;
	}
}

?>
