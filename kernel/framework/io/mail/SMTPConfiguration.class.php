<?php
/*##################################################
 *                           SMTPConfiguration.class.php
 *                            -------------------
 *   begin                : March 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SMTPConfiguration
{
	private $auth_mode = '';
	private $host = '';
	private $port = 0;
	private $login = '';
	private $password = '';
	
	public function get_auth_mode()
	{
		return $this->auth_mode;
	}
	
	public function set_auth_mode($auth_mode)
	{
		$this->auth_mode = $auth_mode;
	}

	public function get_host()
	{
		return $this->host;
	}
	
	public function set_host($host)
	{
		$this->host = $host;
	}
	
	public function get_port()
	{
		return $this->port;
	}
	
	public function set_port($port)
	{
		$this->port = $port;
	}
	
	public function get_login()
	{
		return $this->login;
	}
	
	public function set_login($login)
	{
		$this->login = $login;
	}
	
	public function get_password()
	{
		return $this->password;
	}
	
	public function set_password($password)
	{
		$this->password = $password;
	}
}
?>