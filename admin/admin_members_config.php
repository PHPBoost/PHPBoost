<?php
/*##################################################
 *                               admin_members_config.php
 *                            -------------------
 *   begin                : April 15, 2006
 *   copyright            : (C) 2006 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
	$user_account_config = UserAccountsConfig::load();
	
	$user_account_config->set_registration_enabled(retrieve(POST, 'activ_register', false));
	$user_account_config->set_welcome_message(stripslashes(FormatingHelper::strparse(retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED))));
	$user_account_config->set_member_accounts_validation_method(retrieve(POST, 'activ_mbr', 0));
	$user_account_config->set_registration_captcha_enabled(retrieve(POST, 'verif_code', false));
	$user_account_config->set_registration_captcha_difficulty(retrieve(POST, 'verif_code_difficulty', 2));
	$user_account_config->set_force_theme_enabled(retrieve(POST, 'force_theme', false));
	$user_account_config->set_avatar_upload_enabled(retrieve(POST, 'activ_up_avatar', false));
	$user_account_config->set_unactivated_accounts_timeout(retrieve(POST, 'delay_unactiv_max', 20));

	$user_account_config->set_default_avatar_name_enabled(retrieve(POST, 'activ_avatar', false));
	$user_account_config->set_avatar_auto_resizing_enabled(retrieve(POST, 'enable_avatar_auto_resizing', false));
	$user_account_config->set_default_avatar_name(retrieve(POST, 'avatar_url', ''));
	
	$user_account_config->set_max_avatar_width(retrieve(POST, 'width_max', 120));
	$user_account_config->set_max_avatar_height(retrieve(POST, 'height_max', 120));
	$user_account_config->set_max_avatar_weight(retrieve(POST, 'weight_max', 20));
	$user_account_config->set_auth_read_members(Authorizations::build_auth_array_from_form(AUTH_READ_MEMBERS));
	
	UserAccountsConfig::save();
	
	AppContext::get_response()->redirect(HOST . SCRIPT); 	
}
else
{			
	$template = new FileTemplate('admin/admin_members_config.tpl');
	
	$user_account_config = UserAccountsConfig::load();
	
	#####################Activation du mail par le membre pour s'inscrire##################
	$array = array(0 => $LANG['no_activ_mbr'], 1 => $LANG['mail'], 2 => $LANG['admin']);
	$activ_mode_option = '';
	foreach ($array as $key => $value)
	{
		$selected = ($user_account_config->get_member_accounts_validation_method() == $key) ? 'selected="selected"' : '' ;		
		$activ_mode_option .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	}
	
	for ($i = 0; $i < 5; $i++)
	{
		$template->assign_block_vars('difficulty', array(
			'VALUE' => $i,
			'SELECTED' => ($user_account_config->get_registration_captcha_difficulty() == $i) ? 'selected="selected"' : ''
		));
	}

	$template->put_all(array(
		'ACTIV_MODE_OPTION' => $activ_mode_option,
		'ACTIV_REGISTER_ENABLED' => $user_account_config->is_registration_enabled() ? 'selected="selected"' : '',
		'ACTIV_REGISTER_DISABLED' => !$user_account_config->is_registration_enabled() ? 'selected="selected"' : '',
		'VERIF_CODE_ENABLED' => $user_account_config->is_registration_captcha_enabled() ? 'checked="checked"' : '',
		'VERIF_CODE_DISABLED' => !$user_account_config->is_registration_captcha_enabled() ? 'checked="checked"' : '',
		'DELAY_UNACTIV_MAX' => $user_account_config->get_unactivated_accounts_timeout(),
		'ALLOW_THEME_ENABLED' => !$user_account_config->is_users_theme_forced() ? 'checked="checked"' : '',
		'ALLOW_THEME_DISABLED' => $user_account_config->is_users_theme_forced() ? 'checked="checked"' : '',
		'AVATAR_UP_ENABLED' => $user_account_config->is_avatar_upload_enabled() ? 'checked="checked"' : '',
		'AVATAR_UP_DISABLED' => !$user_account_config->is_avatar_upload_enabled() ? 'checked="checked"' : '',
		'AVATAR_ENABLED' => $user_account_config->is_default_avatar_enabled() ? 'checked="checked"' : '',
		'AVATAR_DISABLED' => !$user_account_config->is_default_avatar_enabled() ? 'checked="checked"' : '',
		'AVATAR_AUTO_RESIZING_ENABLED' => $user_account_config->is_avatar_auto_resizing_enabled() ? 'checked="checked"' : '',
		'AVATAR_AUTO_RESIZING_DISABLED' => !$user_account_config->is_avatar_auto_resizing_enabled() ? 'checked="checked"' : '',
		'WIDTH_MAX' => $user_account_config->get_max_avatar_width(),
		'HEIGHT_MAX' => $user_account_config->get_max_avatar_height(),
		'WEIGHT_MAX' => $user_account_config->get_max_avatar_weight(),
		'AVATAR_URL' => $user_account_config->get_default_avatar_name(),
		'AUTH_READ_MEMBERS' => Authorizations::generate_select(AUTH_READ_MEMBERS, $user_account_config->get_auth_read_members()),
		'CONTENTS' => FormatingHelper::unparse($user_account_config->get_welcome_message()),
		'KERNEL_EDITOR' => display_editor(),
		'GD_DISABLED' => (!@extension_loaded('gd')) ? 'disabled="disabled"' : '',
		'L_AUTH_MEMBERS' => $LANG['auth_members'],
		'L_AUTH_READ_MEMBERS' => $LANG['auth_read_members'],
		'L_AUTH_READ_MEMBERS_EXPLAIN' => $LANG['auth_read_members_explain'],
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
		'L_ACTIV_AUTO_RESIZING_AVATAR' => $LANG['enable_auto_resizing_avatar'],
		'L_ACTIV_AUTO_RESIZING_AVATAR_EXPLAIN' => $LANG['enable_auto_resizing_avatar_explain'],
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
	
	$template->display(); 
}

require_once('../admin/admin_footer.php');

?>