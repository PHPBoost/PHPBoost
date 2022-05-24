<?php
/**
 * Provides access to the HTTP request parameters
 * @package     IO
 * @subpackage  HTTP
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 24
 * @since       PHPBoost 3.0 - 2009 10 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
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
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && TextHelper::strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	private static function get_server_name()
	{
		return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME');
	}

	public function get_is_https()
	{
		if ((!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == '443' ))
			return true;
		else if ((isset ($_SERVER['HTTPS'] ) || isset($_SERVER['HTTP_HTTPS']) || isset($_SERVER['HTTP_X_SECURE'])) && (!empty ($_SERVER['HTTPS'] ) || !empty($_SERVER['HTTP_HTTPS']) || !empty($_SERVER['HTTP_X_SECURE'])) && ((TextHelper::strtolower($_SERVER['HTTPS']) || TextHelper::strtolower($_SERVER['HTTP_HTTPS']) || TextHelper::strtolower($_SERVER['HTTP_X_SECURE'])) !== 'off'))
			return true;
		else
			return false;
	}

	public function get_is_localhost()
	{
		$patterns = array('localhost', 'local.dev', 'local.pbt', '127.0.0.1', '::1');

		foreach ($patterns as $value)
		{
			if (TextHelper::strpos(self::get_server_name(), $value) !== false)
				return true;
		}

		return false;
	}

	public function get_site_url()
	{
		return 'http' . ($this->get_is_https() ? 's' : '') . '://' . self::get_server_name();
	}

	public function get_current_url()
	{
		return $this->get_site_url() . $_SERVER['REQUEST_URI'];
	}

	// get full site domain url
	public function get_site_domain_name()
	{
		return self::get_server_name();
	}

	// get site domain name (without host)
	public function get_domain_name($url = '')
	{
		$pieces = parse_url(!empty($url) ? $url : self::get_site_url());
		$domain = isset($pieces['host']) ? $pieces['host'] : '';
		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/iu', $domain, $regs))
			return $regs['domain'];

		return false;
	}

	public function get_is_subdomain()
	{
		return $this->get_site_domain_name() != $this->get_domain_name() && !preg_match('/www./u', $this->get_site_domain_name());
	}

	public function get_user_agent()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	}

	public function get_url_referrer()
	{
		return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	}

	public function is_mobile_device()
	{
		return isset($_SERVER['HTTP_USER_AGENT']) ? preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($_SERVER['HTTP_USER_AGENT'], 0, 4)) : false;
	}

	public function is_search_engine_robot()
	{
		return Robots::is_robot();
	}

	public function get_ip_address()
	{
		if ($_SERVER)
		{
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE))
			{
				if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false)
				{
					$iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
					$ip = $iplist[0]; // we keep only the first, "normally" it's the client IP if the header was not forged
				}
				else
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_REMOTE_IP']))
				$ip = $_SERVER['HTTP_REMOTE_IP'];
			else
				$ip = $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			if (getenv('HTTP_X_FORWARDED_FOR'))
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			elseif (getenv('HTTP_CLIENT_IP'))
				$ip = getenv('HTTP_CLIENT_IP');
			elseif(getenv('HTTP_REMOTE_IP'))
				$ip=getenv('HTTP_REMOTE_IP');
			else
				$ip = getenv('REMOTE_ADDR');
		}

		if (filter_var($ip, FILTER_VALIDATE_IP))
			return $ip;
		else
			return '0.0.0.0';
	}
	
	public function get_location_info_by_ip()
	{
		$ip_data = @json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip=' . $this->get_ip_address()));
		if($ip_data && $ip_data->geoplugin_countryName != null)
			return $ip_data->geoplugin_countryCode;
		
		return '';
	}
}
?>
