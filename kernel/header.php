<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 25
 * @since       PHPBoost 1.2 - 2005 07 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

if (defined('PHPBOOST') !== true)
{
	exit;
}

$env = new SiteDisplayGraphicalEnvironment();
$env->set_breadcrumb($Bread_crumb);

if (isset($location_id) && !empty($location_id) && !AppContext::get_session()->location_id_already_exists($location_id))
	$env->set_location_id($location_id);

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
if (defined('DESCRIPTION'))
{
	$env->get_seo_meta_data()->set_description(DESCRIPTION);
}

ob_start();

if (isset($location_id) && !empty($location_id) && AppContext::get_session()->location_id_already_exists($location_id))
{
	$user_display_name = UserService::display_user_profile_link(AppContext::get_session()->get_user_on_location_id($location_id));

	$tpl = new StringTemplate('# INCLUDE MESSAGE_HELPER #');

	$tpl->put('MESSAGE_HELPER', MessageHelper::display(StringVars::replace_vars(LangLoader::get_message('warning.locked.content.description', 'warning-lang'), array('user_display_name' => $user_display_name ? $user_display_name : LangLoader::get_message('warning.locked.content.another.user', 'warning-lang'))), MessageHelper::NOTICE));

	$tpl->display();

	require_once('../kernel/footer.php');
	die();
}
?>
