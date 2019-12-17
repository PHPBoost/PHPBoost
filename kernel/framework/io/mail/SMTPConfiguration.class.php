<?php
/**
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 06
 * @since       PHPBoost 3.0 - 2010 03 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
