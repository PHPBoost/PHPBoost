<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 05
 * @since       PHPBoost 1.6 - 2007 07 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 */
require_once('../kernel/begin.php');
define('TITLE', $LANG['files_management']);
$upload_lang = LangLoader::get('upload-common'); // load lang file

$popup = retrieve(GET, 'popup', '');
$editor = retrieve(GET, 'edt', '');
$parse = retrieve(GET, 'parse', '');
$no_path = retrieve(GET, 'no_path', '');
$close_button = retrieve(GET, 'close_button', '');
$display_close_button = false;

// Personal or public files
$request = AppContext::get_request();
$is_public_checkbox = ($request->get_postvalue('is_public_checkbox', 'off') == 'on' ? 1 : 0);
$item_id = $request->get_int('item_id', 0);
$status = $request->get_int('status', 0);
if ($item_id)
{
    PersistenceContext::get_querier()->update(PREFIX . "upload", array('public' => $status), 'WHERE id = :id', array('id' => $item_id));
}

if (!empty($popup))
{ //Popup.
    $env = new SiteDisplayFrameGraphicalEnvironment();
    Environment::set_graphical_environment($env);
    ob_start();
    $field = retrieve(GET, 'fd', '');

    $display_close_button = $close_button != '0';
    $popup = '&popup=1&fd=' . $field . '&edt=' . $editor . '&parse=' . $parse . '&no_path=' . $no_path;
    $popup_noamp = '&popup=1&fd=' . $field . '&edt=' . $editor . '&parse=' . $parse . '&no_path=' . $no_path;
} else
{ // Display management interface.
    $Bread_crumb->add($LANG['user'], UserUrlBuilder::profile(AppContext::get_current_user()->get_id())->rel());
    $Bread_crumb->add($LANG['files_management'], UserUrlBuilder::upload_files_panel()->rel());
    require_once('../kernel/header.php');
    $field = '';
    $popup = '';
    $popup_noamp = '';
}

if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
{ // No visitors !
    $error_controller = PHPBoostErrors::unexisting_page();
    DispatchManager::redirect($error_controller);
}

$files_upload_config = FileUploadConfig::load();

// access authorization ?
if (!AppContext::get_current_user()->check_auth($files_upload_config->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT))
{
    $error_controller = PHPBoostErrors::unexisting_page();
    DispatchManager::redirect($error_controller);
}

$folder = (int)retrieve(GET, 'f', 0);
$parent_folder = (int)retrieve(GET, 'fup', 0);
$home_folder = (bool)retrieve(GET, 'root', false);
$del_folder = (int)retrieve(GET, 'delf', 0);
$del_file = (int)retrieve(GET, 'del', 0);
$get_error = retrieve(GET, 'error', '');
$get_l_error = retrieve(GET, 'erroru', '');
$move_folder = (int)retrieve(GET, 'movefd', 0);
$move_file = (int)retrieve(GET, 'movefi', 0);
$to = retrieve(POST, 'new_cat', -1);

if (!empty($parent_folder))
{ // folder change
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
    } else
        AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $parent_folder . '&' . $popup_noamp, '', '&'));
}
elseif ($home_folder) // Root return
    AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?' . $popup_noamp, '', '&'));
elseif (!empty($_FILES['upload_file']['name']) && AppContext::get_request()->has_getparameter('f'))
{ // Adding a file
    $error = '';
    // Groups upload authorizations
    $group_limit = AppContext::get_current_user()->check_max_value(DATA_GROUP_LIMIT, $files_upload_config->get_maximum_size_upload());
    $unlimited_data = ($group_limit === -1) || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);

    $member_memory_used = Uploads::Member_memory_used(AppContext::get_current_user()->get_id());
    if ($member_memory_used >= $group_limit && !$unlimited_data)
        $error = 'e_max_data_reach';
    else
    {
        // if folder is not writable, try CHMOD 777
        @clearstatcache();
        $dir = PATH_TO_ROOT . '/upload/';
        if (!is_writable($dir))
            $is_writable = (@chmod($dir, 0777));

        @clearstatcache();
        if (is_writable($dir))
        { // Folder writable, upload is possible
            $weight_max = $unlimited_data ? 100000000 : ($group_limit - $member_memory_used);

            $Upload = new Upload($dir);
            $Upload->file('upload_file', '`\.(' . implode('|', array_map('preg_quote', $files_upload_config->get_authorized_extensions())) . ')+$`iu', Upload::UNIQ_NAME, $weight_max);

            if ($Upload->get_error() != '')
            { // Error, stop here
                $error = $Upload->get_error();
                if ($Upload->get_error() == 'e_upload_max_weight')
                    $error = 'e_max_data_reach';
                AppContext::get_response()->redirect('/user/upload.php?f=' . $folder . '&erroru=' . $error . '&' . $popup_noamp . '#message_helper');
            }
            else
            { // Insertion in database
                foreach ($Upload->get_files_parameters() as $parameters)
                {
                    $result = PersistenceContext::get_querier()->insert(DB_TABLE_UPLOAD, array('public' => $is_public_checkbox, 'idcat' => $folder, 'name' => $parameters['name'], 'path' => $parameters['path'], 'user_id' => AppContext::get_current_user()->get_id(), 'size' => $parameters['size'], 'type' => $parameters['extension'], 'timestamp' => time()));
                    $id_file = $result->get_last_inserted_id();
                }
            }
        } else
            $error = 'e_upload_failed_unwritable';
    }

    $anchor = !empty($error) ? '&error=' . $error . '&' . $popup_noamp . '#message_helper' : '&' . $popup_noamp . (!empty($id_file) ? '#fifl' . $id_file : '');
    AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $folder . $anchor, '', '&'));
}
elseif (!empty($del_folder))
{ // delete one folder
    AppContext::get_session()->csrf_get_protect(); // csrf protection

    if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
        Uploads::Del_folder($del_folder);
    else
    {
        $check_user_id = 0;
        try {
            $check_user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $del_folder));
        } catch (RowNotFoundException $ex) {
            
        }

        // folder and all content delete
        if ($check_user_id == AppContext::get_current_user()->get_id())
        {
            Uploads::Del_folder($del_folder);
        } else
        {
            $error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
        }
    }

    AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $folder . '&' . $popup_noamp, '', '&'));
} elseif (!empty($del_file))
{ // File delete
    AppContext::get_session()->csrf_get_protect(); // csrf protection

    if (AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
    {
        Uploads::Del_file($del_file, AppContext::get_current_user()->get_id(), Uploads::ADMIN_NO_CHECK);
    } else
    {
        $error = Uploads::Del_file($del_file, AppContext::get_current_user()->get_id());
        if (!empty($error))
        {
            $error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
        }
    }

    AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $folder . '&' . $popup_noamp, '', '&'));
} elseif (!empty($move_folder) && $to != -1)
{ // folder move
    AppContext::get_session()->csrf_get_protect(); // csrf protection

    $folder_owner = 0;
    try {
        $folder_owner = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD_CAT, 'user_id', 'WHERE id = :id', array('id' => $move_folder));
    } catch (RowNotFoundException $ex) {
        
    }

    if ($folder_owner == AppContext::get_current_user()->get_id())
    {
        include('upload_functions.php');
        $sub_cats = array();
        upload_find_subcats($sub_cats, $move_folder, AppContext::get_current_user()->get_id());
        $sub_cats[] = $move_folder;
        // If we don't move the file to one of his sons or to himself
        if (!in_array($to, $sub_cats))
        {
            if (AppContext::get_current_user()->get_id() || $to == 0)
            {
                PersistenceContext::get_querier()->update(DB_TABLE_UPLOAD_CAT, array('id_parent' => $to), 'WHERE id = :id', array('id' => $move_folder));
                AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $to . '&' . $popup_noamp, '', '&'));
            }
        } else
            AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?movefd=' . $move_folder . '&f=0&error=folder_contains_folder&' . $popup_noamp, '', '&'));
    }
    else
    {
        $error_controller = PHPBoostErrors::unexisting_page();
        DispatchManager::redirect($error_controller);
    }
} elseif (!empty($move_file) && $to != -1)
{ // file move
    AppContext::get_session()->csrf_get_protect(); // csrf protection

    try {
        $file_infos = PersistenceContext::get_querier()->select_single_row(DB_TABLE_UPLOAD, array('idcat', 'user_id'), 'WHERE id = :id', array('id' => $move_file));
    } catch (RowNotFoundException $e) {
        $error_controller = PHPBoostErrors::unexisting_element();
        DispatchManager::redirect($error_controller);
    }

    $id_cat = $file_infos['idcat'];
    $file_owner = $file_infos['user_id'];
    // If the file belongs to us then we can do what we want
    if ($file_owner == AppContext::get_current_user()->get_id())
    {
        // If destination folder belongs to us
        if (AppContext::get_current_user()->get_id() || $to == 0)
        {
            PersistenceContext::get_querier()->update(DB_TABLE_UPLOAD, array('idcat' => $to), 'WHERE id = :id', array('id' => $move_file));
            AppContext::get_response()->redirect(HOST . DIR . url('/user/upload.php?f=' . $to . '&' . $popup_noamp, '', '&'));
        } else
        {
            $error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
        }
    } else
    {
        $error_controller = PHPBoostErrors::unexisting_page();
        DispatchManager::redirect($error_controller);
    }
} elseif (!empty($move_folder) || !empty($move_file))
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
    ));

    if ($get_error == 'folder_contains_folder')
        $tpl->put('message_helper', MessageHelper::display($LANG['upload_folder_contains_folder'], MessageHelper::WARNING));

    // list of available files
    include_once('upload_functions.php');
    $cats = array();

    $is_folder = !empty($move_folder);
    // Displaying the folder / file to move
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
    } else
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
        switch ($info_move['type']) {
            // Pictures
            case 'jpg':
            case 'png':
            case 'gif':
            case 'bmp':
                list($width_source, $height_source) = @getimagesize('../upload/' . $info_move['path']);
                $size_img = ' (' . $width_source . 'x' . $height_source . ')';

                // The actual image is displayed if it is not too big
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
            'SIZE' => ($info_move['size'] > 1024) ? NumberHelper::round($info_move['size'] / 1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($info_move['size'], 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common'),
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
} else
{
    $is_admin = AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);

    $tpl = new FileTemplate('user/upload.tpl');

    // errors management
    $array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_unlink_disabled', 'e_max_data_reach');
    if (in_array($get_error, $array_error))
        $tpl->put('message_helper', MessageHelper::display($LANG[$get_error], MessageHelper::WARNING));
    if ($get_error == 'incomplete')
        $tpl->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));

    if (isset($LANG[$get_l_error]))
        $tpl->put('message_helper', MessageHelper::display($LANG[$get_l_error], MessageHelper::WARNING));

    $tpl->put_all(array(
        'C_POPUP' => !empty($popup),
        'POPUP' => $popup,
        'C_TINYMCE_EDITOR' => AppContext::get_current_user()->get_editor() == 'TinyMCE',
        'C_DISPLAY_CLOSE_BUTTON' => $display_close_button,
        'FIELD' => $field,
        'FOLDER_ID' => !empty($folder) ? $folder : '0',
        'USER_ID' => AppContext::get_current_user()->get_id(),
        'URL' => $folder > 0 ? Uploads::get_url($folder, '', '&amp;' . $popup) : '',
        'MAX_FILE_SIZE' => ServerConfiguration::get_upload_max_filesize(),
        'MAX_FILE_SIZE_TEXT' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()),
        'ALLOWED_EXTENSIONS' => implode('", "', $files_upload_config->get_authorized_extensions()),
        'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
        'L_CONFIRM_DEL_FOLDER' => $LANG['confirm_del_folder'],
        'L_CONFIRM_EMPTY_FOLDER' => $LANG['confirm_empty_folder'],
        'L_FOLDER_ALREADY_EXIST' => LangLoader::get_message('element.already_exists', 'status-messages-common'),
        'L_FOLDER_FORBIDDEN_CHARS' => $LANG['folder_forbidden_chars'],
        'L_FILES_MANAGEMENT' => $LANG['files_management'],
        'L_FILES_ACTION' => $LANG['files_management'],
        'L_CONFIG_FILES' => $LANG['files_config'],
        'L_ADD_FILES' => $LANG['files_add'],
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
        'L_PUBLIC_CHECKBOX' => $upload_lang['public.checkbox'],
        'L_PUBLIC_TITLE' => $upload_lang['public.title'],
        'L_PERSONAL_TITLE' => $upload_lang['personal.title'],
        'L_CHANGE_PERSONAL' => $upload_lang['change.to.personal'],
        'L_CHANGE_PUBLIC' => $upload_lang['change.to.public'],
    ));

    list($total_folder_size, $total_public_size, $total_personal_files, $total_public_files, $total_directories) = array(0, 0, 0, 0, 0);
    // folder display
    $result = PersistenceContext::get_querier()->select("SELECT id, name, id_parent, user_id
	FROM " . DB_TABLE_UPLOAD_CAT . "
	WHERE id_parent = :id_parent AND user_id = :user_id
	ORDER BY name", array(
        'id_parent' => $folder,
        'user_id' => AppContext::get_current_user()->get_id()
    ));
    while ($row = $result->fetch()) {
        $name_cut = (TextHelper::strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

        $tpl->assign_block_vars('folder', array(
            'ID' => $row['id'],
            'NAME' => $name_cut,
            'RENAME_FOLDER' => '<span id="fhref' . $row['id'] . '"><a href="javascript:display_rename_folder(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" aria-label="' . LangLoader::get_message('edit', 'common') . '"><i  class="fa fa-edit"></i></a></span>',
            'MOVE' => '<a href="javascript:upload_display_block(' . $row['id'] . ');" onmouseover="upload_hide_block(' . $row['id'] . ', 1);" onmouseout="upload_hide_block(' . $row['id'] . ', 0);" class="fa fa-share" aria-label="' . $LANG['moveto'] . '"></a>',
            'U_MOVE' => url('.php?movefd=' . $row['id'] . '&amp;f=' . $folder . $popup),
            'L_TYPE_DEL_FOLDER' => $LANG['del_folder']
        ));
        $total_directories ++;
    }
    $result->dispose();

    $now = new Date();

    // Personal or Public loop
    $types = array('personal_files' => 'up.idcat = :idcat AND up.user_id = :user_id', 'public_files' => 'up.public = 1');
    foreach ($types as $loop_id => $where_clause)
    {
        // Display files inside folder
        $result = PersistenceContext::get_querier()->select("SELECT up.id, up.public, up.name, up.path, up.size, up.type, up.timestamp, m.user_id
    	FROM " . DB_TABLE_UPLOAD . " up
    	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = up.user_id
        WHERE " . $where_clause . "
    	ORDER BY up.name", array(
            'idcat' => $folder,
            'user_id' => AppContext::get_current_user()->get_id()
        ));
        while ($row = $result->fetch()) {
            $name_cut = (TextHelper::strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

            $get_img_mimetype = Uploads::get_img_mimetype($row['type']);
            $size_img = '';
            switch ($row['type']) {
                // Pictures
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'bmp':
                case 'svg':
                case 'nef':
                case 'raw':
                case 'ico':
                case 'tif':
                    list($width_source, $height_source) = @getimagesize(PATH_TO_ROOT . '/upload/' . $row['path']);
                    $size_img = ' (' . $width_source . 'x' . $height_source . ')';
                    $width_source = !empty($width_source) ? $width_source + 30 : 0;
                    $height_source = !empty($height_source) ? $height_source + 30 : 0;
                    $bbcode = '[img]/upload/' . $row['path'] . '[/img]';
                    $tinymce = '<img src="/upload/' . $row['path'] . '" alt="' . $row['name'] . '" />';
                    $link = '/upload/' . $row['path'];
                    break;
                // Sounds
                case 'wav':
                case 'ogg':
                case 'mp3':
                    $bbcode = '[sound]/upload/' . $row['path'] . '[/sound]';
                    $tinymce = '<a href="/upload/' . $row['path'] . '">' . $row['name'] . '</a>';
                    $link = '/upload/' . $row['path'];
                    break;
                // Videos
                case 'webm':
                case 'mp4':
                    $bbcode = '[movie=100,100]/upload/' . $row['path'] . '[/movie]';
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
            $inserted_code = !empty($parse) ? (!empty($no_path) ? $link : PATH_TO_ROOT . $link) : ($is_bbcode_editor ? addslashes($bbcode) : TextHelper::htmlspecialchars($tinymce));
            $tpl->assign_block_vars($loop_id, array(
                'C_ENABLED_THUMBNAILS' => FileUploadConfig::load()->get_display_file_thumbnail(),
                'C_IMG' => $get_img_mimetype['img'] == 'far fa-file-image',
                'C_RECENT_FILE' => $row['timestamp'] > ($now->get_timestamp() - (2 * 60)), // File added less than 2 minutes ago
                'ID' => $row['id'],
                'C_IS_PUBLIC_FILE' => $row['public'] == 1,
                'IMG' => $get_img_mimetype['img'],
                'URL' => PATH_TO_ROOT . $link,
                'TITLE' => str_replace('"', '\"', $row['name']),
                'NAME' => $name_cut,
                'RENAME_FILE' => '<span id="fihref' . $row['id'] . '"><a href="javascript:display_rename_file(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" aria-label="' . LangLoader::get_message('edit', 'common') . '"><i class="fa fa-edit" aria-hidden="true"></i></a></span>',
                'FILETYPE' => $get_img_mimetype['filetype'] . $size_img,
                'DISPLAYED_CODE' => $displayed_code,
                'SIZE' => ($row['size'] > 1024) ? NumberHelper::round($row['size'] / 1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($row['size'], 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common'),
                'INSERTED_CODE' => $inserted_code,
                'LIGHTBOX' => !empty($size_img) ? ' data-lightbox="1"' : '',
                'U_MOVE' => url('.php?movefi=' . $row['id'] . '&amp;f=' . $folder . $popup)
            ));

            if ($loop_id == 'public_files')
            {
                $total_public_files ++;
                $total_public_size += $row['size'];
            } else
            {
                $total_personal_files ++;
                $total_folder_size += $row['size'];
            }
        }
        $result->dispose();
    }
    // Authorization to upload without limit to groups.
    $group_limit = AppContext::get_current_user()->check_max_value(DATA_GROUP_LIMIT, $files_upload_config->get_maximum_size_upload());
    $unlimited_data = ($group_limit === -1) || AppContext::get_current_user()->check_level(User::ADMIN_LEVEL);

    $total_size = 0;
    try {
        $total_size = PersistenceContext::get_querier()->get_column_value(DB_TABLE_UPLOAD, 'SUM(size)', 'WHERE user_id = :id', array('id' => AppContext::get_current_user()->get_id()));
    } catch (RowNotFoundException $ex) {
        
    }

    $total_size = !empty($folder) ? Uploads::Member_memory_used(AppContext::get_current_user()->get_id()) : $total_size;
    $tpl->put_all(array(
        'PERCENT' => !$unlimited_data ? '(' . NumberHelper::round($total_size / $group_limit, 3) * 100 . '%)' : '',
        'SIZE_LIMIT' => !$unlimited_data ? (($group_limit > 1024) ? NumberHelper::round($group_limit / 1024, 2) . ' ' . LangLoader::get_message('unit.megabytes', 'common') : NumberHelper::round($group_limit, 0) . ' ' . LangLoader::get_message('unit.kilobytes', 'common')) : $LANG['illimited'],
        'MAX_FILES_SIZE' => !$unlimited_data ? (($group_limit * 1024 > 1024 * 1024) ? NumberHelper::round($group_limit * 1024, 2) : NumberHelper::round($group_limit * 1024, 0)) : -1,
        'TOTAL_SIZE' =>  File::get_formated_size($total_size * 1024),
        'TOTAL_PUBLIC_SIZE' => File::get_formated_size($total_public_size * 1024),
        'TOTAL_FOLDER_SIZE' => File::get_formated_size($total_folder_size * 1024),
        'TOTAL_FOLDERS' => $total_directories,
        'TOTAL_PERSONAL_FILES' => $total_personal_files,
        'TOTAL_PUBLIC_FILES' => $total_public_files
    ));

    $tpl->put_all(array(
        'C_PERSONAL_SUMMARY' => $total_directories > 0 || $total_personal_files > 0,
        'C_PERSONAL_FILES' => $total_personal_files > 0,
        'C_PUBLIC_FILES' => $total_public_files > 0,
        'L_NO_ITEM' => LangLoader::get_message('no_item_now', 'common')
    ));

    $tpl->display();
}

if (empty($popup))
    require_once('../kernel/footer.php');
else
    require_once('../kernel/footer_no_display.php');
?>
