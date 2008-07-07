<?php
/*##################################################
 *                               admin_terms.php
 *                            -------------------
 *   begin                : Februar 06 2007
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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

$Cache->Load_file('member');

if( !empty($_POST['msg_register']) ) //Message à l'inscription.
{
	$config_member['activ_register'] = isset($CONFIG_MEMBER['activ_register']) ? numeric($CONFIG_MEMBER['activ_register']) : 0;
	$config_member['activ_mbr'] = isset($CONFIG_MEMBER['activ_mbr']) ? numeric($CONFIG_MEMBER['activ_mbr']) : 0; //désactivé par defaut. 
	$config_member['verif_code'] = isset($CONFIG_MEMBER['verif_code']) ? numeric($CONFIG_MEMBER['verif_code']) : 0; //désactivé par defaut. 
	$config_member['delay_unactiv_max'] = isset($CONFIG_MEMBER['delay_unactiv_max']) ? numeric($CONFIG_MEMBER['delay_unactiv_max']) : '';
	$config_member['force_theme'] = isset($CONFIG_MEMBER['force_theme']) ? numeric($CONFIG_MEMBER['force_theme']) : 0; //Désactivé par défaut.
	$config_member['activ_up_avatar'] = isset($CONFIG_MEMBER['activ_up_avatar']) ? numeric($CONFIG_MEMBER['activ_up_avatar']) : 0; //Désactivé par défaut.
	$config_member['width_max'] = isset($CONFIG_MEMBER['width_max']) ? numeric($CONFIG_MEMBER['width_max']) : 120;
	$config_member['height_max'] = isset($CONFIG_MEMBER['height_max']) ? numeric($CONFIG_MEMBER['height_max']) : 120;
	$config_member['weight_max'] = isset($CONFIG_MEMBER['weight_max']) ? numeric($CONFIG_MEMBER['weight_max']) : 20;
	$config_member['activ_avatar'] = isset($CONFIG_MEMBER['activ_avatar']) ? numeric($CONFIG_MEMBER['activ_avatar']) : 0;
	$config_member['avatar_url'] = isset($CONFIG_MEMBER['avatar_url']) ? $CONFIG_MEMBER['avatar_url'] : 0;
	$config_member['msg_mbr'] = isset($CONFIG_MEMBER['msg_mbr']) ? $CONFIG_MEMBER['msg_mbr'] : '';
	
	$config_member['msg_register'] = stripslashes(retrieve(POST, 'contents', '', TSTRING_PARSE));
	
	$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_member)) . "' WHERE name = 'member'", __LINE__, __FILE__); //MAJ	
	
	###### Régénération du cache $CONFIG_MEMBER #######
	$Cache->Generate_file('member');
		
	redirect(HOST . SCRIPT); 	
}
else
{			
	$Template->Set_filenames(array(
		'admin_terms'=> 'admin/admin_terms.tpl'
	));
	
	$Template->Assign_vars(array(
		'L_TERMS' => $LANG['register_terms'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
	));
	
	$msg_register = $Sql->Query("SELECT value FROM ".PREFIX."configs WHERE name = 'member'", __LINE__, __FILE__); //Message à l'inscription.
	
	$Template->Assign_vars(array(
		'CONTENTS' => unparse($CONFIG_MEMBER['msg_register']),
		'KERNEL_EDITOR' => display_editor(),
		'L_TERMS' => $LANG['register_terms'],
		'L_EXPLAIN_TERMS' => $LANG['explain_terms'],
		'L_CONTENTS' => $LANG['contents'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));		
	
	$Template->Pparse('admin_terms'); 
}

require_once('../kernel/admin_footer.php');

?>