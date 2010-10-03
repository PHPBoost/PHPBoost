<?php
/*##################################################
 *                             HTTPResponse.class.php
 *                            -------------------
 *   begin                : Januar 23, 2010
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
 * @desc Manages response via the HTTP protocol
 * @package {@package}
 */
class HTTPResponse
{
	const PROTOCOL = 'HTTP/1.0';

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
 	    '505' => 'HTTP Version Not Supported'
	);

	public function __construct($status_code = 200)
	{
		$this->set_status_code($status_code);
	}

	/**
	 * @desc Send header to client.
	 * @param string $url
	 */
	public function set_header($name, $value)
	{
		header($name . ' : ' . $value);
	}

	/**
	 * @desc Set defaut headers for the response.
	 * @param string $url
	 */
	public function set_default_attributes()
	{
		$this->set_header('Content-type', 'text/html; charset=iso-8859-1');
		$this->set_header('Expires', 'Mon, 1 Dec 2003 01:00:00 GMT');
		$this->set_header('Last-Modified', gmdate("D, d M Y H:i:s") . " GMT");
		$this->set_header('Cache-Control', 'no-store, no-cache, must-revalidate');
		$this->set_header('Cache-Control', 'post-check=0, pre-check=0');
		$this->set_header('Pragma', 'no-cache');
	}

	/**
	 * @desc Redirects the user to the URL and stops purely the script execution (database deconnexion...).
	 * @param string $url URL at which you want to redirect the user.
	 */
	public function redirect($url)
	{
		if (!($url instanceof Url))
		{
			$url = new Url($url);
		}
		$url = $url->rel();

		header('Location:' . $url);
		exit;
	}

	/**
	 * Set cookie for the application's client.
	 * @param HTTPCookie $cookie
	 */
	public function set_cookie(HTTPCookie $cookie)
	{
		setcookie($cookie->get_name(), $cookie->get_value(), $cookie->get_expiration_date(), $cookie->get_path(),
		$cookie->get_domain(), $cookie->get_secure(), $cookie->get_httponly());
	}

	/**
	 * @desc Clean the output buffer.
	 */
	public function clean_output()
	{
		@ob_clean();
	}

	/**
	 * @desc Send the status code
	 * @param int $status_code
	 */
	private function set_status_code($status_code)
	{
		if (isset(self::$status_list[$status_code]))
		{
			header(self::PROTOCOL . $status_code . self::$status_list[$status_code]);
		}
	}
}

?>