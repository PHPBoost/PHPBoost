<?php
/*##################################################
 *                           HTTPRequestCustom.class.php
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
class HTTPRequestCustom
{
	const bool = 0x00;
	const int = 0x01;
	const float = 0x02;
	const string = 0x03;
	const t_array = 0x04;
	const none = 0x05;
	
	private $_request;
	private $_get;
	private $_post;
	
	public function __construct()
	{
		$this->_request = self::sanitize_html($_REQUEST);
		$this->_get = self::sanitize_html($_GET);
		$this->_post = self::sanitize_html($_POST);
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
		return $this->has_rawparameter($parameter, $this->_request);
	}

	public function has_getparameter($parameter)
	{
		return $this->has_rawparameter($parameter, $this->_get);
	}

	public function has_postparameter($parameter)
	{
		return $this->has_rawparameter($parameter, $this->_post);
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
		$this->set_rawvalue($varname, $value, $this->_get);
		$this->set_rawvalue($varname, $value, $this->_post);
		$this->set_rawvalue($varname, $value, $this->_request);
	}

	public function set_getvalue($varname, $value)
	{
		$this->set_rawvalue($varname, $value, $this->_get);
		$this->set_rawvalue($varname, $value, $this->_request);
	}

	public function set_postvalue($varname, $value)
	{
		$this->set_rawvalue($varname, $value, $this->_post);
		$this->set_rawvalue($varname, $value, $this->_request);
	}

	private function set_rawvalue($varname, $value, &$array)
	{
		$array[$varname] = $value;
	}

	public function get_value($varname, $default_value = null)
	{
		return $this->get_var($this->_request, self::none, $varname, $default_value);
	}

	public function get_bool($varname, $default_value = null)
	{
		return $this->get_var($this->_request, self::bool, $varname, $default_value);
	}

	public function get_int($varname, $default_value = null)
	{
		return $this->get_var($this->_request, self::int, $varname, $default_value);
	}

	public function get_float($varname, $default_value = null)
	{
		return $this->get_var($this->_request, self::float, $varname, $default_value);
	}

	public function get_string($varname, $default_value = null)
	{
		return $this->get_var($this->_request, self::string, $varname, $default_value);
	}

	public function get_array($varname, $default_value = array())
	{
		return $this->get_var($this->_request, self::t_array, $varname, $default_value);
	}

	public function get_cookie($varname, $default_value = null)
	{
		return $this->get_var($_COOKIE, self::string, $varname, $default_value);
	}

	public function _get_parameters_array($varname, $default_value = array())
	{
		return $this->get_var($this->_request, self::t_array, $varname, $default_value);
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
		return $this->get_var($this->_get, self::none, $varname, $default_value);
	}

	public function get_getbool($varname, $default_value = null)
	{
		return $this->get_var($this->_get, self::bool, $varname, $default_value);
	}

	public function get_getint($varname, $default_value = null)
	{
		return $this->get_var($this->_get, self::int, $varname, $default_value);
	}

	public function get_getfloat($varname, $default_value = null)
	{
		return $this->get_var($this->_get, self::float, $varname, $default_value);
	}

	public function get_getstring($varname, $default_value = null)
	{
		return $this->get_var($this->_get, self::string, $varname, $default_value);
	}

	public function get_getarray($varname, $default_value = array())
	{
		return $this->get_var($this->_get, self::t_array, $varname, $default_value);
	}

	public function get_postvalue($varname, $default_value = null)
	{
		return $this->get_var($this->_post, self::none, $varname, $default_value);
	}

	public function get_postbool($varname, $default_value = null)
	{
		return $this->get_var($this->_post, self::bool, $varname, $default_value);
	}

	public function get_postint($varname, $default_value = null)
	{
		return $this->get_var($this->_post, self::int, $varname, $default_value);
	}

	public function get_postfloat($varname, $default_value = null)
	{
		return $this->get_var($this->_post, self::float, $varname, $default_value);
	}

	public function get_poststring($varname, $default_value = null)
	{
		return $this->get_var($this->_post, self::string, $varname, $default_value);
	}

	public function get_postarray($varname, $default_value = array())
	{
		return $this->get_var($this->_post, self::t_array, $varname, $default_value);
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
		return $value === 'true' || $value === '1' || $value === 'on' || $value === true;
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
		if (is_array($value))
			$value = implode(',', $value);
		
		$value = self::sanitize($value);
		return (string) $value;
	}

	private static function sanitize($value)
	{
		return str_replace(array("\r\n", "\r"), "\n", $value);
	}

	private static function sanitize_html(Array $array)
	{
		$proper_array = array();
		
		foreach ($array as $key => $value)
		{
			if (is_array($value))
				$proper_array[$key] = self::sanitize_html($value);
			else
				$proper_array[$key] = TextHelper::htmlspecialchars($value);
		}
		
		return $proper_array;
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


	public function get_is_ajax_request()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	private static function get_http_host()
	{
		return (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
	}

	public function get_is_https()
	{
		return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off';
	}

	public function get_is_localhost()
	{
		$patterns = array('localhost', 'local.dev', '127.0.0.1', '::1');
		
		foreach ($patterns as $value)
		{
			if (strpos(self::get_http_host(), $value) !== false)
				return true;
		} 
		
		return false;
	}

	public function get_site_url()
	{
		return 'http' . ($this->get_is_https() ? 's' : '') . '://' . self::get_http_host();
	}

	public function get_site_domain_name()
	{
		return self::get_http_host();
	}

	public function get_user_agent()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	public function get_url_referrer()
	{
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	}
	
	public function get_ip_address()
	{
		if ($_SERVER)
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			elseif (isset($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			else
			{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
			{
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_CLIENT_IP'))
			{
				$ip = getenv('HTTP_CLIENT_IP');
			}
			else
			{
				$ip = getenv('REMOTE_ADDR');
			}
		}

		if (preg_match('`^((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))$|^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(([0-9A-Fa-f]{1,4}:){0,5}:((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|(::([0-9A-Fa-f]{1,4}:){0,5}((b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b).){3}(b((25[0-5])|(1d{2})|(2[0-4]d)|(d{1,2}))b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$`', $ip))
		{
			return $ip;
		}
		else
		{
			return '0.0.0.0';
		}
	}
}
?>
