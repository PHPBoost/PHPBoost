<?php
/*##################################################
 *		             CookieBarConfig.class.php
 *                            -------------------
 *   begin                : September 18, 2016
 *   copyright            : (C) 2016 Genet Arnaud
 *   email                : elenwii@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU ServerEnvironment Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU ServerEnvironment Public License for more details.
 *
 * You should have received a copy of the GNU ServerEnvironment Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class CookieBarConfig extends AbstractConfigData
{
		/**
	 * Returns the configuration.
	 * @return ServerEnvironmentConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'cookie-bar-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'cookie-bar-config');
	}
}
?>