<?php
/**
 * This class defines the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 31
 * @since       PHPBoost 3.0 - 2009 12 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class ModuleController extends AbstractController
{
	protected static $module_id;
	protected static $module;
	protected static $module_configuration;

	public static function __static()
	{
		self::$module_id = self::$module_id ? self::$module_id : Environment::get_running_module_name();
	}
	
	public function __construct($module_id = '')
	{
		self::$module_id = $module_id ? $module_id : Environment::get_running_module_name();
	}

	public final function get_right_controller_regarding_authorizations()
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
	
	public static function get_module()
	{
		if (self::$module_id && !in_array(self::$module_id, array('admin', 'kernel', 'user')))
			self::$module = ModulesManager::get_module(self::$module_id);
		
		return self::$module;
	}
	
	public static function get_module_configuration()
	{
		if (self::$module_id && !in_array(self::$module_id, array('admin', 'kernel', 'user')) && self::get_module())
			self::$module_configuration = self::get_module()->get_configuration();
		
		return self::$module_configuration;
	}
}
?>
