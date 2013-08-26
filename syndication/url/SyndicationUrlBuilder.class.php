<?php
/*##################################################
 *                       SyndicationUrlBuilder.class.php
 *                            -------------------
 *   begin                : July 18, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 
class SyndicationUrlBuilder
{
	const RSS_FEED = 'rss';
	const ATOM_FEED = 'atom';
	
	public static function rss($id_module, $id_category = null)
	{
		return self::build($id_module, self::RSS_FEED, $id_category);
	}
	
	public static function atom($id_module, $id_category = null)
	{
		return self::build($id_module, self::ATOM_FEED, $id_category);
	}
	
	private static function build($id_module, $type = self::RSS_FEED, $id_category = null) 
	{
		return DispatchManager::get_url('/syndication', '/' . $type . '/'. $id_module . '/' . 
			  ($id_category !== null && $id_category !== 0 ? $id_category : ''));
	}
}
?>