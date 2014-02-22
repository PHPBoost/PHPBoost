<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : October 25, 2005
 *   copyright            : (C) 2005 Viarre Régis / Sautel Benoît
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
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

require_once('../kernel/begin.php');
require_once('../forum/forum_begin.php');
require_once('../forum/forum_tools.php');

$id_get = retrieve(GET, 'id', 0);
$cat_name = !empty($CAT_FORUM[$id_get]['name']) ? $CAT_FORUM[$id_get]['name'] : '';
$Bread_crumb->add($CONFIG_FORUM['forum_name'], 'index.php' . SID);
$Bread_crumb->add($cat_name, '');

if (!empty($id_get) && !empty($CAT_FORUM[$id_get]['name']))
	define('TITLE', $LANG['title_forum'] . ' - ' . addslashes($CAT_FORUM[$id_get]['name']));
else
	define('TITLE', $LANG['title_forum']);
require_once('../kernel/header.php');

$modulesLoader = AppContext::get_extension_provider_service();
$module = $modulesLoader->get_provider('forum');
if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
{
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

include('../kernel/footer.php');

?>