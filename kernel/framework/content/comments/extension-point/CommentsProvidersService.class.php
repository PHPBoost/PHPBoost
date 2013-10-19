<?php
/*##################################################
 *                        CommentsProvidersService.class.php
 *                            -------------------
 *   begin                : September 23, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

class CommentsProvidersService
{	
	public static function module_containing_extension_point($module_id)
	{
		return in_array($module_id, self::get_extension_point_ids());
	}
	
	public static function get_extension_point_ids()
	{
		return array_keys(self::get_extension_point());
	}
	
	public static function get_extension_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(CommentsExtensionPoint::EXTENSION_POINT);
	}
	
	public static function get_provider($module_id, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		if (self::module_containing_extension_point($module_id))
		{
			$extension_point = self::get_extension_point();
			return $extension_point[$module_id]->get_comments_topic($topic_identifier);
		}
	}
}
?>