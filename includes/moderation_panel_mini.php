<?php
/*##################################################
 *                                moderation_panel_mini.php
 *                            -------------------
 *   begin                : August 10, 2006
 *   copyright          : (C) 2006 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

if( defined('PHPBOOST') !== true)	exit;

$check_auth_by_group = false;

if( isset($_array_group_forum_level) )
{
	$check_auth_by_group = false;
	foreach($_array_group_forum_level as $id => $level)
	{
		if( $level >= 1 )
		{
			$check_auth_by_group = true;
			break;
		}
	}
}

$Cache->Load_file('moderation_panel');

$Template->Set_filenames(array(
	'moderation_panel_mini'=> 'moderation_panel_mini.tpl'
));

if( $Member->Check_level(MODO_LEVEL) || $check_auth_by_group )
{
	$nbr_alerts = 0;
	
	/*if( $Member->Check_level(ADMIN_LEVEL) )
		$nbr_alerts = $nbr_alerts_for_admins;
	elseif( $Member->Check_level(ADMIN_LEVEL) )
		$nbr_alerts = $nbr_alerts_for_modos;
	elseif( isset($nbr_alerts_for_groups[$Member->Get_attribute('user_groups')]) )
		$nbr_alerts = $nbr_alerts_for_groups[$Member->Get_attribute('user_groups')];
	*/
	$Template->Assign_block_vars('moderation_panel', array(
		'IMG' => ($nbr_alerts > 0) ? 'moderation_panel_new.gif' : 'moderation_panel.png',
		'NBR_ALERTS' => $nbr_alerts,
		'L_MODERATION_PANEL' => $LANG['moderation_panel']		
	));
}

?>