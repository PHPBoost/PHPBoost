<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 19
 * @since       PHPBoost 1.2 - 2005 06 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
	exit;

$env = new AdminDisplayGraphicalEnvironment();
Environment::set_graphical_environment($env);

if (!defined('TITLE'))
{
	define('TITLE', LangLoader::get_message('common.unknown', 'common-lang'));
}

$module_id = Environment::get_running_module_name();
$section = '';
if (!Environment::home_page_running() && ModulesManager::is_module_installed($module_id) && ModulesManager::is_module_activated($module_id))
{
	$section = ModulesManager::get_module($module_id)->get_configuration()->get_name();
}

$env->set_page_title(TITLE, $section);

ob_start();
?>
