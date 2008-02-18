<?php
/*##################################################
 *                               shoutbox_mini.php
 *                            -------------------
 *   begin                : July 29, 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

if( defined('PHP_BOOST') !== true ) exit;

//Mini Shoutbox non activée si sur la page archive shoutbox.
if( strpos(SCRIPT, '/shoutbox/shoutbox.php') === false )
{
	load_module_lang('shoutbox', $CONFIG['lang']);
	$Cache->Load_file('shoutbox'); //Chargement du cache
	
	###########################Insertion##############################
	if( !empty($_POST['shoutbox']) )
	{		
		//Membre en lecture seule?
		if( $Member->Get_attribute('user_readonly') > time() ) 
			$Errorh->Error_handler('e_readonly', E_USER_REDIRECT); 
			
		$shout_pseudo = !empty($_POST['shout_pseudo']) ? clean_user($_POST['shout_pseudo']) : $LANG['guest']; //Pseudo posté.
		$shout_contents = !empty($_POST['shout_contents']) ? trim($_POST['shout_contents']) : '';	
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
						redirect(HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=flood', '', '&'));
				}
				
				//Vérifie que le message ne contient pas du flood de lien.				
				$shout_contents = parse($shout_contents, $CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
				if( !check_nbr_links($shout_pseudo, 0) ) //Nombre de liens max dans le pseudo.
					redirect(HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=lp_flood', '', '&'));
				if( !check_nbr_links($shout_contents, $CONFIG_SHOUTBOX['shoutbox_max_link']) ) //Nombre de liens max dans le message.
					redirect(HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=l_flood', '', '&'));
					
				$Sql->Query_inject("INSERT INTO ".PREFIX."shoutbox (login, user_id, contents, timestamp) VALUES ('" . $shout_pseudo . "', '" . $Member->Get_attribute('user_id') . "', '" . $shout_contents . "', '" . time() . "')", __LINE__, __FILE__);
				
				redirect(HOST . transid(SCRIPT . '?' . QUERY_STRING, '', '&'));
			}
			else //utilisateur non autorisé!
				redirect(HOST . DIR . '/shoutbox/shoutbox.php' . transid('?error=auth', '', '&'));
		}	
	}
	
	###########################Affichage##############################
	$Template->Set_filenames(array(
		'shoutbox_mini' => '../templates/' . $CONFIG['theme'] . '/shoutbox/shoutbox_mini.tpl'
	));

	//Pseudo du membre connecté.
	if( $Member->Get_attribute('user_id') !== -1 )
		$Template->Assign_vars(array(
			'SHOUTBOX_PSEUDO' => $Member->Get_attribute('login'),
			'C_HIDDEN_SHOUT' => true
		));
	else
		$Template->Assign_vars(array(
			'SHOUTBOX_PSEUDO' => $LANG['guest'],
			'C_VISIBLE_SHOUT' => true
		));
		
	$Template->Assign_vars(array(
		'SID' => SID,		
		'L_ALERT_TEXT' => $LANG['require_text'],
		'L_ALERT_UNAUTH_POST' => $LANG['e_unauthorized'],
		'L_ALERT_FLOOD' => $LANG['e_flood'],
		'L_ALERT_LINK_FLOOD' => sprintf($LANG['e_l_flood'], $CONFIG_SHOUTBOX['shoutbox_max_link']),
		'L_ALERT_LINK_PSEUDO' => $LANG['e_link_pseudo'],
		'L_ALERT_INCOMPLETE' => $LANG['e_incomplete'],
		'L_DELETE_MSG' => $LANG['alert_delete_msg'],
		'L_SHOUTBOX' => $LANG['title_shoutbox'],
		'L_MESSAGE' => $LANG['message'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SUBMIT' => $LANG['submit'],
		'L_REFRESH' => $LANG['refresh'],
		'L_ARCHIVE' => $LANG['archive']
	));
	
	$result = $Sql->Query_while("SELECT id, login, user_id, contents 
	FROM ".PREFIX."shoutbox 
	ORDER BY timestamp DESC 
	" . $Sql->Sql_limit(0, 25), __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$row['user_id'] = (int)$row['user_id'];		
		if( $Member->Check_level(1) || ($row['user_id'] === $Member->Get_attribute('user_id') && $Member->Get_attribute('user_id') !== -1) )
			$del = '<script type="text/javascript"><!-- 
			document.write(\'<a href="javascript:Confirm_del_shout(' . $row['id'] . ');" title="' . $LANG['delete'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/delete_mini.png" alt="" /></a>\'); 
			--></script><noscript><a href="../shoutbox/shoutbox' . transid('.php?del=true&amp;id=' . $row['id']) . '"><img src="../templates/' . $CONFIG['theme'] . '/images/delete_mini.png" alt="" /></a></noscript>';
		else
			$del = '';
	
		if( $row['user_id'] !== -1 ) 
			$row['login'] = $del . ' <a class="small_link" href="../member/member' . transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest'])  . '</a>';
		else
			$row['login'] = $del . ' <span class="text_small" style="font-style: italic;">' . (!empty($row['login']) ? wordwrap_html($row['login'], 16) : $LANG['guest']) . '</span>';
		
		$Template->Assign_block_vars('shout',array(
			'IDMSG' => $row['id'],
			'PSEUDO' => $row['login'],
			'CONTENTS' => ucfirst($row['contents']) //Majuscule premier caractère.
		));							
	}
	$Sql->Close($result);
	
	$Template->Pparse('shoutbox_mini'); 
}

?>