<?php
/*##################################################
 *                             moderation_panel_begin.php
 *                            -------------------
 *   begin                : March 01, 2008
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

if( defined('PHPBOOST') !== true)	
	exit;
	
//$Cache->Load_file('forum');

############### Header du panneau de modo ################
$Template->Set_filenames(array(
	'moderation_panel_top'=> 'moderation_panel_top.tpl',
	'moderation_panel_bottom'=> 'moderation_panel_bottom.tpl'
));

//Listing des modules disponibles:
$i = 0;
$result = $Sql->Query_while("SELECT name 
FROM ".PREFIX."modules
WHERE activ = 1", __LINE__, __FILE__);
$nbr_modules = $Sql->Sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."modules WHERE activ = 1 AND admin = 1");
while( $row = $Sql->Sql_fetch_assoc($result) )
{
	$config = load_ini_file('../' . $row['name'] . '/lang/', $CONFIG['lang']);
	if( is_array($config) )
	{	
		if( isset($config['moderation_panel']) && $config['moderation_panel'] == 1 )
		{
			$Template->Assign_block_vars('list_modules', array(
				'DM_A_CLASS' => ' style="background-image:url(../' . $row['name'] . '/' . $row['name'] . '_mini.png);"',
				'NAME' => $config['name'],
				'MOD_NAME' => !empty($row['name']) ? $row['name'] : '',
				'U_LINK' => transid('moderation_'. $row['name'] . '.php')
			));
			$i++;
		}
	}	
}
$Sql->Close($result);

$Template->Assign_vars(array(
	'L_MODERATION_PANEL' => $LANG['moderation_panel'],
	'L_MEMBERS' => $LANG['member_s'],
	'L_MODULES' => $LANG['modules']
));

?>
