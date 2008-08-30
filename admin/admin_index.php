<?php
/*##################################################
 *                              admin_index.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Template->Set_filenames(array(
	'admin_index'=> 'admin/admin_index.tpl'
));

/*
//Vérification des mises à jour du noyau  et des modules sur le site officiel.
$get_info_update = @file_get_contents_emulate('http://www.phpboost.com/phpboost/updates.txt');
$check_core_update = false;
$check_modules_update = false;
$modules_update = array();
if( !empty($get_info_update) )
{	
	//Maj du noyau.
	$array_infos = explode("\n", $get_info_update);
	if( isset($array_infos[0]) )
	{
		$check_version = substr(strstr($array_infos[0], ':'), 1);
		if( version_compare($check_version, $CONFIG['version'], '<=') != -1 )
		{
			$check_core_update = true;
			$l_core_update = sprintf($LANG['core_update_available'], $check_version);
		}
		else
			$l_core_update = $LANG['no_core_update_available'];
	}
	else
		$l_core_update = $LANG['unknow_update'];
	
	//Mise à jour des modules.
	$count = count($array_infos);	
	for($i = 1; $i < $count; $i++)
	{
		if( isset($array_infos[$i]) )
		{
			$array_infos_modules = explode(':', $array_infos[$i]);
			$name = $array_infos_modules[0];
			$version = $array_infos_modules[1];
			
			//Nouvelle version du module.
			if( isset($modules_config[$name]['version']) && $modules_config[$name]['version'] != $version )
				$modules_update[$name] = $version;
		}
	}	
	
	if( count($modules_update) > 0 )
	{
		$check_modules_update = true;
		$l_modules_update = $LANG['module_update_available'];
	}
	else
		$l_modules_update = $LANG['no_module_update_available'];
}
else
{	
	$l_core_update = $LANG['unknow_update'];
	$l_modules_update = $LANG['unknow_update'];
}
$Template->Assign_vars(array(
	'VERSION' => $CONFIG['version'],
	'WARNING_CORE' => ($check_core_update) ? ' error_warning' : '',
	'WARNING_MODULES' => ($check_modules_update) ? ' error_warning' : '',
	'UPDATE_AVAILABLE' => ($check_core_update) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/admin/update_available.png" alt="" class="valign_middle" />' : '',
	'UPDATE_MODULES_AVAILABLE' => ($check_modules_update) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/admin/update_available.png" alt="" class="valign_middle" />' : '',
	'L_UPDATE_AVAILABLE' => $LANG['update_available'],
	'L_CORE_UPDATE' => $l_core_update,
	'L_MODULES_UPDATE' => $l_modules_update	
));
//Listing des modules mis à jour.
foreach($modules_update as $name => $version)
{
	$Template->Assign_block_vars('modules_available', array(
		'ID' => $name,
		'NAME' => $modules_config[$name]['name'],
		'VERSION' => $version
	));
}
*/

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/administrator_alert_service.class.php');

$alerts_list = AdministratorAlertService::get_all_alerts();

$Template->Assign_vars(array(
	'C_ALERT_OR_ACTION' => ((bool)count($alerts_list)),
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_ADMIN_ALERTS' => $LANG['administrator_alerts'],
	'L_NO_ALERT_OR_ACTION' => $LANG['no_alert_or_action'],
	'L_TYPE' => $LANG['type'],
	'L_DATE' => $LANG['date'],
	'L_PRIORITY' => $LANG['priority'],
	'L_DISPLAY_ALL_ALERTS' => $LANG['display_all_alerts'],
	'L_QUICK_LINKS' => $LANG['quick_links'],
	'L_MEMBERS_MANAGMENT' => $LANG['members_managment'],
	'L_MENUS_MANAGMENT' => $LANG['menus_managment'],
	'L_MODULES_MANAGMENT' => $LANG['modules_managment'],
	'L_STATS' => $LANG['stats'],
	'L_USER_ONLINE' => $LANG['user_online'],
	'L_USER_IP' => $LANG['user_ip'],
	'L_LOCALISATION' => $LANG['localisation'],
	'L_LAST_UPDATE' => $LANG['last_update'],
    'L_WEBSITE_UPDATES' => $LANG['website_updates']
));

//On va chercher la liste des alertes en attente, on afficher les 5 dernières
foreach(AdministratorAlertService::get_all_alerts('creation_date', 'desc', 0, 5) as $alert)
{
	$img_type = '';
	
	switch($alert->get_priority())
	{
		case PRIORITY_VERY_LOW:
			$color = 'FFFFFF';
			break;
		case PRIORITY_LOW:
			$color = 'ECDBB7';
			break;
		case PRIORITY_MEDIUM:
			$color = 'F5D5C6';
			break;
		case PRIORITY_HIGH:
			$img_type = 'important.png';
			$color = 'FFD5D1';
			break;
		case PRIORITY_VERY_HIGH:
			$img_type = 'errors_mini.png';
			$color = 'F3A29B';
			break;
		default:
		$color = 'FFFFFF';
	}
	
	$creation_date = $alert->get_creation_date();
	
	$Template->Assign_block_vars('alerts', array(
		'URL' => $alert->get_fixing_url(),
		'NAME' => $alert->get_entitled(),
		'PRIORITY' => $alert->get_priority_name(),
		'STYLE' => 'background:#' . $color . ';',
		'IMG' => !empty($img_type) ? '<img src="../templates/' . $CONFIG['theme'] . '/images/admin/' . $img_type . '" alt="" class="valign_middle" />' : '',
		'DATE' => $creation_date->format(DATE_FORMAT)
	));
}
  
$result = $Sql->Query_while("SELECT s.user_id, s.level, s.session_ip, s.session_time, s.session_script, s.session_script_get, 
s.session_script_title, m.login 
FROM ".PREFIX."sessions s
LEFT JOIN ".PREFIX."member m ON s.user_id = m.user_id
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while( $row = $Sql->Sql_fetch_assoc($result) )
{
	//On vérifie que la session ne correspond pas à un robot.
	$robot = $Session->check_robot($row['session_ip']);

	switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
	{ 		
		case MEMBER_LEVEL:
		$class = 'member';
		break;
		
		case MODERATOR_LEVEL: 
		$class = 'modo';
		break;
		
		case ADMIN_LEVEL: 
		$class = 'admin';
		break;
	} 
		
	if( !empty($robot) )
		$login = '<span class="robot">' . ($robot == 'unknow_bot' ? $LANG['unknow_bot'] : $robot) . '</span>';
	else
		$login = !empty($row['login']) ? '<a class="' . $class . '" href="../member/member.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a>' : $LANG['guest'];
	
	$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
	
	$Template->Assign_block_vars('user', array(
		'USER' => !empty($login) ? $login : $LANG['guest'],
		'USER_IP' => $row['session_ip'],
		'WHERE' => '<a href="' . HOST . DIR . $row['session_script'] . $row['session_script_get'] . '">' . stripslashes($row['session_script_title']) . '</a>',
		'TIME' => gmdate_format('date_format_long', $row['session_time'])
	));	
}
$Sql->Close($result);
	
$Template->Pparse('admin_index'); // traitement du modele

require_once('../admin/admin_footer.php');

?>