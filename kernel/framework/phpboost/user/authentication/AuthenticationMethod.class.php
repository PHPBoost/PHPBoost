<?php
/*##################################################
 *                            AuthenticationMethod.class.php
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
 * @desc The AuthenticationMethod interface could be implemented in different ways to enable specifics
 * authentication mecanisms.
 * PHPBoost comes with a PHPBoostAuthenticationMethod which will be performed on the internal member
 * list. But it is possible to implement external authentication mecanism by providing others
 * implementations of this class to support LDAP authentication, OpenID, Facebook connect and more...
 *
 * @package {@package}
 */
abstract class AuthenticationMethod
{
	protected $error_msg;
	
	/**
	 * @desc associate the current authentication method with the given user_id.
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already associate with an authentication method
	 */
	abstract public function associate($user_id);

	/**
	 * @desc dissociate the current authentication method with the given user_id.
	 * @param int $user_id
	 * @throws IllegalArgumentException if the user_id is already dissociate with an authentication method
	 */
	abstract public function dissociate($user_id);

	/**
	 * @desc Tries to authenticate the user and returns true on success, false otherwise.
	 * @return int $user_id, if authentication has been performed successfully
	 */
	abstract public function authenticate();
	
	public function has_error()
	{
		return !empty($this->error_msg);
	}
	
	public function get_error_msg()
	{
		return $this->error_msg;
	}
}
?>