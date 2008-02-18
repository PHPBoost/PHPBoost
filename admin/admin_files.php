<?php
/*##################################################
 *                               admin_files.php
 *                            -------------------
 *   begin                : March 06, 2007
 *   copyright          : (C) 2007 Viarre R�gis
 *   email                : crowkait@gmail.com
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

require_once('../includes/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../includes/admin_header.php');

//Initialisation  de la class de gestion des fichiers.
include_once('../includes/files.class.php');
$Files = new Files; 

$folder = !empty($_GET['f']) ? numeric($_GET['f']) : 0;
$folder_member = !empty($_GET['fm']) ? numeric($_GET['fm']) : 0;
$parent_folder = !empty($_GET['fup']) ? numeric($_GET['fup']) : 0;
$home_folder = !empty($_GET['root']) ? true : false;
$del_folder = !empty($_GET['delf']) ? numeric($_GET['delf']) : 0;
$empty_folder = !empty($_GET['eptf']) ? numeric($_GET['eptf']) : 0;
$del_file = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
$get_l_error = !empty($_GET['erroru']) ? trim($_GET['erroru']) : '';
$show_member = !empty($_GET['showm']) ? true : false;
$move_folder = !empty($_GET['movef']) ? numeric($_GET['movef']) : 0;
$move_file = !empty($_GET['movefi']) ? numeric($_GET['movefi']) : 0;
$to = isset($_GET['to']) ? numeric($_GET['to']) : -1;

if( $parent_folder ) //Changement de dossier
{
	$parent_folder = $Sql->Query_array("upload_cat", "id_parent", "user_id", "WHERE id = '" . $parent_folder . "'", __LINE__, __FILE__);

	if( $parent_folder['user_id'] == -1 )
		redirect(HOST . DIR . '/admin/admin_files.php?showm=1');
	else
		redirect(HOST . DIR . '/admin/admin_files.php?f=' . $parent_folder['id_parent']);
}
elseif( $home_folder ) //Retour � la racine.
	redirect(HOST . DIR . '/admin/admin_files.php');
elseif( !empty($_FILES['upload_file']['name']) && isset($_GET['f']) ) //Ajout d'un fichier.
{
	$folder = !empty($_GET['f']) ? numeric($_GET['f']) : 0;
	//Si le dossier n'est pas en �criture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../upload/';
	if( !is_writable($dir) )
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	
	@clearstatcache();
	$error = '';
	if( is_writable($dir) ) //Dossier en �criture, upload possible
	{
		include_once('../includes/upload.class.php');
		$Upload = new Upload($dir);
		$Upload->Upload_file('upload_file', '`([a-z0-9_-])+\.(' . implode('|', array_map('preg_quote', $CONFIG_FILES['auth_extensions'])) . ')+`i', UNIQ_NAME);
		
		if( !empty($Upload->error) ) //Erreur, on arr�te ici
			redirect(HOST . DIR . '/admin/admin_files.php?f=' . $folder . '&erroru=' . $Upload->error . '#errorh');
		else //Insertion dans la bdd
		{
			$check_user_folder = $Sql->Query("SELECT user_id FROM ".PREFIX."upload_cat WHERE id = '" . $folder . "'", __LINE__, __FILE__);
			$user_id = ($check_user_folder <= 0) ? -1 : $Member->Get_attribute('user_id');
			$Sql->Query("INSERT INTO ".PREFIX."upload (idcat, name, path, user_id, size, type, timestamp) VALUES ('" . $folder . "', '" . securit($_FILES['upload_file']['name']) . "', '" . securit($Upload->filename['upload_file']) . "', '" . $user_id . "', '" . numeric(number_round($_FILES['upload_file']['size']/1024, 1), 'float') . "', '" . $Upload->extension['upload_file'] . "', '" . time() . "')", __LINE__, __FILE__);
		}
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '&error=' . $error . '#errorh' : '';
	redirect(HOST . DIR . '/admin/admin_files.php?f=' . $folder . $error);
}
elseif( !empty($del_folder) ) //Supprime un dossier.
{
	//Suppression du dossier et de tout le contenu	
	$Files->Del_folder($del_folder);
	redirect(HOST . DIR . '/admin/admin_files.php?f=' . $folder);
}
elseif( !empty($empty_folder) ) //Vide un dossier membre.
{
	//Suppression de tout les dossiers enfants.
	$Files->Del_folder($empty_folder, EMPTY_FOLDER);

	redirect(HOST . DIR . '/admin/admin_files.php?showm=1');
}
elseif( !empty($del_file) ) //Suppression d'un fichier
{
	//Suppression d'un fichier.
	$Files->Del_file($del_file, -1, ADMIN_NO_CHECK);
	
	redirect(HOST . DIR . '/admin/admin_files.php?f=' . $folder);
}
elseif( !empty($move_folder) && $to != -1 ) //D�placement d'un dossier
{
	$move_list_parent = array();
	$result = $Sql->Query_while("SELECT id, id_parent, name
	FROM ".PREFIX."upload_cat
	WHERE user_id = '" . $Member->Get_attribute('user_id') . "'
	ORDER BY id", __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$move_list_parent[$row['id']] = $row['id_parent'];
	}
	$Sql->Close($result);

	$array_child_folder = array();
	$Files->Find_subfolder($move_list_parent, $move_folder, $array_child_folder);
	if( !in_array($to, $array_child_folder) ) //Dossier de destination non sous-dossier du dossier source.
		$Files->Move_folder($move_folder, $to, $Member->Get_attribute('user_id'), ADMIN_NO_CHECK);
	
	redirect(HOST . DIR . '/admin/admin_files.php?f=' . $to);
}
elseif( !empty($move_file) && $to != -1 ) //D�placement d'un fichier
{
	$Files->Move_file($move_file, $to, $Member->Get_attribute('user_id'), ADMIN_NO_CHECK);
	
	redirect(HOST . DIR . '/admin/admin_files.php?f=' . $to);
}
else
{
	$Template->Set_filenames(array(
		'admin_files_management' => '../templates/' . $CONFIG['theme'] . '/admin/admin_files_management.tpl'
	));

	$sql_request = !empty($folder_member) 
	? 	("SELECT uc.user_id, m.login
		FROM ".PREFIX."upload_cat uc
		LEFT JOIN ".PREFIX."member m ON m.user_id = uc.user_id
		WHERE uc.user_id = '" . $folder_member . "'
		UNION
		SELECT u.user_id, m.login
		FROM ".PREFIX."upload u
		LEFT JOIN ".PREFIX."member m ON m.user_id = u.user_id
		WHERE u.user_id = '2'")
	: 	("SELECT uc.user_id, m.login
		FROM ".PREFIX."upload_cat uc
		LEFT JOIN ".PREFIX."member m ON m.user_id = uc.user_id
		WHERE uc.id = '" . $folder . "'");

	$result = $Sql->Query_while($sql_request, __LINE__, __FILE__);
	$folder_info = $Sql->Sql_fetch_assoc($result);
		
	//Gestion des erreurs.
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_unlink_disabled');
	if( in_array($get_error, $array_error) )
		$Errorh->Error_handler($LANG[$get_error], E_USER_WARNING);
	if( $get_error == 'incomplete' )
		$Errorh->Error_handler($LANG['e_incomplete'], E_USER_NOTICE);  

	if( isset($LANG[$get_l_error]) )
		$Errorh->Error_handler($LANG[$get_l_error], E_USER_WARNING);  

	if( $show_member )
		$url = $Files->Get_admin_url($folder, '/<a href="admin_files.php?showm=1">' . $LANG['member_s'] . '</a>');
	elseif( !empty($folder_member) || !empty($folder_info['user_id']) )
		$url = $Files->Get_admin_url($folder, '', '<a href="admin_files.php?showm=1">' . $LANG['member_s'] . '</a>/<a href="admin_files.php?fm=' . $folder_info['user_id'] . '">' . $folder_info['login'] . '</a>/');
	elseif( empty($folder) )
		$url = '/';	
	else
		$url = $Files->Get_admin_url($folder, '');
		
	$Template->Assign_vars(array(
		'FOLDER_ID' => !empty($folder) ? $folder : '0',
		'USER_ID' => !empty($folder_info['user_id']) ? $folder_info['user_id'] : '-1',
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'URL' => $url,
		'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
		'L_CONFIRM_DEL_FOLDER' => $LANG['confirm_del_folder'],
		'L_CONFIRM_EMPTY_FOLDER' => $LANG['confirm_empty_folder'],
		'L_FOLDER_ALREADY_EXIST' => $LANG['folder_already_exist'],
		'L_FOLDER_FORBIDDEN_CHARS' => $LANG['folder_forbidden_chars'],
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_FILES_ACTION' => $LANG['files_management'],
		'L_CONFIG_FILES' => $LANG['files_config'],
		'L_ADD_FILES' => $LANG['file_add'],
		'L_NAME' => $LANG['name'],
		'L_SIZE' => $LANG['size'],
		'L_MOVETO' => $LANG['moveto'],
		'L_DATA' => $LANG['data'],
		'L_FOLDER_SIZE' => $LANG['folder_size'],
		'L_FOLDERS' => $LANG['folders'],
		'L_ROOT' => $LANG['root'],
		'L_FOLDER_NEW' => $LANG['folder_new'],
		'L_FOLDER_UP' => $LANG['folders_up'],
		'L_FILES' => $LANG['files'],
		'L_DELETE' => $LANG['delete'],
		'L_EMPTY' => $LANG['empty'],
		'L_UPLOAD' => $LANG['upload'],
		'L_URL' => $LANG['url']
	));

	if( $folder == 0 && !$show_member && empty($folder_member) )
	{	
		$Template->Assign_block_vars('folder', array(
			'NAME' => '<a class="com" href="admin_files.php?showm=1">' . $LANG['member_s'] . '</a>',
			'IMG_FOLDER' => 'member_max.png',
			'L_TYPE_DEL_FOLDER' => $LANG['empty_member_folder']
		));
	}
	
	list($total_folder_size, $total_files, $total_directories) = array(0, 0, 0);

	$sql_files = "SELECT up.id, up.name, up.path, up.size, up.type, up.timestamp, m.user_id, m.login
	FROM ".PREFIX."upload up
	LEFT JOIN ".PREFIX."member m ON m.user_id = up.user_id
	WHERE idcat = '" . $folder . "'" . ((empty($folder) || $folder_info['user_id'] <= 0) ? ' AND up.user_id = -1' : ' AND up.user_id != -1');
	
	if( $show_member )
		$sql_folder = "SELECT uc.user_id as id, uc.user_id, m.login as name, 0 as id_parent
		FROM ".PREFIX."upload_cat uc
		LEFT JOIN ".PREFIX."member m ON m.user_id = uc.user_id
		WHERE uc.id_parent = '" . $folder . "' AND uc.user_id != -1 
		UNION
		SELECT u.user_id as id, u.user_id, m.login as name, 0 as id_parent
		FROM ".PREFIX."upload u
		LEFT JOIN ".PREFIX."member m ON m.user_id = u.user_id
		ORDER BY name
		";
	elseif( !empty($folder_member) )
	{	
		$sql_folder = "SELECT id, name, id_parent, user_id
		FROM ".PREFIX."upload_cat 
		WHERE id_parent = 0 AND user_id = '" . $folder_member . "'
		ORDER BY name";
		$sql_files = "SELECT up.id, up.name, up.path, up.size, up.type, up.timestamp, m.user_id, m.login
		FROM ".PREFIX."upload up
		LEFT JOIN ".PREFIX."member m ON m.user_id = up.user_id
		WHERE up.idcat = 0 AND up.user_id = '" . $folder_member . "'";
	}
	else
		$sql_folder = "SELECT id, name, id_parent, user_id
		FROM ".PREFIX."upload_cat 
		WHERE id_parent = '" . $folder . "'" . ((empty($folder) || $folder_info['user_id'] <= 0) ? ' AND user_id = -1' : ' AND user_id != -1') . "
		ORDER BY name";

	//Affichage des dossiers
	$result = $Sql->Query_while($sql_folder, __LINE__, __FILE__);
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		$name_cut = (strlen(html_entity_decode($row['name'])) > 22) ? htmlentities(substr(html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];	
		
		$Template->Assign_block_vars('folder', array(
			'ID' => $row['id'],
			'NAME' => $name_cut,
			'IMG_FOLDER' => $show_member ? 'member_max.png' : 'folder_max.png',
			'RENAME_FOLDER' => !$show_member ? '<span id="fhref' . $row['id'] . '"><a href="javascript:display_rename_folder(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" style="vertical-align:middle;" /></a></span>' : '',
			'DEL_TYPE' => $show_member ? 'eptf' : 'delf',
			'DEL_TYPE_IMG' => $show_member ? '<img src="../templates/' . $CONFIG['theme'] . '/images/upload/trash_mini.png" alt="" class="valign_middle" />' : '<img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/delete.png" alt="" class="valign_middle" />',
			'ALERT_DEL' => $show_member ? 'member' : 'folder',
			'MOVE' => !$show_member ? '<a href="javascript:upload_display_block(' . $row['id'] . ');" onmouseover="upload_hide_block(' . $row['id'] . ', 1);" onmouseout="upload_hide_block(' . $row['id'] . ', 0);" class="bbcode_hover" title="' . $LANG['moveto'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/upload/move.png" alt="" style="vertical-align:middle;" /></a>' : '',
			'U_ONCHANGE_FOLDER' => "'admin_files" . transid(".php?movef=" . $row['id'] . "&amp;to=' + this.options[this.selectedIndex].value + '") . "'",
			'L_TYPE_DEL_FOLDER' => $LANG['del_folder'],
			'U_FOLDER' => '?' . ($show_member ? 'fm=' . $row['user_id'] : 'f=' . $row['id']),
			'U_MOVE' => '.php?movefd=' . $row['id']
		));
		$total_directories++;
	}	
	$Sql->Close($result);
		
	if( !$show_member ) //Dossier membres.
	{
		//Affichage des fichiers contenu dans le dossier
		$result = $Sql->Query_while($sql_files, __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$name_cut = (strlen(html_entity_decode($row['name'])) > 22) ? htmlentities(substr(html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];
		
			$get_img_mimetype = $Files->Get_img_mimetype($row['type']);
			$size_img = '';
			switch($row['type'])
			{
				//Images
				case 'jpg':
				case 'png':
				case 'gif':
				case 'bmp':
				list($width_source, $height_source) = @getimagesize('../upload/' . $row['path']);
				$size_img = ' (' . $width_source . 'x' . $height_source . ')';
				$width_source = !empty($width_source) ? $width_source + 30 : 0;
				$height_source = !empty($height_source) ? $height_source + 30 : 0;
				$bbcode = '[img]../upload/' . $row['path'] . '[/img]';
				$link = '<a class="com" href="javascript:popup_upload(\'' . $row['id'] . '\', ' . $width_source . ', ' . $height_source . ', \'yes\')">';
				break;
				//Sons
				case 'mp3':
				$bbcode = '[sound]../upload/' . $row['path'] . '[/sound]';
				$link = '<a class="com" href="javascript:popup_upload(\'' . $row['id'] . '\', 220, 10, \'no\')">';
				break;
				default:
				$bbcode = '[url=../upload/' . $row['path'] . ']' . $row['name'] . '[/url]';				
				$link = '<a class="com" href="../upload/' . $row['path'] . '">';
			}
			
			$Template->Assign_block_vars('files', array(
				'ID' => $row['id'],
				'IMG' => '<img src="../templates/' . $CONFIG['theme'] . '/images/upload/' . $get_img_mimetype['img'] . '" alt="" />',
				'URL' => $link,
				'NAME' => $name_cut,
				'RENAME_FILE' => '<span id="fihref' . $row['id'] . '"><a href="javascript:display_rename_file(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . $LANG['edit'] . '"><img src="../templates/' . $CONFIG['theme'] . '/images/' . $CONFIG['lang'] . '/edit.png" alt="" style="vertical-align:middle;" /></a></span>',
				'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
				'BBCODE' => '<input size="25" type="text" class="text" onClick="select_div(\'text_' . $row['id'] . '\');" id="text_' . $row['id'] . '" style="margin-top:2px;cursor:pointer;" value="' . $bbcode . '" />',
				'SIZE' => ($row['size'] > 1024) ? number_round($row['size']/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($row['size'], 0) . ' ' . $LANG['unit_kilobytes'],
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'LOGIN' => '<a href="../member/member.php?id=' . $row['user_id'] . '">' . $row['login'] . '</a>',
				'U_MOVE' => '.php?movefi=' . $row['id']
				
			));
			
			$total_folder_size += $row['size'];
			$total_files++;
		}	
		$Sql->Close($result);
	}
	

	$total_size = $Sql->Query("SELECT SUM(size) FROM ".PREFIX."upload", __LINE__, __FILE__);
	$Template->Assign_vars(array(
		'TOTAL_SIZE' => ($total_size > 1024) ? number_round($total_size/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($total_size, 0) . ' ' . $LANG['unit_kilobytes'],
		'TOTAL_FOLDER_SIZE' => ($total_folder_size > 1024) ? number_round($total_folder_size/1024, 2) . ' ' . $LANG['unit_megabytes'] : number_round($total_folder_size, 0) . ' ' . $LANG['unit_kilobytes'],
		'TOTAL_FOLDERS' => $total_directories,
		'TOTAL_FILES' => $total_files
	));


	if( $total_directories == 0 && $total_files == 0 && (!empty($folder) || !empty($show_member)) )
		$Template->Assign_block_vars('empty_folder', array(
			'EMPTY_FOLDER' => $LANG['empty_folder']
		));
		
	$Template->Pparse('admin_files_management');
}
	
require_once('../includes/admin_footer.php');

?>