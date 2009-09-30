<?php
/*##################################################
 *                               admin_groups.php
 *                            -------------------
 *   begin                : June 01, 2006
 *   copyright          : (C) 2006 Viarre Régis
 *   email                : crowkait@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$idgroup = retrieve(GET, 'id', 0);
$idgroup_post = retrieve(POST, 'id', 0);
$add = retrieve(GET, 'add', 0);
$add_post = retrieve(POST, 'add', 0);
$del_group = !empty($_GET['del']) ? true : false;
$add_mbr = !empty($_POST['add_mbr']) ? true : false;
$del_mbr = !empty($_GET['del_mbr']) ? true : false;
$user_id = retrieve(GET, 'user_id', 0);

if (!empty($_POST['valid']) && !empty($idgroup_post)) //Modification du groupe.
{
	$name = retrieve(POST, 'name', '');
	$img = retrieve(POST, 'img', '');
	$auth_flood = retrieve(POST, 'auth_flood', 1);
	$pm_group_limit = retrieve(POST, 'pm_group_limit', 75);
	$color_group = retrieve(POST, 'color_group', '');
	$data_group_limit = isset($_POST['data_group_limit']) ? numeric($_POST['data_group_limit'], 'float') * 1024 : '5120';
		
	$group_auth = array('auth_flood' => $auth_flood, 'pm_group_limit' => $pm_group_limit, 'data_group_limit' => $data_group_limit);
	$Sql->query_inject("UPDATE " . DB_TABLE_GROUP . " SET name = '" . $name . "', img = '" . $img . "', color = '" . $color_group . "', auth = '" . serialize($group_auth) . "' WHERE id = '" . $idgroup_post . "'", __LINE__, __FILE__);
	
	GroupsCacheData::invalidate(); //On régénère le fichier de cache des groupes
	
	redirect('/admin/admin_groups.php?id=' . $idgroup_post);
}
elseif (!empty($_POST['valid']) && $add_post) //ajout  du groupe.
{
	$name = retrieve(POST, 'name', '');
	$img = retrieve(POST, 'img', '');
	$auth_flood = retrieve(POST, 'auth_flood', 1);
	$pm_group_limit = retrieve(POST, 'pm_group_limit', 75);
	$color_group = retrieve(POST, 'color_group', '');
	$data_group_limit = isset($_POST['data_group_limit']) ? numeric($_POST['data_group_limit'], 'float') * 1024 : '5120';
	
	if (!empty($name))
	{
		//Insertion
		$group_auth = array('auth_flood' => $auth_flood, 'pm_group_limit' => $pm_group_limit, 'data_group_limit' => $data_group_limit);
		$Sql->query_inject("INSERT INTO " . DB_TABLE_GROUP . " (name, img, color, auth, members) VALUES ('" . $name . "', '" . $img . "', '" . $color_group . "', '" . serialize($group_auth) . "', '')", __LINE__, __FILE__);
		
		GroupsCacheData::invalidate(); //On régénère le fichier de cache des groupes
		
		redirect('/admin/admin_groups.php?id=' . $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "group"));
	}
	else
	{
		redirect('/admin/admin_groups.php?error=incomplete#errorh');
	}
}
elseif (!empty($idgroup) && $del_group) //Suppression du groupe.
{	
	$array_members = explode('|', $Sql->query("SELECT members FROM " . DB_TABLE_GROUP . " WHERE id = '" . $idgroup . "'", __LINE__, __FILE__));
	foreach ($array_members as $key => $user_id)
	{
		GroupsService::remove_member($user_id, $idgroup); //Mise à jour des membres étant dans le groupe supprimé.
	}

	$Sql->query_inject("DELETE FROM " . DB_TABLE_GROUP . " WHERE id = '" . $idgroup . "'", __LINE__, __FILE__); //On supprime dans la bdd.
		
	GroupsCacheData::invalidate(); //On régénère le fichier de cache des groupes
	
	redirect(HOST . SCRIPT);
}
elseif (!empty($idgroup) && $add_mbr) //Ajout du membre au groupe.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	$login = retrieve(POST, 'login_mbr', '');
	$user_id = $Sql->query("SELECT user_id FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
	if (!empty($user_id))
	{
		if (GroupsService::add_member($user_id, $idgroup)) //Succès.
		{
			GroupsCacheData::invalidate();
			redirect('/admin/admin_groups.php?id=' . $idgroup . '#add');
		}
		else
		{
			redirect('/admin/admin_groups.php?id=' . $idgroup . '&error=already_group#errorh');
		}
	}
	else
	{
		redirect('/admin/admin_groups.php?id=' . $idgroup . '&error=incomplete#errorh');
	}
}
elseif ($del_mbr && !empty($user_id) && !empty($idgroup)) //Suppression du membre du groupe.
{
	$Session->csrf_get_protect(); //Protection csrf
	
	GroupsService::remove_member($user_id, $idgroup);
	GroupsCacheData::invalidate();
	redirect('/admin/admin_groups.php?id=' . $idgroup . '#add');
}
elseif (!empty($_FILES['upload_groups']['name'])) //Upload
{
	//Si le dossier n'est pas en écriture on tente un CHMOD 777
	@clearstatcache();
	$dir = '../images/group/';
	if (!is_writable($dir))
	{
		$is_writable = (@chmod($dir, 0777)) ? true : false;
	}
	
	@clearstatcache();
	$error = '';
	if (is_writable($dir)) //Dossier en écriture, upload possible
	{
		import('io/upload');
		$Upload = new Upload($dir);
		if (!$Upload->file('upload_groups', '`([a-z0-9()_-])+\.(jpg|gif|png|bmp)+$`i'))
		{
			$error = $Upload->error;
		}
	}
	else
		$error = 'e_upload_failed_unwritable';
	
	$error = !empty($error) ? '&error=' . $error : '';
	redirect(HOST . SCRIPT . '?add=1' . $error);	
}
elseif (!empty($idgroup)) //Interface d'édition du groupe.
{
	$Template->set_filenames(array(
		'admin_groups_management2'=> 'admin/admin_groups_management2.tpl'
	));
	
	$group = $Sql->query_array(DB_TABLE_GROUP, 'id', 'name', 'img', 'color', 'auth', 'members', "WHERE id = '" . $idgroup . "'", __LINE__, __FILE__);
	if (!empty($group['id']))
	{
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		if ($get_error == 'incomplete')
		{
			$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
		}
		elseif ($get_error == 'already_group')
		{
			$Errorh->handler($LANG['e_already_group'], E_USER_NOTICE);
		}
		
		$nbr_member_group = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_groups = '" . $group['id'] . "'", __LINE__, __FILE__);
		//On crée une pagination si le nombre de membre est trop important.
		import('util/pagination');
		$Pagination = new Pagination();
		
		//On recupère les dossier des images des groupes.
		import('io/filesystem/folder');

		$img_groups = '<option value="">--</option>';
		$image_folder_path = new Folder(PATH_TO_ROOT . '/images/group');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
		{
			$file = $image->get_name();
			$selected = ($file == $group['img']) ? ' selected="selected"' : '';
			$img_groups .= '<option value="' . $file . '"' . $selected . '>' . $file . '</option>';
		}
	
		$array_group = unserialize($group['auth']);
		$Template->assign_vars(array(
			'NAME' => $group['name'],
			'IMG' => $group['img'],
			'GROUP_ID' => $idgroup,
			'PAGINATION' => $Pagination->display('admin_groups.php?id=' . $idgroup . '&amp;p=%d', $nbr_member_group, 'p', 25, 3),
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'IMG_GROUPS' => $img_groups,
			'C_EDIT_GROUP' => true,
			'AUTH_FLOOD_ENABLED' => $array_group['auth_flood'] == 1 ? 'checked="checked"' : '',
			'AUTH_FLOOD_DISABLED' => $array_group['auth_flood'] == 0 ? 'checked="checked"' : '',
			'PM_GROUP_LIMIT' => $array_group['pm_group_limit'],
			'DATA_GROUP_LIMIT' => number_round($array_group['data_group_limit']/1024, 2),
			'COLOR_GROUP' => $group['color'],
			'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
			'L_REQUIRE_LOGIN' => $LANG['require_name'],
			'L_CONFIRM_DEL_USER_GROUP' => $LANG['confirm_del_member_group'],
			'L_GROUPS_MANAGEMENT' => $LANG['groups_management'],
			'L_ADD_GROUPS' => $LANG['groups_add'],
			'L_REQUIRE' => $LANG['require'],
			'L_NAME' => $LANG['name'],
			'L_IMG_ASSOC_GROUP' => $LANG['img_assoc_group'],
			'L_IMG_ASSOC_GROUP_EXPLAIN' => $LANG['img_assoc_group_explain'],
			'L_AUTH_FLOOD' => $LANG['auth_flood'],
			'L_PM_GROUP_LIMIT' => $LANG['pm_group_limit'],
			'L_PM_GROUP_LIMIT_EXPLAIN' => $LANG['pm_group_limit_explain'],
			'L_DATA_GROUP_LIMIT' => $LANG['data_group_limit'],
			'L_DATA_GROUP_LIMIT_EXPLAIN' => $LANG['data_group_limit_explain'],
			'L_COLOR_GROUP' => $LANG['color_group'],
			'L_COLOR_GROUP_EXPLAIN' => $LANG['color_group_explain'],
			'L_YES' => $LANG['yes'],
			'L_NO' => $LANG['no'],
			'L_ADD' => $LANG['add'],
			'L_MB' => $LANG['unit_megabytes'],
			'L_MBR_GROUP' => $LANG['mbrs_group'],
			'L_PSEUDO' => $LANG['pseudo'],
			'L_SEARCH' => $LANG['search'],
			'L_UPDATE' => $LANG['update'],
			'L_RESET' => $LANG['reset'],
			'L_DELETE' => $LANG['delete'],
			'L_ADD_MBR_GROUP' => $LANG['add_mbr_group']
		));
		
		//Liste des membres du groupe.
		$members = $Sql->query("SELECT members FROM " . DB_TABLE_GROUP . " WHERE id = '" . numeric($group['id']) . "'", __LINE__, __FILE__);
		$members = explode('|', $members);
		foreach ($members as $key => $user_id)
		{
			$login = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . numeric($user_id) . "'", __LINE__, __FILE__);
			if (!empty($login))
			{
				$Template->assign_block_vars('member', array(
					'USER_ID' => $user_id,
					'LOGIN' => $login,
					'U_USER_ID' => url('.php?id=' . $user_id, '-' . $user_id . '.php')
				));
			}
		}
	}
	else
		redirect(HOST . SCRIPT);
	
	$Template->pparse('admin_groups_management2');
}
elseif ($add) //Interface d'ajout du groupe.
{
	$Template->set_filenames(array(
	'admin_groups_management2'=> 'admin/admin_groups_management2.tpl'
	));
	
	//On recupère les dossier des images des groupes contenu dans le dossier /images/group.
	$img_groups = '<option value="" selected="selected">--</option>';
	import('io/filesystem/folder');

	$img_groups = '<option value="">--</option>';
	$image_folder_path = new Folder(PATH_TO_ROOT . '/images/group');
	foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif)$`i') as $image)
	{
		$file = $image->get_name();
		$img_groups .= '<option value="' . $file . '">' . $file . '</option>';
	}
		
	$Template->assign_vars(array(
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'IMG_GROUPS' => $img_groups,
		'C_ADD_GROUP' => true,
		'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
		'L_REQUIRE_NAME' => $LANG['require_name'],
		'L_CONFIRM_DEL_USER_GROUP' => $LANG['confirm_del_member_group'],
		'L_GROUPS_MANAGEMENT' => $LANG['groups_management'],
		'L_ADD_GROUPS' => $LANG['groups_add'],
		'L_REQUIRE' => $LANG['require'],
		'L_UPLOAD_GROUPS' => $LANG['upload_group'],
		'L_UPLOAD_FORMAT' => $LANG['upload_rank_format'],
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
		'L_COLOR_GROUP_EXPLAIN' => $LANG['color_group_explain'],
		'L_MB' => $LANG['unit_megabytes'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_ADD' => $LANG['add']
	));

	$Template->pparse('admin_groups_management2');
}
else //Liste des groupes.
{
	$Template->set_filenames(array(
		'admin_groups_management'=> 'admin/admin_groups_management.tpl'
	 ));
	 
	$nbr_group = $Sql->count_table("group", __LINE__, __FILE__);
	//On crée une pagination si le nombre de group est trop important.
	import('util/pagination');
	$Pagination = new Pagination();
	
	$Template->assign_vars(array(
		'PAGINATION' => $Pagination->display('admin_groups', $nbr_group, 'p', 25, 3),
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'KERNEL_EDITOR' => display_editor(),
		'L_CONFIRM_DEL_GROUP' => $LANG['confirm_del_group'],
		'L_GROUPS_MANAGEMENT' => $LANG['groups_management'],
		'L_ADD_GROUPS' => $LANG['groups_add'],
		'L_NAME' => $LANG['name'],
		'L_IMAGE' => $LANG['image'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete']
	));
	  
  
	$result = $Sql->query_while("SELECT id, name, img
	FROM " . DB_TABLE_GROUP . "
	ORDER BY name
	" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('group', array(
			'LINK' => url('.php?g=' . $row['id'], '-0.php?g=' . $row['id']),
			'ID' => $row['id'],
			'NAME' => $row['name'],
			'IMAGE' => !empty($row['img']) ? '<img src="../images/group/' . $row['img'] . '" alt="" />' : ''
		));
	}
	$Sql->query_close($result);
	
	$Template->pparse('admin_groups_management');
}

require_once('../admin/admin_footer.php');

?>