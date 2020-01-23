<?php
/**
 * This class defines the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 3.0 - 2009 12 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class ModuleController extends AbstractController
{
	protected static $module_id;

	public static function __static()
	{
		self::$module_id = Environment::get_running_module_name();
	}
	
	public function __construct($module_id = '')
	{
		self::$module_id = $module_id ? $module_id : Environment::get_running_module_name();
	}

	public final function get_right_controller_regarding_authorizations()
	{
		if (ModulesManager::is_module_installed(Environment::get_running_module_name()))
		{
			$module = ModulesManager::get_module(Environment::get_running_module_name());
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
	
	public static function get_module()
	{
		return ModulesManager::get_module(self::$module_id);
	}
}
?>
