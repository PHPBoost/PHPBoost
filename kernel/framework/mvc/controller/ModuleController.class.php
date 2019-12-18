<?php
/**
 * This class defines the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 18
 * @since       PHPBoost 3.0 - 2009 12 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class ModuleController extends AbstractController
{
	public final function get_right_controller_regarding_authorizations()
	{
		if (ModulesManager::is_module_installed(Environment::get_running_module_name()))
		{
			$module = ModulesManager::get_module(Environment::get_running_module_name());
			if (!$module->is_activated())
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
	
	public final function get_module()
	{
		return ModulesManager::get_module(Environment::get_running_module_name());
	}
}
?>
