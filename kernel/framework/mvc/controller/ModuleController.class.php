<?php
/**
 * This class defines the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 14
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
}
?>
