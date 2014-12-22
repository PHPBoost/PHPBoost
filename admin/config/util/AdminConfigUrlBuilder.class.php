<?php
/*##################################################
 *                       AdminConfigUrlBuilder.class.php
 *                            -------------------
 *   begin                : September 20, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

class AdminConfigUrlBuilder
{
	private static $dispatcher = '/admin/config';
	
	/*
	 * @ return Url
	 */
	public static function general_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/general/');
	}
	
	/*
	 * @ return Url
	 */
	public static function advanced_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/advanced/', true);
	}
	
	/*
	 * @ return Url
	 */
	public static function mail_config()
	{
		return DispatchManager::get_url(self::$dispatcher, '/mail/');
	}
}
?>