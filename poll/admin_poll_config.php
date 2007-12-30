<?php
/*##################################################
 *                               admin_poll_config.php
 *                            -------------------
 *   begin                : June 21, 2005
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
###################################################*/

require_once('../includes/admin_begin.php');
load_module_lang('poll', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

##########################admin_poll_config.tpl###########################
//Si c'est confirmé on execute
if( !empty($_POST['valid']))
{
	$config_poll = array();
	$config_poll['poll_auth'] = isset($_POST['poll_auth']) ? numeric($_POST['poll_auth']) : -1;
	$config_poll['poll_mini'] = !empty($_POST['poll_mini']) ? numeric($_POST['poll_mini']) : -1;	
	$config_poll['poll_cookie'] = !empty($_POST['poll_cookie']) ? stripslashes(securit($_POST['poll_cookie'])) : 'poll';	
	$config_poll['poll_cookie_lenght'] = !empty($_POST['poll_cookie_lenght']) ? (numeric($_POST['poll_cookie_lenght']) * 3600) : 500;	
		
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_poll)) . "' WHERE name = 'poll'", __LINE__, __FILE__);
	
	###### Régénération du cache du mini poll #######
	$cache->generate_module_file('poll');
	
	header('location:' . HOST . SCRIPT); 	
	exit;
}
//Sinon on rempli le formulaire
else	
{		
	$template->set_filenames(array(
	'admin_poll_config' => '../templates/' . $CONFIG['theme'] . '/poll/admin_poll_config.tpl'
	));

	$cache->load_file('poll');
	
	$template->assign_vars(array(
		'COOKIE_NAME' => !empty($CONFIG_POLL['poll_cookie']) ? $CONFIG_POLL['poll_cookie'] : 'poll',
		'COOKIE_LENGHT' => !empty($CONFIG_POLL['poll_cookie_lenght']) ?  ($CONFIG_POLL['poll_cookie_lenght']/3600) : 500,		
		'L_POLL_MANAGEMENT' => $LANG['poll_management'],
		'L_POLL_ADD' => $LANG['poll_add'],
		'L_POLL_CONFIG' => $LANG['poll_config'],
		'L_POLL_MINI' => $LANG['pool_mini'],
		'L_RANK' => $LANG['rank_vote'],
		'L_COOKIE_NAME' => $LANG['cookie_name'],
		'L_COOKIE_LENGHT' => $LANG['poll_cookie_lenght'],
		'L_HOUR' => $LANG['hours'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$i = 0;
	//Mini poll courant	
	$result = $sql->query_while("SELECT id, question 
	FROM ".PREFIX."poll
	WHERE archive = 0 AND visible = 1
	ORDER BY timestamp", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		if( $row['id'] == $CONFIG_POLL['poll_mini'] )
			$selected = 'selected="selected"';
		else
			$selected = '';

		$option = '';
		if( $i == 0 ) //Ajoute un choix vide, pour marquer l'abscence de mini poll.
		{
			$selected_null = ($CONFIG_POLL['poll_mini'] == '-1') ? 'selected="selected"' : '';
			$option = '<option value="-1" ' . $selected_null . '> -- </option>';
			$i++;
		}
		
		$template->assign_block_vars('select', array(
			'POLL_CURRENT' => $option . '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['question'] . '</option>'
		));						
	}
	$sql->close($result); 					
	 
	
	//Rang d'autorisation.
	$CONFIG_POLL['poll_auth'] = isset($CONFIG_POLL['poll_auth']) ? $CONFIG_POLL['poll_auth'] : '-1';	
	for($i = -1; $i <= 2; $i++)
	{
		switch($i) 
		{	
			case -1:
				$rank = $LANG['guest'];
			break;				
			case 0:
				$rank = $LANG['member'];
			break;				
			case 1: 
				$rank = $LANG['modo'];
			break;		
			case 2:
				$rank = $LANG['admin'];
			break;					
			default: -1;
		} 

		$selected = ($CONFIG_POLL['poll_auth'] == $i) ? 'selected="selected"' : '' ;
		$template->assign_block_vars('select_auth', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	} 
	 
	$template->pparse('admin_poll_config');	
}

require_once('../includes/admin_footer.php');

?>