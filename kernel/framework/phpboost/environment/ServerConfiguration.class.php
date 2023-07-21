<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 26
 * @since       PHPBoost 3.0 - 2010 05 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

class ServerConfiguration
{
	const MIN_PHP_VERSION = '7.1';
	const RECOMMENDED_PHP_VERSION = '7.4';
	private static $mod_rewrite = 'mod_rewrite';

	/**
	 * @return string system php version.
	 */
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
	* Detects max size of file cab be uploaded to server
	*
	* Based on php.ini parameters "upload_max_filesize", "post_max_size" &
	* "memory_limit". Valid for single file upload form. May be used
	* as MAX_FILE_SIZE hidden input or to inform user about max allowed file size.
	*
	* @return int Max file size in bytes
	*/
	public static function get_upload_max_filesize()
	{
		$normalize = function($size)
		{
			if (preg_match('/^([\d\.]+)([KMG])$/i', $size, $match))
			{
				$pos = array_search($match[2], array("K", "M", "G"));
				if ($pos !== false)
				{
					$size = $match[1] * pow(1024, $pos + 1);
				}
			}
			return $size;
		};
		
		$max_upload = $normalize(ini_get('upload_max_filesize'));
		$max_post = $normalize(ini_get('post_max_size'));
		$memory_limit = $normalize(ini_get('memory_limit'));
		if ($memory_limit <= '0' AND $max_post <= '0') {
			$maxFileSize = $max_upload;
		} elseif ($max_post <= '0') {
            $maxFileSize = min($max_upload, $memory_limit);
		} elseif ($memory_limit <= '0') {
            $maxFileSize = min($max_upload, $max_post);
		} else {
            $maxFileSize = min($max_upload, $max_post, $memory_limit);
		}
		return $maxFileSize;
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
	 * @return true if zip library is available, else false.
	 */
	public function has_zip_library()
	{
		return @extension_loaded('zip');
	}

	/**
	 * @return true if allow_url_fopen directive is enabled, else false.
	 */
	public function has_allow_url_fopen()
	{
		return ini_get('allow_url_fopen');
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
		throw new UnsupportedOperationException('can\'t check url rewriting availability');
	}
}
?>
