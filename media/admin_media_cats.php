<?php
/*##################################################
 *                               admin_media_cats.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('media_begin.php');
$media_categories = new MediaCats();

$Template->set_filenames(array('admin_media_cat'=> 'media/admin_media_cats.tpl'));

$id_up = retrieve(GET, 'id_up', 0);
$id_down = retrieve(GET, 'id_down', 0);
$id_show = retrieve(GET, 'show', 0);
$id_hide = retrieve(GET, 'hide', 0);
$cat_to_del = retrieve(GET, 'del', 0);
$cat_to_del_post = retrieve(POST, 'cat_to_del', 0);
$id_edit = retrieve(GET, 'edit', 0);
$new_cat = retrieve(GET, 'new', false);
$error = retrieve(GET, 'error', '');

if ($id_up > 0)
{
	$media_categories->move($id_up, MOVE_CATEGORY_UP);
	AppContext::get_response()->redirect(url('admin_media_cats.php'));
}
elseif ($id_down > 0)
{
	$media_categories->move($id_down, MOVE_CATEGORY_DOWN);
	AppContext::get_response()->redirect(url('admin_media_cats.php'));
}
elseif ($id_show > 0)
{
	$media_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_media_cats.php'));
}
elseif ($id_hide > 0)
{
	$media_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_media_cats.php'));
}
elseif ($cat_to_del > 0)
{
	$Template->put_all(array(
		'L_REMOVING_CATEGORY' => $MEDIA_LANG['removing_category'],
		'L_EXPLAIN_REMOVING' => $MEDIA_LANG['removing_category_explain'],
		'L_DELETE_CATEGORY_AND_CONTENT' => $MEDIA_LANG['remove_category_and_its_content'],
		'L_MOVE_CONTENT' => $MEDIA_LANG['move_category_content'],
		'L_SUBMIT' => $LANG['delete']
	));

	$Template->assign_block_vars('removing_interface', array(
		'CATEGORY_TREE' => $media_categories->build_select_form(0, 'id_parent', 'id_parent', $cat_to_del),
		'IDCAT' => $cat_to_del
	));
}
elseif (!empty($_POST['submit']))
{
	$error_string = 'e_success';
	//Deleting a category
	if (!empty( $cat_to_del_post))
	{
		$delete_content = (!empty($_POST['action']) && $_POST['action'] == 'move') ? false : true;
		$id_parent = retrieve(POST, 'id_parent', 0);

		if ($delete_content)
		{
			$media_categories->Delete_category_recursively($cat_to_del_post);
		}
		else
		{
			$media_categories->Delete_category_and_move_content($cat_to_del_post, $id_parent);
		}
	}
	else
	{
		$id_cat = retrieve(POST, 'idcat', 0);
		$id_parent = retrieve(POST, 'id_parent', 0);
		$name = retrieve(POST, 'name', '');
		$image = retrieve(POST, 'image', '');
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		$mime_type = retrieve(POST, 'mime_type', 0);
		$activ_array = retrieve(POST, 'activ', 0, TARRAY);
		$activ = is_array($activ_array) ? array_sum($activ_array) : 0;

		//Autorisations
		if (!empty($_POST['special_auth']))
		{
			$array_auth_all = Authorizations::build_auth_array_from_form(MEDIA_AUTH_READ, MEDIA_AUTH_CONTRIBUTION, MEDIA_AUTH_WRITE);
			$new_auth = addslashes(serialize($array_auth_all));
		}
		else
		{
			$new_auth = addslashes(serialize($MEDIA_CATS[0]['auth']));
		}

		if (empty($name))
		{
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#message_helper'), '', '&');
		}

		if ($id_cat > 0)
		{
			$error_string = $media_categories->Update_category($id_cat, $id_parent, $name, $description, $image, $new_auth, $mime_type, $activ);
		}
		else
		{
			$error_string = $media_categories->add($id_parent, $name, $description, $image, $new_auth, $mime_type, $activ);
		}
	}

	$Cache->Generate_module_file('media');

	AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#message_helper'), '', '&');
}
elseif ($new_cat XOR $id_edit > 0)
{
	$Template->put_all(array(
		'KERNEL_EDITOR' => display_editor(),
		'L_CATEGORY' => $MEDIA_LANG['category'],
		'L_REQUIRED_FIELDS' => $MEDIA_LANG['required_fields'],
		'L_CAT_NAME' => $MEDIA_LANG['cat_name'],
		'L_CAT_LOCATION' => $MEDIA_LANG['cat_location'],
		'L_CAT_DESCRIPTION' => $MEDIA_LANG['cat_description'],
		'L_CAT_IMAGE' => $MEDIA_LANG['cat_image'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_MIME_TYPE' => $MEDIA_LANG['mime_type'],
		'L_TYPE_BOTH' => $MEDIA_LANG['type_both'],
		'L_TYPE_MUSIC' => $MEDIA_LANG['type_music'],
		'L_TYPE_VIDEO' => $MEDIA_LANG['type_video'],
		'L_DISPLAY' => $MEDIA_LANG['display'],
		'L_IN_MEDIA' => $MEDIA_LANG['display_in_media'],
		'L_IN_LIST' => $MEDIA_LANG['display_in_list'],
		'L_DISPLAY_COM' => $MEDIA_LANG['display_com'],
		'L_DISPLAY_NOTE' => $MEDIA_LANG['display_note'],
		'L_DISPLAY_USER' => $MEDIA_LANG['display_poster'],
		'L_DISPLAY_COUNTER' => $MEDIA_LANG['display_view'],
		'L_DISPLAY_DATE' => $MEDIA_LANG['display_date'],
		'L_DISPLAY_DESC' => $MEDIA_LANG['display_desc'],
		'L_DISPLAY_NBR' => $MEDIA_LANG['display_nbr'],
		'L_SPECIAL_AUTH' => $MEDIA_LANG['special_auth'],
		'L_READ_AUTH' => $MEDIA_LANG['auth_read'],
		'L_CONTRIBUTE_AUTH' => $MEDIA_LANG['auth_contrib'],
		'L_WRITE_AUTH' => $MEDIA_LANG['auth_write']
	));

	if ($id_edit > 0 && array_key_exists($id_edit, $MEDIA_CATS))
	{
		$default_auth = !empty($MEDIA_CATS[$id_edit]['auth']) ? $MEDIA_CATS[$id_edit]['auth'] : $MEDIA_CATS[0]['auth'];

		$Template->assign_block_vars('edition_interface', array(
			'NAME' => $MEDIA_CATS[$id_edit]['name'],
			'DESCRIPTION' => FormatingHelper::unparse($MEDIA_CATS[$id_edit]['desc']),
			'IMAGE' => $MEDIA_CATS[$id_edit]['image'],
			'CATEGORIES_TREE' => $media_categories->build_select_form($MEDIA_CATS[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit,
			'TYPE_BOTH' => $MEDIA_CATS[$id_edit]['mime_type'] == MEDIA_TYPE_BOTH ? ' checked="checked"' : '',
			'TYPE_MUSIC' => $MEDIA_CATS[$id_edit]['mime_type'] == MEDIA_TYPE_MUSIC ? ' checked="checked"' : '',
			'TYPE_VIDEO' => $MEDIA_CATS[$id_edit]['mime_type'] == MEDIA_TYPE_VIDEO ? ' checked="checked"' : '',
			'COM_LIST' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DL_COM) !== 0 ? 'checked="checked"' : '',
			'COM_MEDIA' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DV_COM) !== 0 ? 'checked="checked"' : '',
			'NOTE_LIST' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DL_NOTE) !== 0 ? 'checked="checked"' : '',
			'NOTE_MEDIA' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DV_NOTE) !== 0 ? 'checked="checked"' : '',
			'USER_LIST' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DL_USER) !== 0 ? 'checked="checked"' : '',
			'USER_MEDIA' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DV_USER) !== 0 ? 'checked="checked"' : '',
			'COUNTER_LIST' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DL_COUNT) !== 0 ? 'checked="checked"' : '',
			'COUNTER_MEDIA' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DV_COUNT) !== 0 ? 'checked="checked"' : '',
			'DATE_LIST' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DL_DATE) !== 0 ? 'checked="checked"' : '',
			'DATE_MEDIA' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DV_DATE) !== 0 ? 'checked="checked"' : '',
			'DESC_LIST' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DL_DESC) !== 0 ? 'checked="checked"' : '',
			'DESC_MEDIA' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_DV_DESC) !== 0 ? 'checked="checked"' : '',
			'NBR' => ($MEDIA_CATS[$id_edit]['active'] & MEDIA_NBR) !== 0 ? 'checked="checked"' : '',
			'READ_AUTH' => Authorizations::generate_select(MEDIA_AUTH_READ, $default_auth),
			'CONTRIBUTE_AUTH' => Authorizations::generate_select(MEDIA_AUTH_CONTRIBUTION, $default_auth),
			'WRITE_AUTH' => Authorizations::generate_select(MEDIA_AUTH_WRITE, $default_auth)
		));
	}
	else
	{
		$Template->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'CATEGORIES_TREE' => $media_categories->build_select_form(0, 'id_parent', 'id_parent'),
			'IDCAT' => 0,
			'TYPE_BOTH' => ' checked="checked"',
			'TYPE_MUSIC' => '',
			'TYPE_VIDEO' => '',
			'COM_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_COM) !== 0 ? 'checked="checked"' : '',
			'COM_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_COM) !== 0 ? 'checked="checked"' : '',
			'NOTE_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_NOTE) !== 0 ? 'checked="checked"' : '',
			'NOTE_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_NOTE) !== 0 ? 'checked="checked"' : '',
			'USER_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_USER) !== 0 ? 'checked="checked"' : '',
			'USER_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_USER) !== 0 ? 'checked="checked"' : '',
			'COUNTER_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_COUNT) !== 0 ? 'checked="checked"' : '',
			'COUNTER_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_COUNT) !== 0 ? 'checked="checked"' : '',
			'DATE_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_DATE) !== 0 ? 'checked="checked"' : '',
			'DATE_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_DATE) !== 0 ? 'checked="checked"' : '',
			'DESC_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_DESC) !== 0 ? 'checked="checked"' : '',
			'DESC_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_DESC) !== 0 ? 'checked="checked"' : '',
			'NBR' => 'checked="checked"',
			'READ_AUTH' => Authorizations::generate_select(MEDIA_AUTH_READ, $MEDIA_CATS[0]['auth']),
			'CONTRIBUTE_AUTH' => Authorizations::generate_select(MEDIA_AUTH_CONTRIBUTION, $MEDIA_CATS[0]['auth']),
			'WRITE_AUTH' => Authorizations::generate_select(MEDIA_AUTH_WRITE, $MEDIA_CATS[0]['auth'])
		));
	}
}
else
{
	if (!empty($error))
	{
		switch ($error)
		{
			case 'e_required_fields_empty':
				$Template->put('message_helper', MessageHelper::display($MEDIA_LANG['required_fields_empty'], E_USER_WARNING));
				break;
			case 'e_unexisting_category':
				$Template->put('message_helper', MessageHelper::display($MEDIA_LANG['unexisting_category'], E_USER_WARNING));
				break;
			case 'e_new_cat_does_not_exist':
				$Template->put('message_helper', MessageHelper::display($MEDIA_LANG['new_cat_does_not_exist'], E_USER_WARNING));
				break;
				case 'e_infinite_loop':
				$Template->put('message_helper', MessageHelper::display($MEDIA_LANG['infinite_loop'], E_USER_WARNING));
				break;
			case 'e_success':
				$Template->put('message_helper', MessageHelper::display($MEDIA_LANG['successful_operation'], E_USER_SUCCESS, 4));
				break;
		}
	}

	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_media_cats.php',
		'url' => array(
			'unrewrited' => 'media.php?cat=%d',
			'rewrited' => 'media-0-%d+%s.php'
		)
	);

	$media_categories->set_display_config($cat_config);

	$Template->assign_block_vars('categories_management', array('CATEGORIES' => $media_categories->build_administration_interface()));
	$Template->put_all(array(
		'L_MANAGEMENT_CATS' => $MEDIA_LANG['management_cat'],
	));
}

require_once('admin_media_menu.php');

$Template->pparse('admin_media_cat');

require_once('../admin/admin_footer.php');

?>
