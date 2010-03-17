<?php
/*##################################################
 *                              online_begin.php
 *                            -------------------
 *   begin                : November 28, 2007
 *   copyright          : (C) 2007 Viarre régis
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if (defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('online'); //Chargement de la langue du module.
define('TITLE', $LANG['online']);

//Chargement du cache
$Cache->load('online');

function get_online($body_only = FALSE)
{
	global $Sql, $Session, $LANG, $CONFIG, $CONFIG_ONLINE;
	
		if (!empty($body_only)) {
			$tpl = new Template('online/online_table.tpl');
    	}
		else
		{
			$tpl = new Template('online/online.tpl');
			$tpl->set_filenames(array(
				'online_table'=> 'online/online_table.tpl'
				));
		}
		
	//Membre connectés..
	$nbr_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_SESSIONS . "
								WHERE level <> -1 
								AND session_time > '" . (time() - $CONFIG['site_session_invit']) . "'"
								, __LINE__, __FILE__);
	import('util/pagination'); 
	$Pagination = new Pagination();
	
	$frequency = !empty($CONFIG_ONLINE['online_refresh_frequency']) ? $CONFIG_ONLINE['online_refresh_frequency'] : 15;
		
	$tpl->assign_vars(array(
		'PAGINATION' => $Pagination->display('online' . url('.php?p=%d'), $nbr_member, 'p', 25, 3),
		'FREQUENCY' => $frequency,
		'TIME' => Date('H:i:s'),
		'L_LOGIN' => $LANG['pseudo'],
		'L_LOCATION' => $LANG['location'],
		'L_LAST_UPDATE' => $LANG['last_update'],
		'L_ONLINE' => $LANG['online']
	));

	$result = $Sql->query_while("SELECT s.*, m.*
		FROM " . DB_TABLE_SESSIONS . " s
		JOIN " . DB_TABLE_MEMBER . " m ON (m.user_id = s.user_id)
		WHERE s.session_time > '" . (time() - $CONFIG['site_session_invit']) . "'
		ORDER BY " . $CONFIG_ONLINE['display_order_online'] . "
		" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25)
		, __LINE__, __FILE__); //Membres enregistrés.
	while ($row = $Sql->fetch_assoc($result))
	{
		switch ($row['level']) //Coloration du membre suivant son level d'autorisation. 
		{ 		
			case 0:
			$status = 'member';
			break;
			
			case 1: 
			$status = 'modo';
			break;
			
			case 2: 
			$status = 'admin';
			break;
			
			default:
			$status = 'member';
		}
		
		$modules_parameters = unserialize($row['modules_parameters']);
		$display_desc = !empty($modules_parameters[MODULE_NAME]['display_desc']) ? TRUE : FALSE;
		var_dump($display_desc);

		$row['session_script_get'] = !empty($row['session_script_get']) ? '?' . $row['session_script_get'] : '';
		$tpl->assign_block_vars('users', array(
			'ID' => $row['user_id'],
			'USER' => !empty($row['login']) ? '<a href="' . HOST . '/member/member.php?id=' . $row['user_id'] . '" class="' . $status . '">' . $row['login'] . '</a>': $LANG['guest'],
			'LOCATION' => '<a href="' . HOST . DIR . $row['session_script'] . $row['session_script_get'] . '">' . stripslashes($row['session_script_title']) . '</a>',
			'LAST_UPDATE' => gmdate_format('date_format_long', $row['session_time']),
			'DESC' => $row['user_desc'],
			'DISPLAY_DESC' => !empty($display_desc) ? 'block' : 'none'
		));	
	}
	$Sql->query_close($result);

	return $tpl->parse(TEMPLATE_STRING_MODE);

}

function switch_display($user_id)
{
	global $Sql;
	
	$sessions = $Sql->query_array(PREFIX . 'sessions', 'modules_parameters', "WHERE user_id = " . intval($user_id) , __LINE__, __FILE__);

	if(empty($sessions['modules_parameters']))
	{
		$display_desc = 1;
	}
	else
	{
		$sessions = unserialize($sessions['modules_parameters']);		
		$display_desc = $sessions[MODULE_NAME]['display_desc'];
		$display_desc = (intval($display_desc) + 1) % 2;
	}

	$record[MODULE_NAME]['display_desc'] = $display_desc;
	$Sql->query_inject(
		"UPDATE ".PREFIX."sessions
			SET
				modules_parameters = '" . $Sql->escape(serialize($record)) ."'
			WHERE
				user_id = " . intval($user_id)
		, __LINE__, __FILE__);
			
	return $display_desc;
}

?>