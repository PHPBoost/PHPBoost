<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 24
 * @since       PHPBoost 5.2 - 2019 12 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class StatsModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('stats');

		self::$delete_old_files_list = array(
			'/lang/french/stats_french.php',
			'/lang/english/stats_english.php',
			'/phpboost/StatsHomePageExtensionPoint.class.php',
			'/phpboost/StatsMenusExtensionPoint.class.php',
			'/templates/admin_stats_management.tpl',
			'/admin_stats.php',
			'/display_stats.php',
			'/stats.php',
			'/stats_begin.php',
			'/stats_functions.php',
		);
		
		// Remove unknown bots in bots list
		$file_path = PATH_TO_ROOT . '/stats/cache/robots.txt';
		if (file_exists($file_path) && is_file($file_path) && is_writable($file_path))
		{
			$line = file($file_path);
			if (isset($line[0]))
			{
				$stats_array = TextHelper::unserialize($line[0]);
				if (isset($stats_array['unknow_bot']))
					unset($stats_array['unknow_bot']);

				$file = @fopen($file_path, 'r+');
				fwrite($file, TextHelper::serialize($stats_array));
				fclose($file);
			}
		}
		
		// Remove old os list
		$file_path = PATH_TO_ROOT . '/stats/cache/os.txt';
		if (file_exists($file_path) && is_file($file_path) && is_writable($file_path))
		{
			$line = file($file_path);
			if (isset($line[0]))
			{
				$stats_array = TextHelper::unserialize($line[0]);
				foreach ($stats_array as $id => $value)
				{
					if (in_array($id, array('aix', 'irix', 'hp-ux', 'os2', 'playstation3', 'psp', 'wii', 'sunos', 'freebsd', 'netbsd', 'windowsold', 'windowsxp', 'windowsserver2003', 'windowsvista', 'windowsphone')))
						unset($stats_array[$id]);
				}

				$file = @fopen($file_path, 'r+');
				fwrite($file, TextHelper::serialize($stats_array));
				fclose($file);
			}
		}
	}
}
?>
