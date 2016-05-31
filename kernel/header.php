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

Environment::set_graphical_environment($env);

if (!defined('TITLE'))
{
	define('TITLE', LangLoader::get_message('unknow', 'main'));
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