<?php
/*##################################################
 *                               ForumUrlBuilder.class.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class ForumUrlBuilder
{
	private static $dispatcher = '/forum';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/config');
	}
	
	/**
	 * @return Url
	 */
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/' . $id . '/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/' . $id . '/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/categories/');
	}
	
	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name)
	{
		return new Url('/forum/' . url('index.php?id=' . $id, 'cat-' . $id . '+' . $rewrited_name . '.php'));
	}
	
	/**
	 * @return Url
	 */
	public static function display_forum($id, $rewrited_name)
	{
		return new Url('/forum/' . url('forum.php?id=' . $id, 'forum-' . $id . '+' . $rewrited_name . '.php'));
	}
	
	/**
	 * @return Url
	 */
	public static function manage_ranks()
	{
		return new Url('/forum/admin_ranks.php');
	}
	
	/**
	 * @return Url
	 */
	public static function add_rank()
	{
		return new Url('/forum/admin_ranks_add.php');
	}
	
	/**
	 * @return Url
	 */
	public static function moderation_panel()
	{
		return new Url('/forum/moderation_forum.php');
	}
	
	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
}
?>
