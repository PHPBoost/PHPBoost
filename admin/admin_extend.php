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
$template->add_lang(LangLoader::get('admin-links-common'));

//Listing des modules disponibles
foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $module)
{
	$configuration = $module->get_configuration();
	$admin_home_page = $configuration->get_admin_main_page();
	if (!empty($admin_home_page))
	{
		$template->assign_block_vars('modules_extend', array(
			'NAME' => $configuration->get_name(),
			'IMG' => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $module->get_id() . '.png',
			'U_ADMIN_MODULE' => PATH_TO_ROOT .'/' . $module->get_id() . '/' . $admin_home_page
		));
	}
}

$template->display();

require_once('../admin/admin_footer.php');
?>
