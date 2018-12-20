<?php
/*##################################################
 *		                PatternsUrlBuilder.class.php
 *                            -------------------
 *
 *   begin                : July 26, 2018
 *   copyright            : (C) 2018 Arnaud Genet
 *   email                : elenwii@phpboost.com
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
 * @author Arnaud Genet <elenwii@phpboost.com>
 */
class PatternsUrlBuilder
{	
	private static $dispatcher = '/patterns';
	
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/');
	}

	public static function manage_patterns()
	{
		return DispatchManager::get_url(self::$dispatcher, '/manage/');
	}

	public static function display_patterns($id_patterns, $rewrited_title)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id_patterns . '-' . $rewrited_title . '/');
	}

	public static function add_patterns($id_category = null)
	{
		return DispatchManager::get_url(self::$dispatcher, '/add/');
	}
	
	public static function edit_patterns($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/edit/');
	}
	
	public static function delete_patterns($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/' . $id . '/delete/?' . 'token=' . AppContext::get_session()->get_token());
	}
	
}
?>