<?php
/**
 * This class defines the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 12 14
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class ModuleController extends AbstractController
{
	protected static string $module_id = '';
	protected static ?Module $module = null;
	protected static ?ModuleConfiguration $module_configuration = null;

	public static function __static(): void
	{
		self::$module_id = self::$module_id ? self::$module_id : Environment::get_running_module_name();
	}

	public function __construct(string $module_id = '')
	{
		self::$module_id = $module_id ? $module_id : Environment::get_running_module_name();
	}

	public final function get_right_controller_regarding_authorizations(): Controller
	{
		if (ModulesManager::is_module_installed(self::$module_id))
		{
			if (!self::get_module()->is_activated())
			{
				return PHPBoostErrors::module_not_activated();
			}
		}
		else
		{
			return PHPBoostErrors::module_not_installed();
		}
		return $this;
	}

	public static function get_module(): ?Module
	{
		if (self::$module_id && !in_array(self::$module_id, ['admin', 'kernel', 'user']))
        {
			self::$module = ModulesManager::get_module(self::$module_id);
        }

		return self::$module;
	}

	public static function get_module_configuration(): ?ModuleConfiguration
	{
		if (self::$module_id && !in_array(self::$module_id, ['admin', 'kernel', 'user']) && self::get_module())
        {
			self::$module_configuration = self::get_module()->get_configuration();
        }

		return self::$module_configuration;
	}
}
?>
