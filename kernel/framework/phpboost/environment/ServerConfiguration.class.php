<?php
/*##################################################
 *                          ServerConfiguration.class.php
 *                            -------------------
 *   begin                : May 30, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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
 * @package {@package}
 * @desc
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 */
class ServerConfiguration
{
	const MIN_PHP_VERSION = '5.4';
	private static $mod_rewrite = 'mod_rewrite';

	public static function get_phpversion()
	{
		$system_phpversion = phpversion();
		$matches = array();
		if (preg_match('`^([0-9]+(?:\.[0-9]+){0,2})`', $system_phpversion, $matches))
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
		return ServerConfiguration::get_phpversion() >= self::MIN_PHP_VERSION;
	}

	/**
	 * @return true if GD libray is available, else false.
	 */
	public function has_gd_library()
	{
		return @extension_loaded('gd');
	}

	/**
	 * @return true if curl libray is available, else false.
	 */
	public function has_curl_library()
	{
		return @extension_loaded('curl');
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