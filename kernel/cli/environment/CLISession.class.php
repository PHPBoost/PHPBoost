<?php
/*##################################################
 *                            CLISession.class.php
 *                            -------------------
 *   begin                : February 08, 2010
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

class CLISession extends Session
{
	public function __construct()
	{
		$this->sql = PersistenceContext::get_sql();
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
		$this->data['user_id'] = 1;
		$this->data['session_id'] = 42;
		$this->data['token'] = self::generate_token();
		return true;
	}

	/**
	 * @desc Get informations from the user, and set it for his session.
	 */
	public function load()
	{
		$this->get_id();

		$this->data['user_id'] = 1;
		$this->data['token'] = self::generate_token();
		$this->data['login'] = 'admin';
		$this->data['level'] = 2;
		$this->data['user_groups'] = '';
		$this->data['user_lang'] = 'english';
		$this->data['user_theme'] = 'base';
		$this->data['user_mail'] = '';
		$this->data['user_pm'] = '0';
		$this->data['user_readonly'] = '0';
		$this->data['user_editor'] = '';
		$this->data['user_timezone'] = 0;
		$this->data['avatar'] = '';
		$this->data['modules_parameters'] = '';
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
		
	}

	/**
	 * @desc Get session identifiers
	 */
	private function get_id()
	{
		$this->data = array();
		$this->data['session_id'] = '';
		$this->data['user_id'] = 1;
		$this->session_mod = 0;
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
		if (empty($this->data['token']))
		{
			$this->data['token'] = self::generate_token();
		}
		return $this->data['token'];
	}

	private static function generate_token()
	{
		return substr(strhash(uniqid(mt_rand(), true), false), 0, 16);
	}

	public function csrf_post_protect($redirect = SEASURF_ATTACK_ERROR_PAGE)
	{
		return true;
	}

	public function csrf_get_protect($redirect = SEASURF_ATTACK_ERROR_PAGE)
	{
		return true;
	}

	public function get_data()
	{
		return $this->data;
	}

	public function supports_cookies()
	{
		return false;
	}
}

?>
