<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class LobbyModulesList
{
	/**
	 * Loads all lobby module entries from config and returns them
	 * as an associative array keyed by module_id.
	 *
	 * @return array<string, LobbyModule>
	 */
	public static function load(): array
	{
		$modules = [];

		foreach (LobbyConfig::load()->get_modules() as $position => $properties)
		{
			$module = new LobbyModule();
			$module->set_properties($properties);
			$modules[$module->get_module_id()] = $module;
		}

		return $modules;
	}

	/**
	 * Persists a modules list into the lobby config, re-indexing positions from 1.
	 *
	 * @param array<int|string, array|LobbyModule> $modules_list
	 */
	public static function save(array $modules_list): void
	{
		$modules = [];
		$i       = 1;

		foreach ($modules_list as $module)
		{
			$modules[$i] = is_array($module) ? $module : $module->get_properties();
			$i++;
		}

		if (!empty($modules))
		{
			LobbyConfig::load()->set_modules($modules);
		}
	}
}
?>
