<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 29
 * @since       PHPBoost 1.6 - 2007 03 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['upload.files.management'] . ' - ' . $lang['admin.administration']);
require_once('../admin/admin_header.php');

// Personal or shared files
$request = AppContext::get_request();
$is_shared_checkbox = ($request->get_postvalue('is_shared_checkbox', 1) === 'on' ? 1 : 0);
$item_id = $request->get_int('item_id', 0);
$status = $request->get_int('status', 0);

if ($item_id)
{
	PersistenceContext::get_querier()->update(PREFIX . "upload", array('shared' => $status), 'WHERE id = :id', array('id' => $item_id));
}

$folder = $request->get_getint('f', 0);
$folder_member = $request->get_getint('fm', 0);
$parent_folder = $request->get_getint('fup', 0);
$home_folder = $request->get_getvalue('root', false);
$del_folder = $request->get_getint('delf', 0);
$empty_folder = $request->get_getint('eptf', 0);
$del_file = $request->get_getint('del', 0);
$get_error = $request->get_getvalue('error', '');
$get_l_error = $request->get_getvalue('erroru', '');
$show_member = $request->get_getvalue('showm', false);
$show_shared = $request->get_getvalue('showp', false);
$move_folder = $request->get_getint('movefd', 0);
$move_file = $request->get_getint('movefi', 0);
$to = retrieve(POST, 'new_cat', -1);

if ($parent_folder) // Changing folder
{
	try {
		$parent_folder = PersistenceContext::get_querier()->select_single_row(PREFIX . 'upload_cat', array('id_parent', 'user_id'), 'WHERE id=:id', array('id' => $parent_folder));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($folder_member))
		AppContext::get_response()->redirect('/admin/admin_files.php?showm=1');
	elseif ($parent_folder['user_id'] != -1 && empty($parent_folder['id_parent']))
		AppContext::get_response()->redirect('/admin/admin_files.php?fm=' . $parent_folder['user_id']);
	else
		AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $parent_folder['id_parent']);
}
elseif ($home_folder) // Back to root
	AppContext::get_response()->redirect('/admin/admin_files.php');
elseif (!empty($_FILES['upload_file']['name'])) // Add file
{
	// If folder is not writable, we try CHMOD 755
	@clearstatcache();
	$dir = PATH_TO_ROOT . '/upload/';
	if (!is_writable($dir))
		$is_writable = @chmod($dir, 0755);

	@clearstatcache();
	$error = '';
	if (is_writable($dir)) // Folder is writable => upload is available
	{
		$Upload = new Upload($dir);
		$Upload->file('upload_file', '`\.(' . implode('|', array_map('preg_quote', FileUploadConfig::load()->get_authorized_extensions())) . ')+$`i', Upload::UNIQ_NAME);

		if ($Upload->get_error() != '') // Error then stop
			AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $folder . '&erroru=' . $Upload->get_error() . '#message_helper');
		else // Insert in database
		{
			$user_id = AppContext::get_current_user()->get_id();
			try {
				$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id=:id', array('id' => $folder));
			} catch (RowNotFoundException $e) {

			}

			$user_id = max($user_id, $folder_member);

			foreach ($Upload->get_files_parameters() as $parameters)
			{
				$result = PersistenceContext::get_querier()->insert(DB_TABLE_UPLOAD, array('shared' => $is_shared_checkbox, 'idcat' => $folder, 'name' => $parameters['name'], 'path' => $parameters['path'], 'user_id' => $user_id, 'size' => $parameters['size'], 'type' => $parameters['extension'], 'timestamp' => time()));
				$id_file = $result->get_last_inserted_id();
			}
		}
	}
	else
		$error = 'e_upload_failed_unwritable';

	$anchor = !empty($error) ? '&error=' . $error . '#message_helper' : (!empty($id_file) ? '#fi1' . $id_file : '');
	AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $folder . ($folder_member > 0 ? '&fm=' . $folder_member : '') . $anchor);
}
elseif (!empty($del_folder)) // Delete a folder
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection
	// Delete folder and its content
	Uploads::Del_folder($del_folder);

	if (!empty($folder_member))
		AppContext::get_response()->redirect('/admin/admin_files.php?fm=' . $folder_member);
	else
		AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $folder);
}
elseif (!empty($empty_folder)) // Empty a member folder
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection
	// Delete all child folders
	Uploads::Empty_folder_member($empty_folder);

	AppContext::get_response()->redirect('/admin/admin_files.php?showm=1');
}
elseif (!empty($del_file)) // Delete a file
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection
	// Delete a file
	Uploads::Del_file($del_file, -1, Uploads::ADMIN_NO_CHECK);

	AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $folder . ($folder_member > 0 ? '&fm=' . $folder_member : ''));
}
elseif (!empty($move_folder) && $to != -1) // Moving folder
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection

	$user_id = AppContext::get_current_user()->get_id();
	try {
		$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id=:id', array('id' => $move_folder));
	} catch (RowNotFoundException $e) {

	}

	$move_list_parent = array();

	if ($user_id)
	{
		$result = PersistenceContext::get_querier()->select("SELECT id, id_parent, name
		FROM " . PREFIX . "upload_cat
		WHERE user_id = :user_id
		ORDER BY id", array(
			'user_id' => $user_id
		));
		while ($row = $result->fetch())
			$move_list_parent[$row['id']] = $row['id_parent'];

		$result->dispose();
	}

	$array_child_folder = array();
	Uploads::Find_subfolder($move_list_parent, $move_folder, $array_child_folder);
	$array_child_folder[] = $move_folder;
	if (!in_array($to, $array_child_folder)) // Destination folder not subfolder of source folder
		Uploads::Move_folder($move_folder, $to, AppContext::get_current_user()->get_id(), Uploads::ADMIN_NO_CHECK);
	else
		AppContext::get_response()->redirect('/admin/admin_files.php?movefd=' . $move_folder . '&f=0&error=folder_contains_folder');

	AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $to);
}
elseif (!empty($move_file) && $to != -1) // Moving file
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection

	Uploads::Move_file($move_file, $to, AppContext::get_current_user()->get_id(), Uploads::ADMIN_NO_CHECK);

	AppContext::get_response()->redirect('/admin/admin_files.php?f=' . $to);
}
elseif (!empty($move_folder) || !empty($move_file))
{
	$view = new FileTemplate('admin/admin_files_move.tpl');
	$view->add_lang($lang);


	if (!empty($folder_member))
	{
		$result = PersistenceContext::get_querier()->select("SELECT uc.user_id, m.display_name
			FROM " . DB_TABLE_UPLOAD_CAT . " uc
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = uc.user_id
			WHERE uc.user_id = :user_id
			UNION
			SELECT u.user_id, m.display_name
			FROM " . DB_TABLE_UPLOAD . " u
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = u.user_id
			WHERE u.user_id = :user_id", array(
			'user_id' => $folder_member
		));
	}
	else
	{
		$result = PersistenceContext::get_querier()->select("SELECT uc.user_id, m.display_name
			FROM " . DB_TABLE_UPLOAD_CAT . " uc
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = uc.user_id
			WHERE uc.id = :id", array(
			'id' => $folder
		));
	}

	$folder_info = $result->fetch();
	$result->dispose();

	if ($show_member)
		$url = Uploads::get_admin_url($folder, ' | <a href="admin_files.php?showm=1">' . $lang['user.members'] . '</a>');
	elseif (!empty($folder_member) || !empty($folder_info['user_id']))
		$url = Uploads::get_admin_url($folder, '', ' | <a href="admin_files.php?showm=1">' . $lang['user.members'] . '</a> | <a href="admin_files.php?fm=' . $folder_info['user_id'] . '">' . $folder_info['display_name'] . '</a> | ');
	elseif (empty($folder))
		$url = '';
	else
		$url = Uploads::get_admin_url($folder, '');

	$view->put_all(array(
		'FOLDER_ID' => !empty($folder) ? $folder : '0',

		'URL' => $url,
	));

	if ($get_error == 'folder_contains_folder')
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.folder.contains.folder'], MessageHelper::WARNING));

	// Available files list
	include_once(PATH_TO_ROOT . '/user/upload_functions.php');
	$cats = array();

	if (empty($folder_member))
		$folder_member = -1;

	$is_folder = !empty($move_folder);
	// Display of folder/file to move
	if ($is_folder)
	{
		try {
			$folder_info = PersistenceContext::get_querier()->select_single_row(PREFIX . 'upload_cat', array('name', 'id_parent'), 'WHERE id=:id', array('id' => $move_folder));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$name = $folder_info['name'];
		$id_cat = $folder_info['id_parent'];
		$view->assign_block_vars('folder', array(
			'NAME' => $name
		));
		$view->put_all(array(
			'SELECTED_CAT' => $id_cat,
			'ID_FILE'      => $move_folder,
			'TARGET'       => url('admin_files.php?movefd=' . $move_folder . '&amp;f=0&amp;token=' . AppContext::get_session()->get_token())
		));
		$cat_explorer = display_cat_explorer($id_cat, $cats, $folder_member, 1);
	}
	else
	{
		try {
			$info_move = PersistenceContext::get_querier()->select_single_row(PREFIX . 'upload', array('path', 'name', 'type', 'size', 'idcat'), 'WHERE id=:id', array('id' => $move_file));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}

		$get_img_mimetype = Uploads::get_img_mimetype($info_move['type']);
		$size_img = '';
		$display_real_img = false;
		switch ($info_move['type']) {
			// Images
			case 'jpg':
			case 'png':
			case 'webp':
			case 'gif':
			case 'bmp':
				$display_real_img = true;
		}

		$cat_explorer = display_cat_explorer($info_move['idcat'], $cats, $folder_member, 1);

		$view->assign_block_vars('file', array(
			'C_ENABLED_THUMBNAILS' => FileUploadConfig::load()->get_display_file_thumbnail(),
			'C_REAL_IMG' => $display_real_img,
			'NAME' => $info_move['name'],
			'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
			'SIZE' => ($info_move['size'] > 1024) ? NumberHelper::round($info_move['size'] / 1024, 2) . ' ' . $lang['common.unit.megabytes'] : NumberHelper::round($info_move['size'], 0) . ' ' . $lang['common.unit.kilobytes'],
			'FILE_ICON' => FileUploadConfig::load()->get_display_file_thumbnail() ? ($display_real_img ? $info_move['path'] : $get_img_mimetype['img']) : $get_img_mimetype['img']
		));
		$view->put_all(array(
			'SELECTED_CAT' => $info_move['idcat'],
			'TARGET' => url('admin_files.php?movefi=' . $move_file . '&amp;f=0&amp;token=' . AppContext::get_session()->get_token())
		));
	}

	$view->put_all(array(
		'FOLDERS' => $cat_explorer,
		'ID_FILE' => $move_file
	));

	$view->display();
}
else
{
	$view = new FileTemplate('admin/admin_files_management.tpl');
	$view->add_lang($lang);

	if (!empty($folder_member))
	{
		$result = PersistenceContext::get_querier()->select("SELECT uc.user_id, m.display_name
			FROM " . DB_TABLE_UPLOAD_CAT . " uc
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = uc.user_id
			WHERE uc.user_id = :user_id
			UNION
			SELECT u.user_id, m.display_name
			FROM " . DB_TABLE_UPLOAD . " u
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = u.user_id
			WHERE u.user_id = :user_id", array(
			'user_id' => $folder_member
		));
	}
	else
	{
		$result = PersistenceContext::get_querier()->select("SELECT uc.user_id, m.display_name
			FROM " . DB_TABLE_UPLOAD_CAT . " uc
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = uc.user_id
			WHERE uc.id = :id", array(
			'id' => $folder
		));
	}

	$folder_info = $result->fetch();
	$result->dispose();

	// Errors management
	$array_error = array('e_upload_no_file', 'e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_failed_unwritable', 'e_unlink_disabled');
	if (in_array($get_error, $array_error))
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_error], MessageHelper::WARNING));
	if ($get_error == 'incomplete')
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.incomplete'], MessageHelper::NOTICE));

	if (isset($lang[$get_l_error]))
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$get_l_error], MessageHelper::WARNING));

	if ($show_member)
		$url = Uploads::get_admin_url($folder, ' | <a href="admin_files.php?showm=1">' . $lang['user.members'] . '</a> |');
	elseif (!empty($folder_member) || !empty($folder_info['user_id']))
		$url = Uploads::get_admin_url($folder, '', ' | <a href="admin_files.php?showm=1">' . $lang['user.members'] . '</a> | <a href="admin_files.php?fm=' . $folder_info['user_id'] . '">' . $folder_info['display_name'] . '</a> | ');
	elseif (empty($folder))
		$url = '';
	else
		$url = Uploads::get_admin_url($folder, '');

	$view->put_all(array(
		'C_MEMBER_ROOT_FOLDER' => $folder == 0 && !empty($folder_info['user_id']),

		'FOLDER_ID'          => !empty($folder) ? $folder : '0',
		'FOLDERM_ID'         => !empty($folder_member) ? '&amp;fm=' . $folder_member : '',
		'USER_ID'            => !empty($folder_info['user_id']) ? $folder_info['user_id'] : '-1',
		'MAX_FILE_SIZE'      => ServerConfiguration::get_upload_max_filesize(),
		'MAX_FILE_SIZE_TEXT' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()),
		'ALLOWED_EXTENSIONS' => implode('", "', FileUploadConfig::load()->get_authorized_extensions()),

		'URL' => $url,
	));

	if ($folder == 0 && !$show_member && empty($folder_member))
	{
		$view->assign_block_vars('folder', array(
			'C_MEMBERS_FOLDER' => true,
			'C_MEMBER_FOLDER'  => true,

			'NAME' => $lang['user.members'],

			'U_FOLDER' => '?showm=1',
		));
	}

	list($total_folder_size, $total_shared_size, $total_personal_files, $total_shared_files, $total_directories) = array(0, 0, 0, 0, 0);

	if ($show_member)
	{
		$result = PersistenceContext::get_querier()->select("SELECT uc.user_id as id, uc.user_id, m.display_name as name, 0 as id_parent
			FROM " . DB_TABLE_UPLOAD_CAT . " uc
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = uc.user_id
			WHERE uc.id_parent = :id AND uc.user_id <> -1
			UNION
			SELECT u.user_id as id, u.user_id, m.display_name as name, 0 as id_parent
			FROM " . DB_TABLE_UPLOAD . " u
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = u.user_id
			WHERE u.user_id <> -1
			ORDER BY name", array(
			'id' => $folder
		));
	}
	elseif (!empty($folder_member) && empty($folder))
	{
		$result = PersistenceContext::get_querier()->select("SELECT id, name, id_parent, user_id
			FROM " . DB_TABLE_UPLOAD_CAT . "
			WHERE id_parent = 0 AND user_id = :user_id
			ORDER BY name", array(
			'user_id' => $folder_member
		));
	}
	else
	{
		$result = PersistenceContext::get_querier()->select("SELECT id, name, id_parent, user_id
			FROM " . DB_TABLE_UPLOAD_CAT . "
			WHERE id_parent = :id" . ((empty($folder) || $folder_info['user_id'] <= 0) ? ' AND user_id = -1' : ' AND user_id <> -1') . "
			ORDER BY name", array(
			'id' => $folder
		));
	}

	// Folder display
	while ($row = $result->fetch())
	{
		$name_cut = (TextHelper::strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

		$view->assign_block_vars('folder', array(
			'C_MEMBER_FOLDER' => $show_member,
			'C_TYPEFOLDER' => !$show_member,

			'ID'                    => $row['id'],
			'NAME'                  => $name_cut,
			'NAME_WITH_SLASHES'     => addslashes($row['name']),
			'NAME_CUT_WITH_SLASHES' => addslashes($name_cut),
			'DEL_TYPE'              => $show_member ? 'eptf' : 'delf',

			'U_ONCHANGE_FOLDER' => "'admin_files" . url(".php?movef=" . $row['id'] . "&amp;to=' + this.options[this.selectedIndex].value + '") . "'",
			'U_FOLDER'          => '?' . ($show_member ? 'fm=' . $row['user_id'] : 'f=' . $row['id']),
			'U_MOVE'            => '.php?movefd=' . $row['id'] . '&amp;f=' . $folder . ($row['user_id'] > 0 ? '&amp;fm=' . $row['user_id'] : '')
		));
		$total_directories++;
	}
	$result->dispose();

	// Display the files from the folder
	$types = array('personal_files' => 'up.idcat = :idcat AND up.user_id = :user_id', 'shared_files' => 'up.shared = 1');
	foreach ($types as $loop_id => $where_clause)
	{

		$now = new Date();
		if (!empty($folder_member) && empty($folder) || $show_shared)
		{
			$result = PersistenceContext::get_querier()->select("SELECT up.id, up.shared, up.name, up.path, up.size, up.type, up.timestamp, m.user_id, m.display_name, m.level, m.user_groups
	    	FROM " . DB_TABLE_UPLOAD . " up
	    	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = up.user_id
	        WHERE " . $where_clause . "
	    	ORDER BY up.name", array(
	            	'idcat' => 0,
					'user_id' => $folder_member
			));
		}
		else
		{
			$result = PersistenceContext::get_querier()->select("SELECT up.id, up.shared, up.name, up.path, up.size, up.type, up.timestamp, m.user_id, m.display_name, m.level, m.user_groups
			FROM " . DB_TABLE_UPLOAD . " up
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = up.user_id
			WHERE idcat = :id" . ((empty($folder) || $folder_info['user_id'] <= 0) ? ' AND up.user_id = -1' : ' AND up.user_id != -1'), array(
				'id' => $folder
			));
		}

		while ($row = $result->fetch())
		{
			$name_cut = (TextHelper::strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

			$get_img_mimetype = Uploads::get_img_mimetype($row['type']);
			$file = new file(PATH_TO_ROOT . '/upload/' . $row['path']);
			$size_img = $link = '';
			switch ($row['type']) {
				// Images
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'webp':
				case 'gif':
				case 'bmp':
				case 'svg':
				case 'nef':
				case 'raw':
				case 'ico':
				case 'tif':
					$width_source = $height_source = 0;
					if ($file->exists())
						list($width_source, $height_source) = @getimagesize(PATH_TO_ROOT . '/upload/' . $row['path']);
					$width_source = !empty($width_source) ? $width_source + 30 : 0;
					$height_source = !empty($height_source) ? $height_source + 30 : 0;
					$size_img = ' (' . $width_source . 'x' . $height_source . ')';
					$bbcode = '[img]/upload/' . $row['path'] . '[/img]';
					$link = PATH_TO_ROOT . '/upload/' . $row['path'];
					break;
				// Sounds
				case 'wav':
				case 'ogg':
				case 'mp3':
					$bbcode = '[sound]/upload/' . $row['path'] . '[/sound]';
					$link = 'javascript:popup_upload(\'' . Url::to_rel('/upload/' . $row['path']) . '\', 220, 10, \'no\')';
					break;
				// Videos
				case 'webm':
				case 'mp4':
					$bbcode = '[movie=800,450]/upload/' . $row['path'] . '[/movie]';
					$tinymce = '<a href="/upload/' . $row['path'] . '">' . $row['name'] . '</a>';
					$link = 'javascript:popup_upload(\'' . Url::to_rel('/upload/' . $row['path']) . '\', 400, 225, \'no\')';
					break;
				default:
					$bbcode = '[url=/upload/' . $row['path'] . ']' . $row['name'] . '[/url]';
					$link = PATH_TO_ROOT . '/upload/' . $row['path'];
			}
			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$view->assign_block_vars($loop_id, array_merge(
				Date::get_array_tpl_vars(new Date($row['timestamp'], Timezone::SERVER_TIMEZONE), 'date'),
				array(
				'C_FILE_EXISTS'        => $file->exists(),
				'C_ENABLED_THUMBNAILS' => FileUploadConfig::load()->get_display_file_thumbnail(),
				'C_IMG'                => $get_img_mimetype['img'] == 'far fa-file-image',
				'C_RECENT_FILE'        => $row['timestamp'] > ($now->get_timestamp() - (2 * 60)), // File added less than 2 minutes ago
				'C_AUTHOR_EXIST'       => !empty($row['display_name']),
				'C_AUTHOR_GROUP_COLOR' => !empty($group_color),
				'C_IS_SHARED_FILE'     => $row['shared'] == 1,

				'ID'                    => $row['id'],
				'AUTHOR'                => $row['display_name'],
				'AUTHOR_LEVEL_CLASS'    => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'    => $group_color,
				'ICON'                  => $get_img_mimetype['img'],
				'NAME'                  => $name_cut,
				'NAME_WITH_SLASHES'     => addslashes($row['name']),
				'NAME_CUT_WITH_SLASHES' => addslashes($name_cut),
				'FILETYPE'              => $get_img_mimetype['filetype'] . $size_img,
				'BBCODE'                => '<input readonly="readonly" type="text" onclick="select_div(\'text_' . $row['id'] . '\');" id="text_' . $row['id'] . '" value="' . $bbcode . '">',
				'DISPLAYED_CODE'        => '/upload/' . $row['path'],
				'SIZE'                  => ($row['size'] > 1024) ? NumberHelper::round($row['size'] / 1024, 2) . ' ' . $lang['common.unit.megabytes'] : NumberHelper::round($row['size'], 0) . ' ' . $lang['common.unit.kilobytes'],

				'LIGHTBOX'         => !empty($size_img) ? ' data-lightbox ="1" data-rel="lightcase:collection"' : '',
				'URL'              => $link,
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_MOVE'           => '.php?movefi=' . $row['id'] . '&amp;f=' . $folder . '&amp;fm=' . $row['user_id']
			)));
			if ($loop_id == 'shared_files')
			{
				$total_shared_files ++;
				$total_shared_size += $row['size'];
			}
			else
			{
				$total_personal_files ++;
				$total_folder_size += $row['size'];
			}
		}

		$result->dispose();
	}


	$total_size = 0;
	try {
		$total_size = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD, 'SUM(size)', '');
	} catch (RowNotFoundException $e) {

	}
	$view->put_all(array(
		'C_PERSONAL_SUMMARY'   => ($total_directories > 0 || $total_personal_files > 0) && !$show_shared,
		'C_SHARED_FILES_EXIST' => $total_shared_files > 0,
		'C_SHOW_SHARED_FILES'  => $show_shared,

		'TOTAL_SIZE'           => File::get_formated_size($total_size * 1024),
		'TOTAL_SHARED_SIZE'    => File::get_formated_size($total_shared_size * 1024),
		'TOTAL_FOLDER_SIZE'    => File::get_formated_size($total_folder_size * 1024),
		'TOTAL_FOLDERS'        => $total_directories,
		'TOTAL_PERSONAL_FILES' => $total_personal_files,
		'TOTAL_SHARED_FILES'   => $total_shared_files,
	));

	$view->display();
}

require_once('../admin/admin_footer.php');
?>
