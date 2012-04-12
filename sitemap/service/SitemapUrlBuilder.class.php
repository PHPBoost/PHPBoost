<?php
/*##################################################
 *                          SitemapUrlBuilder.class.php
 *                            -------------------
 *   begin                : December 23, 2009
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

class SitemapUrlBuilder
{
	private static $dispatcher = '/sitemap/index.php';

	/**
	 * @return Url
	 */
	public static function get_general_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin');
	}

	/**
	 * @return Url
	 */
	public static function get_xml_file_generation()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/generate');
	}
}
?>