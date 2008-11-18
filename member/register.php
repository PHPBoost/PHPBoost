<?php
/*##################################################
 *                                register.php
 *                            -------------------
 *   begin                : August 04 2005
 *   copyright          : (C) 2005 Viarre Régis
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_register']);
require_once('../kernel/header.php'); 

$Cache->load('member');
if (!$CONFIG_MEMBER['activ_register'])
	redirect(get_start_page());

$key = retrieve(GET, 'key', '');
$get_error = retrieve(GET, 'error', '');
$get_erroru = retrieve(GET, 'erroru', '');

if (empty($key))
{
	if (!$User->check_level(MEMBER_LEVEL) && !empty($CONFIG_MEMBER['msg_register']) && empty($_POST['confirm']) && empty($get_error) && empty($get_erroru))
	{
		$Template->set_filenames(array(
			'register' => 'member/register.tpl'
		));
		
		$Template->assign_block_vars('confirm', array(
		));
				
		$Template->assign_vars(array(
			'MSG_REGISTER' => $CONFIG_MEMBER['msg_register'],
			'L_REGISTER' => $LANG['register'],
			'L_REGISTRATION_TERMS' => $LANG['register_terms'],
			'L_ACCEPT' => $LANG['register_accept'],
			'L_SUBMIT' => $LANG['submit']			
		));	
		
		$Template->pparse('register');
	}
	elseif ($User->check_level(MEMBER_LEVEL) !== true && (!empty($_POST['confirm']) || empty($CONFIG_MEMBER['msg_register']) || !empty($get_error) || !empty($get_erroru)))
	{
		$Template->set_filenames(array(
			'register' => 'member/register.tpl'
		));
		
		$Template->assign_block_vars('register', array(
		));
		
		//Gestion des erreurs.
		switch($get_error)
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
			
		//Mode d'activation du membre.
		if ($CONFIG_MEMBER['activ_mbr'] == '1')
		{
			$Template->assign_block_vars('register.activ_mbr', array(
				'L_ACTIV_MBR' => $LANG['activ_mbr_mail']
			));
		}
		elseif ($CONFIG_MEMBER['activ_mbr'] == '2')
		{
			$Template->assign_block_vars('register.activ_mbr', array(
				'L_ACTIV_MBR' => $LANG['activ_mbr_admin']
			));
		}
		
		//Code de vérification, anti-bots.
		include_once('../kernel/framework/util/captcha.class.php');
		$Captcha = new Captcha();
		if ($Captcha->gd_loaded() && $CONFIG_MEMBER['verif_code'] == '1')
		{
			$Captcha->set_difficulty($CONFIG_MEMBER['verif_code_difficulty']);
			$Template->assign_vars(array(
				'C_VERIF_CODE' => true,
				'VERIF_CODE' => $Captcha->display_form(),
				'L_REQUIRE_VERIF_CODE' => $Captcha->js_require()
			));		
		}
		
		//Autorisation d'uploader un avatar sur le serveur.
		if ($CONFIG_MEMBER['activ_up_avatar'] == 1)
		{
			$Template->assign_block_vars('register.upload_avatar', array(
				'WEIGHT_MAX' => $CONFIG_MEMBER['weight_max'],
				'HEIGHT_MAX' => $CONFIG_MEMBER['height_max'],
				'WIDTH_MAX' => $CONFIG_MEMBER['width_max']
			));
		}		
		
		//Gestion langue par défaut.
		$array_identifier = '';
		$lang_identifier = '../images/stats/other.png';
		$result = $Sql->query_while("SELECT lang 
		FROM ".PREFIX."lang
		WHERE activ = 1 AND secure = -1", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{	
			$lang_info = load_ini_file('../lang/', $row['lang']);
			if ($lang_info)
			{
				$lang_name = !empty($lang_info['name']) ? $lang_info['name'] : $row['lang'];
				$array_identifier .= 'array_identifier[\'' . $row['lang'] . '\'] = \'' . $lang_info['identifier'] . '\';' . "\n";
				$selected = '';
				if ($row['lang'] == $CONFIG['lang'])
				{
					$selected = 'selected="selected"';
					$lang_identifier = '../images/stats/countries/' . $lang_info['identifier'] . '.png';
				}			
				$Template->assign_block_vars('register.select', array(
					'LANG' => '<option value="' . $row['lang'] . '" ' . $selected . '>' . $lang_name . '</option>'
				));
			}
		}
		$Sql->query_close($result);
		
		//Gestion éditeur par défaut.
		$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
		$select_editors = '';
		foreach ($editors as $code => $name)
		{
			$selected = ($code == $CONFIG['editor']) ? 'selected="selected"' : '';
			$select_editors .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
		}
		
		//Gestion fuseau horaire par défaut.
		$select_timezone = '';
		for ($i = -12; $i <= 14; $i++)
		{
			$selected = ($i == $CONFIG['timezone']) ? 'selected="selected"' : '';
			$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
			$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
		}
		
		$Template->assign_vars(array(
			'JS_LANG_IDENTIFIER' => $array_identifier,
			'IMG_LANG_IDENTIFIER' => $lang_identifier,
			'SELECT_EDITORS' => $select_editors,
			'SELECT_TIMEZONE' => $select_timezone,
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_PASSWORD' => $LANG['require_password'],
			'L_REGISTER' => $LANG['register'],
			'L_REQUIRE' => $LANG['require'],
			'L_MAIL' => $LANG['mail'],
			'L_VALID' => $LANG['valid'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_PSEUDO_HOW' => $LANG['pseudo_how'],
			'L_PASSWORD' => $LANG['password'],
			'L_PASSWORD_HOW' => $LANG['password_how'],
			'L_CONFIRM_PASSWORD' => $LANG['confirm_pass'],
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
			'L_PREVIOUS_PASS' => $LANG['previous_pass'],
			'L_EDIT_JUST_IF_MODIF' => $LANG['edit_if_modif'],
			'L_NEW_PASS' => $LANG['new_pass'],
			'L_CONFIRM_PASS' => $LANG['confirm_pass'],
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
		if ($CONFIG_MEMBER['force_theme'] == 0) //Thèmes aux membres autorisés.
		{
			$result = $Sql->query_while("SELECT theme 
			FROM ".PREFIX."themes
			WHERE activ = 1 AND secure = -1", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{	
				$theme_info = load_ini_file('../templates/' . $row['theme'] . '/config/', $CONFIG['lang']);
				if ($theme_info)
				{
					$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $row['theme'];
					$selected = ($row['theme'] == $CONFIG['theme']) ? 'selected="selected"' : '';
					$Template->assign_block_vars('register.select_theme', array(
						'THEME' => '<option value="' . $row['theme'] . '" ' . $selected . '>' . $theme_name . '</option>'
					));
				}
			}
			$Sql->query_close($result);	
		}
		else //Thème par défaut forcé.
		{
			$theme_info = load_ini_file('/config/', $CONFIG['lang']);
			$theme_name = !empty($theme_info['name']) ? $theme_info['name'] : $CONFIG['theme'];
			$Template->assign_block_vars('register.select_theme', array(
				'THEME' => '<option value="' . $CONFIG['theme'] . '" selected="selected">' . $theme_name . '</option>'
			));
		}

		//Champs supplémentaires.
		$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."member_extend_cat WHERE display = 1", __LINE__, __FILE__);
		if ($extend_field_exist > 0)
		{
			$Template->assign_vars(array(			
				'L_MISCELLANEOUS' => $LANG['miscellaneous']
			));
			$Template->assign_block_vars('register.miscellaneous', array(			
			));
			$result = $Sql->query_while("SELECT exc.name, exc.contents, exc.field, exc.require, exc.field_name, exc.possible_values, exc.default_values
			FROM ".PREFIX."member_extend_cat AS exc
			WHERE exc.display = 1
			ORDER BY exc.class", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{	
				// field: 0 => base de données, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
				$field = '';
				switch($row['field'])
				{
					case 1:
					$field = '<input type="text" size="30" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '" class="text" value="' .  $row['default_values'] . '" />';
					break;
					case 2:
					$field = '<textarea class="post" rows="4" cols="27" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">' . unparse($row['default_values']) . '</textarea>';
					break;
					case 3:
					$field = '<select name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">';
					$array_values = explode('|', $row['possible_values']);
					$i = 0;
					foreach ($array_values as $values)
					{
						$selected = ($values ==  $row['default_values']) ? 'selected="selected"' : '';
						$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
						$i++;
					}
					$field .= '</select>';
					break;
					case 4:
					$field = '<select name="' . $row['field_name'] . '[]" multiple="multiple" id="' . $row['field_name'] . '">';
					$array_values = explode('|', $row['possible_values']);
					$array_default_values = explode('|', $row['default_values']);
					$i = 0;
					foreach ($array_values as $values)
					{
						$selected = in_array($values, $array_default_values) ? 'selected="selected"' : '';
						$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
						$i++;
					}
					$field .= '</select>';
					break;
					case 5:
					$array_values = explode('|', $row['possible_values']);
					foreach ($array_values as $values)
					{
						$checked = ($values ==  $row['default_values']) ? 'checked="checked"' : '';
						$field .= '<input type="radio" name="' . $row['field_name'] . '" id="' . $row['field_name'] . '" value="' . $values . '" ' . $checked . ' /> ' . ucfirst($values) . '<br />';
					}
					break;
					case 6:
					$array_values = explode('|', $row['possible_values']);
					$array_default_values = explode('|', $row['default_values']);
					$i = 0;
					foreach ($array_values as $values)
					{
						$checked = in_array($values, $array_default_values) ? 'checked="checked"' : '';
						$field .= '<input type="checkbox" name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '<br />';
						$i++;
					}
					break;
				}				
				
				$Template->assign_block_vars('register.miscellaneous.list', array(
					'NAME' => $row['require'] ? '* ' . ucfirst($row['name']) : ucfirst($row['name']),
					'ID' => $row['field_name'],
					'DESC' => !empty($row['contents']) ? ucfirst($row['contents']) : '',
					'FIELD' => $field
				));
			}
			$Sql->query_close($result);	
		}
		
		$Template->pparse('register');
	}
	else
		redirect(get_start_page());
}
elseif (!empty($key) && $User->check_level(MEMBER_LEVEL) !== true) //Activation du compte membre
{
	$Template->set_filenames(array(
		'register' => 'member/register.tpl'
	));
	
	$Template->assign_block_vars('activ', array(
	));	
	
	$check_mbr = $Sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."member WHERE activ_pass = '" . $key . "'", __LINE__, __FILE__);
	if ($check_mbr == '1') //Activation du compte.
	{
		$Sql->query_inject("UPDATE ".PREFIX."member SET user_aprob = 1, activ_pass = '' WHERE activ_pass = '" . $key . "'", __LINE__, __FILE__);
		
		$Template->assign_vars(array(
			'L_REGISTER' => $LANG['register'],
			'L_ACTIVATION_REPORT' => $LANG['activ_mbr_mail_success']
		));	
	}
	else
	{
		$Template->assign_vars(array(
			'L_REGISTER' => $LANG['register'],
			'L_ACTIVATION_REPORT' => $LANG['activ_mbr_mail_error']
		));	
	}
	
	$Template->pparse('register');
}
else
	redirect(get_start_page());

require_once('../kernel/footer.php'); 

?>