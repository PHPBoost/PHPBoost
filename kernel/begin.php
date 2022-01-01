<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 1.4 - 2006 02 08
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once 'init.php';

$running_module_name = Environment::get_running_module_name();
if (!in_array($running_module_name, array('user', 'admin', 'kernel')))
{
	if (ModulesManager::is_module_installed($running_module_name))
	{
		$module = ModulesManager::get_module($running_module_name);
		if (!$module->is_activated())
		{
			DispatchManager::redirect(PHPBoostErrors::module_not_activated());
		}
	}
	else
	{
		DispatchManager::redirect(PHPBoostErrors::module_not_installed());
	}
}
?>
