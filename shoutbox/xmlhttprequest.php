<?php
/*##################################################
 *                                xmlhttprequest.php
 *                            -------------------
 *   begin                : December 20, 2007
 *   copyright          : (C) 2007 Viarre Régis
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../includes/begin.php');
require_once('../shoutbox/shoutbox_begin.php');
require_once('../includes/header_no_display.php');

$add = !empty($_GET['add']) ? true : false;
$del = !empty($_GET['del']) ? true : false;
$refresh = !empty($_GET['refresh']) ? true : false;

if( $add )
{
	//Membre en lecture seule?
	if( $Member->Get_attribute('user_readonly') > time() ) 
	{
		echo -6;
		exit;
	}
		
	$shout_pseudo = !empty($_POST['pseudo']) ? securit(utf8_decode($_POST['pseudo'])) : $LANG['guest'];
	$shout_contents = !empty($_POST['contents']) ? trim(utf8_decode($_POST['contents'])) : '';
	if( !empty($shout_pseudo) && !empty($shout_contents) )
	{
		//Accès pour poster.		
		if( $Member->Check_level($CONFIG_SHOUTBOX['shoutbox_auth']) )
		{
			//Mod anti-flood, autorisé aux membres qui bénificie de l'autorisation de flooder.
			$check_time = ($Member->Get_attribute('user_id') !== -1 && $CONFIG['anti_flood'] == 1) ? $Sql->Query("SELECT MAX(timestamp) as timestamp FROM ".PREFIX."shoutbox WHERE user_id = '" . $Member->Get_attribute('user_id') . "'", __LINE__, __FILE__) : '';
			if( !empty($check_time) && !$Member->Check_max_value(AUTH_FLOOD) )
			{
				if( $check_time >= (time() - $CONFIG['delay_flood']) )
				{
					echo -2;
					exit;
				}
			}
			
			//Vérifie que le message ne contient pas du flood de lien.
			$shout_contents = parse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);		
			if( !check_nbr_links($shout_pseudo, 0) ) //Nombre de liens max dans le pseudo.
			{	
				echo -3;
				exit;
			}
			if( !check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link']) ) //Nombre de liens max dans le message.
			{	
				echo -4;
				exit;
			}
			
			$Sql->Query_inject("INSERT INTO ".PREFIX."shoutbox (login, user_id, contents, timestamp) VALUES('" . $shout_pseudo . "', '" . $Member->Get_attribute('user_id') . "','" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
			$last_msg_id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."shoutbox"); 
			
			if( $Member->Get_attribute('user_id') !== -1 )
				$shout_pseudo = '<a href="javascript:Confirm_del_shout(' . $last_msg_id . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/delete_mini.png" alt="" /></a> <a class="small_link" href="../member/member' . transid('.php?id=' . $Member->Get_attribute('user_id'), '-' . $Member->Get_attribute('user_id') . '.php') . '">' . (!empty($shout_pseudo) ? wordwrap_html($shout_pseudo, 16) : $LANG['guest'])  . '</a>';
			else
				$shout_pseudo = '<span class="text_small" style="font-style: italic;">' . (!empty($shout_pseudo) ? wordwrap_html($shout_pseudo, 16) : $LANG['guest']) . '</span>';
				
			echo "array_shout[0] = '" . $shout_pseudo . "';";
			echo "array_shout[1] = '" . str_replace(array("\n", "\r"), array('', ''), $shout_contents) . "';";
			echo "array_shout[2] = '" . $last_msg_id . "';";
		}
		else //utilisateur non autorisé!
			echo -1;
	}
	else
		echo -5;
}
elseif( $refresh )
{
	$result = $Sql->Query_while("SELECT id, login, user_id, contents 
	FROM ".PREFIX."shoutbox 
	ORDER BY timestamp DESC 
	" . $Sql->Sql_limit(0, 25), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$row['user_id'] = (int)$row['user_id'];		
		if( $Member->Check_level(1) || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1) )
			$del = '<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/delete_mini.png" alt="" /></a>';
		else
			$del = '';
	
		if( $row['user_id'] !== -1 ) 
			$row['login'] = $del . ' <a class="small_link" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest'])  . '</a>';
		else
			$row['login'] = $del . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
		
		echo '<p id="shout_container_' . $row['id'] . '">' . $row['login'] . '<span class="text_small">: ' . str_replace(array("\n", "\r"), array('', ''), ucfirst($row['contents'])) . '</span></p>' . "\n";
	}
	$Sql->Close($result);
}
elseif( $del )
{
	$shout_id = !empty($_POST['idmsg']) ? numeric($_POST['idmsg']) : '';
	if( !empty($shout_id) )
	{
		$user_id = (int)$Sql->Query("SELECT user_id FROM ".PREFIX."shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
		if( $Member->Check_level(1) || ($user_id === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1) )
		{
			$Sql->Query_inject("DELETE FROM ".PREFIX."shoutbox WHERE id = '" . $shout_id . "'", __LINE__, __FILE__);
			echo 1;
		}
	}
}

$Sql->Sql_close(); //Fermeture de mysql

?>