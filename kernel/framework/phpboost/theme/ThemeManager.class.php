<?php
/*##################################################
/**
 *                         ThemeManager.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class ThemeManager
{
	private static $errors = null;
	
	public static function install($module_id, $enable_module true, )
	{
		
		self::regenerate_cache();
	}
	
	public static function unistall()
	{
	
		self::regenerate_cache();
	}
	
	public static function get_errors()
	{
		return self::$errors;
	}
	
	private static function regenerate_cache()
	{
		ThemesCache::invalidate();
		
    	ModulesCssFilesCache::invalidate();
	}
}
?>
