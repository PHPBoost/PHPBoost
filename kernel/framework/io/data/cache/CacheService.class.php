<?php
/*##################################################
 *                           CacheService.class.php
 *                            -------------------
 *   begin                : April 11, 2010
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

class CacheService
{
	private static $all_files_regex_without_extensions = '`^\.|.*\.log|apc.php|debug.php`i';

	private static $cache_folder;
	private static $tpl_cache_folder;
	private static $css_cache_folder;
	private static $syndication_cache_folder;

	public function __construct()
	{
		self::$cache_folder = new Folder(PATH_TO_ROOT . '/cache');
		self::$tpl_cache_folder = new Folder(self::$cache_folder->get_path() . '/tpl');
		self::$css_cache_folder = new Folder(self::$cache_folder->get_path() . '/css');
		self::$syndication_cache_folder = new Folder(self::$cache_folder->get_path() . '/syndication');
	}

	public function clear_cache()
	{
		$this->clear_phpboost_cache();
		$this->clear_template_cache();
		$this->clear_css_cache();
		$this->clear_syndication_cache();
	}

	public function clear_phpboost_cache()
	{
		CacheManager::clear();
		$this->delete_files(self::$cache_folder, self::$all_files_regex_without_extensions);
	}

	public function clear_template_cache()
	{
		$this->delete_files(self::$tpl_cache_folder, self::$all_files_regex_without_extensions, true);
	}

	public function clear_css_cache()
	{
		$this->delete_files(self::$css_cache_folder, self::$all_files_regex_without_extensions, true);
	}

	public function clear_syndication_cache()
	{
		$this->delete_files(self::$syndication_cache_folder, self::$all_files_regex_without_extensions);
	}

	private function delete_files(Folder $folder, $regex = '', $delete_sub_folders = false)
	{
		$files_to_delete = $folder->get_files($regex, true);
		foreach ($files_to_delete as $file)
		{
			$file->delete();
		}
		
		if ($delete_sub_folders)
		{
			foreach ($folder->get_folders() as $f)
			{
				$this->delete_files($f, self::$all_files_regex_without_extensions, true);
			}
		}
	}
}
?>