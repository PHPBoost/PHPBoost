<?php
/*##################################################
 *                               admin_maintain.php
 *                            -------------------
 *   begin                : Februar 07, 2007
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
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

//Si c'est confirmé on execute
if( !empty($_POST['valid']) )
{
	
	$maintain = isset($_POST['maintain']) ? numeric($_POST['maintain']) : 0; //Désactivé par défaut.
	if( $maintain != -1 )
		$maintain = !empty($maintain) ? time() + $maintain : '0';	
	
	$CONFIG['maintain_text'] = !empty($_POST['contents']) ? stripslashes(parse($_POST['contents'])) : '';
	$CONFIG['maintain_delay'] = isset($_POST['display_delay']) ? numeric($_POST['display_delay']) : 0;
	$CONFIG['maintain_display_admin'] = isset($_POST['maintain_display_admin']) ? numeric($_POST['maintain_display_admin']) : 0;
	$CONFIG['maintain'] = $maintain;
	$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### Régénération du cache $CONFIG #######
	$cache->generate_file('config');
	
	header('location:' . HOST . SCRIPT);
	exit;
}
else //Sinon on rempli le formulaire	 
{		
	$template->set_filenames(array(
		'admin_maintain' => '../templates/' . $CONFIG['theme'] . '/admin/admin_maintain.tpl'
	));
	
	$CONFIG['maintain_delay'] = isset($CONFIG['maintain_delay']) ? $CONFIG['maintain_delay'] : 1;
	$CONFIG['maintain_display_admin'] = isset($CONFIG['maintain_display_admin']) ? $CONFIG['maintain_display_admin'] : 1;

	$template->assign_vars(array(
		'MAINTAIN_CONTENTS' => !empty($CONFIG['maintain_text']) ? unparse($CONFIG['maintain_text']) : '',
		'DISPLAY_DELAY_ENABLED' => ($CONFIG['maintain_delay'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_DELAY_DISABLED' => ($CONFIG['maintain_delay'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_ENABLED' => ($CONFIG['maintain_display_admin'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_DISABLED' => ($CONFIG['maintain_display_admin'] == 0) ? 'checked="checked"' : '',
		'L_MAINTAIN' => $LANG['maintain'],
		'L_SET_MAINTAIN' => $LANG['maintain_for'],
		'L_MAINTAIN_DELAY' => $LANG['maintain_delay'],
		'L_MAINTAIN_DISPLAY_ADMIN' => $LANG['maintain_display_admin'],
		'L_MAINTAIN_TEXT' => $LANG['maintain_text'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']		
	));
		
	//Durée de la maintenance.
	$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800'); 
	$array_delay = array(0 => $LANG['unspecified'], 1 => $LANG['no'], 2 => '1 ' . $LANG['minute'], 3 => '5 ' . $LANG['minutes'], 4 => '15 ' . $LANG['minutes'], 5 => '30 ' . $LANG['minutes'], 6 => '1 ' . $LANG['hour'], 7 => '2 ' . $LANG['hours'], 8 => '1 ' . $LANG['day'], 9 => '2 ' . $LANG['days'], 10 => '1 ' . $LANG['week']); 
	
	$CONFIG['maintain'] = isset($CONFIG['maintain']) ? $CONFIG['maintain'] : -1;
	if( $CONFIG['maintain'] != -1 )
	{
		$key_delay = 0;
		$current_time = time();
		for($i = 10; $i >= 0; $i--)
		{					
			$delay = ($CONFIG['maintain'] - $current_time) - $array_time[$i];		
			if( $delay >= $array_time[$i] ) 
			{	
				$key_delay = $i;
				break;
			}
		}
	}
	else
		$key_delay = -1;

	foreach( $array_time as $key => $time)
	{
		$selected = (($key_delay + 1) == $key) ? 'selected="selected"' : '' ;
		
		$template->assign_block_vars('select_maintain', array(
			'DELAY' => '<option value="' . $time . '" ' . $selected . '>' . $array_delay[$key] . '</option>'
		));
	}
	
	include_once('../includes/bbcode.php');
	
	$template->pparse('admin_maintain');
}

require_once('../includes/admin_footer.php');

?>