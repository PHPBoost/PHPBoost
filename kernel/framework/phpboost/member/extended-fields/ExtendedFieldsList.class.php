<?php
/*##################################################
 *                              ExtendedFieldsList.class.php
 *                            -------------------
 *   begin                : March 27, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class ExtendedFieldsList
{
	private static $original_path = '/kernel/framework/phpboost/member/extended-fields/field/';
	private static $pattern = '`ExtendedField\.class\.php$`';
	private static $exclude_files = array('AbstractMemberExtendedField');
	private static $files = array();
	
	public static function get_files()
	{
		self::$files = array_merge(self::get_original_files(), self::get_modules_files());
		
		return self::$files;
	}
	
	private static function get_original_files()
	{
		return self::search_files(PATH_TO_ROOT . self::$original_path);
	}
	
	private static function get_modules_files()
	{
		$files = array();
		foreach (ModulesManager::get_activated_modules_map() as $module)
		{
			$files_module = self::search_phpboost_folder_module(PATH_TO_ROOT . '/' . $module->get_id());
			if (!empty($files_module))
			{
				foreach ($files_module as $file)
				{
					$files[] = $file;
				}
			}
		}
		return $files;
	}

	private static function search_phpboost_folder_module($directory)
	{
		$files = array();
		$folder = new Folder($directory);
		foreach ($folder->get_folders('`^[phpboost]{1}.*$`i') as $folder)
		{
			$files_folder = self::search_files($folder->get_path());
			if (!empty($files_folder))
			{
				$files = $files_folder;
			}
		}
		return $files;
	}
	
	private static function search_files($directory)
	{
		$files = array();
		$folder = new Folder($directory);
		foreach ($folder->get_files(self::$pattern) as $file)
		{
			if (!in_array($file->get_name_without_extension(), self::$exclude_files))
			{
				$files[] = $file->get_name_without_extension();
			}
		}
		return $files;
	}
}
?>