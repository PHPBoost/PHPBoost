<?php
/*##################################################
 *                               admin_video_cats.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
include_once('video_begin.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

include_once('video_cats.class.php');
$video_categories = new VideoCats();

$id_up = retrieve(GET, 'id_up', 0);
$id_down = retrieve(GET, 'id_down', 0);
$id_show = retrieve(GET, 'show', 0);
$id_hide = retrieve(GET, 'hide', 0);
$cat_to_del = retrieve(GET, 'del', 0);
$cat_to_del_post = retrieve(POST, 'cat_to_del', 0);
$id_edit = retrieve(GET, 'edit', 0);
$new_cat = retrieve(GET, 'new', false);
$error = retrieve(GET, 'error', '');

$Template->set_filenames(array(
	'admin_video_cat'=> 'video/admin_video_cats.tpl'
));

$Template->assign_vars(array(
	'L_VIDEO_MANAGEMENT' => $VIDEO_LANG['video_management'],
	'L_CATS_MANAGEMENT' => $VIDEO_LANG['cats_management'],
	'L_CONFIG_MANAGEMENT' => $VIDEO_LANG['video_configuration'],
	'L_VIDEO_LIST' => $VIDEO_LANG['video_list'],
	'L_ADD_CAT' => $VIDEO_LANG['add_cat']
));

if( $id_up > 0 )
{
	$video_categories->move($id_up, MOVE_CATEGORY_UP);
	redirect(transid('admin_video_cats.php'));
}
elseif( $id_down > 0 )
{
	$video_categories->move($id_down, MOVE_CATEGORY_DOWN);
	redirect(transid('admin_video_cats.php'));
}
elseif( $id_show > 0 )
{
	$video_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	redirect(transid('admin_video_cats.php'));
}
elseif( $id_hide > 0 )
{
	$video_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	redirect(transid('admin_video_cats.php'));
}
elseif( $cat_to_del > 0 )
{
	$Template->assign_vars(array(
		'L_REMOVING_CATEGORY' => $VIDEO_LANG['removing_category'],
		'L_EXPLAIN_REMOVING' => $VIDEO_LANG['explain_removing_category'],
		'L_DELETE_CATEGORY_AND_CONTENT' => $VIDEO_LANG['delete_category_and_its_content'],
		'L_MOVE_CONTENT' => $VIDEO_LANG['move_category_content'],
		'L_SUBMIT' => $LANG['delete']
	));
	$Template->assign_block_vars('removing_interface', array(
		'CATEGORY_TREE' => $video_categories->build_select_form(0, 'id_parent', 'id_parent', $cat_to_del),
		'IDCAT' => $cat_to_del,
	));
}
elseif( !empty($_POST['submit']) )
{
	$error_string = 'e_success';
	//Deleting a category
	if( !empty( $cat_to_del_post) )
	{
		$delete_content = (!empty($_POST['action']) && $_POST['action'] == 'move') ? false : true;
		$id_parent = retrieve(POST, 'id_parent', 0);

		if( $delete_content )
		{
			$video_categories->Delete_category_recursively($cat_to_del_post);
		}
		else
		{
			$video_categories->Delete_category_and_move_content($cat_to_del_post, $id_parent);
		}
	}
	else
	{
		$id_cat = retrieve(POST, 'idcat', 0);
		$id_parent = retrieve(POST, 'id_parent', 0);
		$name = retrieve(POST, 'name', '');
		$image = retrieve(POST, 'image', '');
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);

		//Autorisations
		if( !empty($_POST['special_auth']) )
		{
			$array_auth_all = Authorizations::build_auth_array_from_form(VIDEO_READ, VIDEO_WRITE, VIDEO_APROB, VIDEO_FLOOD, VIDEO_EDIT, VIDEO_DELETE, VIDEO_MODERATE);
			$new_auth = addslashes(serialize($array_auth_all));
		}
		else
			$new_auth = addslashes(serialize($VIDEO_CATS[0]['auth']));

		if( empty($name) )
			redirect(transid(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');

		if( $id_cat > 0 )
			$error_string = $video_categories->Update_category($id_cat, $id_parent, $name, $description, $image, $new_auth);
		else
			$error_string = $video_categories->add($id_parent, $name, $description, $image, $new_auth);
	}

	$Cache->Generate_module_file('video');

	redirect(transid(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
}
//Updating the number of subquestions of each category
elseif( !empty($_GET['recount']) )
{
	$video_categories->Recount_all_video();
	redirect(transid(HOST . SCRIPT . '?error=e_recount_success', '', '&'));
}
elseif( $new_cat XOR $id_edit > 0 )
{
	$Template->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'L_CATEGORY' => $VIDEO_LANG['category'],
		'L_REQUIRED_FIELDS' => $VIDEO_LANG['required_fields'],
		'L_NAME' => $VIDEO_LANG['category_name'],
		'L_LOCATION' => $VIDEO_LANG['category_location'],
		'L_DESCRIPTION' => $VIDEO_LANG['cat_description'],
		'L_IMAGE' => $VIDEO_LANG['category_image'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_READ_AUTH' => $VIDEO_LANG['auth_read'],
		'L_WRITE_AUTH' => $VIDEO_LANG['auth_write'],
		'L_APROB_AUTH' => $VIDEO_LANG['auth_aprob'],
		'L_FLOOD_AUTH' => $VIDEO_LANG['auth_flood'],
		'L_EDIT_AUTH' => $VIDEO_LANG['auth_edit'],
		'L_DELETE_AUTH' => $VIDEO_LANG['auth_delete'],
		'L_MODERATE_AUTH' => $VIDEO_LANG['auth_moderate'],
		'L_SPECIAL_AUTH' => $VIDEO_LANG['special_auth'],
		'L_SPECIAL_AUTH_EXPLAIN' => $VIDEO_LANG['special_auth_explain']
	));

	if( $id_edit > 0 && array_key_exists($id_edit, $VIDEO_CATS) )
		$Template->assign_block_vars('edition_interface', array(
			'NAME' => $VIDEO_CATS[$id_edit]['name'],
			'DESCRIPTION' => unparse($VIDEO_CATS[$id_edit]['description']),
			'IMAGE' => $VIDEO_CATS[$id_edit]['image'],
			'CATEGORIES_TREE' => $video_categories->build_select_form($VIDEO_CATS[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => !empty($VIDEO_CATS[$id_edit]['auth']) ? 'true' : 'false',
			'DISPLAY_SPECIAL_AUTH' => !empty($VIDEO_CATS[$id_edit]['auth']) ? 'block' : 'none',
			'SPECIAL_CHECKED' => !empty($VIDEO_CATS[$id_edit]['auth']) ? 'checked="checked"' : '',
			'READ_AUTH' => Authorizations::generate_select(VIDEO_READ, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
			'WRITE_AUTH' => Authorizations::generate_select(VIDEO_WRITE, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
			'APROB_AUTH' => Authorizations::generate_select(VIDEO_APROB, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
			'FLOOD_AUTH' => Authorizations::generate_select(VIDEO_FLOOD, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
			'EDIT_AUTH' => Authorizations::generate_select(VIDEO_EDIT, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
			'DELETE_AUTH' => Authorizations::generate_select(VIDEO_DELETE, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
			'MODERATE_AUTH' => Authorizations::generate_select(VIDEO_MODERATE, !empty($VIDEO_CATS[$id_edit]['auth']) ? $VIDEO_CATS[$id_edit]['auth'] : $VIDEO_CATS[0]['auth']),
		));
	else
	{
		$id_edit = 0;
		$Template->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'CATEGORIES_TREE' => $video_categories->build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => 'false',
			'DISPLAY_SPECIAL_AUTH' => 'none',
			'SPECIAL_CHECKED' => '',
			'READ_AUTH' => Authorizations::generate_select(VIDEO_READ, $VIDEO_CATS[0]['auth']),
			'WRITE_AUTH' => Authorizations::generate_select(VIDEO_WRITE, $VIDEO_CATS[0]['auth']),
			'APROB_AUTH' => Authorizations::generate_select(VIDEO_APROB, $VIDEO_CATS[0]['auth']),
			'FLOOD_AUTH' => Authorizations::generate_select(VIDEO_FLOOD, $VIDEO_CATS[0]['auth']),
			'EDIT_AUTH' => Authorizations::generate_select(VIDEO_EDIT, $VIDEO_CATS[0]['auth']),
			'DELETE_AUTH' => Authorizations::generate_select(VIDEO_DELETE, $VIDEO_CATS[0]['auth']),
			'MODERATE_AUTH' => Authorizations::generate_select(VIDEO_MODERATE, $VIDEO_CATS[0]['auth'])
		));
	}
}
else
{
	if( !empty($error) )
	{
		switch($error)
		{
			case 'e_required_fields_empty' :
				$Errorh->handler($VIDEO_LANG['required_fields_empty'], E_USER_WARNING);
				break;
			case 'e_unexisting_category' :
				$Errorh->handler($VIDEO_LANG['unexisting_category'], E_USER_WARNING);
				break;
			case 'e_new_cat_does_not_exist' :
				$Errorh->handler($VIDEO_LANG['new_cat_does_not_exist'], E_USER_WARNING);
				break;
				case 'e_infinite_loop' :
				$Errorh->handler($VIDEO_LANG['infinite_loop'], E_USER_WARNING);
				break;
			case 'e_success' :
				$Errorh->handler($VIDEO_LANG['successful_operation'], E_USER_SUCCESS);
				break;
				case 'e_recount_success' :
				$Errorh->handler($VIDEO_LANG['recount_success'], E_USER_SUCCESS);
				break;
		}
	}

	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_video_cats.php',
		'url' => array(
			'unrewrited' => 'video.php?id=%d',
			'rewrited' => 'video-%d+%s.php'),
		);

	$video_categories->set_display_config($cat_config);

	$Template->assign_block_vars('categories_management', array(
		'CATEGORIES' => $video_categories->build_administration_interface()
	));

	$Template->assign_vars(array(
		'L_RECOUNT_VIDEO' => $VIDEO_LANG['recount_video_number'],
		'THEME' => $CONFIG['theme']
	));
}

$Template->pparse('admin_video_cat');

require_once('../admin/admin_footer.php');

?>
