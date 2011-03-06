<?php
/*##################################################
 *                               admin_members_config.php
 *                            -------------------
 *   begin                : April 15, 2006
 *   copyright          : (C) 2006 Viarre Régis
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

if (!empty($_POST['msg_mbr'])) //Message aux membres.
{
	$config_member['activ_register'] = retrieve(POST, 'activ_register', 0);
	$config_member['msg_mbr'] = stripslashes(strparse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED)));
	$config_member['msg_register'] = $CONFIG_USER['msg_register'];
	$config_member['activ_mbr'] = retrieve(POST, 'activ_mbr', 0); //désactivé par defaut. 
	$config_member['verif_code'] = (isset($_POST['verif_code']) && @extension_loaded('gd')) ? numeric($_POST['verif_code']) : 0; //désactivé par defaut. 
	$config_member['verif_code_difficulty'] = retrieve(POST, 'verif_code_difficulty', 2);
	$config_member['delay_unactiv_max'] = retrieve(POST, 'delay_unactiv_max', 0); 
	$config_member['force_theme'] = retrieve(POST, 'force_theme', 0); //Désactivé par défaut.
	$config_member['activ_up_avatar'] = retrieve(POST, 'activ_up_avatar', 0); //Désactivé par défaut.
	$config_member['width_max'] = retrieve(POST, 'width_max', 120);
	$config_member['height_max'] = retrieve(POST, 'height_max', 120);
	$config_member['weight_max'] = retrieve(POST, 'weight_max', 20);
	$config_member['activ_avatar'] = retrieve(POST, 'activ_avatar', 0);
	$config_member['avatar_url'] = retrieve(POST, 'avatar_url', '');
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_member)) . "' WHERE name = 'member'", __LINE__, __FILE__); //MAJ	
	
	###### Régénération du cache $CONFIG_USER #######
	$Cache->Generate_file('member');
	
	redirect(HOST . SCRIPT); 	
}
else
{			
	$Template->set_filenames(array(
		'admin_members_config'=> 'admin/admin_members_config.tpl'
	));
	
	#####################Activation du mail par le membre pour s'inscrire##################
	$array = array(0 => $LANG['no_activ_mbr'], 1 => $LANG['mail'], 2 => $LANG['admin']);
	$activ_mode_option = '';
	foreach ($array as $key => $value)
	{
		$selected = ( $CONFIG_USER['activ_mbr'] == $key ) ? 'selected="selected"' : '' ;		
		$activ_mode_option .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	}
	
	for ($i = 0; $i < 5; $i++)
	{
		$Template->assign_block_vars('difficulty', array(
			'VALUE' => $i,
			'SELECTED' => ($CONFIG_USER['verif_code_difficulty'] == $i) ? 'selected="selected"' : ''
		));
	}
	
	$Template->assign_vars(array(
		'ACTIV_MODE_OPTION' => $activ_mode_option,
		'ACTIV_REGISTER_ENABLED' => $CONFIG_USER['activ_register'] == 1 ? 'selected="selected"' : '',
		'ACTIV_REGISTER_DISABLED' => $CONFIG_USER['activ_register'] == 0 ? 'selected="selected"' : '',
		'VERIF_CODE_ENABLED' => ($CONFIG_USER['verif_code'] == 1 && @extension_loaded('gd')) ? 'checked="checked"' : '',
		'VERIF_CODE_DISABLED' => ($CONFIG_USER['verif_code'] == 0) ? 'checked="checked"' : '',
		'DELAY_UNACTIV_MAX' => !empty($CONFIG_USER['delay_unactiv_max']) ? $CONFIG_USER['delay_unactiv_max'] : '',
		'ALLOW_THEME_ENABLED' => ($CONFIG_USER['force_theme'] == 0) ? 'checked="checked"' : '',
		'ALLOW_THEME_DISABLED' => ($CONFIG_USER['force_theme'] == 1) ? 'checked="checked"' : '',
		'AVATAR_UP_ENABLED' => ($CONFIG_USER['activ_up_avatar'] == 1) ? 'checked="checked"' : '',
		'AVATAR_UP_DISABLED' => ($CONFIG_USER['activ_up_avatar'] == 0) ? 'checked="checked"' : '',
		'AVATAR_ENABLED' => ($CONFIG_USER['activ_avatar'] == 1) ? 'checked="checked"' : '',
		'AVATAR_DISABLED' => ($CONFIG_USER['activ_avatar'] == 0) ? 'checked="checked"' : '',
		'WIDTH_MAX' => !empty($CONFIG_USER['width_max']) ? $CONFIG_USER['width_max'] : '120',
		'HEIGHT_MAX' => !empty($CONFIG_USER['height_max']) ? $CONFIG_USER['height_max'] : '120',
		'WEIGHT_MAX' => !empty($CONFIG_USER['weight_max']) ? $CONFIG_USER['weight_max'] : '20',
		'AVATAR_URL' => !empty($CONFIG_USER['avatar_url']) ? $CONFIG_USER['avatar_url'] : '',
		'CONTENTS' => unparse($CONFIG_USER['msg_mbr']),
		'KERNEL_EDITOR' => display_editor(),
		'GD_DISABLED' => (!@extension_loaded('gd')) ? 'disabled="disabled"' : '',
		'L_KB' => $LANG['unit_kilobytes'],
		'L_PX' => $LANG['unit_pixels'],
		'L_ACTIV_REGISTER' => $LANG['activ_register'],
		'L_REQUIRE_MAX_WIDTH' => $LANG['require_max_width'],
		'L_REQUIRE_HEIGHT' => $LANG['require_height'],
		'L_REQUIRE_WEIGHT' => $LANG['require_weight'],
		'L_USERS_MANAGEMENT' => $LANG['members_management'],
		'L_USERS_ADD' => $LANG['members_add'],
		'L_USERS_CONFIG' => $LANG['members_config'],
		'L_USERS_PUNISHMENT' => $LANG['punishment_management'],
		'L_USERS_MSG' => $LANG['members_msg'],
		'L_ACTIV_MBR' => $LANG['activ_mbr'],
		'L_DELAY_UNACTIV_MAX' => $LANG['delay_activ_max'],
		'L_DELAY_UNACTIV_MAX_EXPLAIN' => $LANG['delay_activ_max_explain'],
		'L_DAYS' => $LANG['days'],
		'L_VERIF_CODE' => $LANG['verif_code'],
		'L_VERIF_CODE_EXPLAIN' => $LANG['verif_code_explain'],
		'L_CAPTCHA_DIFFICULTY' => $LANG['captcha_difficulty'],
		'L_ALLOW_THEME_MBR' => $LANG['allow_theme_mbr'],
		'L_AVATAR_MANAGEMENT' => $LANG['avatar_management'],
		'L_ACTIV_UP_AVATAR' => $LANG['activ_up_avatar'],
		'L_WIDTH_MAX_AVATAR' => $LANG['width_max_avatar'],
		'L_WIDTH_MAX_AVATAR_EXPLAIN' => $LANG['width_max_avatar_explain'],
		'L_HEIGHT_MAX_AVATAR' => $LANG['height_max_avatar'],
		'L_HEIGHT_MAX_AVATAR_EXPLAIN' => $LANG['height_max_avatar_explain'],
		'L_WEIGHT_MAX_AVATAR' => $LANG['weight_max_avatar'],
		'L_WEIGHT_MAX_AVATAR_EXPLAIN' => $LANG['weight_max_avatar_explain'],
		'L_ACTIV_DEFAUT_AVATAR' => $LANG['activ_defaut_avatar'],
		'L_ACTIV_DEFAUT_AVATAR_EXPLAIN' => $LANG['activ_defaut_avatar_explain'],
		'L_URL_DEFAUT_AVATAR' => $LANG['url_defaut_avatar'],
		'L_URL_DEFAUT_AVATAR_EXPLAIN' => $LANG['url_defaut_avatar_explain'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_CONTENTS' => $LANG['content'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$Template->pparse('admin_members_config'); 
}

require_once('../admin/admin_footer.php');

?>