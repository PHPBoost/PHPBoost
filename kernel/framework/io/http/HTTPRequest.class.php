<?php
/*##################################################
 *                           HTTPRequest.class.php
 *                            -------------------
 *   begin                : October 17 2009
 *   copyright            : (C) 2009 Loic Rouchon
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc Provides access to the HTTP request parameters
 * @package {@package}
 */
class HTTPRequest
{
	const bool = 0x00;
	const int = 0x01;
	const float = 0x02;
	const string = 0x03;
	const t_array = 0x04;
	const none = 0x05;
	const VALID_IP_ADDRESS_REGEX = '`(?:(?:[0-9]{1,3}\.){3}[0-9]{1,3})|(?:(?:[0-9abcdef]{0,4}:{1,2}){0,7}[0-9abcdef]{1,4})`';

	/**
	 * @desc
	 * @return Url
	 */
	public function get_current_url()
	{
		$requested_uri = '.';
		if (!empty($_SERVER['REQUEST_URI']))
		{
			$requested_uri = $_SERVER['REQUEST_URI'];
		}
		else
		{
			$env_requested_uri = getenv('REQUEST_URI');
			if (!empty($env_requested_uri))
			{
				$requested_uri = $env_requested_uri;
			}
		}
		$site_path = GeneralConfig::load()->get_site_path();
		$requested_uri = preg_replace('`^' . $site_path . '`', '', $requested_uri);
		return new Url($requested_uri);
	}

	/**
	 * @desc Returns the ip address of the user viewing the page.
	 * The address returned could be an ipv6 or an ipv4 address. If the given address does not match
	 * an ipv4 or an ipv6 format, null is returned.
	 * @return string the user ip address
	 */
	public function get_ip_address()
	{
		foreach (array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR') as $env_varname)
		{
			$ip = $this->get_env_var($env_varname);
			if (!empty($ip) && preg_match(self::VALID_IP_ADDRESS_REGEX, $ip))
			{
				return $ip;
			}
		}
		return null;
	}

	/**
	 * @desc Searches for the environment variable value in array <code>$_SERVER</code> and if not found,
	 * 	it will try to the <code>getenv()</code> method.
	 * @param string $name the environment variable name to be search
	 * @return mixed the environment variable value.
	 */
	private function get_env_var($name)
	{
		if (isset($_SERVER[$name]))
		{
			return $_SERVER[$name];
		}
		return getenv($name);
	}

	public function is_post_method()
	{
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}

	public function is_get_method()
	{
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}

	public function has_parameter($parameter)
	{
		return $this->has_rawparameter($parameter, $_REQUEST);
	}

	public function has_getparameter($parameter)
	{
		return $this->has_rawparameter($parameter, $_GET);
	}

	public function has_postparameter($parameter)
	{
		return $this->has_rawparameter($parameter, $_POST);
	}

	public function has_cookieparameter($parameter)
	{
		return $this->has_rawparameter($parameter, $_COOKIE);
	}

	private function has_rawparameter($parameter, &$array)
	{
		return isset($array[$parameter]);
	}

	public function set_value($varname, $value)
	{
		$this->set_rawvalue($varname, $value, $_GET);
		$this->set_rawvalue($varname, $value, $_POST);
		$this->set_rawvalue($varname, $value, $_REQUEST);
	}

	public function set_getvalue($varname, $value)
	{
		$this->set_rawvalue($varname, $value, $_GET);
		$this->set_rawvalue($varname, $value, $_REQUEST);
	}

	public function set_postvalue($varname, $value)
	{
		$this->set_rawvalue($varname, $value, $_POST);
		$this->set_rawvalue($varname, $value, $_REQUEST);
	}

	private function set_rawvalue($varname, $value, &$array)
	{
		$array[$varname] = $value;
	}

	public function get_value($varname, $default_value = null)
	{
		return $this->get_var($_REQUEST, self::none, $varname, $default_value);
	}

	public function get_bool($varname, $default_value = null)
	{
		return $this->get_var($_REQUEST, self::bool, $varname, $default_value);
	}

	public function get_int($varname, $default_value = null)
	{
		return $this->get_var($_REQUEST, self::int, $varname, $default_value);
	}

	public function get_float($varname, $default_value = null)
	{
		return $this->get_var($_REQUEST, self::float, $varname, $default_value);
	}

	public function get_string($varname, $default_value = null)
	{
		return $this->get_var($_REQUEST, self::string, $varname, $default_value);
	}

	public function get_cookie($varname, $default_value = null)
	{
		return $this->get_var($_COOKIE, self::string, $varname, $default_value);
	}

	public function get_array($varname, $default_value = array())
	{
		return $this->get_var($_REQUEST, self::t_array, $varname, $default_value);
	}

	/**
	 * @param string $varname
	 * @return UploadedFile The uploaded file
	 * @throws UnexistingHTTPParameterException if the file was not found in the request
	 * @throws UploadedFileTooLargeException if the uploaded file is too large
	 * @throws Exception if any other error occurs
	 */
	public function get_file($varname)
	{
		if (isset($_FILES[$varname]))
		{
			return FileUploadService::retrieve_file($varname);
		}
		else
		{
			throw new UnexistingHTTPParameterException($varname);
		}
	}

	public function get_getvalue($varname, $default_value = null)
	{
		return $this->get_var($_GET, self::none, $varname, $default_value);
	}

	public function get_getbool($varname, $default_value = null)
	{
		return $this->get_var($_GET, self::bool, $varname, $default_value);
	}

	public function get_getint($varname, $default_value = null)
	{
		return $this->get_var($_GET, self::int, $varname, $default_value);
	}

	public function get_getfloat($varname, $default_value = null)
	{
		return $this->get_var($_GET, self::float, $varname, $default_value);
	}

	public function get_getstring($varname, $default_value = null)
	{
		return $this->get_var($_GET, self::string, $varname, $default_value);
	}

	public function get_getarray($varname, $default_value = array())
	{
		return $this->get_var($_GET, self::t_array, $varname, $default_value);
	}

	public function get_postvalue($varname, $default_value = null)
	{
		return $this->get_var($_POST, self::none, $varname, $default_value);
	}

	public function get_postbool($varname, $default_value = null)
	{
		return $this->get_var($_POST, self::bool, $varname, $default_value);
	}

	public function get_postint($varname, $default_value = null)
	{
		return $this->get_var($_POST, self::int, $varname, $default_value);
	}

	public function get_postfloat($varname, $default_value = null)
	{
		return $this->get_var($_POST, self::float, $varname, $default_value);
	}

	public function get_poststring($varname, $default_value = null)
	{
		return $this->get_var($_POST, self::string, $varname, $default_value);
	}

	public function get_postarray($varname, $default_value = array())
	{
		return $this->get_var($_POST, self::t_array, $varname, $default_value);
	}

	private function get_var($mode, $type, $varname, $default_value)
	{
		if (!isset($mode[$varname]) && $default_value === null)
		{
			throw new UnexistingHTTPParameterException($varname);
		}
		else if (empty($mode[$varname]) && $default_value !== null)
		{
			return $default_value;
		}
		else
		{
			return $this->get_raw_var($mode, $type, $varname, $default_value);
		}
	}

	private function get_raw_var($mode, $type, $varname, $default_value)
	{
		$value = $mode[$varname];

		switch ($type)
		{
			case self::bool:
				return $this->get_raw_bool($value);
			case self::int:
				return $this->get_raw_int($value, $varname, $default_value);
			case self::float:
				return $this->get_raw_float($value, $varname, $default_value);
			case self::string:
				return $this->get_raw_string($value);
			case self::t_array:
				return $this->get_raw_array($value, $default_value);
			case self::none:
			default:
				return $this->get_raw_string($value);
		}
	}

	private function get_raw_bool($value)
	{
		return $value === 'true' || $value === '1' || $value === true;
	}

	private function get_raw_int($value, $varname, $default_value)
	{
		if (is_numeric($value))
		{
			return NumberHelper::numeric($value, 'int');
		}
		if ($default_value !== null)
		{
			return $default_value;
		}
		else
		{
			throw new ParameterTypeMismatchException($varname, 'int', $value);
		}
	}

	private function get_raw_float($value, $varname, $default_value)
	{
		if (is_float($value))
		{
			return NumberHelper::numeric($value, 'float');
		}
		if ($default_value !== null)
		{
			return $default_value;
		}
		else
		{
			throw new ParameterTypeMismatchException($varname, 'float', $value);
		}
	}

	private function get_raw_string($value)
	{
		$value = self::sanitize($value);
		return (string) $value;
	}

	private static function sanitize($value)
	{
		if (MAGIC_QUOTES)
		{
			$value = stripslashes($value);
		}
		return str_replace(array("\r\n", "\r"), "\n", $value);
	}

	private function get_raw_array(array $array, array $default_value)
	{
		if (!is_array($array))
		{
			return $default_value;
		}
		foreach ($array as &$item)
		{
			$item = self::sanitize($item);
		}
		return $array;
	}
}
?>