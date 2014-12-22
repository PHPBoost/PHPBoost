<?php
/*##################################################
 *                        ContentFormattingProvidersService.class.php
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

class ContentFormattingProvidersService
{
	public static function create_factory($language)
	{
		return self::get_editor($language);
	}
	
	public static function get_editor($id)
	{
		$editors = self::get_editors();
		if (array_key_exists($id, $editors))
		{
			return $editors[$id];
		}
	}
	
	public static function get_editors()
	{
		$editors = array();
		foreach (self::get_extensions_point() as $id => $provider)
		{
			$editors[$id] = $provider;
		}
		return $editors;
	}
	
	public static function get_extensions_point()
	{
		return AppContext::get_extension_provider_service()->get_extension_point(ContentFormattingExtensionPoint::EXTENSION_POINT);
	}
}
?>