<?php
/**
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class AdminModuleController extends AbstractController
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
		if (!AppContext::get_current_user()->is_admin())
		{
			return new UserLoginController(UserLoginController::ADMIN_LOGIN, TextHelper::substr(REWRITED_SCRIPT, TextHelper::strlen(GeneralConfig::load()->get_site_path())));
		}
		else if (ModulesManager::is_module_installed(self::$module_id))
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
		if (self::$module_id && !in_array(self::$module_id, array('admin', 'kernel', 'user')))
			self::$module_configuration = self::get_module()->get_configuration();
		
		return self::$module_configuration;
	}
}
?>
