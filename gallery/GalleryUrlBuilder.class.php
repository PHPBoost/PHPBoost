<?php
/*##################################################
 *   GalleryUrlBuilder.class.php
 *   ---------------------------
 *   begin                : August 13, 2011
 *   copyright            : (C) 2011 Alain091
 *   email                : alain091@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

class GalleryUrlBuilder
{
	const PREFIX = '/gallery/gallery';
	
	public static function get_link_item($idcat, $id, $com = null, $sort = null)
	{
		return Url::to_absolute(self::PREFIX.url(
			'.php?cat='.$idcat.'&id='.$id.(isset($com)?'&com='.$com:'').(isset($sort)?'&sort='.$sort:'') ,
			'-'.$idcat.'-'.$id.'.php'.(isset($com)?'?com='.$com:'').(isset($sort)?'&sort='.$sort:'')));
	}
	
	public static function get_link_item_add($idcat, $id)
	{
		return Url::to_absolute(self::PREFIX.url(
			'.php?add=1&cat='.$idcat.'&id='.$id,
			'-'.$idcat.'-'.$id.'.php?add=1',
			'&'));
	}
	
	public static function get_link_cat($id, $name = null)
	{
		if (!empty($name))
			$name = '+'+Url::encode_rewrite($name);
			
		return Url::to_absolute(self::PREFIX.url(
			'.php?cat='.$id,
			'-'.$id.$name.'.php'));
	}
	
	public static function get_link_cat_add($id, $error = null, $token = null)
	{
		if (!empty($error))
			$error = '&error='. $error;
		if (!empty($token))
			$token = '&token='. $token;			
		return Url::to_absolute(self::PREFIX.url(
			'.php?add=1&cat='. $id . $error . $token,
			'-'. $id .'.php?add=1'. $error . $token,
			'&'));
	}
}
?>
