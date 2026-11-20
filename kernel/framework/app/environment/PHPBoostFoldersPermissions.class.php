<?php
/**
 * @package     PHPBoost
 * @subpackage  Environment
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 20
 * @since       PHPBoost 3.0 - 2010 05 29
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class PHPBoostFoldersPermissions
{
	private static array $folders_path;

	public static function __static(): void
	{
		self::$folders_path = [
            '/',
            '/cache', '/cache/addons', '/cache/backup', '/cache/syndication', '/cache/tpl', '/cache/css', '/cache/js',
            '/images/avatars', '/images/customization', '/images/group', '/images/maths', '/images/smileys',
			'/kernel/db', '/modules', '/lang', '/templates', '/upload'
        ];
	}

	public static function validate(): bool
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

	public static function get_permissions(): array
	{
		@clearstatcache();
		$permissions = [];
		foreach (self::$folders_path as $folder_path)
		{
			$folder = new Folder(PATH_TO_ROOT . $folder_path);
			$permissions[$folder_path] = $folder;
		}
		return $permissions;
	}
}
?>
