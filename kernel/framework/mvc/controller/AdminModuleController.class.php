<?php
/**
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 18
 * @since       PHPBoost 3.0 - 2011 10 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class AdminModuleController extends AbstractController
{
	public final function get_right_controller_regarding_authorizations()
	{
		if (!AppContext::get_current_user()->is_admin())
		{
			return new UserLoginController(UserLoginController::ADMIN_LOGIN, TextHelper::substr(REWRITED_SCRIPT, TextHelper::strlen(GeneralConfig::load()->get_site_path())));
		}
		else if (ModulesManager::is_module_installed(Environment::get_running_module_name()))
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
