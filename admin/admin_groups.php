<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 15
 * @since       PHPBoost 1.2 - 2005 06 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
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

if ($valid && !empty($idgroup_post)) //Modification du groupe.
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

	GroupsCache::invalidate(); //On régénère le fichier de cache des groupes

	AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup_post);
}
elseif ($valid && $add_post) //ajout  du groupe.
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
			//Insertion
			$group_auth = array('auth_flood' => $auth_flood, 'pm_group_limit' => $pm_group_limit, 'data_group_limit' => $data_group_limit);
			$result = PersistenceContext::get_querier()->insert(DB_TABLE_GROUP, array('name' => $name, 'img' => $img, 'color' => $color_group, 'auth' => TextHelper::serialize($group_auth), 'members' => ''));

			GroupsCache::invalidate(); //On régénère le fichier de cache des groupes

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
elseif (!empty($idgroup) && $del_group) //Suppression du groupe.
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
				GroupsService::remove_member($user_id, $idgroup); //Mise à jour des membres étant dans le groupe supprimé.
		}
	}

	PersistenceContext::get_querier()->delete(DB_TABLE_GROUP, 'WHERE id=:id', array('id' => $idgroup));

	GroupsCache::invalidate(); //On régénère le fichier de cache des groupes

	AppContext::get_response()->redirect(HOST . DIR . '/admin/admin_groups.php');
}
elseif (!empty($idgroup) && $add_mbr) //Ajout du membre au groupe.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$login = retrieve(POST, 'login_mbr', '');
	$user_id = 0;
	try {
		$user_id = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER, 'user_id', 'WHERE display_name=:login', array('login' => $login));
	} catch (RowNotFoundException $e) {}

	if (!empty($user_id))
	{
		if (GroupsService::add_member($user_id, $idgroup)) //Succès.
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
elseif ($del_mbr && !empty($user_id) && !empty($idgroup)) //Suppression du membre du groupe.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	GroupsService::remove_member($user_id, $idgroup);
	GroupsCache::invalidate();
	SessionData::recheck_cached_data_from_user_id($user_id);
	AppContext::get_response()->redirect('/admin/admin_groups.php?id=' . $idgroup . '#add');
}
elseif (!empty($_FILES['upload_groups']['name'])) //Upload
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = PATH_TO_ROOT .'/images/group/';
	if (!is_writable($dir))
	{
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	}

	@clearstatcache();
	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
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
elseif (!empty($idgroup)) //Interface d'édition du groupe.
{
	$template = new FileTemplate('admin/admin_groups_management2.tpl');

	try {
		$group = PersistenceContext::get_querier()->select_single_row(DB_TABLE_GROUP, array('id', 'name', 'img', 'color', 'auth', 'members'), 'WHERE id=:id', array('id' => $idgroup));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($group['id']))
	{
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		if ($get_error == 'incomplete')
		{
			$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));
		}
		elseif ($get_error == 'already_group')
		{
			$template->put('message_helper', MessageHelper::display($LANG['e_already_group'], MessageHelper::NOTICE));
		}

		//On recupère les dossier des images des groupes.
		$img_groups = '<option value="">--</option>';
		$image_folder_path = new Folder(PATH_TO_ROOT . '/images/group');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`iu') as $image)
		{
			$file = $image->get_name();
			$selected = ($file == $group['img']) ? ' selected="selected"' : '';
			$img_groups .= '<option value="' . $file . '"' . $selected . '>' . $file . '</option>';
		}
		$array_group = TextHelper::unserialize(stripslashes($group['auth']));

		$template->put_all(array(
			'NAME' => stripslashes($group['name']),
			'IMG' => $group['img'],
			'GROUP_ID' => $idgroup,
			'IMG_GROUPS' => $img_groups,
			'C_EDIT_GROUP' => true,
			'AUTH_FLOOD_ENABLED' => $array_group['auth_flood'] == 1 ? 'checked="checked"' : '',
			'AUTH_FLOOD_DISABLED' => $array_group['auth_flood'] == 0 ? 'checked="checked"' : '',
			'PM_GROUP_LIMIT' => $array_group['pm_group_limit'],
			'DATA_GROUP_LIMIT' => NumberHelper::round($array_group['data_group_limit']/1024, 2),
			'COLOR_GROUP' => (TextHelper::substr($group['color'], 0, 1) != '#' ? '#' : '') . $group['color'],
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_LOGIN' => $LANG['require_name'],
			'L_CONFIRM_DEL_USER_GROUP' => LangLoader::get_message('confirm.delete', 'status-messages-common'),
			'L_GROUPS_MANAGEMENT' => $LANG['groups_management'],
			'L_ADD_GROUPS' => $LANG['groups_add'],
			'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
			'L_NAME' => $LANG['name'],
			'L_IMG_ASSOC_GROUP' => $LANG['img_assoc_group'],
			'L_IMG_ASSOC_GROUP_EXPLAIN' => $LANG['img_assoc_group_explain'],
			'L_AUTH_FLOOD' => $LANG['auth_flood'],
			'L_PM_GROUP_LIMIT' => $LANG['pm_group_limit'],
			'L_PM_GROUP_LIMIT_EXPLAIN' => $LANG['pm_group_limit_explain'],
			'L_DATA_GROUP_LIMIT' => $LANG['data_group_limit'],
			'L_DATA_GROUP_LIMIT_EXPLAIN' => $LANG['data_group_limit_explain'],
			'L_COLOR_GROUP' => $LANG['color_group'],
			'L_DELETE_GROUP_COLOR' => $LANG['delete_color_group'],
			'L_YES' => LangLoader::get_message('yes', 'common'),
			'L_NO' => LangLoader::get_message('no', 'common'),
			'L_ADD' => LangLoader::get_message('add', 'common'),
			'L_MBR_GROUP' => $LANG['mbrs_group'],
			'L_PSEUDO' => LangLoader::get_message('display_name', 'user-common'),
			'L_SEARCH' => $LANG['search'],
			'L_UPDATE' => $LANG['update'],
			'L_RESET' => $LANG['reset'],
			'L_DELETE' => LangLoader::get_message('delete', 'common'),
			'L_ADD_MBR_GROUP' => $LANG['add_mbr_group']
		));

		//Liste des membres du groupe.
		$members = '';
		try {
			$members = PersistenceContext::get_querier()->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id=:id', array('id' => NumberHelper::numeric($group['id'])));
		} catch (RowNotFoundException $e) {}

		$number_member = 0;
		if (!empty($members))
		{
			$members = str_replace('|', ',', $members);
			$result = PersistenceContext::get_querier()->select('SELECT user_id, display_name, level, groups
				FROM ' . DB_TABLE_MEMBER . '
				WHERE user_id IN (' . $members . ')');

			while ($row = $result->fetch())
			{
				$group_color = User::get_group_color($row['groups'], $row['level']);

				$template->assign_block_vars('member', array(
					'C_GROUP_COLOR' => !empty($group_color),
					'USER_ID' => $row['user_id'],
					'LOGIN' => $row['display_name'],
					'LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'GROUP_COLOR' => $group_color,
					'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel()
				));
				$number_member++;
			}
			$result->dispose();
		}

		$template->put_all(array(
			'C_NO_MEMBERS' => $number_member == 0,
			'NO_MEMBERS' => LangLoader::get_message('no_member', 'user-common')
		));
	}
	else
		AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);

	$template->display();
}
elseif ($add) //Interface d'ajout du groupe.
{
	$template = new FileTemplate('admin/admin_groups_management2.tpl');

	//Gestion erreur.
	$get_success = retrieve(GET, 'success', '');
	if ($get_success == 1)
	{
		$template->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 10));
	}
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
	{
		$template->put('message_helper', MessageHelper::display($LANG['e_incomplete'], MessageHelper::NOTICE));
	}
	elseif ($get_error == 'group_already_exists')
	{
		$template->put('message_helper', MessageHelper::display(LangLoader::get_message('element.already_exists', 'status-messages-common'), MessageHelper::NOTICE));
	}

	//On recupère les dossier des images des groupes contenu dans le dossier /images/group.
	$img_groups = '<option value="" selected="selected">--</option>';

	$img_groups = '<option value="">--</option>';
	$image_folder_path = new Folder(PATH_TO_ROOT . '/images/group');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`iu') as $image)
	{
		$file = $image->get_name();
		$img_groups .= '<option value="' . $file . '">' . $file . '</option>';
	}

	$template->put_all(array(
		'IMG_GROUPS' => $img_groups,
		'C_ADD_GROUP' => true,
		'MAX_FILE_SIZE' => ServerConfiguration::get_upload_max_filesize(),
        'MAX_FILE_SIZE_TEXT' => File::get_formated_size(ServerConfiguration::get_upload_max_filesize()),
		'ALLOWED_EXTENSIONS' => implode('", "',FileUploadConfig::load()->get_authorized_picture_extensions()),
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE_NAME' => $LANG['require_name'],
		'L_CONFIRM_DEL_USER_GROUP' => LangLoader::get_message('confirm.delete', 'status-messages-common'),
		'L_GROUPS_MANAGEMENT' => $LANG['groups_management'],
		'L_ADD_GROUPS' => $LANG['groups_add'],
		'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
		'L_UPLOAD_GROUPS' => $LANG['upload_group'],
		'L_UPLOAD_FORMAT' => $LANG['explain_upload_img'],
		'L_UPLOAD' => $LANG['upload'],
		'L_NAME' => $LANG['name'],
		'L_IMG_ASSOC_GROUP' => $LANG['img_assoc_group'],
		'L_IMG_ASSOC_GROUP_EXPLAIN' => $LANG['img_assoc_group_explain'],
		'L_AUTH_FLOOD' => $LANG['auth_flood'],
		'L_PM_GROUP_LIMIT' => $LANG['pm_group_limit'],
		'L_PM_GROUP_LIMIT_EXPLAIN' => $LANG['pm_group_limit_explain'],
		'L_DATA_GROUP_LIMIT' => $LANG['data_group_limit'],
		'L_DATA_GROUP_LIMIT_EXPLAIN' => $LANG['data_group_limit_explain'],
		'L_COLOR_GROUP' => $LANG['color_group'],
		'L_YES' => LangLoader::get_message('yes', 'common'),
		'L_NO' => LangLoader::get_message('no', 'common'),
		'L_ADD' => LangLoader::get_message('add', 'common')
	));

	$template->display();
}
else //Liste des groupes.
{
	$template = new FileTemplate('admin/admin_groups_management.tpl');

	$group_cache = GroupsCache::load()->get_groups();

	$nbr_group = count($group_cache);

	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');

	$template->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_CONFIRM_DEL_GROUP' => LangLoader::get_message('confirm.delete', 'status-messages-common'),
		'L_GROUPS_MANAGEMENT' => $LANG['groups_management'],
		'L_ADD_GROUPS' => $LANG['groups_add'],
		'L_NAME' => $LANG['name'],
		'L_IMAGE' => $LANG['image'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => LangLoader::get_message('delete', 'common')
	));


	$result = PersistenceContext::get_querier()->select("SELECT id, name, img, color
	FROM " . DB_TABLE_GROUP . "
	ORDER BY name");
	while ($row = $result->fetch())
	{
		$template->assign_block_vars('group', array(
			'C_GROUP_COLOR' => !empty($row['color']),
			'U_USER_GROUP' => UserUrlBuilder::group($row['id'])->rel(),
			'ID' => $row['id'],
			'NAME' => stripslashes($row['name']),
			'GROUP_COLOR' => '#' . $row['color'],
			'IMAGE' => !empty($row['img']) ? '<img src="'. PATH_TO_ROOT .'/images/group/' . $row['img'] . '" alt="' . stripslashes($row['name']) . '" />' : ''
		));
	}
	$result->dispose();

	$template->display();
}

require_once('../admin/admin_footer.php');
?>
