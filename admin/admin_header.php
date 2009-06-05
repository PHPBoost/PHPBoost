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

//Ajout des éventuels css alternatifs du module.
$alternative_css = '';
if (defined('ALTERNATIVE_CSS'))
{
    $alternative = null;
    $styles = @unserialize(ALTERNATIVE_CSS);
    if (is_array($styles))
    {
        foreach ($styles as $module => $style) {
            $base = PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
            $file = $base . $style . '.css';
            if (file_exists($file))
            {
                $alternative = $file;
            }
            else
            {
                $alternative = PATH_TO_ROOT . '/' . $module . '/templates/' . $style . '.css';
            }
            $alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
        }
    }
    else
    {
        $array_alternative_css = explode(',', str_replace(' ', '', ALTERNATIVE_CSS));
        $module = $array_alternative_css[0];
        $base 	= PATH_TO_ROOT . '/templates/' . get_utheme() . '/modules/' . $module . '/' ;
        foreach ($array_alternative_css as $alternative)
        {
            $file = $base . $alternative . '.css';
            if (file_exists($file))
            {
                $alternative = $file;
            }
            else
            {
                $alternative = PATH_TO_ROOT . '/' . $module . '/templates/' . $alternative . '.css';
            }
            $alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
        }
    }
}

$Template->assign_vars(array(
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => TITLE,
	'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
	'SID' => SID,
	'LANG' => get_ulang(),
	'THEME' => get_utheme(),
	'ALTERNATIVE_CSS' => $alternative_css,
	'C_BBCODE_TINYMCE_MODE' => $User->get_attribute('user_editor') == 'tinymce',
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
    'L_ADD_FEED_MENU' => $LANG['menus_feed_add'],
	'L_MANAGEMENT' => $LANG['management'],
	'L_PUNISHEMENT' => $LANG['punishement'],
	'L_UPDATE_MODULES' => $LANG['update_module'],
	'L_SITE_LINK' => $LANG['link_management'],
	'L_SITE_MENU' => $LANG['menu_management'],
	'L_MODERATION' => $LANG['moderation'],
	'L_MAINTAIN' => $LANG['maintain'],
	'L_USER' => $LANG['member_s'],
	'L_EXTEND_FIELD' => $LANG['extend_field'],
	'L_RANKS' => $LANG['ranks'],
	'L_TERMS' => $LANG['terms'],
	'L_GROUP' => $LANG['group'],
	'L_CONTENTS' => $LANG['content'],
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
$array_pos = array(0, 4, 3, 3, 3, 1);
$menus_numbers = array('index' => 1, 'administration' => 2, 'tools' => 3, 'members' => 4, 'content' => 5, 'modules' => 6);
foreach ($modules_config as $module_name => $auth)
{
	$name = $modules_config[$module_name]['module_name'];
	if (is_array($modules_config[$module_name]))
	{
		$menu_pos_name = $modules_config[$module_name]['admin'];
		$menu_pos = 0;
		
		if (!empty($menu_pos_name) && !empty($menus_numbers[$menu_pos_name]))
		    $menu_pos = $menus_numbers[$menu_pos_name];

		//Le module possède une administration
		if ($menu_pos > 0)
		{
			$array_pos[$menu_pos-1]++;
			$idmenu = $array_pos[$menu_pos-1];
			$Template->assign_vars(array(
				'C_ADMIN_LINKS_' . $menu_pos => true
			));
				
			if (!empty($modules_config[$module_name]['admin_links']))
			{
				$admin_links = parse_ini_array($modules_config[$module_name]['admin_links']);
				$links = '';
				$i = 0;
				$j = 0;
				foreach ($admin_links as $key => $value)
				{
					if (is_array($value))
					{
						$links .= '<li class="extend" onmouseover="show_menu(\'' . $idmenu . $i . $name . '\', 2);" onmouseout="hide_menu(2);"><a href="#" style="background-image:url(' . PATH_TO_ROOT . '/' . $name . '/' . $name . '_mini.png);cursor:default;">' . $key . '</a><ul id="sssmenu' . $idmenu . $i . $name . '">' . "\n";
						foreach ($value as $key2 => $value2)
							$links .= '<li><a href="' . PATH_TO_ROOT . '/' . $name . '/' . $value2 . '" style="background-image:url(' . PATH_TO_ROOT . '/' . $name . '/' . $name . '_mini.png);">' . $key2 . '</a></li>' . "\n";
						$links .= '</ul></li>' . "\n";
						$i++;
					}
					else
						$links .= '<li><a href="' . PATH_TO_ROOT . '/' . $name . '/' . $value . '" style="background-image:url(' . PATH_TO_ROOT . '/' . $name . '/' . $name . '_mini.png);">' . $key . '</a></li>' . "\n";
					$j++;
				}
				
				$Template->assign_block_vars('admin_links_' . $menu_pos, array(
					'C_ADMIN_LINKS_EXTEND' => ($j > 0 ? true : false),
					'IDMENU' => $idmenu,
					'NAME' => $modules_config[$module_name]['name'],
					'LINKS' => $links,
					'U_ADMIN_MODULE' => PATH_TO_ROOT . '/' . $name . '/admin_' . $name . '.php',
					'IMG' => PATH_TO_ROOT . '/' . $name . '/' . $name . '_mini.png'
				));	
			}
			else
			{
				$Template->assign_block_vars('admin_links_' . $menu_pos, array(
					'IDMENU' => $menu_pos,
					'NAME' => $modules_config[$module_name]['name'],
					'U_ADMIN_MODULE' => PATH_TO_ROOT . '/' . $name . '/admin_' . $name . '.php',
					'IMG' => PATH_TO_ROOT . '/' . $name . '/' . $name . '_mini.png'
				));	
			}			
		}
	}
}

$Template->pparse('admin_header');

?>