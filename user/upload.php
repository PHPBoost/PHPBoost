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
define('TITLE', $LANG['files_management']);

$popup = retrieve(GET, 'popup', '');
$editor = retrieve(GET, 'edt', '');
$parse = retrieve(GET, 'parse', '');
$no_path = retrieve(GET, 'no_path', '');
$close_button = retrieve(GET, 'close_button', '');
$display_close_button = false;

if (!empty($popup)) //Popup.
{
	$env = new SiteDisplayFrameGraphicalEnvironment();
	Environment::set_graphical_environment($env);
	ob_start();
	$field = retrieve(GET, 'fd', '');
	
	$display_close_button = $close_button != '0';
	$popup = '&popup=1&fd=' . $field . '&edt=' . $editor . '&parse='. $parse .'&no_path=' . $no_path;
	$popup_noamp = '&popup=1&fd=' . $field . '&edt=' . $editor . '&parse='. $parse .'&no_path=' . $no_path;
}
else //Affichage de l'interface de gestion.
{
	$Bread_crumb->add($LANG['user'], UserUrlBuilder::profile(AppContext::get_current_user()->get_id())->rel());
	$Bread_crumb->add($LANG['files_management'], UserUrlBuilder::upload_files_panel()->rel());
	require_once('../kernel/header.php');
	$field = '';
	$popup = '';
	$popup_noamp = '';
}

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Visiteurs interdits!
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$files_upload_config = FileUploadConfig::load();

//Droit d'accès?.
if (!AppContext::get_current_user()->check_auth($files_upload_config->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT))
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

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
		AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=0&' . $popup_noamp, '', '&'));
	
	try {
		$info_folder = PersistenceContext::get_querier()->select_single_row(DB_TABLE_UPLOAD_CAT, array('id_parent', 'user_id'), 'WHERE id = :id', array('id' => $parent_folder));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	if ($info_folder['id_parent'] != 0 || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
	{
		if ($parent_folder['user_id'] == -1)
			AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?showm=1', '', '&'));
		else
			AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $info_folder['id_parent'] . '&' . $popup_noamp, '', '&'));
	}
	else
		AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $parent_folder . '&' . $popup_noamp, '', '&'));
}
elseif ($home_folder) //Retour à la racine.
	AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?' . $popup_noamp, '', '&'));
elseif (!empty($_FILES['upload_file']['name']) && AppContext::get_request()->has_getparameter('f')) //Ajout d'un fichier.
{
	$error = '';
	//Autorisation d'upload aux groupes.
	$group_limit = AppContext::get_current_user()->check_max_value(DATA_GROUP_LIMIT, $files_upload_config->get_maximum_size_upload());
	$unlimited_data = ($group_limit === -1) || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
	
	$member_memory_used = Uploads::Member_memory_used(AppContext::get_current_user()->get_id());
	if ($member_memory_used >= $group_limit && !$unlimited_data)
		$error = 'e_max_data_reach';
	else
	{
		//Si le dossier n'est pas en écriture on tente un CHMOD 777
		@clearstatcache();
		$dir = PATH_TO_ROOT . '/upload/';
		if (!is_writable($dir))
			$is_writable = (@chmod($dir, 0777));
		
		@clearstatcache();
		if (is_writable($dir)) //Dossier en écriture, upload possible
		{
			$weight_max = $unlimited_data ? 100000000 : ($group_limit - $member_memory_used);
			
			$Upload = new Upload($dir);
			$Upload->file('upload_file', '`([a-z0-9()_-])+\.(' . implode('|', array_map('preg_quote', $files_upload_config->get_authorized_extensions())) . ')+$`i', Upload::UNIQ_NAME, $weight_max);
			
			if ($Upload->get_error() != '') //Erreur, on arrête ici
			{
				$error = $Upload->get_error();
				if ($Upload->get_error() == 'e_upload_max_weight')
					$error = 'e_max_data_reach';
				AppContext::get_response()->redirect('/user/upload.php?f=' . $folder . '&erroru=' . $error . '&' . $popup_noamp . '#message_helper');
			}
			else //Insertion dans la bdd
			{
				$result = PersistenceContext::get_querier()->insert(DB_TABLE_UPLOAD, array('idcat' => $folder, 'name' => $Upload->get_original_filename(), 'path' => $Upload->get_filename(), 'user_id' => AppContext::get_current_user()->get_id(), 'size' => $Upload->get_human_readable_size(), 'type' => $Upload->get_extension(), 'timestamp' => time()));
				$id_file = $result->get_last_inserted_id();
			}
		}
		else
			$error = 'e_upload_failed_unwritable';
	}
	
	$anchor = !empty($error) ? '&error=' . $error . '&' . $popup_noamp . '#message_helper' : '&' . $popup_noamp . (!empty($id_file) ? '#fi1' . $id_file : '');
	AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $folder . $anchor, '', '&'));
}
elseif (!empty($del_folder)) //Supprime un dossier.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		Uploads::Del_folder($del_folder);
	else
	{
		$check_user_id = 0;
		try {
			$check_user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $del_folder));
		} catch (RowNotFoundException $ex) {}
		
		//Suppression du dossier et de tout le contenu
		if ($check_user_id == AppContext::get_current_user()->get_id())
		{
			Uploads::Del_folder($del_folder);
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $folder . '&' . $popup_noamp, '', '&'));
}
elseif (!empty($del_file)) //Suppression d'un fichier
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
	{
		Uploads::Del_file($del_file, AppContext::get_current_user()->get_id(), Uploads::ADMIN_NO_CHECK);
	}
	else
	{
		$error = Uploads::Del_file($del_file, AppContext::get_current_user()->get_id());
		if (!empty($error))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	
	AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $folder . '&' . $popup_noamp, '', '&'));
}
elseif (!empty($move_folder) && $to != -1) //Déplacement d'un dossier
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	$folder_owner = 0;
	try {
		$folder_owner = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $move_folder));
	} catch (RowNotFoundException $ex) {}
	
	if ($folder_owner == AppContext::get_current_user()->get_id())
	{
		include('upload_functions.php');
		$sub_cats = array();
		upload_find_subcats($sub_cats, $move_folder, AppContext::get_current_user()->get_id());
		$sub_cats[] = $move_folder;
		//Si on ne déplace pas le dossier dans un de ses fils ou dans lui même
		if (!in_array($to, $sub_cats))
		{
			if (AppContext::get_current_user()->get_id() || $to == 0)
			{
				PersistenceContext::get_querier()->update(DB_TABLE_UPLOAD_CAT, array('id_parent' => $to), 'WHERE id = :id', array('id' => $move_folder));
				AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $to . '&' . $popup_noamp, '', '&'));
			}
		}
		else
			AppContext::get_response()->redirect(HOST . DIR . url('/userr/upload.php?movefd=' . $move_folder . '&f=0&error=folder_contains_folder&' . $popup_noamp, '', '&'));
	}
	else
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($move_file) && $to != -1) //Déplacement d'un fichier
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf
	
	try {
		$file_infos = PersistenceContext::get_querier()->select_single_row(DB_TABLE_UPLOAD, array('idcat', 'user_id'), 'WHERE id = :id', array('id' => $move_file));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}
	
	$id_cat = $file_infos['idcat'];
	$file_owner = $file_infos['user_id'];
	//Si le fichier nous appartient alors on peut en faire ce que l'on veut
	if ($file_owner == AppContext::get_current_user()->get_id())
	{
		//Si le dossier de destination nous appartient
		if (AppContext::get_current_user()->get_id() || $to == 0)
		{
			PersistenceContext::get_querier()->update(DB_TABLE_UPLOAD, array('idcat' => $to), 'WHERE id = :id', array('id' => $move_file));
			AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $to . '&' . $popup_noamp, '', '&'));
		}
		else
		{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}
	}
	else
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif (!empty($move_folder) || !empty($move_file))
{
	$tpl = new FileTemplate('user/upload_move.tpl');
	
	$tpl->put_all(array(
		'POPUP' => $popup,
		'C_DISPLAY_CLOSE_BUTTON' => $display_close_button,
		'FIELD' => $field,
		'FOLDER_ID' => !empty($folder) ? $folder : '0',
		'URL' => Uploads::get_url($folder, '', '&amp;' . $popup),
		'L_FILES_MANAGEMENT' => $LANG['files_management'],
		'L_MOVE_TO' => $LANG['moveto'],
		'L_ROOT' => $LANG['root'],
		'L_URL' => $LANG['url'],
		'L_SUBMIT' => $LANG['submit'],
		'U_ROOT' => '<a href="upload.php?' . $popup . '">' . AppContext::get_current_user()->get_display_name() . '</a>/'
	));
	
	if ($get_error == 'folder_contains_folder')
		$tpl->put('message_helper', MessageHelper::display($LANG['upload_folder_contains_folder'], MessageHelper::WARNING));
	
	//liste des fichiers disponibles
	include_once('upload_functions.php');
	$cats = array();
	
	$is_folder = !empty($move_folder);
	//Affichage du dossier/fichier à déplacer
	if ($is_folder)
	{
		try {
			$folder_info = PersistenceContext::get_querier()->select_single_row(DB_TABLE_UPLOAD_CAT, array('name', 'id_parent'), 'WHERE id = :id', array('id' => $move_folder));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}
		
		$name = $folder_info['name'];
		$id_cat = $folder_info['id_parent'];
		$tpl->assign_block_vars('folder', array(
			'NAME' => $name
		));
		$tpl->put_all(array(
			'SELECTED_CAT' => $id_cat,
			'ID_FILE' => $move_folder,
			'TARGET' => url('upload.php?movefd=' . $move_folder . '&amp;f=0&amp;token=' . AppContext::get_session()->get_token() . $popup)
		));
		$cat_explorer = display_cat_explorer($id_cat, $cats, 1, AppContext::get_current_user()->get_id());
	}
	else
	{
		try {
			$info_move = PersistenceContext::get_querier()->select_single_row(DB_TABLE_UPLOAD, array('path', 'name', 'type', 'size', 'idcat'), 'WHERE id = :id', array('id' => $move_file));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}
		
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
		
		$cat_explorer = display_cat_explorer($info_move['idcat'], $cats, 1, AppContext::get_current_user()->get_id());
		
		$tpl->assign_block_vars('file', array(
			'C_DISPLAY_REAL_IMG' => $display_real_img,	
			'NAME' => $info_move['name'],
			'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
			'SIZE' => ($info_move['size'] > 1024) ? NumberHelper::round($info_move['size']/1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($info_move['size'], 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common'),
			'FILE_ICON' => $display_real_img ? $info_move['path'] : $get_img_mimetype['img']
		));
		$tpl->put_all(array(
			'SELECTED_CAT' => $info_move['idcat'],
			'TARGET' => url('upload.php?movefi=' . $move_file . '&amp;f=0&amp;token=' . AppContext::get_session()->get_token() . $popup)
		));
	}
	
	$tpl->put_all(array(
		'FOLDERS' => $cat_explorer,
		'ID_FILE' => $move_file
	));
	
	$tpl->display();
}
else
{
	$is_admin = AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
	
	$tpl = new FileTemplate('user/upload.tpl');

	//Gestion des erreurs.
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_unlink_disabled', 'e_max_data_reach');
	if (in_array($get_error, $array_error))
		$tpl->put('message_helper', MessageHelper::display($LANG[$get_error], MessageHelper::WARNING));
	if ($get_error == 'incomplete')
		$tpl->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));

	if (isset($LANG[$get_l_error]))
		$tpl->put('message_helper', MessageHelper::display($LANG[$get_l_error], MessageHelper::WARNING));

	$tpl->put_all(array(
		'POPUP' => $popup,
		'C_TINYMCE_EDITOR' => AppContext::get_current_user()->get_editor() == 'TinyMCE',
		'C_DISPLAY_CLOSE_BUTTON' => $display_close_button,
		'FIELD' => $field,
		'FOLDER_ID' => !empty($folder) ? $folder : '0',
		'USER_ID' => AppContext::get_current_user()->get_id(),
		'URL' => $folder > 0 ? Uploads::get_url($folder, '', '&amp;' . $popup) : '',
		'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
		'L_CONFIRM_DEL_FOLDER' => $LANG['confirm_del_folder'],
		'L_CONFIRM_EMPTY_FOLDER' => $LANG['confirm_empty_folder'],
		'L_FOLDER_ALREADY_EXIST' => LangLoader::get_message('element.already_exists', 'status-messages-common'),
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
		'L_FOLDER_CONTENT' => $LANG['folder_content'],
		'L_FOLDER_UP' => $LANG['folders_up'],
		'L_FILES' => $LANG['files'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_EMPTY' => $LANG['empty'],
		'L_UPLOAD' => $LANG['upload'],
		'L_URL' => $LANG['url'],
		'U_ROOT' => '<a href="upload.php?' . $popup . '">' . AppContext::get_current_user()->get_display_name() . '</a>/'
	));
	
	list($total_folder_size, $total_files, $total_directories) = array(0, 0, 0);
	//Affichage des dossiers
	$result = PersistenceContext::get_querier()->select("SELECT id, name, id_parent, user_id
	FROM " . DB_TABLE_UPLOAD_CAT . "
	WHERE id_parent = :id_parent AND user_id = :user_id
	ORDER BY name", array(
		'id_parent' => $folder,
		'user_id' => AppContext::get_current_user()->get_id()
	));
	while ($row = $result->fetch())
	{
		$name_cut = (strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlentities(substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];
		
		$tpl->assign_block_vars('folder', array(
			'ID' => $row['id'],
			'NAME' => $name_cut,
			'RENAME_FOLDER' => '<span id="fhref' . $row['id'] . '"><a href="javascript:display_rename_folder(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . LangLoader::get_message('edit', 'common') . '" class="fa fa-edit"></a></span>',
			'MOVE' => '<a href="javascript:upload_display_block(' . $row['id'] . ');" onmouseover="upload_hide_block(' . $row['id'] . ', 1);" onmouseout="upload_hide_block(' . $row['id'] . ', 0);" class="fa fa-move" title="' . $LANG['moveto'] . '"></a>',
			'U_MOVE' => url('.php?movefd=' . $row['id'] . '&amp;f=' . $folder . $popup),
			'L_TYPE_DEL_FOLDER' => $LANG['del_folder']
		));
		$total_directories++;
	}
	$result->dispose();

	$now = new Date();
	//Affichage des fichiers contenu dans le dossier
	$result = PersistenceContext::get_querier()->select("SELECT up.id, up.name, up.path, up.size, up.type, up.timestamp, m.user_id
	FROM " . DB_TABLE_UPLOAD . " up
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = up.user_id
	WHERE up.idcat = :idcat AND up.user_id = :user_id
	ORDER BY up.name", array(
		'idcat' => $folder,
		'user_id' => AppContext::get_current_user()->get_id()
	));
	while ($row = $result->fetch())
	{
		$name_cut = (strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlentities(substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];
		
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
			$tinymce = '<img src="/upload/' . $row['path'] . '" alt="' . $row['name'] . '" />';
			$link = '/upload/' . $row['path'];
			break;
			//Image svg
			case 'svg':
			$bbcode = '[img]/upload/' . $row['path'] . '[/img]';
			$tinymce = '<img src="/upload/' . $row['path'] . '" alt="' . $row['name'] . '" />';
			$link = '/upload/' . $row['path'];
			break;
			//Sons
			case 'mp3':
			$bbcode = '[sound]/upload/' . $row['path'] . '[/sound]';
			$tinymce = '<a href="/upload/' . $row['path'] . '">' . $row['name'] . '</a>';
			$link = '/upload/' . $row['path'];
			break;
			default:
			$bbcode = '[url=/upload/' . $row['path'] . ']' . $row['name'] . '[/url]';
			$tinymce = '<a href="/upload/' . $row['path'] . '">' . $row['name'] . '</a>';
			$link = '/upload/' . $row['path'];
		}
		$is_bbcode_editor = ($editor == 'BBCode');
		$displayed_code = $is_bbcode_editor ? $bbcode : '/upload/' . $row['path'];
		$inserted_code = !empty($parse) ? (!empty($no_path) ? $link : PATH_TO_ROOT . $link) : ($is_bbcode_editor ? addslashes($bbcode) : TextHelper::htmlentities($tinymce));
		$tpl->assign_block_vars('files', array(
			'C_RECENT_FILE' => $row['timestamp'] > ($now->get_timestamp() - (15 * 60)),  // Ficher ajouté il y a moins de 15 minutes
			'ID' => $row['id'],
			'IMG' => $get_img_mimetype['img'],
			'URL' => PATH_TO_ROOT . $link,
			'TITLE' => str_replace('"', '\"', $row['name']),
			'NAME' => $name_cut,
			'RENAME_FILE' => '<span id="fihref' . $row['id'] . '"><a href="javascript:display_rename_file(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . LangLoader::get_message('edit', 'common') . '" class="fa fa-edit"></a></span>',
			'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
			'BBCODE' => '<input type="text" readonly="readonly" onclick="select_div(\'text_' . $row['id'] . '\');" id="text_' . $row['id'] . '" class="upload-input-bbcode" value="' . $displayed_code . '">',
			'SIZE' => ($row['size'] > 1024) ? NumberHelper::round($row['size']/1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($row['size'], 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common'),
			'INSERT' => !empty($popup) ? '<a href="javascript:insert_popup(\'' . $inserted_code . '\')" title="' . $LANG['popup_insert'] . '" class="fa fa-clipboard"></a>' : '',
			'LIGHTBOX' => !empty($size_img) ? ' data-lightbox="1"' : '',
			'U_MOVE' => url('.php?movefi=' . $row['id'] . '&amp;f=' . $folder . $popup)
		));
		
		$total_folder_size += $row['size'];
		$total_files++;
	}
	$result->dispose();
	
	//Autorisation d'uploader sans limite aux groupes.
	$group_limit = AppContext::get_current_user()->check_max_value(DATA_GROUP_LIMIT, $files_upload_config->get_maximum_size_upload());
	$unlimited_data = ($group_limit === -1) || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);
	
	$total_size = 0;
	try {
		$total_size = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD, 'SUM(size)', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id()));
	} catch (RowNotFoundException $ex) {}
	
	$total_size = !empty($folder) ? Uploads::Member_memory_used(AppContext::get_current_user()->get_id()) : $total_size;
	$tpl->put_all(array(
		'PERCENT' => !$unlimited_data ? '(' . NumberHelper::round($total_size/$group_limit, 3) * 100 . '%)' : '',
		'SIZE_LIMIT' => !$unlimited_data ? (($group_limit > 1024) ? NumberHelper::round($group_limit/1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($group_limit, 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common')) : $LANG['illimited'],
		'TOTAL_SIZE' => ($total_size > 1024) ? NumberHelper::round($total_size/1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($total_size, 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common'),
		'TOTAL_FOLDER_SIZE' => ($total_folder_size > 1024) ? NumberHelper::round($total_folder_size/1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($total_folder_size, 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common'),
		'TOTAL_FOLDERS' => $total_directories,
		'TOTAL_FILES' => $total_files
	));

	if ($total_directories == 0 && $total_files == 0)
	{
		$tpl->put_all(array(
			'C_EMPTY_FOLDER' => true,
			'L_EMPTY_FOLDER' => LangLoader::get_message('no_item_now', 'common')
		));
	}
	
	$tpl->display();
}

if (empty($popup))
	require_once('../kernel/footer.php');
else
	require_once('../kernel/footer_no_display.php');
?>
