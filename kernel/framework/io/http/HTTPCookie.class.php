<?php
/*##################################################
 *                             HTTPCookie.class.php
 *                            -------------------
 *   begin                : Januar 27, 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc Manages cookies via the HTTP protocol
 * @package {@package}
 */

class HTTPCookie
{
	private $name;
	private $value;
	private $expiry_date;
	private $path = '/';
	private $domain = '';
	private $secure = false;
	private $httponly = true;

	public function __construct($name, $value = '', $timestamp = null)
	{
		$this->name = $name;
		$this->value = $value;

		if ($timestamp == null)
		{
			$this->expiry_date = time() + 3600*744; //1 month
		}
		else
		{
			$this->expiry_date = $timestamp;
		}
	}

	/**
	 * The time the cookie expires. This is a Unix timestamp so is
	 * in number of seconds since the epoch. In other words, you'll
	 * most likely set this with the time function
	 * plus the number of seconds before you want it to expire.
	 * @param int $timestamp
	 */
	public function set_expiry_date($timestamp)
	{
		$this->expiry_date = $timestamp;
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
		$this->secure = $secure;
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
		$this->httponly = $httponly;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_value()
	{
		return $this->value;
	}

	public function set_value($value)
	{
		$this->value = $value;
	}

	public function get_expiry_date()
	{
		return $this->expiry_date;
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