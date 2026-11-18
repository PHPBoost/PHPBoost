<?php
/**
 * @package     IO
 * @subpackage  Mail
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 17
 * @since       PHPBoost 3.0 - 2010 03 08
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SMTPConfiguration
{
	private string $auth_mode = '';
	private string $host = '';
	private int|string $port = 0;
	private string $login = '';
	private string $password = '';

	public function get_auth_mode(): string
	{
		return $this->auth_mode;
	}

	public function set_auth_mode(string $auth_mode): void
	{
		$this->auth_mode = $auth_mode;
	}

	public function get_host(): string
	{
		return $this->host;
	}

	public function set_host(string $host): void
	{
		$this->host = $host;
	}

	public function get_port(): int|string
	{
		return $this->port;
	}

	public function set_port(int|string $port): void
	{
		$this->port = $port;
	}

	public function get_login(): string
	{
		return $this->login;
	}

	public function set_login(string $login): void
	{
		$this->login = $login;
	}

	public function get_password(): string
	{
		return $this->password;
	}

	public function set_password(string $password): void
	{
		$this->password = $password;
	}
}
?>
