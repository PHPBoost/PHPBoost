<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 29
 * @since       PHPBoost 1.2 - 2005 06 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs();

define('TITLE', $lang['user.groups.management'] . ' - ' . $lang['admin.administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$idgroup = $request->get_getint('id', 0);
$idgroup_post = $request->get_postint('id', 0);
$add = $request->get_getint('add', 0);
$add_post = $request->get_postint('add', 0);
$del_group = $request->get_getint('del', 0);
$add_mbr = $request->get_postvalue('add_mbr', false);
$del_mbr = $request->get_getint('del_mbr', 0);
$user_id = $request->get_getint('user_id', 0);
$valid = $request->get_postvalue('valid', false);
$data_group_limit = $request->get_postvalue('data_group_limit', '');

$_NBR_ELEMENTS_PER_PAGE = 25;

if ($valid && !empty($idgroup_post)) // group modification
{
	$name = retrieve(POST, 'name', '');
	$img = retrieve(POST, 'img', '');
	$auth_flood = (int)retrieve(POST, 'auth_flood', 1);
	$pm_group_limit = (int)retrieve(POST, 'pm_group_limit', 75);
	$color_group = retrieve(POST, 'color_group', '');
	$color_group = TextHelper::substr($color_group, 0, 1) == '#' ? TextHelper::substr($color_group, 1) : $color_group;
	$delete_group_color = (bool)retrieve(POST, 'delete_group_color', false);

	if ($delete_group_color)
		$color_group = '';

	$data_group_limit = $data_group_limit ? NumberHelper::numeric($data_group_limit, 'float') * 1024 : '5120';

	$group_auth = array('auth_flood' => $auth_flood, 'pm_group_limit' => $pm_group_limit, 'data_group_limit' => $data_group_limit);
	PersistenceContext::get_querier()->update(DB_TABLE_GROUP, array('name' => $name, 'img' => $img, 'color' => $color_group, 'auth' => TextHelper::serialize($group_auth)), 'WHERE id = :id', array('id' => $idgroup_post));

	GroupsCache::invalidate(); // Regeneration of the group's cache

	AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup_post);
}
elseif ($valid && $add_post) // Add group
{
	$name = retrieve(POST, 'name', '');
	$img = retrieve(POST, 'img', '');
	$auth_flood = (int)retrieve(POST, 'auth_flood', 1);
	$pm_group_limit = (int)retrieve(POST, 'pm_group_limit', 75);
	$color_group = retrieve(POST, 'color_group', '');
	$color_group = TextHelper::substr($color_group, 0, 1) == '#' ? TextHelper::substr($color_group, 1) : $color_group;
	$data_group_limit = $data_group_limit ? NumberHelper::numeric($data_group_limit, 'float') * 1024 : '5120';

	if (!empty($name))
	{
		if (!GroupsCache::load()->group_name_exists($name))
		{
			// Insert in database
			$group_auth = array('auth_flood' => $auth_flood, 'pm_group_limit' => $pm_group_limit, 'data_group_limit' => $data_group_limit);
			$result = PersistenceContext::get_querier()->insert(DB_TABLE_GROUP, array('name' => $name, 'img' => $img, 'color' => $color_group, 'auth' => TextHelper::serialize($group_auth), 'members' => ''));

			GroupsCache::invalidate(); // Regeneration of the group's cache

			AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $result->get_last_inserted_id());
		}
		else
			AppContext::get_response()->redirect('/admin/admin_groups.php?add=1&error=group_already_exists#message_helper');
	}
	else
	{
		AppContext::get_response()->redirect('/admin/admin_groups.php?add=1&error=incomplete#message_helper');
	}
}
elseif (!empty($idgroup) && $del_group) // Delete group.
{
	$array_members = array();
	try {
		$array_members = explode('|', PersistenceContext::get_querier()->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id=:id', array('id' => $idgroup)));
	} catch (RowNotFoundException $e) {}

	if(!empty($array_members))
	{
		foreach ($array_members as $key => $user_id)
		{
			if (!empty($user_id) && UserService::user_exists('WHERE user_id=:user_id', array('user_id' => $user_id)))
				GroupsService::remove_member($user_id, $idgroup); // Updating the list of members being in the deleted group.
		}
	}

	PersistenceContext::get_querier()->delete(DB_TABLE_GROUP, 'WHERE id=:id', array('id' => $idgroup));

	GroupsCache::invalidate(); // Regeneration of the group's cache

	AppContext::get_response()->redirect(HOST . DIR . '/admin/admin_groups.php');
}
elseif (!empty($idgroup) && $add_mbr) // Add member to group
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection

	$login = retrieve(POST, 'login_mbr', '');
	$user_id = 0;
	try {
		$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name=:login', array('login' => $login));
	} catch (RowNotFoundException $e) {}

	if (!empty($user_id))
	{
		if (GroupsService::add_member($user_id, $idgroup)) // Success.
		{
			GroupsCache::invalidate();
			SessionData::recheck_cached_data_from_user_id($user_id);
			AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup . '#add');
		}
		else
		{
			AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup . '&error=already_group#message_helper');
		}
	}
	else
	{
		AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup . '&error=incomplete#message_helper');
	}
}
elseif ($del_mbr && !empty($user_id) && !empty($idgroup)) // Delete member from the group
{
	AppContext::get_session()->csrf_get_protect(); // csrf protection

	GroupsService::remove_member($user_id, $idgroup);
	GroupsCache::invalidate();
	SessionData::recheck_cached_data_from_user_id($user_id);
	AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup . '#add');
}
elseif (!empty($_FILES['upload_groups']['name'])) // Upload
{
	// If the folder is not writable, we try CHMOD 755
	@clearstatcache();
	$dir = PATH_TO_ROOT .'/images/group/';
	if (!is_writable($dir))
	{
		$is_writable = @chmod($dir, 0755);
	}

	@clearstatcache();
	$error = '';
	if (is_writable($dir)) // Writable folder, upload is available
	{
		$authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();

		if (!empty($authorized_pictures_extensions))
		{
			$Upload = new Upload($dir);
			$Upload->disableContentCheck();
			if (!$Upload->file('upload_groups', '`\.(' . implode('|', array_map('preg_quote', $authorized_pictures_extensions)) . ')+$`iu'))
			{
				$error = $Upload->get_error();
			}
		}
		else
			$error = 'e_upload_invalid_format';
	}
	else
		$error = 'e_upload_failed_unwritable';

	$error = !empty($error) ? '&error=' . $error : '&success=1';
	AppContext::get_response()->redirect(HOST . SCRIPT . '?add=1' . $error);
}
elseif (!empty($idgroup)) // Group editing interface
{
	$view = new FileTemplate('admin/admin_groups_management2.tpl');
	$view->add_lang($lang);

	try {
		$group = PersistenceContext::get_querier()->select_single_row(DB_TABLE_GROUP, array('id', 'name', 'img', 'color', 'auth', 'members'), 'WHERE id=:id', array('id' => $idgroup));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($group['id']))
	{
		// errors management
		$get_error = retrieve(GET, 'error', '');
		if ($get_error == 'incomplete')
		{
			$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.incomplete'], MessageHelper::NOTICE));
		}
		elseif ($get_error == 'already_group')
		{
			$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.already.group'], MessageHelper::NOTICE));
		}

		// Get the groups images folders
		$img_groups = '<option value="">--</option>';
		$image_folder_path = new Folder(PATH_TO_ROOT . '/images/group');
		foreach ($image_folder_path->get_files('`\.(png|webp|jpg|bmp|gif)$`iu') as $image)
		{
			$file = $image->get_name();
			$selected = ($file == $group['img']) ? ' selected="selected"' : '';
			$img_groups .= '<option value="' . $file . '"' . $selected . '>' . $file . '</option>';
		}
		$array_group = TextHelper::unserialize(stripslashes($group['auth']));

		$view->put_all(array(
			'C_EDIT_GROUP'    => true,
			'C_HAS_THUMBNAIL' => !empty($group['img']),

			'NAME'                => stripslashes($group['name']),
			'GROUP_ID'            => $idgroup,
			'THUMBNAILS_LIST'     => $img_groups,
			'AUTH_FLOOD_ENABLED'  => $array_group['auth_flood'] == 1 ? 'checked="checked"' : '',
			'AUTH_FLOOD_DISABLED' => $array_group['auth_flood'] == 0 ? 'checked="checked"' : '',
			'GROUP_PM_LIMIT'      => $array_group['pm_group_limit'],
			'GROUP_DATA_LIMIT'    => NumberHelper::round($array_group['data_group_limit']/1024, 2),
			'GROUP_COLOR'         => (TextHelper::substr($group['color'], 0, 1) != '#' ? '#' : '') . $group['color'],

			'U_THUMBNAIL' => Url::to_rel('/images/group/' . $group['img']),
		));

		// Group members list
		$members = '';
		try {
			$members = PersistenceContext::get_querier()->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id=:id', array('id' => NumberHelper::numeric($group['id'])));
		} catch (RowNotFoundException $e) {}

		$number_member = 0;
		if (!empty($members))
		{
			$members = str_replace('|', ',', $members);
			$result = PersistenceContext::get_querier()->select('SELECT user_id, display_name, level, user_groups
				FROM ' . DB_TABLE_MEMBER . '
				WHERE user_id IN (' . $members . ')');

			while ($row = $result->fetch())
			{
				$group_color = User::get_group_color($row['user_groups'], $row['level']);

				$view->assign_block_vars('member', array(
					'C_GROUP_COLOR' => !empty($group_color),

					'USER_ID'     => $row['user_id'],
					'LOGIN'       => $row['display_name'],
					'LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'GROUP_COLOR' => $group_color,

					'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel()
				));
				$number_member++;
			}
			$result->dispose();
		}

		$view->put_all(array(
			'C_NO_MEMBER' => $number_member == 0
		));
	}
	else
		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);

	$view->display();
}
elseif ($add) // Add group interface
{
	$view = new FileTemplate('admin/admin_groups_management2.tpl');
	$view->add_lang($lang);

	// Errors management
	$get_success = retrieve(GET, 'success', '');
	if ($get_success == 1)
	{
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.process.success'], MessageHelper::SUCCESS, 10));
	}
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
	{
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.incomplete'], MessageHelper::NOTICE));
	}
	elseif ($get_error == 'group_already_exists')
	{
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang['warning.element.already.exists'], MessageHelper::NOTICE));
	}

	// Get the groups images folders contained in the /images/group folder.
	$img_groups = '<option value="" selected="selected">--</option>';

	$img_groups = '<option value="">--</option>';
	$image_folder_path = new Folder(PATH_TO_ROOT . '/images/group');
	foreach ($image_folder_path->get_files('`\.(png|webp|jpg|bmp|gif)$`iu') as $image)
	{
		$file = $image->get_name();
		$img_groups .= '<option value="' . $file . '">' . $file . '</option>';
	}

	$view->put_all(array(
		'C_ADD_GROUP' => true,

		'THUMBNAILS_LIST'    => $img_groups,
		'MAX_FILE_SIZE'      => ServerConfiguration::get_upload_max_filesize(),
        'MAX_FILE_SIZE_TEXT' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()),
		'ALLOWED_EXTENSIONS' => implode('", "',FileUploadConfig::load()->get_authorized_picture_extensions()),
	));

	$view->display();
}
else // Groups list
{
	$view = new FileTemplate('admin/admin_groups_management.tpl');
	$view->add_lang($lang);

	$result = PersistenceContext::get_querier()->select("SELECT id, name, img, color
	FROM " . DB_TABLE_GROUP . "
	ORDER BY name");
	while ($row = $result->fetch())
	{
		$view->assign_block_vars('group', array(
			'C_THUMBNAIL'   => !empty($row['img']),
			'C_GROUP_COLOR' => !empty($row['color']),

			'ID'          => $row['id'],
			'NAME'        => stripslashes($row['name']),
			'GROUP_COLOR' => '#' . $row['color'],

			'U_THUMBNAIL' => Url::to_rel('/images/group/' . $row['img']),
			'U_GROUP'     => UserUrlBuilder::group($row['id'])->rel(),
		));
	}
	$result->dispose();

	$view->display();
}

require_once('../admin/admin_footer.php');
?>
