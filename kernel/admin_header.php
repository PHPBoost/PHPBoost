<?php
/*##################################################
 *                               admin_header.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

if (!defined(PATH_TO_ROOT))
    define('PATH_TO_ROOT', '..');

if( defined('PHPBOOST') !== true) exit;

if( !defined('TITLE') )
	define('TITLE', $LANG['unknow']);
	
$Session->Session_check(TITLE); //Vérification de la session.

$Template->Set_filenames(array(
	'admin_header'=> 'admin/admin_header.tpl'
));

$Template->Assign_vars(array(
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => TITLE,
	'THEME' => $CONFIG['theme'],
));

$Template->Pparse('admin_header'); // traitement du modele

//!\\ Connexion à l'administration //!\\
require_once(PATH_TO_ROOT . '/kernel/admin_access.php');

$Template->Set_filenames(array(
	'admin_sub_header'=> 'admin/admin_sub_header.tpl'
));

$Template->Assign_vars(array(
	'SID' => SID,
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme'],
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
	'L_MANAGEMENT' => $LANG['management'],
	'L_PUNISHEMENT' => $LANG['punishement'],
	'L_UPDATE_MODULES' => $LANG['update_module'],
	'L_SITE_LINK' => $LANG['link_management'],
	'L_SITE_MENU' => $LANG['menu_management'],
	'L_MODERATION' => $LANG['moderation'],
	'L_DATABASE_QUERY' => $LANG['db_executed_query'],
	'L_MAINTAIN' => $LANG['maintain'],
	'L_MEMBER' => $LANG['member_s'],
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
	'L_STATS' => $LANG['stats'],	
	'L_ERRORS' => $LANG['errors'],
	'L_PHPINFO' => $LANG['phpinfo'],
	'L_COMMENTS' => $LANG['comments'],
	'L_SITE_DATABASE' => $LANG['database'],
	'L_UPDATER' => $LANG['updater'],
	'L_MODULES' => $LANG['modules'],
	'L_CACHE' => $LANG['cache'],
	'L_EXTEND_MENU' => $LANG['extend_menu'],
	'U_INDEX_SITE' => ((substr($CONFIG['start_page'], 0, 1) == '/') ? '..' . $CONFIG['start_page'] : $CONFIG['start_page']) 
));

//Listing des modules disponibles:
$modules_config = array();
foreach($MODULES as $name => $array)
{	
	$array_info = load_ini_file(PATH_TO_ROOT . '/' . $name . '/lang/', $CONFIG['lang']);
	$array_info['module_name'] = $name;
	$modules_config[$array_info['name']] = $array_info;
}

ksort($modules_config);
foreach($modules_config as $module_name => $auth)
{
	$name = $modules_config[$module_name]['module_name'];
	if( is_array($modules_config[$module_name]) )
	{	
		if( $modules_config[$module_name]['admin'] == 1 )
		{
			if( !empty($modules_config[$module_name]['admin_links']) )
			{	
				$admin_links = parse_ini_array($modules_config[$module_name]['admin_links']);
				$links = '';
				foreach($admin_links as $key => $value)
				{
					if( is_array($value) )
					{	
						$links .= '<li class="extend" onmouseover="show_menu(\'7' . $name . '\', 2);" onmouseout="hide_menu(2);"><a href="#" style="background-image:url(../' . $name . '/' . $name . '_mini.png);cursor:default;">' . $key . '</a><ul id="sssmenu7' . $name . '">';
						foreach($value as $key2 => $value2)
							$links .= '<li><a href="../' . $name . '/' . $value2 . '" style="background-image:url(../' . $name . '/' . $name . '_mini.png);">' . $key2 . '</a></li>';
						$links .= '</ul></li>';
					}
					else
						$links .= '<li><a href="../' . $name . '/' . $value . '" style="background-image:url(../' . $name . '/' . $name . '_mini.png);">' . $key . '</a></li>';
				}
				
				$Template->Assign_block_vars('modules', array(
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
				$Template->Assign_block_vars('modules', array(
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

$Template->Pparse('admin_sub_header'); 

?>