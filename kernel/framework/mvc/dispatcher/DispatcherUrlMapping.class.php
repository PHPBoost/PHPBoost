<?php
/**
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 10 06
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DispatcherUrlMapping extends UrlMapping
{
	private bool $high_priority = false;
	private bool $low_priority = false;

	/**
	 * Core system folders that always live at the site root.
	 */
	private static array $core_root_folders = [
		'kernel', 'admin', 'cache', 'install', 'update',
		'user', 'syndication', 'upload', 'images', 'lang', 'templates',
	];

	/**
	 * Determines whether a module name resolves to a root-level folder.
	 * A module is considered root when:
	 *   - it is in the hard-coded core list, OR
	 *   - it has an actual directory at PATH_TO_ROOT/$module
	 *     (covers lobby and any future root-level module).
	 */
	private static function is_root_module(string $module): bool
	{
		if (in_array($module, self::$core_root_folders))
		{
			return true;
		}
		if (defined('PATH_TO_ROOT') && is_dir(PATH_TO_ROOT . '/' . $module))
		{
			return true;
		}
		return false;
	}

	/**
	 * @param UrlMapping[] $mappings
	 */
	public function __construct(string $dispatcher_name, string $match = '([\w/_-]*)$', string $from_path = '', string $redirect_path = '', bool $high_priority = false)
	{
		$module = explode('/', $dispatcher_name)[1];
		$prefix = self::is_root_module($module) ? '' : '/modules';

		if (!empty($from_path))
		{
			if ($from_path == 'root')
			{
				$from = '^' . $match;
				$to   = $prefix . $dispatcher_name . '?url=/' . ($redirect_path ? $redirect_path : '$1');
				$this->low_priority = true;
			}
			else
			{
				$dispatcher_path = ltrim(TextHelper::substr($from_path, 0, TextHelper::strrpos($from_path, '/') + 1), '/');
				$from = '^' . $dispatcher_path . $match;
				$to   = $prefix . $dispatcher_name . '?url=/' . $dispatcher_path . '$1';
				$this->high_priority = true;
			}
		}
		elseif ($high_priority)
		{
			$dispatcher_path = ltrim(TextHelper::substr($dispatcher_name, 0, TextHelper::strrpos($dispatcher_name, '/') + 1), '/');
			$from = '^' . $dispatcher_path . $match;
			$to   = $prefix . $dispatcher_name . '?url=/' . ($redirect_path ? $redirect_path : '$1');
			$this->high_priority = true;
		}
		else
		{
			$dispatcher_path = ltrim(TextHelper::substr($dispatcher_name, 0, TextHelper::strrpos($dispatcher_name, '/') + 1), '/');
			$from = '^' . $dispatcher_path . $match;
			$to   = $prefix . $dispatcher_name . '?url=/$1';
		}

		parent::__construct($from, $to);
	}

	/**
	 * Returns true when this mapping must be placed at high priority in .htaccess.
	 */
	public function is_high_priority(): bool
	{
		return $this->high_priority;
	}

	/**
	 * Returns true when this mapping must be placed at low priority in .htaccess.
	 */
	public function is_low_priority(): bool
	{
		return $this->low_priority;
	}
}
?>
