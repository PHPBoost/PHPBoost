<?php
/*##################################################
 *                               admin_begin.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Admin, v 1.0.0
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$template = new FileTemplate('admin/admin_extend.tpl');

$admin_links_lang = LangLoader::get('admin-links-common');
$template->put_all(array(
	'L_CONFIGURATION' => $admin_links_lang['configuration'],
	'L_SITE' => $admin_links_lang['index.site'],
	'L_DISCONNECT' => $admin_links_lang['index.disconnect'],
	'L_INDEX_ADMIN' => $admin_links_lang['administration'],
	'L_SITE_MENU' => $admin_links_lang['content.menus'],
	'L_MAINTAIN' => $admin_links_lang['tools.maintain'],
	'L_USER' => $admin_links_lang['users'],
	'L_EXTEND_FIELD' => $admin_links_lang['users.extended-fields'],
	'L_RANKS' => $admin_links_lang['users.ranks'],
	'L_GROUP' => $admin_links_lang['users.groups'],
	'L_THEME' => $admin_links_lang['administration.themes'],
	'L_SMILEY' => $admin_links_lang['administration.smileys'],
	'L_ERRORS' => $admin_links_lang['tools.errors-archived'],
	'L_COM' => $admin_links_lang['content.comments'],
	'L_MODULES' => $admin_links_lang['modules'],
	'L_CACHE' => $admin_links_lang['tools.cache'],
	'L_FILES' => $admin_links_lang['content.files'],
	'L_CONTENT_CONFIG' => $admin_links_lang['content'],
	'L_LANG' => $admin_links_lang['administration.langs'],
	'L_ADMINISTRATOR_ALERTS' => $admin_links_lang['administration.alerts'],
	'L_SERVER' => $admin_links_lang['tools.server.system-report'],
	'U_INDEX_SITE' => Environment::get_home_page(),
    'L_WEBSITE_UPDATES' => $admin_links_lang['updates']
));

//Listing des modules disponibles:
$i = 1;
$modules = ModulesManager::get_activated_modules_map_sorted_by_localized_name();
$nbr_modules = count($modules);
foreach ($modules as $module)
{
	$configuration = $module->get_configuration();
	$name = $configuration->get_name();
	$admin_home_page = $configuration->get_admin_main_page();
	if (!empty($admin_home_page))
	{
		$template->assign_block_vars('modules_extend', array(
			'NAME' => $name,
			'IMG' => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $module->get_id() . '.png',
			'START_TR' => ((($i - 1) % 5) == 0 || $i == 1)? '<tr style="text-align:center;">' : '',
			'END_TR' => ((($i % 5) == 0 && $i != 1) || $i == $nbr_modules ) ? '</tr>' : '',			
			'U_ADMIN_MODULE' => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $admin_home_page
		));
		$i++;
	}
}
//Complétion éventuelle des cases du tableaux.
if ($i != 0)
{
	$i--;
	while (($i % 5) != 0)
	{
		$template->assign_block_vars('modules_extend.td', array(
			'TD' => '<td class="row2" style="width:20%;">&nbsp;</td>'
		));
		$i++;
	}
}

$template->display();

require_once('../admin/admin_footer.php');
?>