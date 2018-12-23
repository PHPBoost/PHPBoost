<?php
/*##################################################
 *                                header.php
 *                            -------------------
 *   begin                : July 09, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
	define('TITLE', LangLoader::get_message('unknown', 'main'));
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
	
	$tpl = new StringTemplate('# INCLUDE MESSAGE #');
	
	$tpl->put('MESSAGE', MessageHelper::display(StringVars::replace_vars(LangLoader::get_message('content.is_locked.description', 'status-messages-common'), array('user_display_name' => $user_display_name ? $user_display_name : LangLoader::get_message('content.is_locked.another_user'))), MessageHelper::NOTICE));
	
	$tpl->display();

	require_once('../kernel/footer.php');
	die();
}
?>