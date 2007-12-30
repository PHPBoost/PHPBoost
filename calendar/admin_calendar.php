<?php
/*##################################################
 *                               admin_calendar.php
 *                            -------------------
 *   begin                : March 13, 2007
 *   copyright          : (C) 2007 Viarre Régis
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

require_once('../includes/admin_begin.php');
load_module_lang('calendar', $CONFIG['lang']); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

##########################admin_calendar.tpl###########################
if( !empty($_POST['valid'])  )
{
	$config_calendar = array();
	$config_calendar['calendar_auth'] = isset($_POST['calendar_auth']) ? numeric($_POST['calendar_auth']) : -1;
		
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_calendar)) . "' WHERE name = 'calendar'", __LINE__, __FILE__);
	
	###### Régénération du cache des news #######
	$cache->generate_module_file('calendar');
	
	header('location:' . HOST . SCRIPT);	
	exit;
}
//Sinon on rempli le formulaire
else	
{		
	$template->set_filenames(array(
		'admin_calendar_config' => '../templates/' . $CONFIG['theme'] . '/calendar/admin_calendar_config.tpl'
	));
	
	$cache->load_file('calendar');
	
	$template->assign_vars(array(
		'L_REQUIRE' => $LANG['require'],	
		'L_CALENDAR' => $LANG['title_calendar'],
		'L_CALENDAR_CONFIG' => $LANG['calendar_config'],
		'L_RANK' => $LANG['rank_post'],
		'L_UPDATE' => $LANG['update'],
		'L_ERASE' => $LANG['erase'],
	));
	
	$CONFIG_CALENDAR['calendar_auth'] = isset($CONFIG_CALENDAR['calendar_auth']) ? $CONFIG_CALENDAR['calendar_auth'] : '-1';	
	//Rang d'autorisation.
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

		$selected = ($CONFIG_CALENDAR['calendar_auth'] == $i) ? 'selected="selected"' : '' ;
		$template->assign_block_vars('select_auth', array(
			'RANK' => '<option value="' . $i . '" ' . $selected . '>' . $rank . '</option>'
		));
	}
		
	$template->pparse('admin_calendar_config');
}

require_once('../includes/admin_footer.php');

?>