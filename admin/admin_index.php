<?php
/*##################################################
 *                               admin_index.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

include_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

$template->set_filenames(array(
	'admin_index' => '../templates/' . $CONFIG['theme'] . '/admin/admin_index.tpl'
));

//Vérification des mises à jour du noyau  et des modules sur le site officiel.
$get_info_update = @file_get_contents_emulate('http://www.phpboost.com/phpboost/updates.txt');
$check_core_update = false;
$check_modules_update = false;
$modules_update = array();
if( !empty($get_info_update) )
{	
	$array_infos = explode("\n", $get_info_update);
	if( isset($array_infos[0]) )
	{
		if( substr(strstr($array_infos[0], ':'), 1) != $CONFIG['version'] )
		{
			$check_core_update = true;
			$l_core_update = sprintf($LANG['core_update_available'], $array_infos[1]);
		}
		else
			$l_core_update = $LANG['no_core_update_available'];
	}
	else
		$l_core_update = $LANG['unknow_update'];
	
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
	
	if( count($modules_update) > 1 )
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
	
	
$template->assign_vars(array(
	'VERSION' => $CONFIG['version'],
	'WARNING_CORE' => ($check_core_update) ? ' error_warning' : '',
	'WARNING_MODULES' => ($check_modules_update) ? ' error_warning' : '',
	'L_INDEX_ADMIN' => $LANG['administration'],
	'L_TEXT_INDEX' => sprintf($LANG['admin_index'], $CONFIG['version']),
	'L_STATS' => $LANG['stats'],
	'L_USER_ONLINE' => $LANG['user_online'],
	'L_TOTAL_USER' => $LANG['total_users'],
	'L_USER_IP' => $LANG['user_ip'],
	'L_LOCALISATION' => $LANG['localisation'],
	'L_LAST_UPDATE' => $LANG['last_update'],
	'L_ON' => $LANG['on'],
	'L_UPDATE_AVAILABLE' => $LANG['update_available'],
	'L_CORE_UPDATE' => $l_core_update,
	'L_MODULES_UPDATE' => $l_modules_update	
));

//Listing des modules mis à jour.
foreach($modules_update as $name => $version)
{
	$template->assign_block_vars('modules_available', array(
		'ID' => $name,
		'NAME' => $modules_config[$name]['name'],
		'VERSION' => $version
	));
}
  
$result = $sql->query_while("SELECT s.user_id, s.level, s.session_ip, s.session_time, s.session_script, s.session_script_get, 
s.session_script_title, m.login 
FROM ".PREFIX."sessions AS s
LEFT JOIN ".PREFIX."member AS m ON s.user_id = m.user_id
WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
GROUP BY s.user_id 
ORDER BY s.session_time DESC", __LINE__, __FILE__);
while( $row = $sql->sql_fetch_assoc($result) )
{
	//On vérifie que la session ne correspond pas à un robot.
	$robot = $session->check_robot($row['session_ip']);

	switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
	{ 		
		case 0:
		$class = 'member';
		break;
		
		case 1: 
		$class = 'modo';
		break;
		
		case 2: 
		$class = 'admin';
		break;
	} 
		
	if( !empty($robot) )
	{
		$login = '<span class="robot">' . $robot . '</span>';
	}
	else
	{
		$login = !empty($row['login']) ? '<a class="' . $class . '" href="../member/member.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a>' : $LANG['guest'];
	}
	
	$login = !empty($login) ? $login : $LANG['guest'];
	
	$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
	
	$template->assign_block_vars('user', array(
		'USER' => $login,
		'USER_IP' => $row['session_ip'],
		'WHERE' => '<a href="' . HOST . $row['session_script'] . $row['session_script_get'] . '">' . $row['session_script_title'] . '</a>',
		'TIME' => date($LANG['date_format'] . ' ' . $LANG['at'] . ' H\hi\m\i\ns\s', $row['session_time'])
	));	
}
$sql->close($result);
	
$template->pparse('admin_index'); // traitement du modele

include_once('../includes/admin_footer.php');

?>