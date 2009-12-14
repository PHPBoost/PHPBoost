<?php
/*##################################################
 *                               upload.php
 *                            -------------------
 *   begin                : July, 07 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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
define('TITLE', $LANG['files_management']);

$popup = retrieve(GET, 'popup', '');
if (!empty($popup)) //Popup.
{
	require_once('../kernel/header_no_display.php');
	$field = retrieve(GET, 'fd', '');
	
	$header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $LANG['xml_lang'] . '" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"  />
<meta http-equiv="Content-Style-Type" content="text/css" />

<title>' . $LANG['files_management'] . '</title>
<link rel="stylesheet" href="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/design.css" type="text/css" media="screen" />
<link rel="stylesheet" href="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
<link rel="stylesheet" href="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/global.css" type="text/css" media="screen, print, handheld" />
<link rel="stylesheet" href="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/content.css" type="text/css" media="screen, print, handheld" />
<link rel="stylesheet" href="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/generic.css" type="text/css" media="screen, print, handheld" />
<link rel="stylesheet" href="' . PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/bbcode.css" type="text/css" media="screen, print, handheld" />
<script type="text/javascript">
<!--
	var PATH_TO_ROOT = "' . PATH_TO_ROOT . '";
	var TOKEN = "' . $Session->get_token() . '";
-->
</script>
<script type="text/javascript" src="' . PATH_TO_ROOT . '/kernel/framework/js/global.js"></script>
<script type="text/javascript" src="' . PATH_TO_ROOT . '/kernel/framework/js/bbcode.js"></script>
</head>

<body>';
	$footer = '<fieldset class="fieldset_submit" style="width:96%;margin:auto;">
			<legend>' . $LANG['close'] . '</legend>
			<input type="button" class="reset" onclick="javascript:close_popup()" value="' . $LANG['close'] . '" />
		</fieldset>
	</body>
</html>';
	$popup = '&amp;popup=1&amp;fd=' . $field;
	$popup_noamp = '&popup=1&fd=' . $field;
}
else //Affichage de l'interface de gestion.
{
	$Bread_crumb->add($LANG['member_area'], url('member.php?id=' . $User->get_attribute('user_id') . '&amp;view=1', 'member-' . $User->get_attribute('user_id') . '.php?view=1'));
	$Bread_crumb->add($LANG['files_management'], url('upload.php'));
	require_once('../kernel/header.php');
	$field = '';
	$header = '';
	$footer = '';
	$popup = '';
	$popup_noamp = '';
}

if (!$User->check_level(MEMBER_LEVEL)) //Visiteurs interdits!
	$Errorh->handler('e_auth', E_USER_REDIRECT);

//Chargement de la configuration.
$Cache->load('uploads');

//Droit d'accès?.
if (!$User->check_auth($CONFIG_UPLOADS['auth_files'], AUTH_FILES))
	$Errorh->handler('e_auth', E_USER_REDIRECT);



$folder = retrieve(GET, 'f', 0);
$parent_folder = retrieve(GET, 'fup', 0);
$home_folder = retrieve(GET, 'root', false);
$del_folder = retrieve(GET, 'delf', 0);
$del_file = retrieve(GET, 'del', 0);
$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');
$move_folder = retrieve(GET, 'movefd', 0);
$move_file = retrieve(GET, 'movefi', 0);
$to = retrieve(POST, 'new_cat', -1);

if (!empty($parent_folder)) //Changement de dossier
{
	if (empty($parent_folder))
		redirect(HOST . DIR . url('/member/upload.php?f=0&' . $popup_noamp, '', '&'));
	
	$info_folder = $Sql->query_array(PREFIX . "upload_cat", "id_parent", "user_id", "WHERE id = '" . $parent_folder . "'", __LINE__, __FILE__);
	if ($info_folder['id_parent'] != 0 || $User->check_level(ADMIN_LEVEL))
	{
		if ($parent_folder['user_id'] == -1)
			redirect(HOST . DIR . url('/member/upload.php?showm=1', '', '&'));
		else
			redirect(HOST . DIR . url('/member/upload.php?f=' . $info_folder['id_parent'] . '&' . $popup_noamp, '', '&'));
	}
	else
		redirect(HOST . DIR . url('/member/upload.php?f=' . $parent_folder . '&' . $popup_noamp, '', '&'));
}
elseif ($home_folder) //Retour à la racine.
	redirect(HOST . DIR . url('/member/upload.php?' . $popup_noamp, '', '&'));
elseif (!empty($_FILES['upload_file']['name']) && isset($_GET['f'])) //Ajout d'un fichier.
{
	$error = '';
	//Autorisation d'upload aux groupes.
	$group_limit = $User->check_max_value(DATA_GROUP_LIMIT, $CONFIG_UPLOADS['size_limit']);
	$unlimited_data = ($group_limit === -1) || $User->check_level(ADMIN_LEVEL);
	
	$member_memory_used = Uploads::Member_memory_used($User->get_attribute('user_id'));
	if ($member_memory_used >= $group_limit && !$unlimited_data)
		$error = 'e_max_data_reach';
	else
	{
		//Si le dossier n'est pas en écriture on tente un CHMOD 777
		@clearstatcache();
		$dir = '../upload/';
		if (!is_writable($dir))
			$is_writable = (@chmod($dir, 0777)) ? true : false;
		
		@clearstatcache();
		if (is_writable($dir)) //Dossier en écriture, upload possible
		{
			$weight_max = $unlimited_data ? 100000000 : ($group_limit - $member_memory_used);
			
			$Upload = new Upload($dir);
			$Upload->file('upload_file', '`([a-z0-9()_-])+\.(' . implode('|', array_map('preg_quote', $CONFIG_UPLOADS['auth_extensions'])) . ')+$`i', Upload::UNIQ_NAME, $weight_max);
			
			if ($Upload->get_error() != '') //Erreur, on arrête ici
			{
				$error = $Upload->get_error();
				if ($Upload->get_error() == 'e_upload_max_weight')
					$error = 'e_max_data_reach';
				redirect('/member/upload.php?f=' . $folder . '&erroru=' . $error . '&' . $popup_noamp . '#errorh');
			}
			else //Insertion dans la bdd
			{
				$Sql->query_inject("INSERT INTO " . DB_TABLE_UPLOAD . " (idcat, name, path, user_id, size, type, timestamp) VALUES ('" . $folder . "', '" . addslashes($Upload->get_original_filename()) . "', '" . addslashes($Upload->get_filename()) . "', '" . $User->get_attribute('user_id') . "', '" . $Upload->get_human_readable_size() . "', '" . $Upload->get_extension() . "', '" . time() . "')", __LINE__, __FILE__);
			}
		}
		else
			$error = 'e_upload_failed_unwritable';
	}
	
	$error = !empty($error) ? '&error=' . $error . '&' . $popup_noamp . '#errorh' : '&' . $popup_noamp;
	redirect(HOST . DIR . url('/member/upload.php?f=' . $folder . $error, '', '&'));
}
elseif (!empty($del_folder)) //Supprime un dossier.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	if ($User->check_level(ADMIN_LEVEL))
		Uploads::Del_folder($del_folder);
	else
	{
		$check_user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $del_folder . "'", __LINE__, __FILE__);
		//Suppression du dossier et de tout le contenu
		if ($check_user_id == $User->get_attribute('user_id'))
			Uploads::Del_folder($del_folder);
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	
	redirect(HOST . DIR . url('/member/upload.php?f=' . $folder . '&' . $popup_noamp, '', '&'));
}
elseif (!empty($del_file)) //Suppression d'un fichier
{
	$Session->csrf_get_protect(); //Protection csrf
	
	if ($User->check_level(ADMIN_LEVEL))
		Uploads::Del_file($del_file, $User->get_attribute('user_id'), Uploads::ADMIN_NO_CHECK);
	else
	{
		$error = Uploads::Del_file($del_file, $User->get_attribute('user_id'));
		if (!empty($error))
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	
	redirect(HOST . DIR . url('/member/upload.php?f=' . $folder . '&' . $popup_noamp, '', '&'));
}
elseif (!empty($move_folder) && $to != -1) //Déplacement d'un dossier
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$folder_owner = $Sql->query("SELECT user_id FROM ".DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $move_folder . "'", __LINE__, __FILE__);
	
	if ($folder_owner == $User->get_attribute('user_id'))
	{
		include('upload_functions.php');
		$sub_cats = array();
		upload_find_subcats($sub_cats, $move_folder, $User->get_attribute('user_id'));
		$sub_cats[] = $move_folder;
		//Si on ne déplace pas le dossier dans un de ses fils ou dans lui même
		if (!in_array($to, $sub_cats))
		{
			$new_folder_owner = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $to . "'", __LINE__, __FILE__);
			if ($new_folder_owner == $User->get_attribute('user_id') || $to == 0)
			{
				$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD_CAT . " SET id_parent = '" . $to . "' WHERE id = '" . $move_folder . "'", __LINE__, __FILE__);
				redirect(HOST . DIR . url('/member/upload.php?f=' . $to . '&' . $popup_noamp, '', '&'));
			}
		}
		else
			redirect(HOST . DIR . url('/member/upload.php?movefd=' . $move_folder . '&f=0&error=folder_contains_folder&' . $popup_noamp, '', '&'));
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif (!empty($move_file) && $to != -1) //Déplacement d'un fichier
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$file_infos = $Sql->query_array(PREFIX . "upload", "idcat", "user_id", "WHERE id = '" . $move_file . "'", __LINE__, __FILE__);
	$id_cat = $file_infos['idcat'];
	$file_owner = $file_infos['user_id'];
	//Si le fichier nous appartient alors on peut en faire ce que l'on veut
	if ($file_owner == $User->get_attribute('user_id'))
	{
		$new_folder_owner = $Sql->query("SELECT user_id FROM " . DB_TABLE_UPLOAD_CAT . " WHERE id = '" . $to . "'", __LINE__, __FILE__);
		//Si le dossier de destination nous appartient
		if ($new_folder_owner == $User->get_attribute('user_id') || $to == 0)
		{
			$Sql->query_inject("UPDATE " . DB_TABLE_UPLOAD . " SET idcat = '" . $to . "' WHERE id = '" . $move_file . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . url('/member/upload.php?f=' . $to . '&' . $popup_noamp, '', '&'));
		}
		else
			$Errorh->handler('e_auth', E_USER_REDIRECT);
	}
	else
		$Errorh->handler('e_auth', E_USER_REDIRECT);
}
elseif (!empty($move_folder) || !empty($move_file))
{
	$Template->set_filenames(array(
		'upload_move'=> 'member/upload_move.tpl'
	));
	
	$Template->assign_vars(array(
		'POPUP' => $popup,
		'HEADER' => $header,
		'FOOTER' => $footer,
		'FIELD' => $field,
		'LANG' => get_ulang(),
		'FOLDER_ID' => !empty($folder) ? $folder : '0',
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'URL' => '' . trim(Uploads::get_url($folder, '', '&amp;' . $popup), '/'),
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_MOVE_TO' => $LANG['moveto'],
		'L_ROOT' => $LANG['root'],
		'L_URL' => $LANG['url'],
		'L_SUBMIT' => $LANG['submit'],
		'U_ROOT' => '<a href="upload.php?' . $popup . '">' . $User->get_attribute('login') . '</a>/'
	));
	
	if ($get_error == 'folder_contains_folder')
		$Errorh->handler($LANG['upload_folder_contains_folder'], E_USER_WARNING);
	
	//liste des fichiers disponibles
	include_once('upload_functions.php');
	$cats = array();
	
	$is_folder = !empty($move_folder);
	//Affichage du dossier/fichier à déplacer
	if ($is_folder)
	{
		$folder_info = $Sql->query_array(PREFIX . "upload_cat", "name", "id_parent", "WHERE id = '" . $move_folder . "'", __LINE__, __FILE__);
		$name = $folder_info['name'];
		$id_cat = $folder_info['id_parent'];
		$Template->assign_block_vars('folder', array(
			'NAME' => $name
		));
		$Template->assign_vars(array(
			'SELECTED_CAT' => $id_cat,
			'ID_FILE' => $move_folder,
			'TARGET' => url('upload.php?movefd=' . $move_folder . '&amp;f=0&amp;token=' . $Session->get_token() . $popup)
		));
		$cat_explorer = display_cat_explorer($id_cat, $cats, 1, $User->get_attribute('user_id'));
	}
	else
	{
		$info_move = $Sql->query_array(PREFIX . "upload", "path", "name", "type", "size", "idcat", "WHERE id = '" . $move_file . "'", __LINE__, __FILE__);
		$get_img_mimetype = Uploads::get_img_mimetype($info_move['type']);
		$size_img = '';
		$display_real_img = false;
		switch ($info_move['type'])
		{
			//Images
			case 'jpg':
			case 'png':
			case 'gif':
			case 'bmp':
			list($width_source, $height_source) = @getimagesize('../upload/' . $info_move['path']);
			$size_img = ' (' . $width_source . 'x' . $height_source . ')';
			
			//On affiche l'image réelle si elle n'est pas trop grande.
			if ($width_source < 350 && $height_source < 350) 
			{
				$display_real_img = true;
			}
		}
		
		$cat_explorer = display_cat_explorer($info_move['idcat'], $cats, 1, $User->get_attribute('user_id'));
		
		$Template->assign_block_vars('file', array(
			'C_DISPLAY_REAL_IMG' => $display_real_img,	
			'NAME' => $info_move['name'],
			'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
			'SIZE' => ($info_move['size'] > 1024) ? number_round($info_move['size']/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($info_move['size'], 0) . ' ' . $LANG['unit_kilobytes'],
			'FILE_ICON' => $display_real_img ? $info_move['path'] : $get_img_mimetype['img']
		));
		$Template->assign_vars(array(
			'SELECTED_CAT' => $info_move['idcat'],
			'TARGET' => url('upload.php?movefi=' . $move_file . '&amp;f=0&amp;token=' . $Session->get_token() . $popup)
		));
	}
	
	$Template->assign_vars(array(
		'FOLDERS' => $cat_explorer,
		'ID_FILE' => $move_file
	));
	
	$Template->pparse('upload_move');
}
else
{
	$is_admin = $User->check_level(ADMIN_LEVEL);
	
	$Template->set_filenames(array(
		'upload' => 'member/upload.tpl'
	));

	//Gestion des erreurs.
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_unlink_disabled', 'e_max_data_reach');
	if (in_array($get_error, $array_error))
		$Errorh->handler($LANG[$get_error], E_USER_WARNING);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);

	if (isset($LANG[$get_l_error]))
		$Errorh->handler($LANG[$get_l_error], E_USER_WARNING);

	$Template->assign_vars(array(
		'POPUP' => $popup,
		'HEADER' => $header,
		'FOOTER' => $footer,
		'FIELD' => $field,
		'LANG' => get_ulang(),
		'FOLDER_ID' => !empty($folder) ? $folder : '0',
		'USER_ID' => $User->get_attribute('user_id'),
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'URL' => '' . trim(Uploads::get_url($folder, '', '&amp;' . $popup), '/'),
		'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
		'L_CONFIRM_DEL_FOLDER' => $LANG['confirm_del_folder'],
		'L_CONFIRM_EMPTY_FOLDER' => $LANG['confirm_empty_folder'],
		'L_FOLDER_ALREADY_EXIST' => $LANG['folder_already_exist'],
		'L_FOLDER_FORBIDDEN_CHARS' => $LANG['folder_forbidden_chars'],
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_FILES_ACTION' => $LANG['files_management'],
		'L_CONFIG_FILES' => $LANG['files_config'],
		'L_ADD_FILES' => $LANG['file_add'],
		'L_ROOT' => $LANG['root'],
		'L_NAME' => $LANG['name'],
		'L_SIZE' => $LANG['size'],
		'L_MOVETO' => $LANG['moveto'],
		'L_DATA' => $LANG['data'],
		'L_FOLDER_SIZE' => $LANG['folder_size'],
		'L_FOLDERS' => $LANG['folders'],
		'L_FOLDER_NEW' => $LANG['folder_new'],
		'L_FOLDER_UP' => $LANG['folders_up'],
		'L_FILES' => $LANG['files'],
		'L_DELETE' => $LANG['delete'],
		'L_EMPTY' => $LANG['empty'],
		'L_UPLOAD' => $LANG['upload'],
		'L_URL' => $LANG['url'],
		'U_ROOT' => '<a href="upload.php?' . $popup . '">' . $User->get_attribute('login') . '</a>/'
	));
	
	list($total_folder_size, $total_files, $total_directories) = array(0, 0, 0);
	//Affichage des dossiers
	$result = $Sql->query_while("SELECT id, name, id_parent, user_id
	FROM " . PREFIX . "upload_cat
	WHERE id_parent = '" . $folder . "' AND user_id = '" . $User->get_attribute('user_id') . "'
	ORDER BY name", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$name_cut = (strlen(html_entity_decode($row['name'])) > 22) ? htmlentities(substr(html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];
		
		$Template->assign_block_vars('folder', array(
			'ID' => $row['id'],
			'NAME' => $name_cut,
			'RENAME_FOLDER' => '<span id="fhref' . $row['id'] . '"><a href="javascript:display_rename_folder(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a></span>',
			'DEL_TYPE_IMG' => '<img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="" class="valign_middle" />',
			'MOVE' => '<a href="javascript:upload_display_block(' . $row['id'] . ');" onmouseover="upload_hide_block(' . $row['id'] . ', 1);" onmouseout="upload_hide_block(' . $row['id'] . ', 0);" class="bbcode_hover" title="' . $LANG['moveto'] . '"><img src="../templates/' . get_utheme() . '/images/upload/move.png" alt="" class="valign_middle" /></a>',
			'U_MOVE' => url('.php?movefd=' . $row['id'] . '&amp;f=' . $folder . $popup),
			'L_TYPE_DEL_FOLDER' => $LANG['del_folder']
		));
		$total_directories++;
	}
	$Sql->query_close($result);

	//Affichage des fichiers contenu dans le dossier
	$result = $Sql->query_while("SELECT up.id, up.name, up.path, up.size, up.type, up.timestamp, m.user_id, m.login
	FROM " . DB_TABLE_UPLOAD . " up
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = up.user_id
	WHERE up.idcat = '" . $folder . "' AND up.user_id = '" . $User->get_attribute('user_id') . "'
	ORDER BY up.name", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$name_cut = (strlen(html_entity_decode($row['name'])) > 22) ? htmlentities(substr(html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];
		
		$get_img_mimetype = Uploads::get_img_mimetype($row['type']);
		$size_img = '';
		switch ($row['type'])
		{
			//Images
			case 'jpg':
			case 'png':
			case 'gif':
			case 'bmp':
			list($width_source, $height_source) = @getimagesize(PATH_TO_ROOT . '/upload/' . $row['path']);
			$size_img = ' (' . $width_source . 'x' . $height_source . ')';
			$width_source = !empty($width_source) ? $width_source + 30 : 0;
			$height_source = !empty($height_source) ? $height_source + 30 : 0;
			$bbcode = '[img]/upload/' . $row['path'] . '[/img]';
			$link = PATH_TO_ROOT . '/upload/' . $row['path'];
			break;
			//Image svg
			case 'svg':
			$bbcode = '[img]/upload/' . $row['path'] . '[/img]';
			$link = 'javascript:popup_upload(\'' . $row['id'] . '\', 0, 0, \'no\')';
			break;
			//Sons
			case 'mp3':
			$bbcode = '[sound]/upload/' . $row['path'] . '[/sound]';
			$link = 'javascript:popup_upload(\'' . $row['id'] . '\', 220, 10, \'no\')';
			break;
			default:
			$bbcode = '[url=/upload/' . $row['path'] . ']' . $row['name'] . '[/url]';
			$link = PATH_TO_ROOT . '/upload/' . $row['path'];
		}
		
		$Template->assign_block_vars('files', array(
			'ID' => $row['id'],
			'IMG' => $get_img_mimetype['img'],
			'URL' => $link,
			'TITLE' => str_replace('"', '\"', $row['name']),
			'NAME' => $name_cut,
			'RENAME_FILE' => '<span id="fihref' . $row['id'] . '"><a href="javascript:display_rename_file(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="" class="valign_middle" /></a></span>',
			'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
			'BBCODE' => '<input size="25" type="text" class="text" onclick="select_div(\'text_' . $row['id'] . '\');" id="text_' . $row['id'] . '" style="margin-top:2px;cursor:pointer;" value="' . $bbcode . '" />',
			'SIZE' => ($row['size'] > 1024) ? number_round($row['size']/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($row['size'], 0) . ' ' . $LANG['unit_kilobytes'],
			'INSERT' => !empty($popup) ? '<a href="javascript:insert_popup(\'' . addslashes($bbcode) . '\')" title="' . $LANG['popup_insert'] . '"><img src="../templates/' . get_utheme() . '/images/upload/insert.png" alt="" class="valign_middle" /></a>' : '',
			'LIGHTBOX' => !empty($size_img) ? ' rel="lightbox[1]"' : '',
			'U_MOVE' => url('.php?movefi=' . $row['id'] . '&amp;f=' . $folder . $popup)
		));
		
		$total_folder_size += $row['size'];
		$total_files++;
	}
	$Sql->query_close($result);
	
	//Autorisation d'uploader sans limite aux groupes.
	$group_limit = $User->check_max_value(DATA_GROUP_LIMIT, $CONFIG_UPLOADS['size_limit']);
	$unlimited_data = ($group_limit === -1) || $User->check_level(ADMIN_LEVEL);
	
	$total_size = !empty($folder) ? Uploads::Member_memory_used($User->get_attribute('user_id')) : $Sql->query("SELECT SUM(size) FROM " . DB_TABLE_UPLOAD . " WHERE user_id = '" . $User->get_attribute('user_id') . "'", __LINE__, __FILE__);
	$Template->assign_vars(array(
		'PERCENT' => !$unlimited_data ? '(' . number_round($total_size/$group_limit, 3) * 100 . '%)' : '',
		'SIZE_LIMIT' => !$unlimited_data ? (($group_limit > 1024) ? number_round($group_limit/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($group_limit, 0) . ' ' . $LANG['unit_kilobytes']) : $LANG['illimited'],
		'TOTAL_SIZE' => ($total_size > 1024) ? number_round($total_size/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($total_size, 0) . ' ' . $LANG['unit_kilobytes'],
		'TOTAL_FOLDER_SIZE' => ($total_folder_size > 1024) ? number_round($total_folder_size/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($total_folder_size, 0) . ' ' . $LANG['unit_kilobytes'],
		'TOTAL_FOLDERS' => $total_directories,
		'TOTAL_FILES' => $total_files
	));

	if ($total_directories == 0 && $total_files == 0)
	{
		$Template->assign_vars(array(
			'C_EMPTY_FOLDER' => true,
			'L_EMPTY_FOLDER' => $LANG['empty_folder']
		));
	}
	
	$Template->pparse('upload');
}

if (empty($popup))
	require_once('../kernel/footer.php');
else
	require_once('../kernel/footer_no_display.php');
?>
