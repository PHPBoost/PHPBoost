<?php
/*##################################################
 *                            AdminSession.class.php
 *                            -------------------
 *   begin                : September 12, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author Régis VIARRE <crowkait@phpboost.com>
 * @desc This class manage user, it provide you methods to get or modify user informations, moreover methods allow you to control user authorizations
 * @package members
 */
class AdminSession extends Session
{
	private $session_id = 1;
	private $token = 42;

	public function __construct()
	{
	}

	/**
	 * @desc Manage the actions for the session caused by the user (connection, disconnection).
	 */
	public function act()
	{
	}

	/**
	 * @desc Start the session
	 * @param int $user_id The member's user id.
	 * @param string $password The member's password.
	 * @param string $session_script Session script value where the session is started.
	 * @param string $session_script_get Get value of session script where the session is started.
	 * @param string $session_script_title Title of session script where the session is started.
	 * @param boolean $autoconnect The member user id.
	 * @param boolean $already_hashed True if password has been already hashed width str_hash() function, false otherwise.
	 * @return True if succed, false otherwise and return an error code.
	 */
	public function start($user_id, $password, $level, $session_script, $session_script_get, $session_script_title, $autoconnect = false, $already_hashed = false)
	{
		return 0;
	}

	/**
	 * @desc Get informations from the user, and set it for his session.
	 */
	public function load()
	{

	}

	/**
	 * @desc Check session validity, and update it
	 * @param string $session_script_title The page title where the session has been check.
	 */
	public function check($session_script_title)
	{

	}

	/**
	 * @desc Destroy the session
	 */
	public function end()
	{

	}

	/**
	 *  @desc Save module's parameters into session
	 * @param mixed module's parameters
	 */
	public function set_module_parameters($parameters, $module = '')
	{

	}

	/**
	 *  @desc Get module's parametres from session
	 * @param string module  module name (if null then current module)
	 * @return array array of parameters
	 */
	public function get_module_parameters($module = '')
	{
		return array();
	}

	/**
	 * @desc Get session identifiers
	 */
	private function get_id()
	{

	}

	/**
	 * @desc Create session int autoconnect mode
	 * @param string $session_script Session script value where the session is started.
	 * @param string $session_script_get Get value of session script where the session is started.
	 * @param string $session_script_title Title of session script where the session is started.
	 */
	private function autoconnect($session_script, $session_script_get, $session_script_title)
	{
		return false;
	}

	/**
	 * @desc Deletes all the existing sessions
	 */
	public function garbage_collector()
	{

	}

	/**
	 * @desc Return the session token
	 * @return string the session token
	 */
	public function get_token()
	{
		return $this->token;
	}
}
?>