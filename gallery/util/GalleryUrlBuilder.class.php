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
	private static $dispatcher = '/gallery';
	
	/**
	 * @return Url
	 */
	public static function configuration()
	{
		return new Url('/gallery/admin_gallery_config.php');
	}
	
	/**
	 * @return Url
	 */
	public static function add_category()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/add/');
	}
	
	/**
	 * @return Url
	 */
	public static function edit_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/edit/');
	}
	
	/**
	 * @return Url
	 */
	public static function delete_category($id)
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/'. $id .'/delete/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage_categories()
	{
		return DispatchManager::get_url(self::$dispatcher, '/admin/categories/');
	}
	
	/**
	 * @return Url
	 */
	public static function manage()
	{
		return new Url('/gallery/admin_gallery.php');
	}
	
	/**
	 * @return Url
	 */
	public static function display_category($id, $rewrited_name, $page = 1)
	{
		return new Url('/gallery/gallery' . url('.php?cat=' . $id . ($page !== 1 ? '&p=' . $page : ''), '-' . $id . '+' . $rewrited_name . ($page !== 1 ? '-' . $page : '') . '.php'));
	}
	
	/**
	 * @return Url
	 */
	public static function admin_add($id_category = null)
	{
		return new Url('/gallery/admin_gallery_add.php' . (!empty($id_category) ? '?cat=' . $id_category : ''));
	}
	
	/**
	 * @return Url
	 */
	public static function add($id_category = null)
	{
		return new Url('/gallery/gallery.php?add=1' . (!empty($id_category) ? '&cat=' . $id_category : ''));
	}
	
	/**
	 * @return Url
	 */
	public static function home()
	{
		return DispatchManager::get_url(self::$dispatcher, '/');
	}
	
	
	// TODO : supprimer ce qui est en dessous si possible
	public static function get_link_item($idcat, $id, $com = null, $sort = null)
	{
		return Url::to_rel('/gallery/gallery' . url('.php?cat=' . $idcat . '&id=' . $id . (!empty($com) ? '&com=' . $com : '') . (!empty($sort) ? '&sort=' . $sort : ''),
			'-' . $idcat . '-' . $id . '.php' . (!empty($com) ? '?com=' . $com : '') . (!empty($sort) ? '&sort=' . $sort : '')));
	}
	
	public static function get_link_cat($id, $name = null)
	{
		if (!empty($name))
			$name = '+' . Url::encode_rewrite($name);
			
		return Url::to_rel('/gallery/gallery'.url('.php?cat='.$id, '-'.$id.$name.'.php'));
	}
	
	public static function get_link_cat_add($id, $error = null, $token = null)
	{
		if (!empty($error))
			$error = '&error='. $error;
		if (!empty($token))
			$token = '&token='. $token;
		return Url::to_rel('/gallery/gallery'.url(
			'.php?add=1&cat='. $id . $error . $token,
			'-'. $id .'.php?add=1'. $error . $token,
			'&'));
	}
}
?>
