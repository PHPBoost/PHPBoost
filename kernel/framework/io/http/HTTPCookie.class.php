<?php
/**
 * Manages cookies via the HTTP protocol
 * @package     IO
 * @subpackage  HTTP
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 01 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class HTTPCookie
{
	private $name;
	private $value;
	private $expiration_date;
	private $path = '/';
	private $domain = '';
	private $secure = false;
	private $httponly = true;

	public function __construct($name, $value, $timestamp = null)
	{
		$this->name = $name;
		$this->value = stripslashes($value);

		if (AppContext::get_request()->get_is_https())
			$this->set_secure(true);

		$site_path = GeneralConfig::load()->get_site_path();
		if (!empty($site_path))
		{
			$this->path = $site_path;
		}

		if ($timestamp == null)
		{
			$this->expiration_date = time() + 3600*744; //1 month
		}
		else
		{
			$this->expiration_date = $timestamp;
		}
	}

	/**
	 * The time the cookie expires. This is a Unix timestamp so is
	 * in number of seconds since the epoch. In other words, you'll
	 * most likely set this with the time function
	 * plus the number of seconds before you want it to expire.
	 * @param int $timestamp
	 */
	public function set_expiration_date($timestamp)
	{
		$this->expiration_date = $timestamp;
	}

	/**
	 * The path on the server in which the cookie will be available on.
	 * If set to '/' (default value), the cookie will be available
	 * within the entire domain.
	 * @param string $path
	 */
	public function set_path($path)
	{
		$this->path = $path;
	}

	/**
	 * The domain that the cookie is available.
	 * @param string $domain
	 */
	public function set_domain($domain)
	{
		$this->domain = $domain;
	}

	/**
	 * Indicates that the cookie should only be transmitted over a
	 * secure HTTPS connection from the client. When set to true, the
	 * cookie will only be set if a secure connection exists. The default
	 * is false. On the server-side, it's on the programmer to send this
	 * kind of cookie only on secure connection (e.g. with respect to
	 * $_SERVER["HTTPS"]).
	 * @param boolean $secure
	 */
	public function set_secure($secure)
	{
		$this->secure = (bool)$secure;
	}

	/**
	 * When true the cookie will be made accessible only through the HTTP
	 * protocol. This means that the cookie won't be accessible by
	 * scripting languages, such as JavaScript. This setting can effectively
	 * help to reduce identity theft through XSS attacks (although it is
	 * not supported by all browsers). Added in PHP 5.2.0.
	 * @param boolean $httponly
	 */
	public function set_httponly($httponly)
	{
		$this->httponly = (bool)$httponly;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_value()
	{
		return $this->value;
	}

	public function get_expiration_date()
	{
		return $this->expiration_date;
	}

	public function get_path()
	{
		return $this->path;
	}

	public function get_domain()
	{
		return $this->domain;
	}

	public function get_secure()
	{
		return $this->secure;
	}

	public function get_httponly()
	{
		return $this->httponly;
	}
}
?>
