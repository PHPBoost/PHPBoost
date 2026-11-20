<?php
/**
 * @package     IO
 * @subpackage  Data\cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 04 11
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CacheService
{
	private static string $all_files_regex_without_extensions = '`^\.|.*\.log|apc.php|debug.php`iu';

	private static ?Folder $cache_folder = null;
	private static ?Folder $tpl_cache_folder = null;
	private static ?Folder $css_cache_folder = null;
	private static ?Folder $js_cache_folder = null;
	private static ?Folder $syndication_cache_folder = null;

	public function __construct()
	{
		self::$cache_folder             = new Folder(PATH_TO_ROOT . '/cache');
		self::$tpl_cache_folder         = new Folder(self::$cache_folder->get_path() . '/tpl');
		self::$css_cache_folder         = new Folder(self::$cache_folder->get_path() . '/css');
		self::$js_cache_folder          = new Folder(self::$cache_folder->get_path() . '/js');
		self::$syndication_cache_folder = new Folder(self::$cache_folder->get_path() . '/syndication');
	}

	public function clear_cache(): void
	{
		$this->clear_phpboost_cache();
		$this->clear_template_cache();
		$this->clear_css_cache();
		$this->clear_js_cache();
		$this->clear_syndication_cache();
	}

	public function clear_phpboost_cache(): void
	{
		CacheManager::clear();
		$this->delete_files(self::$cache_folder, self::$all_files_regex_without_extensions);
	}

	public function clear_template_cache(): void
	{
		$this->delete_files(self::$tpl_cache_folder, self::$all_files_regex_without_extensions, true);
	}

	public function clear_css_cache(): void
	{
		$this->delete_files(self::$css_cache_folder, self::$all_files_regex_without_extensions, true);
	}

	public function clear_js_cache(): void
	{
		$this->delete_files(self::$js_cache_folder, self::$all_files_regex_without_extensions, true);
	}

	public function clear_syndication_cache(): void
	{
		$this->delete_files(self::$syndication_cache_folder, self::$all_files_regex_without_extensions);
	}

	private function delete_files(Folder $folder, string $regex = '', bool $delete_sub_folders = false): void
	{
		$files_to_delete = $folder->get_files($regex, true);
		foreach ($files_to_delete as $file)
		{
			if ($file->exists())
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
