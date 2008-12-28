<?php
/*##################################################
 *                             admin_header.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

if (defined('PHPBOOST') !== true)
	exit;

if (!defined('TITLE'))
	define('TITLE', $LANG['unknow']);
	
$Session->check(TITLE); //Vérification de la session.

//!\\ Connexion à l'administration //!\\
require_once(PATH_TO_ROOT . '/admin/admin_access.php');


$Template->set_filenames(array(
	'admin_header'=> 'admin/admin_header.tpl',
	'subheader_menu'=> 'admin/subheader_menu.tpl'
));

$Template->assign_vars(array(
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => TITLE,
	'PATH_TO_ROOT' => PATH_TO_ROOT,
	'SID' => SID,
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'L_ADMINISTRATION' => $LANG['administration'],
	'L_INDEX' => $LANG['index'],
	'L_SITE' => $LANG['site'],
	'L_INDEX_SITE' => $LANG['site'],
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_DISCONNECT' => $LANG['disconnect'],
	'L_TOOLS' => $LANG['tools'],
	'L_CONFIGURATION' => $LANG['configuration'],
	'L_CONFIG_ADVANCED' => $LANG['config_advanced'],
    'L_ADD' => $LANG['add'],
    'L_ADD_CONTENT_MENU' => $LANG['menus_content_add'],
    'L_ADD_LINKS_MENU' => $LANG['menus_links_add'],
	'L_MANAGEMENT' => $LANG['management'],
	'L_PUNISHEMENT' => $LANG['punishement'],
	'L_UPDATE_MODULES' => $LANG['update_module'],
	'L_SITE_LINK' => $LANG['link_management'],
	'L_SITE_MENU' => $LANG['menu_management'],
	'L_MODERATION' => $LANG['moderation'],
	'L_DATABASE_QUERY' => $LANG['db_executed_query'],
	'L_MAINTAIN' => $LANG['maintain'],
	'L_USER' => $LANG['member_s'],
	'L_EXTEND_FIELD' => $LANG['extend_field'],
	'L_RANKS' => $LANG['ranks'],
	'L_TERMS' => $LANG['terms'],
	'L_GROUP' => $LANG['group'],
	'L_CONTENTS' => $LANG['contents'],
	'L_PAGES' => $LANG['pages'],
	'L_FILES' => $LANG['files'],
	'L_THEME' => $LANG['themes'],
	'L_LANG' => $LANG['languages'],
	'L_SMILEY' => $LANG['smile'],
	'L_ADMINISTRATOR_ALERTS' => $LANG['administrator_alerts'],
	'L_STATS' => $LANG['stats'],
	'L_ERRORS' => $LANG['errors'],
	'L_SERVER' => $LANG['server'],
	'L_PHPINFO' => $LANG['phpinfo'],
	'L_SYSTEM_REPORT' => $LANG['system_report'],
	'L_COMMENTS' => $LANG['comments'],
	'L_SITE_DATABASE' => $LANG['database'],
	'L_UPDATER' => $LANG['updater'],
	'L_KERNEL' => $LANG['kernel'],
	'L_MODULES' => $LANG['modules'],
	'L_THEMES' => $LANG['themes'],
	'L_CACHE' => $LANG['cache'],
	'L_SYNDICATION' => $LANG['syndication'],
	'L_EXTEND_MENU' => $LANG['extend_menu'],
	'L_CONTENT_CONFIG' => $LANG['content_config'],
	'U_INDEX_SITE' => get_start_page(),
    'L_WEBSITE_UPDATES' => $LANG['website_updates']
));

//Listing des modules disponibles:
$modules_config = array();

foreach ($MODULES as $name => $array)
{
	$array_info = load_ini_file(PATH_TO_ROOT . '/' . $name . '/lang/', get_ulang());
	if (is_array($array_info))
	{
		$array_info['module_name'] = $name;
		$modules_config[$array_info['name']] = $array_info;
	}
}

ksort($modules_config);
foreach ($modules_config as $module_name => $auth)
{
	$name = $modules_config[$module_name]['module_name'];
	if (is_array($modules_config[$module_name]))
	{
		if ($modules_config[$module_name]['admin'] == 1)
		{
			if (!empty($modules_config[$module_name]['admin_links']))
			{
				$admin_links = parse_ini_array($modules_config[$module_name]['admin_links']);
				$links = '';
				foreach ($admin_links as $key => $value)
				{
					if (is_array($value))
					{
						$links .= '<li class="extend" onmouseover="show_menu(\'7' . $name . '\', 2);" onmouseout="hide_menu(2);"><a href="#" style="background-image:url(../' . $name . '/' . $name . '_mini.png);cursor:default;">' . $key . '</a><ul id="sssmenu7' . $name . '">';
						foreach ($value as $key2 => $value2)
							$links .= '<li><a href="../' . $name . '/' . $value2 . '" style="background-image:url(../' . $name . '/' . $name . '_mini.png);">' . $key2 . '</a></li>';
						$links .= '</ul></li>';
					}
					else
						$links .= '<li><a href="../' . $name . '/' . $value . '" style="background-image:url(../' . $name . '/' . $name . '_mini.png);">' . $key . '</a></li>';
				}
				
				$Template->assign_block_vars('modules', array(
					'C_ADVANCED_LINK' => true,
					'C_DEFAULT_LINK' => false,
					'ID' => $name,
					'LINKS' => $links,
					'DM_A_STYLE' => ' style="background-image:url(../' . $name . '/' . $name . '_mini.png);"',
					'NAME' => $modules_config[$module_name]['name'],
					'U_ADMIN_MODULE' => PATH_TO_ROOT . '/' . $name . '/admin_' . $name . '.php'
				));
			}
			else
			{
				$Template->assign_block_vars('modules', array(
					'C_DEFAULT_LINK' => true,
					'C_ADVANCED_LINK' => false,
					'DM_A_STYLE' => ' style="background-image:url(../' . $name . '/' . $name . '_mini.png);"',
					'NAME' => $modules_config[$module_name]['name'],
					'U_ADMIN_MODULE' => PATH_TO_ROOT . '/' . $name . '/admin_' . $name . '.php'
				));
			}
		}
	}
}

$Template->pparse('admin_header');

?>