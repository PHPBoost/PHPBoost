<?php
/*##################################################
 *             		 PHPBoostFoldersPermissions.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Loic Rouchon
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

abstract class PHPBoostFoldersPermissions
{
	private static $folders_path;

	public static function __static()
	{
		self::$folders_path = array('/', '/cache', '/cache/backup', '/cache/syndication',
			'/cache/tpl', '/cache/css', '/images/avatars', '/images/customization', '/images/group', '/images/maths', '/images/smileys',
			'/kernel/db', '/lang', '/templates', '/upload');
	}

	public static function validate()
	{
		$permissions = self::get_permissions();
		foreach ($permissions as $folder_name => $folder)
		{
			if (!$folder->is_writable())
			{
				return false;
			}
		}
		return true;
	}

	public static function get_permissions()
	{
		@clearstatcache();
		$permissions = array();
		foreach (self::$folders_path as $folder_path)
		{
			$folder = new Folder(PATH_TO_ROOT . $folder_path);
			$permissions[$folder_path] = $folder;
		}
		return $permissions;
	}
}
?>