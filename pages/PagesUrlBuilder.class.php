<?php
/*##################################################
 *   PagesUrlBuilder.class.php
 *   -------------------------
 *   begin                : August 13, 2011
 *   copyright            : (C) 2011 alain091
 *   email                : alain091@gmail.com
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

class PagesUrlBuilder
{
	const PREFIX = '/pages/';

	public static function get_link_item($encoded_title)
	{
		return PATH_TO_ROOT . self::PREFIX.url(
			'pages.php?title=' . $encoded_title,
			$encoded_title);
	}
	
	public static function get_link_error($error=null)
	{
		if (!empty($error))
			$error = '?error='.$error;
		return PATH_TO_ROOT . self::PREFIX.url('pages.php'.$error);
	}
	
	public static function get_link_item_com($id,$com=0)
	{
		return PATH_TO_ROOT . self::PREFIX.url(
			'pages.php?id='.$id.'&com=0');
	}
}
?>
