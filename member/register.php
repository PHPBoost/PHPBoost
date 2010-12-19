<?php
/*##################################################
 *                                register.php
 *                            -------------------
 *   begin                : August 04 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_register']);
require_once('../kernel/header.php'); 

$user_accounts_config = UserAccountsConfig::load();

if (!$user_accounts_config->is_registration_enabled())
{
	AppContext::get_response()->redirect(Environment::get_home_page());
}

$key = retrieve(GET, 'key', '');
$get_error = retrieve(GET, 'error', '');
$get_erroru = retrieve(GET, 'erroru', '');
$register_valid = retrieve(POST, 'register_valid', '');
$register_confirm = retrieve(POST, 'confirm', '');

$registration_agreement = $user_accounts_config->get_registration_agreement();

if (empty($key))
{
	if (!$User->check_level(MEMBER_LEVEL) && !empty($registration_agreement) && empty($register_confirm) && empty($get_error) && empty($get_erroru))
	{
		$Template->set_filenames(array(
			'register' => 'member/register.tpl'
		));
		
		$Template->put_all(array(
			'C_CONFIRM_REGISTER' => true,
			'L_HAVE_TO_ACCEPT' => !empty($register_valid) ? $LANG['register_have_to_accept'] : '',
			'MSG_REGISTER' => FormatingHelper::second_parse($registration_agreement),
			'L_REGISTER' => $LANG['register'],
			'L_REGISTRATION_TERMS' => $LANG['register_terms'],
			'L_ACCEPT' => $LANG['register_accept'],
			'L_SUBMIT' => $LANG['submit']			
		));	
		
		$Template->pparse('register');
	}
	elseif ($User->check_level(MEMBER_LEVEL) !== true && (!empty($register_confirm) || empty($registration_agreement) || !empty($get_error) || !empty($get_erroru)))
	{
		$Template->set_filenames(array(
			'register' => 'member/register.tpl'
		));
		
		//Gestion des erreurs.
		switch ($get_error)
		{
			case 'verif_code':
			$errstr = $LANG['e_incorrect_verif_code'];
			break;
			case 'lenght_mini':
			$errstr = $LANG['pseudo_how'] . ', ' . $LANG['password_how'];
			break;
			case 'pass_same':
			$errstr = $LANG['e_pass_same'];
			break;
			case 'incomplete':
			$errstr = $LANG['e_incomplete'];
			break;
			case 'invalid_mail':
			$errstr = $LANG['e_mail_invalid'];
			break;
			case 'pseudo_auth':
			$errstr = $LANG['e_pseudo_auth'];
			break;
			case 'mail_auth':
			$errstr = $LANG['e_mail_auth'];
			break;
			default:
			$errstr = '';
		}
		if (!empty($errstr))
			$Errorh->handler($errstr, E_USER_NOTICE);  

		if (isset($LANG[$get_erroru]))
			$Errorh->handler($LANG[$get_erroru], E_USER_WARNING);  
			
		$Template->put_all(array(
			'C_REGISTER' => true
		));	
			
		//Mode d'activation du membre.
		if ($user_accounts_config->get_member_accounts_validation_method() == 1)
		{
			$Template->assign_block_vars('activ_mbr', array(
				'L_ACTIV_MBR' => $LANG['activ_mbr_mail']
			));
		}
		elseif ($user_accounts_config->get_member_accounts_validation_method() == '2')
		{
			$Template->assign_block_vars('activ_mbr', array(
				'L_ACTIV_MBR' => $LANG['activ_mbr_admin']
			));
		}
		
		//Code de vérification, anti-bots.
		
		$Captcha = new Captcha();
		if ($Captcha->is_available() && $user_accounts_config->is_registration_captcha_enabled() == '1')
		{
			$Captcha->set_difficulty($user_accounts_config->get_registration_captcha_difficulty());
			$Template->put_all(array(
				'C_VERIF_CODE' => true,
				'VERIF_CODE' => $Captcha->display_form(),
				'L_REQUIRE_VERIF_CODE' => $Captcha->js_require()
			));		
		}
		
		//Autorisation d'uploader un avatar sur le serveur.
		if ($user_accounts_config->is_avatar_upload_enabled())
		{
			$Template->assign_block_vars('upload_avatar', array(
				'WEIGHT_MAX' => $user_accounts_config->get_max_avatar_weight(),
				'HEIGHT_MAX' => $user_accounts_config->get_max_avatar_height(),
				'WIDTH_MAX' => $user_accounts_config->get_max_avatar_width()
			));
		}		
		
		//Gestion langue par défaut.
		$array_identifier = '';
		$lang_identifier = '../images/stats/other.png';
		$langs_cache = LangsCache::load();
		foreach($langs_cache->get_installed_langs() as $lang => $properties)
		{
			if ($properties['auth'] == -1)
			{
				$info_lang = load_ini_file('../lang/', $lang);
				$selected = '';
				if (UserAccountsConfig::load()->get_default_lang() == $lang)
				{
					$selected = ' selected="selected"';
					$lang_identifier = '../images/stats/countries/' . $info_lang['identifier'] . '.png';
				}
				
				$array_identifier .= 'array_identifier[\'' . $lang . '\'] = \'' . $info_lang['identifier'] . '\';' . "\n";
				$Template->assign_block_vars('select_lang', array(
					'NAME' => !empty($info_lang['name']) ? $info_lang['name'] : $lang,
					'IDNAME' => $lang,
					'SELECTED' => $selected
				));
			}
		}
		
		//Gestion éditeur par défaut.
		$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
		$select_editors = '';
		$default_editor = ContentFormattingConfig::load()->get_default_editor();
		foreach ($editors as $code => $name)
		{
			$selected = ($code == $default_editor) ? 'selected="selected"' : '';
			$select_editors .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
		}
		
		//Gestion fuseau horaire par défaut.
		$select_timezone = '';
		$timezone = GeneralConfig::load()->get_site_timezone();
		for ($i = -12; $i <= 14; $i++)
		{
			$selected = ($i == $timezone) ? 'selected="selected"' : '';
			$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
			$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
		}
		
		$Template->put_all(array(
			'JS_LANG_IDENTIFIER' => $array_identifier,
			'IMG_LANG_IDENTIFIER' => $lang_identifier,
			'SELECT_EDITORS' => $select_editors,
			'SELECT_TIMEZONE' => $select_timezone,
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_PASSWORD' => $LANG['require_password'],
			'L_REGISTER' => $LANG['register'],
			'L_REQUIRE' => $LANG['require'],
			'L_PASSWORD_SAME' => $LANG['e_pass_same'],
			'L_MAIL_INVALID' => $LANG['e_mail_invalid'],
			'L_PSEUDO_AUTH' => $LANG['e_pseudo_auth'],
			'L_MAIL_AUTH' => $LANG['e_mail_auth'],
			'L_MAIL' => $LANG['mail'],
			'L_VALID' => $LANG['valid'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_PSEUDO_HOW' => $LANG['pseudo_how'],
			'L_PASSWORD' => $LANG['password'],
			'L_PASSWORD_HOW' => $LANG['password_how'],
			'L_CONFIRM_PASSWORD' => $LANG['confirm_password'],
			'L_VERIF_CODE' => $LANG['verif_code'],
			'L_VERIF_CODE_EXPLAIN' => $LANG['verif_code_explain'],
			'L_LANG_CHOOSE' => $LANG['choose_lang'],
			'L_OPTIONS' => $LANG['options'],
			'L_THEME_CHOOSE' => $LANG['choose_theme'],
			'L_EDITOR_CHOOSE' => $LANG['choose_editor'],
			'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
			'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
			'L_HIDE_MAIL' => $LANG['hide_mail'],
			'L_HIDE_MAIL_WHO' => $LANG['hide_mail_who'],
			'L_INFO' => $LANG['info'],
			'L_WEB_SITE' => $LANG['web_site'],
			'L_LOCALISATION' => $LANG['localisation'],		
			'L_JOB' => $LANG['job'],
			'L_HOBBIES' => $LANG['hobbies'],
			'L_SEX' => $LANG['sex'],
			'L_MALE' => $LANG['male'],
			'L_FEMALE' => $LANG['female'],
			'L_DATE_OF_BIRTH' => $LANG['date_of_birth'],
			'L_DATE_FORMAT' => $LANG['date_birth_format'],
			'L_SIGN' => $LANG['sign'],
			'L_SIGN_WHERE' => $LANG['sign_where'],
			'L_CONTACT' => $LANG['contact'],
			'L_AVATAR_MANAGEMENT' => $LANG['avatar_gestion'],
			'L_AVATAR_LINK' => $LANG['avatar_link'],
			'L_AVATAR_LINK_WHERE' => $LANG['avatar_link_where'],
			'L_WEIGHT_MAX' => $LANG['weight_max'],
			'L_UPLOAD_AVATAR' => $LANG['upload_avatar'],
			'L_UPLOAD_AVATAR_WHERE' => $LANG['upload_avatar_where'],
			'L_SUBMIT' => $LANG['submit'],		
			'L_PREVIOUS_PASS' => $LANG['previous_password'],
			'L_EDIT_JUST_IF_MODIF' => $LANG['fill_only_if_modified'],
			'L_NEW_PASS' => $LANG['new_password'],
			'L_CONFIRM_PASS' => $LANG['confirm_password'],
			'L_LANG_CHOOSE' => $LANG['choose_lang'],
			'L_HIDE_MAIL' => $LANG['hide_mail'],
			'L_HIDE_MAIL_WHO' => $LANG['hide_mail_who'],
			'L_INFO' => $LANG['info'],
			'L_SITE_WEB' => $LANG['web_site'],
			'L_LOCALISATION' => $LANG['localisation'],
			'L_HEIGHT_MAX' => $LANG['height_max'],
			'L_WIDTH_MAX' => $LANG['width_max']
		));		
		
		//Gestion thème par défaut.
		if (!$user_accounts_config->is_users_theme_forced()) //Thèmes aux membres autorisés.
		{
			foreach(ThemesCache::load()->get_installed_themes() as $theme => $theme_properties)
			{
				if (UserAccountsConfig::load()->get_default_theme() == $theme || ($User->check_auth($theme_properties['auth'], AUTH_THEME) && $theme != 'default'))
				{
					$selected = (UserAccountsConfig::load()->get_default_theme() == $theme) ? ' selected="selected"' : '';
					$info_theme = load_ini_file('../templates/' . $theme . '/config/', UserAccountsConfig::load()->get_default_lang());
					$Template->assign_block_vars('select_theme', array(
						'NAME' => $info_theme['name'],
						'IDNAME' => $theme,
						'SELECTED' => $selected
					));
				}
			}
		}
		else //Thème par défaut forcé.
		{
			$theme_info = load_ini_file('/config/', get_ulang());
			$Template->assign_block_vars('select_theme', array(
				'NAME' => !empty($theme_info['name']) ? $theme_info['name'] : UserAccountsConfig::load()->get_default_theme(),
				'IDNAME' => UserAccountsConfig::load()->get_default_theme()
			));
		}
		
		ExtendFieldMember::display($Template);

		$Template->pparse('register');
	}
	else
		AppContext::get_response()->redirect(Environment::get_home_page());
}
elseif (!empty($key) && $User->check_level(MEMBER_LEVEL) !== true) //Activation du compte membre
{
	$Template->set_filenames(array(
		'register' => 'member/register.tpl'
	));
	
	$Template->put_all(array(
		'C_ACTIVATION_REGISTER' => true
	));	
	
	$check_mbr = $Sql->query("SELECT COUNT(*) as compt FROM " . DB_TABLE_MEMBER . " WHERE activ_pass = '" . $key . "'", __LINE__, __FILE__);
	if ($check_mbr == '1') //Activation du compte.
	{
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_aprob = 1, activ_pass = '' WHERE activ_pass = '" . $key . "'", __LINE__, __FILE__);
		
		$Template->put_all(array(
			'L_REGISTER' => $LANG['register'],
			'L_ACTIVATION_REPORT' => $LANG['activ_mbr_mail_success']
		));	
	}
	else
	{
		$Template->put_all(array(
			'L_REGISTER' => $LANG['register'],
			'L_ACTIVATION_REPORT' => $LANG['activ_mbr_mail_error']
		));	
	}
	
	$Template->pparse('register');
}
else
	AppContext::get_response()->redirect(Environment::get_home_page());

require_once('../kernel/footer.php'); 

?>