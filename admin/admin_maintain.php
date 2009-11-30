<?php
/*##################################################
 *                               admin_maintain.php
 *                            -------------------
 *   begin                : Februar 07, 2007
 *   copyright            : (C) 2007 Viarre Régis
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	$maintain_check = retrieve(POST, 'maintain_check', 0);
	switch ($maintain_check) 
	{
		case 1:
			$maintain = retrieve(POST, 'maintain', 0); //Désactivé par défaut.
			if ($maintain != -1)
				$maintain = !empty($maintain) ? time() + $maintain + 5 : '0';	
		break;
		case 2:
			$maintain = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
			$maintain = strtotimestamp($maintain, $LANG['date_format_short']);
		break;
		default:
			$maintain = '0';
	}
	
	$CONFIG['maintain_auth'] = Authorizations::build_auth_array_from_form(AUTH_MAINTAIN);
	$CONFIG['maintain_text'] = stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE));
	$CONFIG['maintain_delay'] = retrieve(POST, 'display_delay', 0);
	$CONFIG['maintain_display_admin'] = retrieve(POST, 'maintain_display_admin', 0);
	$CONFIG['maintain'] = $maintain;
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
	###### Régénération du cache $CONFIG #######
	$Cache->Generate_file('config');
	
	redirect(HOST . SCRIPT);
}
else //Sinon on rempli le formulaire	 
{		
	$Template->set_filenames(array(
		'admin_maintain'=> 'admin/admin_maintain.tpl'
	));
	
	//Durée de la maintenance.
	$array_time = array(-1, 60, 300, 600, 900, 1800, 3600, 7200, 10800, 14400, 18000, 21600, 25200, 28800, 57600); 
	$array_delay = array($LANG['unspecified'], '1 ' . $LANG['minute'], '5 ' . $LANG['minutes'], '10 ' . $LANG['minutes'], '15 ' . $LANG['minutes'], '30 ' . $LANG['minutes'], '1 ' . $LANG['hour'], '2 ' . $LANG['hours'], '3 ' . $LANG['hours'], '4 ' . $LANG['hours'], '5 ' . $LANG['hours'], '6 ' . $LANG['hours'], '7 ' . $LANG['hours'], '8 ' . $LANG['hours'], '16 ' . $LANG['hours']); 
	
	$array_size = count($array_time) - 1;
	$CONFIG['maintain'] = isset($CONFIG['maintain']) ? $CONFIG['maintain'] : -1;
	if ($CONFIG['maintain'] != -1)
	{
		$key_delay = 0;
		$current_time = time();
		for ($i = $array_size; $i >= 1; $i--)
		{					
			if (($CONFIG['maintain'] - $current_time) - $array_time[$i] < 0 && ($CONFIG['maintain'] - $current_time) - $array_time[$i-1] > 0)
			{	
				$key_delay = $i-1;
				break;
			}	
		}
	}
	else
		$key_delay = 0;

	$delay_maintain_option = '';
	foreach ($array_time as $key => $time)
	{
		$selected = ($key_delay == $key) ? 'selected="selected"' : '' ;
		$delay_maintain_option .= '<option value="' . $time . '" ' . $selected . '>' . $array_delay[$key] . '</option>' . "\n";
	}
	
	$CONFIG['maintain_delay'] = isset($CONFIG['maintain_delay']) ? $CONFIG['maintain_delay'] : 1;
	$CONFIG['maintain_display_admin'] = isset($CONFIG['maintain_display_admin']) ? $CONFIG['maintain_display_admin'] : 1;

	$check_until = ($CONFIG['maintain'] != -1 && $CONFIG['maintain'] > (time() + 86400));
	$Template->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'DELAY_MAINTAIN_OPTION' => $delay_maintain_option,
		'AUTH_WEBSITE' => Authorizations::generate_select(AUTH_MAINTAIN, (!empty($CONFIG['maintain_auth']) ? $CONFIG['maintain_auth'] : array())),
		'MAINTAIN_CONTENTS' => !empty($CONFIG['maintain_text']) ? unparse($CONFIG['maintain_text']) : '',
		'DISPLAY_DELAY_ENABLED' => ($CONFIG['maintain_delay'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_DELAY_DISABLED' => ($CONFIG['maintain_delay'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_ENABLED' => ($CONFIG['maintain_display_admin'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_ADMIN_DISABLED' => ($CONFIG['maintain_display_admin'] == 0) ? 'checked="checked"' : '',
		'MAINTAIN_CHECK_NO' => ($CONFIG['maintain'] != -1 && $CONFIG['maintain'] <= time()) ? ' checked="checked"' : '',
		'MAINTAIN_CHECK_DELAY' => ($CONFIG['maintain'] == -1 || ($CONFIG['maintain'] > time() && $CONFIG['maintain'] <= (time() + 86400))) ? ' checked="checked"' : '',
		'MAINTAIN_CHECK_UNTIL' => $check_until ? ' checked="checked"' : '',
		'DATE_UNTIL' => $check_until ? gmdate_format('date_format_short', $CONFIG['maintain']) : '',
		'L_MAINTAIN' => $LANG['maintain'],
		'L_UNTIL' => $LANG['until'],
		'L_DURING' => $LANG['during'],
		'L_SET_MAINTAIN' => $LANG['maintain_for'],
		'L_MAINTAIN_DELAY' => $LANG['maintain_delay'],
		'L_MAINTAIN_DISPLAY_ADMIN' => $LANG['maintain_display_admin'],
		'L_AUTH_WEBSITE' => $LANG['maintain_auth'],
		'L_MAINTAIN_TEXT' => $LANG['maintain_text'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']		
	));
	
	$Template->pparse('admin_maintain');
}

require_once('../admin/admin_footer.php');

?>