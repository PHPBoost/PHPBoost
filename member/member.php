<?php
/*##################################################
 *                                member.php
 *                            -------------------
 *   begin                : August 04 2005
 *   copyright            : (C) 2005 Viarre Régis
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
define('TITLE', $LANG['member_area']);

$edit_get = retrieve(GET, 'edit', false);
$id_get = retrieve(GET, 'id', 0, TUNSIGNED_INT);
$view_get = retrieve(GET, 'view', 0);

if (!empty($view_get) || !empty($edit_get))
{
	if ($User->check_level(MEMBER_LEVEL))
		$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
	
	$title_mbr = !empty($edit_get) ? $LANG['profile_edition'] : '';
	$Bread_crumb->add($title_mbr, '');
}
else
	$Bread_crumb->add($LANG['member'], url('member.php', ''));

require_once('../kernel/header.php');

$show_group = retrieve(GET, 'g', 0);
$post_group = retrieve(GET, 'show_group', 0);
$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');

if (!empty($id_get)) //Espace membre
{
	$Template->set_filenames(array(
		'member'=> 'member/member.tpl'
	));
	
	if ($edit_get && $User->get_attribute('user_id') === $id_get && ($User->check_level(MEMBER_LEVEL))) //Edition du profil
	{
		//Update profil
		$row = $Sql->query_array(DB_TABLE_MEMBER, 'user_lang', 'user_theme', 'user_mail', 'user_local', 'user_web', 'user_occupation', 'user_hobbies', 'user_avatar', 'user_show_mail', 'user_editor', 'user_timezone', 'user_sex', 'user_born', 'user_sign', 'user_desc', 'user_msn', 'user_yahoo', "WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
		
		$user_born = '';
		$array_user_born = explode('-', $row['user_born']);
		$date_birth = explode('/', $LANG['date_birth_parse']);
		for ($i = 0; $i < 3; $i++)
		{
			if ($date_birth[$i] == 'DD')
			{
				$user_born .= $array_user_born[2 - $i];
				$born_day = $array_user_born[2 - $i];
			}
			elseif ($date_birth[$i] == 'MM')
			{
				$user_born .= $array_user_born[2 - $i];
				$born_month = $array_user_born[2 - $i];
			}
			elseif ($date_birth[$i] == 'YYYY')
			{
				$user_born .= $array_user_born[2 - $i];
				$born_year = $array_user_born[2 - $i];
			}
			$user_born .= ($i != 2) ? '/' : '';
		}
		
		$user_sex = '';
		if (!empty($row['user_sex']))
			$user_sex = ($row['user_sex'] == 1) ? 'man.png' : 'woman.png';
	
		$Template->assign_vars(array(
			'C_USER_UPDATE_PROFIL' => true,
			'USER_THEME' => $row['user_theme'],
			'USER_LOGIN' => $User->get_attribute('login'),
			'MAIL' => $row['user_mail'],
			'LOCAL' => $row['user_local'],
			'WEB' => $row['user_web'],
			'OCCUPATION' => $row['user_occupation'],
			'HOBBIES' => $row['user_hobbies'],
			'USER_AVATAR' => (!empty($row['user_avatar'])) ? '<img src="' . $row['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>',
			'SHOW_MAIL_CHECKED' => ($row['user_show_mail'] == 0) ? 'checked="checked"' : '',
			'USER_BORN' => $user_born,
			'BORN_DAY' => $born_day,
			'BORN_MONTH' => $born_month,
			'BORN_YEAR' => $born_year,
			'USER_SEX' => !empty($user_sex) ? '<img src="../templates/' . get_utheme() . '/images/' . $user_sex . '" alt="" />' : '',
			'USER_SIGN' => FormatingHelper::unparse($row['user_sign']),
			'USER_SIGN_EDITOR' => display_editor('user_sign'),
			'USER_DESC' => FormatingHelper::unparse($row['user_desc']),
			'USER_DESC_EDITOR' => display_editor('user_desc'),
			'USER_MSN' => $row['user_msn'],
			'USER_YAHOO' => $row['user_yahoo'],
			'U_USER_ACTION_UPDATE' => url('.php?id=' . $User->get_attribute('user_id') . '&amp;token=' . $Session->get_token(), '-' . $User->get_attribute('user_id') . '.php?token=' . $Session->get_token()),
			'L_REQUIRE_MAIL' => $LANG['require_mail'],
			'L_MAIL_INVALID' => $LANG['e_mail_invalid'],
			'L_MAIL_AUTH' => $LANG['e_mail_auth'],
			'L_PASSWORD_SAME' => $LANG['e_pass_same'],
			'L_PASSWORD_HOW' => $LANG['password_how'],
			'L_USER_AREA' => $LANG['member_area'],
			'L_PROFIL_EDIT' => $LANG['profile_edition'],
			'L_REQUIRE' => $LANG['require'],
			'L_MAIL' => $LANG['mail'],
			'L_VALID' => $LANG['valid'],
			'L_PREVIOUS_PASS' => $LANG['previous_password'],
			'L_EDIT_JUST_IF_MODIF' => $LANG['fill_only_if_modified'],
			'L_NEW_PASS' => $LANG['new_password'],
			'L_CONFIRM_PASS' => $LANG['confirm_password'],
			'L_DEL_USER' => $LANG['del_member'],
			'L_LANG_CHOOSE' => $LANG['choose_lang'],
			'L_OPTIONS' => $LANG['options'],
			'L_THEME_CHOOSE' => $LANG['choose_theme'],
			'L_EDITOR_CHOOSE' => $LANG['choose_editor'],
			'L_TIMEZONE_CHOOSE' => $LANG['timezone_choose'],
			'L_TIMEZONE_CHOOSE_EXPLAIN' => $LANG['timezone_choose_explain'],
			'L_HIDE_MAIL' => $LANG['hide_mail'],
			'L_HIDE_MAIL_WHO' => $LANG['hide_mail_who'],
			'L_INFO' => $LANG['info'],
			'L_SITE_WEB' => $LANG['web_site'],
			'L_LOCALISATION' => $LANG['localisation'],
			'L_JOB' => $LANG['job'],
			'L_HOBBIES' => $LANG['hobbies'],
			'L_SEX' => $LANG['sex'],
			'L_DATE_OF_BIRTH' => $LANG['date_of_birth'],
			'L_DATE_FORMAT' => $LANG['date_birth_format'],
			'L_BIOGRAPHY' => $LANG['biography'],
			'L_YEARS_OLD' => $LANG['years_old'],
			'L_SIGN' => $LANG['sign'],
			'L_SIGN_WHERE' => $LANG['sign_where'],
			'L_CONTACT' => $LANG['contact'],
			'L_AVATAR_MANAGEMENT' => $LANG['avatar_gestion'],
			'L_CURRENT_AVATAR' => $LANG['current_avatar'],
			'L_WEIGHT_MAX' => $LANG['weight_max'],
			'L_HEIGHT_MAX' => $LANG['height_max'],
			'L_WIDTH_MAX' => $LANG['width_max'],
			'L_UPLOAD_AVATAR' => $LANG['upload_avatar'],
			'L_UPLOAD_AVATAR_WHERE' => $LANG['upload_avatar_where'],
			'L_AVATAR_LINK' => $LANG['avatar_link'],
			'L_AVATAR_LINK_WHERE' => $LANG['avatar_link_where'],
			'L_AVATAR_DEL' => $LANG['avatar_del'],
			'L_UNIT_PX' => $LANG['unit_pixels'],
			'L_UNIT_KO' => $LANG['unit_kilobytes'],
			'L_UPDATE' => $LANG['update'],
			'L_RESET' => $LANG['reset']
		));
		
		//Gestion langue par défaut.
		$array_identifier = '';
		$lang_identifier = '../images/stats/other.png';
		$ulang = get_ulang();
		foreach($LANGS_CONFIG as $lang => $array_info)
		{
			if ($User->check_level($array_info['secure']))
			{
				$info_lang = load_ini_file('../lang/', $lang);
				$selected = '';
				if ($ulang == $lang)
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
		$Template->assign_vars(array(
			'JS_LANG_IDENTIFIER' => $array_identifier,
			'IMG_LANG_IDENTIFIER' => $lang_identifier
		));
		$user_account_config = UserAccountsConfig::load();
		
		//Gestion thème par défaut.
		if (!$user_account_config->is_users_theme_forced()) //Thèmes aux membres autorisés.
		{
			$utheme = get_utheme();
			foreach($THEME_CONFIG as $theme => $array_info)
			{
				if ($CONFIG['theme'] == $theme || ($User->check_level($array_info['secure']) && $theme != 'default'))
				{
					$selected = ($utheme == $theme) ? ' selected="selected"' : '';
					$info_theme = load_ini_file('../templates/' . $theme . '/config/', get_ulang());
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
				'NAME' => !empty($theme_info['name']) ? $theme_info['name'] : $CONFIG['theme'],
				'IDNAME' => $CONFIG['theme']
			));
		}
		
		//Editeur texte par défaut.
		$editors = array('bbcode' => 'BBCode', 'tinymce' => 'Tinymce');
		$select_editors = '';
		foreach ($editors as $code => $name)
		{
			$selected = ($code == $row['user_editor']) ? 'selected="selected"' : '';
			$select_editors .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
		}
		$Template->assign_block_vars('select_editor', array(
			'SELECT_EDITORS' => $select_editors
		));
		
		//Gestion fuseau horaire par défaut.
		$select_timezone = '';
		for ($i = -12; $i <= 14; $i++)
		{
			$selected = ($i == $row['user_timezone']) ? 'selected="selected"' : '';
			$name = (!empty($i) ? ($i > 0 ? ' + ' . $i : ' - ' . -$i) : '');
			$select_timezone .= '<option value="' . $i . '" ' . $selected . '> [GMT' . $name . ']</option>';
		}
		$Template->assign_block_vars('select_timezone', array(
			'SELECT_TIMEZONE' => $select_timezone
		));
		
		//Sex par défaut
		$array_sex = array('--', $LANG['male'], $LANG['female']);
		$i = 0;
		foreach ($array_sex as $value_sex)
		{
			$selected = ($i == $row['user_sex']) ? 'selected="selected"' : '';

			$Template->assign_block_vars('select_sex', array(
				'SEX' => '<option value="' . $i . '" ' . $selected . '>' . $value_sex . '</option>'
			));
			
			$i++;
		}
		
		//Autorisation d'uploader un avatar sur le serveur.
		if ($user_account_config->is_avatar_upload_enabled())
		{
			$Template->assign_vars(array(
				'C_UPLOAD_AVATAR' => true,
				'WEIGHT_MAX' => $user_account_config->get_max_avatar_weight(),
				'HEIGHT_MAX' => $user_account_config->get_max_avatar_height(),
				'WIDTH_MAX' => $user_account_config->get_max_avatar_width()
			));
		}
		
		//Champs supplémentaires.
		$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE display = 1", __LINE__, __FILE__);
		if ($extend_field_exist > 0)
		{
			$Template->assign_vars(array(
				'C_PROFIL_MISCELLANEOUS' => true,
				'L_MISCELLANEOUS' => $LANG['miscellaneous']
			));

			$result = $Sql->query_while("SELECT exc.name, exc.contents, exc.field, exc.required, exc.field_name, exc.possible_values, exc.default_values, ex.*
			FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
			LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $id_get . "'
			WHERE exc.display = 1
			ORDER BY exc.class", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				// field: 0 => base de données, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
				$field = '';
				$row[$row['field_name']] = !empty($row[$row['field_name']]) ? $row[$row['field_name']] : $row['default_values'];
				switch ($row['field'])
				{
					case 1:
					$field = '<label><input type="text" size="30" id="' . $row['field_name'] . '" name="' . $row['field_name'] . '" class="text" value="' . $row[$row['field_name']] . '" /></label>';
					break;
					case 2:
					$field = '<label><textarea class="post" rows="4" cols="27" id="' . $row['field_name'] . '" name="' . $row['field_name'] . '"> ' . FormatingHelper::unparse($row[$row['field_name']]) . '</textarea></label>';
					break;
					case 3:
					$field = '<label><select name="' . $row['field_name'] . '" id="' . $row['field_name'] . '">';
					$array_values = explode('|', $row['possible_values']);
					$i = 0;
					foreach ($array_values as $values)
					{
						$selected = ($values == $row[$row['field_name']]) ? 'selected="selected"' : '';
						$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
						$i++;
					}
					$field .= '</select></label>';
					break;
					case 4:
					$field = '<label><select name="' . $row['field_name'] . '[]" multiple="multiple" id="' . $row['field_name'] . '">';
					$array_values = explode('|', $row['possible_values']);
					$array_default_values = explode('|', $row[$row['field_name']]);
					$i = 0;
					foreach ($array_values as $values)
					{
						$selected = in_array($values, $array_default_values) ? 'selected="selected"' : '';
						$field .= '<option name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $selected . '/> ' . ucfirst($values) . '</option>';
						$i++;
					}
					$field .= '</select></label>';
					break;
					case 5:
					$array_values = explode('|', $row['possible_values']);
					foreach ($array_values as $values)
					{
						$checked = ($values == $row[$row['field_name']]) ? 'checked="checked"' : '';
						$field .= '<label><input type="radio" name="' . $row['field_name'] . '" value="' . $values . '" id="' . $row['field_name'] . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
					}
					break;
					case 6:
					$array_values = explode('|', $row['possible_values']);
					$array_default_values = explode('|', $row[$row['field_name']]);
					$i = 0;
					foreach ($array_values as $values)
					{
						$checked = in_array($values, $array_default_values) ? 'checked="checked"' : '';
						$field .= '<label><input type="checkbox" name="' . $row['field_name'] . '_' . $i . '" value="' . $values . '" ' . $checked . '/> ' . ucfirst($values) . '</label><br />';
						$i++;
					}
					break;
				}
				
				if ($row['required'])
				{	
					$Template->assign_block_vars('miscellaneous_js_list', array(
						'L_REQUIRED' => sprintf($LANG['required_field'], ucfirst($row['name'])),
						'ID' => $row['field_name']
					));
				}
				
				$Template->assign_block_vars('miscellaneous_list', array(
					'NAME' => $row['required'] ? '* ' . ucfirst($row['name']) : ucfirst($row['name']),
					'ID' => $row['field_name'],
					'DESC' => !empty($row['contents']) ? ucfirst($row['contents']) : '',
					'FIELD' => $field
				));
			}
			$Sql->query_close($result);
		}
		
		//Gestion des erreurs.
		switch ($get_error)
		{
			case 'pass_mini':
			$errstr = $LANG['e_pass_mini'];
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
			case 'auth_mail':
			$errstr = $LANG['e_mail_auth'];
			break;
			default:
			$errstr = '';
		}
		if (!empty($errstr))
			$Errorh->handler($errstr, E_USER_NOTICE);

		if (isset($LANG[$get_l_error]))
			$Errorh->handler($LANG[$get_l_error], E_USER_WARNING);
	}
	elseif (!empty($_POST['valid']) && ($User->get_attribute('user_id') === $id_get) && ($User->check_level(MEMBER_LEVEL))) //Update du profil
	{
		$check_pass = !empty($_POST['pass']) ? true : false;
		$check_pass_bis = !empty($_POST['pass_bis']) ? true : false;
		
		//Changement de password
		if ($check_pass && $check_pass_bis)
		{
			$password_old_hash = !empty($_POST['pass_old']) ? strhash($_POST['pass_old']) : '';
			$password = retrieve(POST, 'pass', '', TSTRING_UNCHANGE);
			$password_hash = !empty($password) ? strhash($password) : '';
			$password_bis = retrieve(POST, 'pass_bis', '', TSTRING_UNCHANGE);
			$password_bis_hash = !empty($password_bis) ? strhash($password_bis) : '';
			$password_old_bdd = $Sql->query("SELECT password FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $User->get_attribute('user_id') . "'",  __LINE__, __FILE__);
			
			if (!empty($password_old_hash) && !empty($password_hash) && !empty($password_bis_hash))
			{
				if ($password_old_hash === $password_old_bdd && $password_hash === $password_bis_hash)
				{
					if (strlen($password) >= 6 && strlen($password_bis) >= 6)
					{
						$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET password = '" . $password_hash . "' WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
					}
					else //Longueur minimale du password
						AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=pass_mini') . '#errorh');
				}
				else //Password non identiques.
					AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=pass_same') . '#errorh');
			}
		}
		
		if (!empty($_POST['del_member'])) //Suppression du compte
		{
			$Sql->query_inject("DELETE FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			
			Uploads::Empty_folder_member($User->get_attribute('user_id')); //Suppression de tout les fichiers et dossiers du membre.

			//On régénère le cache
			$Cache->Generate_file('stats');
		}
		
		//Mise à jour du reste de la config.
		$user_mail = strtolower($_POST['mail']); //Mail en minuscule.
		if (check_mail($user_mail))
		{
			$user_lang = retrieve(POST, 'user_lang', '');
			$user_theme = retrieve(POST, 'user_theme', '');
			$user_editor = retrieve(POST, 'user_editor', '');
			$user_timezone = retrieve(POST, 'user_timezone', '');
			
			$user_show_mail = !empty($_POST['user_show_mail']) ? '0' : '1';
			$user_local = retrieve(POST, 'user_local', '');
			$user_occupation =  retrieve(POST, 'user_occupation', '');
			$user_hobbies = retrieve(POST, 'user_hobbies', '');
			$user_desc = retrieve(POST, 'user_desc', '', TSTRING_PARSE);
			$user_sex = retrieve(POST, 'user_sex', 0);
			$user_sign = retrieve(POST, 'user_sign', '', TSTRING_PARSE);
			$user_msn = retrieve(POST, 'user_msn', '');
			$user_yahoo = retrieve(POST, 'user_yahoo', '');
			
			$user_web = retrieve(POST, 'user_web', '');
			if (!empty($user_web) && substr($user_web, 0, 7) != 'http://' && substr($user_web, 0, 6) != 'ftp://' && substr($user_web, 0, 8) != 'https://')
				$user_web = 'http://' . $user_web;
				
			//Gestion de la date de naissance.
			$user_born = strtodate($_POST['user_born'], $LANG['date_birth_parse']);
		
			//Gestion de la suppression de l'avatar.
			if (!empty($_POST['delete_avatar']))
			{
				$user_avatar_path = $Sql->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
				if (!empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match))
				{
					if (is_file($user_avatar_path) && isset($match[1]))
						@unlink('../images/avatars/' . $match[1]);
				}
				
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_avatar = '' WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
			}

			$user_account_config = UserAccountsConfig::load();
			
			//Gestion upload d'avatar.
			$user_avatar = '';
			if ($user_account_config->is_avatar_upload_enabled())
			{
				$dir = '../images/avatars/';
				$Upload = new Upload($dir);
			
				$Upload->file('avatars', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i', Upload::UNIQ_NAME, $user_account_config->get_max_avatar_weight() * 1024);
				if ($Upload->get_size() > 0)
				{
					if ($Upload->get_error() != '') //Erreur, on arrête ici
					{
						AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&erroru=' . $Upload->get_error()) . '#errorh');
					}
					else
					{
						$path = $dir . $Upload->get_filename();
						$error = $Upload->check_img($user_account_config->get_max_avatar_width(), $user_account_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
						if (!empty($error)) //Erreur, on arrête ici
							AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&erroru=' . $error) . '#errorh');
						else
						{
							//Suppression de l'ancien avatar (sur le serveur) si il existe!
							$user_avatar_path = $Sql->query("SELECT user_avatar FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
							if (!empty($user_avatar_path) && preg_match('`\.\./images/avatars/(([a-z0-9()_-])+\.([a-z]){3,4})`i', $user_avatar_path, $match))
							{
								if (is_file($user_avatar_path) && isset($match[1]))
									@unlink('../images/avatars/' . $match[1]);
							}
							$user_avatar = $path; //Avatar uploadé et validé
						}
					}
				}
			}
			
			if (!empty($_POST['avatar']))
			{
				$path = strprotect($_POST['avatar']);
				$error = Util::check_img_dimension($user_account_config->get_max_avatar_width(), $user_account_config->get_max_avatar_height(), Upload::DELETE_ON_ERROR);
				if (!empty($error)) //Erreur, on arrête ici
					AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&erroru=' . $error) . '#errorh');
				else
					$user_avatar = $path; //Avatar posté et validé.
			}
			$user_avatar = !empty($user_avatar) ? " user_avatar = '" . $user_avatar . "', " : '';
			
			if (!empty($user_mail))
			{
				$check_mail = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $user_mail . "' AND login <> '" . addslashes($User->get_attribute('login')) . "'", __LINE__, __FILE__);
				$user_mail = "user_mail = '" . $user_mail . "', ";
				if ($check_mail >= 1) //Autre utilisateur avec le même mail!
					AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=auth_mail') . '#errorh');
				
				//Suppression des images des stats concernant les membres, si l'info a été modifiée.
				$info_mbr = $Sql->query_array(DB_TABLE_MEMBER, "user_theme", "user_sex", "WHERE user_id = '" . numeric($User->get_attribute('user_id')) . "'", __LINE__, __FILE__);
				if ($info_mbr['user_sex'] != $user_sex)
					@unlink('../cache/sex.png');
				if ($info_mbr['user_theme'] != $user_theme)
					@unlink('../cache/theme.png');
				
				$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER . " SET user_lang = '" . $user_lang . "', user_theme = '" . $user_theme . "',
				" . $user_mail . "user_show_mail = '" . $user_show_mail . "', user_editor = '" . $user_editor . "', user_timezone = '" . $user_timezone . "', user_local = '" . $user_local . "',
				" . $user_avatar . "user_msn = '" . $user_msn . "', user_yahoo = '" . $user_yahoo . "',
				user_web = '" . $user_web . "', user_occupation = '" . $user_occupation . "', user_hobbies = '" . $user_hobbies . "',
				user_desc = '" . $user_desc . "', user_sex = '" . $user_sex . "', user_born = '" . $user_born . "',
				user_sign = '" . $user_sign . "' WHERE user_id = '" . numeric($User->get_attribute('user_id')) . "'", __LINE__, __FILE__);
				
				//Champs supplémentaires.
				$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE display = 1", __LINE__, __FILE__);
				if ($extend_field_exist > 0)
				{
					$req_update = '';
					$req_field = '';
					$req_insert = '';
					$result = $Sql->query_while("SELECT field_name, field, possible_values, regex, required
					FROM " . PREFIX . "member_extend_cat
					WHERE display = 1", __LINE__, __FILE__);
					while ($row = $Sql->fetch_assoc($result))
					{
						$field = isset($_POST[$row['field_name']]) ? $_POST[$row['field_name']] : '';
						//Champs requis, si vide redirection.
						if ($row['required'] && $row['field'] != 6 && empty($field))
							AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=incomplete') . '#errorh');
				
						//Validation par expressions régulières.
						if (is_numeric($row['regex']) && $row['regex'] >= 1 && $row['regex'] <= 5)
						{
							$array_regex = array(
								1 => '`^[0-9]+$`',
								2 => '`^[a-z]+$`',
								3 => '`^[a-z0-9]+$`',
								4 => '`^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-zA-Z]{2,4}$`',
								5 => '`^http(s)?://[a-z0-9._/-]+\.[-[:alnum:]]+\.[a-zA-Z]{2,4}(.*)$`'
							);
							$row['regex'] = $array_regex[$row['regex']];
						}
						
						$valid_field = true;
						if (!empty($row['regex']) && $row['field'] <= 2)
						{
							if (@preg_match($row['regex'], trim($field)))
								$valid_field = true;
							else
								$valid_field = false;
						}
						
						if ($row['field'] == 2)
							$field = FormatingHelper::strparse($field);
						elseif ($row['field'] == 4)
						{
							$array_field = is_array($field) ? $field : array();
							$field = '';
							foreach ($array_field as $value)
								$field .= strprotect($value) . '|';
						}
						elseif ($row['field'] == 6)
						{
							$field = '';
							$i = 0;
							$array_possible_values = explode('|', $row['possible_values']);
							foreach ($array_possible_values as $value)
							{
								$field .= !empty($_POST[$row['field_name'] . '_' . $i]) ? strprotect($value) . '|' : '';
								$i++;
							}
							if ($row['required'] && empty($field))
								AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=incomplete') . '#errorh');
						}
						else
							$field = strprotect($field);
							
						if (!empty($field))
						{
							if ($valid_field) //Validation par expression régulière si présente.
							{
								$req_update .= $row['field_name'] . ' = \'' . trim($field, '|') . '\', ';
								$req_field .= $row['field_name'] . ', ';
								$req_insert .= '\'' . trim($field, '|') . '\', ';
							}
						}
					}
					$Sql->query_close($result);
					
					$check_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND . " WHERE user_id = '" . numeric($User->get_attribute('user_id')) . "'", __LINE__, __FILE__);
					if ($check_member)
					{
						if (!empty($req_update))
							$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND . " SET " . trim($req_update, ', ') . " WHERE user_id = '" . numeric($User->get_attribute('user_id')) . "'", __LINE__, __FILE__);
					}
					else
					{
						if (!empty($req_insert))
							$Sql->query_inject("INSERT INTO " . DB_TABLE_MEMBER_EXTEND . " (user_id, " . trim($req_field, ', ') . ") VALUES ('" . numeric($User->get_attribute('user_id')) . "', " . trim($req_insert, ', ') . ")", __LINE__, __FILE__);
					}
				}
				
				AppContext::get_response()->redirect('/member/member' . url('.php?id=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php', '&'));
			}
			else
				AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=incomplete') . '#errorh');
		}
		else
			AppContext::get_response()->redirect('/member/member' . url('.php?id=' .  $id_get . '&edit=1&error=invalid_mail') . '#errorh');
	}
	elseif (!empty($view_get) && $User->get_attribute('user_id') === $id_get && ($User->check_level(MEMBER_LEVEL))) //Zone membre
	{
		//Info membre
		$msg_mbr = FormatingHelper::second_parse(UserAccountsConfig::load()->get_welcome_message());
	
		//Chargement de la configuration.
		$Cache->load('uploads');

		//Droit d'accès?.
		$is_auth_files = $User->check_auth($CONFIG_UPLOADS['auth_files'], AUTH_FILES);
	
		$Template->assign_vars(array(
			'C_USER_INDEX' => true,
			'C_IS_MODERATOR' => $User->get_attribute('level') >= MODERATOR_LEVEL,
			'SID' => SID,
			'LANG' => get_ulang(),
			'COLSPAN' => $is_auth_files ? 3 : 2,
			'USER_NAME' => $User->get_attribute('login'),
			'PM' => $User->get_attribute('user_pm'),
			'IMG_PM' => ($User->get_attribute('user_pm') > 0) ? 'new_pm.gif' : 'pm.png',
			'MSG_MBR' => $msg_mbr,
			'U_USER_ID' => url('.php?id=' . $User->get_attribute('user_id') . '&amp;edit=true'),
			'U_USER_PM' => url('.php?pm=' . $User->get_attribute('user_id'), '-' . $User->get_attribute('user_id') . '.php'),
			'U_CONTRIBUTION_PANEL' => url('contribution_panel.php'),
			'U_MODERATION_PANEL' => url('moderation_panel.php'),
			'L_PROFIL' => $LANG['profile'],
			'L_WELCOME' => $LANG['welcome'],
			'L_PROFIL_EDIT' => $LANG['profile_edition'],
			'L_FILES_MANAGEMENT' => $LANG['files_management'],
			'L_PRIVATE_MESSAGE' => $LANG['private_message'],
			'L_CONTRIBUTION_PANEL' => $LANG['contribution_panel'],
			'L_MODERATION_PANEL' => $LANG['moderation_panel']
		));
		
		//Affichage du lien vers l'interface des fichiers.
		if ($is_auth_files)
		{
			$Template->assign_vars(array(
				'C_USER_AUTH_FILES' => true
			));
		}
	}
	else  //Profil public du membre.
	{
		$row = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'level', 'login', 'user_groups', 'user_mail', 'user_local', 'user_web', 'user_occupation', 'user_hobbies', 'user_avatar', 'user_show_mail', 'timestamp', 'user_sex', 'user_born', 'user_sign', 'user_desc', 'user_msn', 'user_msg', 'user_yahoo', 'last_connect', 'user_ban', 'user_warning', "WHERE user_id = '" . $id_get . "' AND user_aprob = 1", __LINE__, __FILE__);
		$user_born = $Sql->query("SELECT " . $Sql->date_diff('user_born') . " FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
		
		if (empty($row['user_id'])) //Vérification de l'existance du membre.
		{
			if ($User->check_level(ADMIN_LEVEL))
			{	
				$check_member = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $id_get . "'", __LINE__, __FILE__);
				if ($check_member)
					AppContext::get_response()->redirect('/admin/admin_members.php?id=' . $id_get);
				else
					$Errorh->handler('e_auth', E_USER_REDIRECT);
			}
			else
				$Errorh->handler('e_auth', E_USER_REDIRECT);
		}

		//Dernière connexion, si vide => date d'enregistrement du membre.
		$row['last_connect'] = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp'];
	
		$user_mail = ($row['user_show_mail'] == 1) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;';
		
		$user_web = !empty($row['user_web']) ? '<a href="' . $row['user_web'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_web'] . '" title="' . $row['user_web'] . '" /></a>' : '&nbsp;';
		$user_avatar = !empty($row['user_avatar']) ? '<img src="' . $row['user_avatar'] . '" alt="" />' : '<em>' . $LANG['no_avatar'] . '</em>';
		
		$user_sex = !empty($row['user_sex']) ? $row['user_sex'] : '&nbsp;';
		switch ($user_sex)
		{
			case 0:
			$user_sex = '&nbsp;';
			break;
			case 1:
			$user_sex = $LANG['male'] . ' <img src="../templates/' . get_utheme() . '/images/man.png" alt="" class="valign_middle" />';
			break;
			case 2:
			$user_sex = $LANG['female'] . ' <img src="../templates/' . get_utheme() . '/images/woman.png" alt="" class="valign_middle" />';
			break;
			default:
			$user_sex = '&nbsp;';
		}
		switch ($row['level'])
		{
			case 0:
			$user_rank = $LANG['member'];
			break;
			case 1:
			$user_rank = $LANG['modo'];
			break;
			case 2:
			$user_rank = $LANG['admin'];
			break;
		}
		
		//Liste des groupes du membre.
		$user_group_list = '';
		$user_groups = explode('|', $row['user_groups']);
		$i = 0;
		foreach ($user_groups as $key => $group_id)
		{
			$group = $Sql->query_array(DB_TABLE_GROUP, 'id', 'name', 'img', "WHERE id = '" . numeric($group_id) . "'", __LINE__, __FILE__);
			if (!empty($group['id']))
				$user_group_list .= '<li><a href="member' . url('.php?g=' . $group_id, '-0.php?g=' . $group_id) . '">' . (!empty($group['img']) ? '<img src="../images/group/' . $group['img'] . '" alt="' . $group['name'] . '" title="' . $group['name'] . '" class="valign_middle" />'  : $group['name']) . '</a></li>';
		}
		$user_group_list = !empty($user_group_list) ? '<ul style="list-style-type:none;">' . $user_group_list . '</ul>' : $LANG['member'];
		
		//Droit d'édition du profil, au membre en question et à l'admin uniquement	.
		$Template->assign_vars(array(
			'C_USER_PROFIL_EDIT' => ($User->get_attribute('user_id') === $id_get || $User->check_level(ADMIN_LEVEL)) ? true : false,
			'C_PROFIL_USER_VIEW' => true,
			'SID' => SID,
			'LANG' => get_ulang(),
			'USER_NAME' => $row['login'],
			'MAIL' => $user_mail,
			'STATUT' => ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
			'USER_AVATAR' => $user_avatar,
			'USER_MSG' => $row['user_msg'],
			'USER_GROUPS_LIST' => $user_group_list,
			'LOCAL' => !empty($row['user_local']) ? $row['user_local'] : '&nbsp;',
			'WEB' => $user_web,
			'OCCUPATION' => !empty($row['user_occupation']) ? $row['user_occupation'] : '&nbsp;',
			'HOBBIES' => !empty($row['user_hobbies']) ? $row['user_hobbies'] : '&nbsp;',
			'USER_SEX' =>  $user_sex,
			'USER_AGE' => ($row['user_born'] != '0000-00-00' && $user_born > 0 && $user_born < 125 ) ? $user_born . ' ' . $LANG['years_old'] : $LANG['unknow'],
			'USER_DESC' => !empty($row['user_desc']) ? FormatingHelper::second_parse($row['user_desc']) : '&nbsp;',
			'USER_MSN' => !empty($row['user_msn']) ? $row['user_msn'] : '&nbsp;',
			'USER_YAHOO' => !empty($row['user_yahoo']) ? $row['user_yahoo'] : '&nbsp;',
			'L_PROFIL' => $LANG['profile'],
			'L_PROFIL_EDIT' => $LANG['profile_edition'],
			'L_AVATAR' => $LANG['avatar'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_STATUT' => $LANG['status'],
			'L_GROUPS' => $LANG['groups'],
			'L_REGISTERED' => $LANG['registered_on'],
			'L_LAST_CONNECT' => $LANG['last_connect'],
			'L_NBR_MSG' => $LANG['nbr_message'],
			'L_DISPLAY_USER_MSG' => $LANG['member_msg_display'],
			'L_WEB_SITE' => $LANG['web_site'],
			'L_LOCALISATION' => $LANG['localisation'],
			'L_JOB' => $LANG['job'],
			'L_HOBBIES' => $LANG['hobbies'],
			'L_SEX' => $LANG['sex'],
			'L_AGE' => $LANG['age'],
			'L_BIOGRAPHY' => $LANG['biography'],
			'L_CONTACT' => $LANG['contact'],
			'L_MAIL' => $LANG['mail'],
			'L_PRIVATE_MESSAGE' => $LANG['private_message'],
			'U_USER_SCRIPT' => ($User->get_attribute('user_id') === $id_get) ? ('../member/member' . url('.php?id=' . $User->get_attribute('user_id') . '&amp;edit=1')) : ('../admin/admin_members.php?id=' . $id_get . '&amp;edit=1'),
			'U_USER_MSG' => url('.php?id=' . $id_get),
			'U_USER_PM' => url('.php?pm=' . $id_get, '-' . $id_get . '.php')
		));
				
		//Champs supplémentaires.
		$extend_field_exist = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE display = 1", __LINE__, __FILE__);
		if ($extend_field_exist > 0)
		{
			$Template->assign_vars(array(
				'C_PROFIL_MISCELLANEOUS' => true,
				'L_MISCELLANEOUS' => $LANG['miscellaneous']
			));

			$result = $Sql->query_while("SELECT exc.name, exc.contents, exc.field, exc.field_name, exc.possible_values, exc.default_values, ex.*
			FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " exc
			LEFT JOIN " . DB_TABLE_MEMBER_EXTEND . " ex ON ex.user_id = '" . $id_get . "'
			WHERE exc.display = 1
			ORDER BY exc.class", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				// field: 0 => base de données, 1 => text, 2 => textarea, 3 => select, 4 => select multiple, 5=> radio, 6 => checkbox
				$field = '';
				$row[$row['field_name']] = !empty($row[$row['field_name']]) ? $row[$row['field_name']] : $row['default_values'];
				switch ($row['field'])
				{
					case 1:
						$field = $row[$row['field_name']];
					break;
					case 2:
						$field = FormatingHelper::second_parse($row[$row['field_name']]);
					break;
					case 3:
						$field = $row[$row['field_name']];
					break;
					case 4:
						$field = implode(', ', explode('|', $row[$row['field_name']]));
					break;
					case 5:
						$field = $row[$row['field_name']];
					break;
					case 6:
						$field = implode(', ', explode('|', $row[$row['field_name']]));
					break;
				}
				
				$Template->assign_block_vars('miscellaneous_list', array(
					'NAME' => ucfirst($row['name']),
					'DESC' => !empty($row['contents']) ? $row['contents'] : '',
					'FIELD' => $field
				));
			}
			$Sql->query_close($result);
		}
	}

	$Template->pparse('member');
}
elseif (!empty($show_group) || !empty($post_group)) //Vue du groupe.
{
	$user_group = !empty($show_group) ? $show_group : $post_group;
	
	$Template->set_filenames(array(
		'member'=> 'member/member.tpl'
	));
	
	$group = $Sql->query_array(DB_TABLE_GROUP, 'id', 'name', 'img', "WHERE id = '" . $user_group . "'", __LINE__, __FILE__);
	if (empty($group['id'])) //Groupe inexistant.
		AppContext::get_response()->redirect('/member/member.php');
		
	$Template->assign_vars(array(
		'SID' => SID,
		'C_GROUP_LIST' => true,
		'ADMIN_GROUPS' => ($User->check_level(ADMIN_LEVEL)) ? '<a href="../admin/admin_groups.php?id=' . $user_group . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt ="" class="valign_middle" /></a>' : '',
		'GROUP_NAME' => $group['name'],
		'L_BACK' => $LANG['back'],
		'L_SELECT_GROUP' => $LANG['select_group'],
		'L_LIST' => $LANG['list'],
		'L_SEARCH' => $LANG['search'],
		'L_AVATAR' => $LANG['avatar'],
		'L_LOGIN' => $LANG['pseudo'],
		'L_STATUT' => $LANG['status'],
		'U_SELECT_SHOW_GROUP' => "'member.php?g=' + this.options[this.selectedIndex].value"
	));
		
	//Liste des groupes.
	$result = $Sql->query_while("SELECT id, name
	FROM " . PREFIX . "group", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('group_select', array(
			'OPTION' => '<option value="' . $row['id'] .'">' . $row['name'] . '</option>'
		));
	}
		
	//Liste des membres appartenant au groupe.
	//Liste des membres du groupe.
	$members = $Sql->query("SELECT members FROM " . DB_TABLE_GROUP . " WHERE id = '" . numeric($user_group) . "'", __LINE__, __FILE__);
	$members = explode('|', $members);
	foreach ($members as $key => $user_id)
	{
		$row = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'login', 'level', 'user_avatar', 'user_warning', 'user_ban', "WHERE user_id = '" . numeric($user_id) . "'", __LINE__, __FILE__);
		if (!empty($row['user_id']))
		{
			//Gestion des rangs
			switch ($row['level'])
			{
				case 0:
				$user_rank = $LANG['member'];
				break;
				case 1:
				$user_rank = $LANG['modo'];
				break;
				case 2:
				$user_rank = $LANG['admin'];
				break;
			}
			
			$user_account_config = UserAccountsConfig::load();
			
			//Avatar	.
			$user_avatar = !empty($row['user_avatar']) ? '<img class="valign_middle" src="' . $row['user_avatar'] . '" alt=""	/>' : '';
			if (empty($row['user_avatar']) && $user_account_config->is_default_avatar_enabled())
				$user_avatar = '<img class="valign_middle" src="../templates/' . get_utheme() . '/images/' .  $user_account_config->get_default_avatar_name() . '" alt="" />';
			
			$Template->assign_block_vars('group_list', array(
				'USER_AVATAR' => $user_avatar,
				'USER_RANK' => ($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $LANG['banned'],
				'U_USER' => '<a href="member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>'
			));
		}
	}
	
	$Template->pparse('member');
}
else //Show all member!
{
  	$Template->set_filenames(array(
		'member'=> 'member/member.tpl'
	));
	
	//Recherche d'un member si javascript bloqué.
	$login = retrieve(POST, 'login', '');
	if (!empty($_POST['search_member']) && !empty($login))
	{
		$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login LIKE '%" . $login . "%'", __LINE__, __FILE__);
		if (!empty($user_id))
			AppContext::get_response()->redirect('/member/member' . url('.php?id=' . $user_id, '-' . $user_id . '.php', '&'));
		else
			$login = $LANG['no_result'];
	}

	$Template->assign_vars(array(
		'C_USER_LIST' => true,
		'SID' => SID,
		'LANG' => get_ulang(),
		'LOGIN' => $login,
		'L_REQUIRE_LOGIN' => $LANG['require_pseudo'],
		'L_SELECT_GROUP' => $LANG['select_group'],
		'L_SEARCH_USER' => $LANG['search_member'],
		'L_LIST' => $LANG['list'],
		'L_SEARCH' => $LANG['search'],
		'L_PROFIL' => $LANG['profile'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_MAIL' => $LANG['mail'],
		'L_REGISTERED' => $LANG['registered_on'],
		'L_MESSAGE' => $LANG['message'],
		'L_LAST_CONNECT' => $LANG['last_connect'],
		'L_PRIVATE_MESSAGE' => $LANG['private_message'],
		'U_SELECT_SHOW_GROUP' => "'member.php?g=' + this.options[this.selectedIndex].value",
		'U_USER_ALPHA_TOP' => url('.php?sort=alph&amp;mode=desc', '-0.php?sort=alph&amp;mode=desc'),
		'U_USER_ALPHA_BOTTOM' => url('.php?sort=alph&amp;mode=asc', '-0.php?sort=alph&amp;mode=asc'),
		'U_USER_TIME_TOP' => url('.php?sort=time&amp;mode=desc', '-0.php?sort=time&amp;mode=desc'),
		'U_USER_TIME_BOTTOM' => url('.php?sort=time&amp;mode=asc', '-0.php?sort=time&amp;mode=asc'),
		'U_USER_MSG_TOP' => url('.php?sort=msg&amp;mode=desc', '-0.php?sort=msg&amp;mode=desc'),
		'U_USER_MSG_BOTTOM' => url('.php?sort=msg&amp;mode=asc', '-0.php?sort=msg&amp;mode=asc'),
		'U_USER_LAST_TOP' => url('.php?sort=last&amp;mode=desc', '-0.php?sort=last&amp;mode=desc'),
		'U_USER_LAST_BOTTOM' => url('.php?sort=last&amp;mode=asc', '-0.php?sort=last&amp;mode=asc')
	));
	
	//Liste des groupes.
	$result = $Sql->query_while("SELECT id, name
	FROM " . PREFIX . "group", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
		$Template->assign_block_vars('group_select', array(
			'OPTION' => '<option value="' . $row['id'] .'">' . $row['name'] . '</option>'
		));
	
	$nbr_member = $Sql->count_table(DB_TABLE_MEMBER, __LINE__, __FILE__);
	
	$get_sort = retrieve(GET, 'sort', '', TSTRING_UNCHANGE);
	switch ($get_sort)
	{
		case 'time' :
		$sort = 'timestamp';
		break;
		case 'last' :
		$sort = 'last_connect';
		break;
		case 'msg' :
		$sort = 'user_msg';
		break;
		case 'alph' :
		$sort = 'login';
		break;
		default :
		$sort = 'timestamp';
	}
	
	$get_mode = retrieve(GET, 'mode', '', TSTRING_UNCHANGE);
	$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
	$unget = (!empty($sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	//On crée une pagination si le nombre de membre est trop important.
	
	$Pagination = new DeprecatedPagination();
		
	$Template->assign_vars(array(
		'PAGINATION' => '&nbsp;<strong>' . $LANG['page'] . ' :</strong> ' . $Pagination->display('member' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'p=%d', '-0-%d.php' . $unget), $nbr_member, 'p', 25, 3)
	));

	$result = $Sql->query_while("SELECT user_id, login, user_mail, user_show_mail, timestamp, user_msg, last_connect
	FROM " . PREFIX . "member
	WHERE user_aprob = 1
	ORDER BY " . $sort . " " . $mode .
	$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$user_msg = !empty($row['user_msg']) ? $row['user_msg'] : '0';
		$user_mail = ( $row['user_show_mail'] == 1 ) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail'] . '" /></a>' : '&nbsp;';
		
		$row['last_connect'] = !empty($row['last_connect']) ? $row['last_connect'] : $row['timestamp'];
		
		$Template->assign_block_vars('member_list', array(
			'PSEUDO' => $row['login'],
			'MAIL' => $user_mail,
			'MSG' => $user_msg,
			'LAST_CONNECT' => gmdate_format('date_format_short', $row['last_connect']),
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'U_USER_ID' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'U_USER_PM' => url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php')
		));
	}
	$Sql->query_close($result);
	
	$Template->pparse('member');
}

require_once('../kernel/footer.php');

?>
