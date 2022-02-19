<?php
/**
 * Manages response via the HTTP protocol
 * @package     IO
 * @subpackage  HTTP
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 19
 * @since       PHPBoost 3.0 - 2010 01 23
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class HTTPResponseCustom
{
	const PROTOCOL = 'HTTP/2';

	private static $status_list = array(
		'101' => 'Switching Protocols',
		'200' => 'OK',
		'201' => 'Created',
		'202' => 'Accepted',
		'203' => 'Non-Authoritative Information',
		'204' => 'No Content',
		'205' => 'Reset Content',
		'206' => 'Partial Content',
		'300' => 'Multiple Choices',
		'301' => 'Moved Permanently',
		'302' => 'Found',
		'303' => 'See Other',
		'304' => 'Not Modified',
		'305' => 'Use Proxy',
		'306' => '(Unused)',
		'307' => 'Temporary Redirect',
		'400' => 'Bad Request',
		'401' => 'Unauthorized',
		'402' => 'Payment Required',
		'403' => 'Forbidden',
		'404' => 'Not Found',
		'405' => 'Method Not Allowed',
		'406' => 'Not Acceptable',
		'407' => 'Proxy Authentication Required',
		'408' => 'Request Timeout',
		'409' => 'Conflict',
		'410' => 'Gone',
		'411' => 'Length Required',
		'412' => 'Precondition Failed',
		'413' => 'Request Entity Too Large',
		'414' => 'Request-URI Too Long',
		'415' => 'Unsupported Media Type',
		'416' => 'Requested Range Not Satisfiable',
		'417' => 'Expectation Failed',
		'500' => 'Internal Server Error',
		'501' => 'Not Implemented',
		'502' => 'Bad Gateway',
		'503' => 'Service Unavailable',
		'504' => 'Gateway Timeout',
		'505' => 'HTTP Version Not Supported');

	private $last_ob_content_before_clean = '';

	public function __construct($status_code = 200)
	{
		$this->set_status_code($status_code);
	}

	/**
	 * Send header to client.
	 * @param string $url
	 */
	public function set_header($name, $value)
	{
		header($name . ': ' . $value);
	}

	/**
	 * Set defaut headers for the response.
	 * @param string $url
	 */
	public function set_default_attributes()
	{
		$this->set_header('Expires', '-1');
		$this->set_header('Last-Modified', gmdate("D, d M Y H:i:s") . " GMT");
		$this->set_header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->set_header('Pragma', 'no-cache');
	}

	/**
	 * Redirects the user to the URL and stops purely the script execution (database deconnexion...).
	 * @param string $url URL at which you want to redirect the user.
	 */
	public function redirect($url, $message = '', $message_type = MessageHelper::SUCCESS, $message_duration = 5)
	{
		if (!($url instanceof Url))
		{
			$url = new Url($url);
		}
		$url = $url->absolute();

		if (!empty($message))
		{
			$this->set_cookie(new HTTPCookie('message', $message, time() + 3600));
			$this->set_cookie(new HTTPCookie('message_type', $message_type, time() + 3600));
			$this->set_cookie(new HTTPCookie('message_duration', $message_duration, time() + 3600));
		}

		header('Location:' . $url);
		exit;
	}

	/**
	 * Set cookie for the application's client.
	 * @param HTTPCookie $cookie
	 */
	public function set_cookie(HTTPCookie $cookie)
	{
		setcookie($cookie->get_name(), $cookie->get_value(), $cookie->get_expiration_date(), $cookie->get_path(), $cookie->get_domain(), $cookie->get_secure(), $cookie->get_httponly());
	}

	/**
	 * Deletes the cookie
	 * @param string $cookie_name the name of the cookie to delete
	 */
	public function delete_cookie($cookie_name)
	{
		$this->set_cookie(new HTTPCookie($cookie_name, '', -1));
	}

	/**
	 * Clean the output buffer.
	 */
	public function clean_output()
	{
		$this->last_ob_content_before_clean = ob_get_contents();
		ob_end_clean();
	}

	/**
	 * Returns the previous output buffer content.
	 * @return string the previous output buffer content.
	 */
	public function get_previous_ob_content()
	{
		return $this->last_ob_content_before_clean;
	}

	/**
	 * Send the status code
	 * @param int $status_code
	 */
	public function set_status_code($status_code)
	{
		if (isset(self::$status_list[$status_code]))
		{
			header(self::PROTOCOL . ' ' . $status_code . ' ' . self::$status_list[$status_code]);
		}
	}
}
?>
