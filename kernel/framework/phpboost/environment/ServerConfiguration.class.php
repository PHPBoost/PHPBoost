<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2019 01 24
 * @since       PHPBoost 3.0 - 2010 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class ServerConfiguration
{
	const MIN_PHP_VERSION = '5.6';
	const RECOMMENDED_PHP_VERSION = '7.1';
	private static $mod_rewrite = 'mod_rewrite';

	public static function get_phpversion()
	{
		$system_phpversion = phpversion();
		$matches = array();
		if (preg_match('`^([0-9]+(?:\.[0-9]+){0,2})`u', $system_phpversion, $matches))
		{
			return $matches[1];
		}
		return $system_phpversion;
	}

	/**
	 * @return true if php version fits to phpboost's requirements.
	 */
	public function is_php_compatible()
	{
		return self::get_phpversion() >= self::MIN_PHP_VERSION;
	}

	/**
	 * @return true if GD library is available, else false.
	 */
	public function has_gd_library()
	{
		return @extension_loaded('gd');
	}

	/**
	 * @return true if curl library is available, else false.
	 */
	public function has_curl_library()
	{
		return @extension_loaded('curl');
	}

	/**
	 * @return true if MBstring (UTF-8) library is available, else false.
	 */
	public function has_mbstring_library()
	{
		return @extension_loaded('mbstring');
	}

	/**
	 * @return true if url rewriting is available, else false.
	 */
	public function has_url_rewriting()
	{
		if (function_exists('apache_get_modules'))
		{
			return in_array(self::$mod_rewrite, apache_get_modules());
		}
		throw new UnsupportedOperationException('can\'t check url rewriting availabilty');
	}
}
?>
