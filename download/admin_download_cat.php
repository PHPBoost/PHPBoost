<?php
/*##################################################
 *                               admin_download_cat.php
 *                            -------------------
 *   begin                : July 15, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

require_once('../kernel/admin_begin.php');
load_module_lang('download'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

include_once('download_auth.php');
$Cache->Load_file('download');
include_once('download_cats.class.php');
$download_categories = new Download_cats();

$id_up = retrieve(GET, 'id_up', 0);
$id_down = retrieve(GET, 'id_down', 0);
$cat_to_del = retrieve(GET, 'del', 0);
$cat_to_del_post = retrieve(POST, 'cat_to_del', 0);
$id_edit = retrieve(GET, 'edit', 0);
$new_cat = !empty($_GET['new']) ? true : false;
$error = retrieve(GET, 'error', '');

if( $id_up > 0 )
{
	$download_categories->Move_category($id_up, MOVE_CATEGORY_UP);
	redirect(transid('admin_download_cat.php'));
}
elseif( $id_down > 0 )
{
	$download_categories->Move_category($id_down, MOVE_CATEGORY_DOWN);
	redirect(transid('admin_download_cat.php'));
}
elseif( $cat_to_del > 0 )
{
	$Template->Set_filenames(array(
		'admin_download_cat_remove'=> 'download/admin_download_cat_remove.tpl'
	));
	
	$Template->Assign_vars(array(
		'CATEGORY_TREE' => $download_categories->Build_select_form(0, 'id_parent', 'id_parent', $cat_to_del),
		'IDCAT' => $cat_to_del,
		'L_REMOVING_CATEGORY' => $DOWNLOAD_LANG['removing_category'],
		'L_EXPLAIN_REMOVING' => $DOWNLOAD_LANG['explain_removing_category'],
		'L_DELETE_CATEGORY_AND_CONTENT' => $DOWNLOAD_LANG['delete_category_and_its_content'],
		'L_MOVE_CONTENT' => $DOWNLOAD_LANG['move_category_content'],
		'L_SUBMIT' => $LANG['delete'],
		'U_FORM_TARGET' => HOST . DIR . transid('/download/admin_download_cat.php')
	));
	
	include_once('admin_download_menu.php');
		
	$Template->Pparse('admin_download_cat_remove');
}
elseif( !empty($_POST['submit']) )
{
	$error_string = 'e_success';
	
	//Deleting a category
	if( $cat_to_del_post > 0 )
	{
		$delete_content = (!empty($_POST['action']) && $_POST['action'] == 'move') ? false : true;
		$id_parent = retrieve(POST, 'id_parent', 0);
		
		if( $delete_content )
			$download_categories->Delete_category_recursively($cat_to_del_post);
		else
			$download_categories->Delete_category_and_move_content($cat_to_del_post, $id_parent);
	}
	else
	{
		$id_cat = retrieve(POST, 'idcat', 0);
		$id_parent = retrieve(POST, 'id_parent', 0);
		$name = retrieve(POST, 'name', '');
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		$icon = retrieve(POST, 'image', ''); 
		$icon_path = retrieve(POST, 'alt_image', ''); 
		$aprob = retrieve(POST, 'aprob', 0);
		$secure = retrieve(POST, 'secure', -1);

		if( !empty($icon_path) )
			$icon = $icon_path;
		
		//Autorisations
		if( !empty($_POST['special_auth']) )
		{
			$array_auth_all = $Group->Return_array_auth(READ_CAT_DOWNLOAD, WRITE_CAT_DOWNLOAD);
			$new_auth = addslashes(serialize($array_auth_all));
		}
		else
			$new_auth = '';

		if( empty($name) )
			redirect(transid(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');

			if( $id_cat > 0 )
			$error_string = $download_categories->Update_category($id_cat, $id_parent, $name, $description, $icon, $new_auth);
		else
			$error_string = $download_categories->Add_category($id_parent, $name, $description, $icon, $new_auth);
	}

	$Cache->Generate_module_file('download');
	
	redirect(transid(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
}
//Updating the number of subquestions of each category
elseif( !empty($_GET['recount']) )
{
	$download_categories->Recount_sub_files();
	redirect(transid(HOST . SCRIPT . '?error=e_recount_success', '', '&'));
}
elseif( $new_cat XOR $id_edit > 0 )
{
	$Template->Set_filenames(array(
		'admin_download_cat_edition'=> 'download/admin_download_cat_edition.tpl'
	));
	
	//Images disponibles
	$rep = './';
	if( is_dir($rep) ) //Si le dossier existe
	{
		$img_str = '<option value="">--</option>';
		$dh = @opendir( $rep);
		$in_dir_icon = false;
		while( !is_bool($image_name = @readdir($dh)) )
		{       
			if( preg_match('`\.(gif|png|jpg|jpeg|tiff)+$`i', $image_name) )
			{
				if( $id_edit > 0 && $DOWNLOAD_CATS[$id_edit]['icon'] == $image_name )
				{
					$img_str .= '<option selected="selected" value="' . $image_name . '">' . $image_name . '</option>'; //On ajoute l'image sélectionnée
					$in_dir_icon = true;
				}
				else
					$img_str .= '<option value="' . $image_name . '">' . $image_name . '</option>'; //On ajoute l'image non sélectionnée
				
			}
		}       
		@closedir($dh); //On ferme le dossier
	}
	
	$Template->Assign_vars(array(
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRED_FIELDS' => $DOWNLOAD_LANG['required_fields'],
		'L_NAME' => $DOWNLOAD_LANG['category_name'],
		'L_LOCATION' => $DOWNLOAD_LANG['category_location'],
		'L_DESCRIPTION' => $DOWNLOAD_LANG['cat_description'],
		'L_IMAGE' => $DOWNLOAD_LANG['icon_cat'],
		'L_EXPLAIN_IMAGE' => $DOWNLOAD_LANG['explain_icon_cat'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_READ_AUTH' => $DOWNLOAD_LANG['auth_read'],
		'L_WRITE_AUTH' => $DOWNLOAD_LANG['auth_write'],
		'L_SPECIAL_AUTH' => $DOWNLOAD_LANG['special_auth'],
		'L_SPECIAL_AUTH_EXPLAIN' => $DOWNLOAD_LANG['special_auth_explain'],
		'IMG_LIST' => $img_str,
	));
		
	if( $id_edit > 0 && array_key_exists($id_edit, $DOWNLOAD_CATS) )	
	{
		$Template->Assign_vars(array(
			'NAME' => $DOWNLOAD_CATS[$id_edit]['name'],
			'DESCRIPTION' => unparse($DOWNLOAD_CATS[$id_edit]['description']),
			'IMAGE' => $DOWNLOAD_CATS[$id_edit]['icon'],
			'CATEGORIES_TREE' => $download_categories->Build_select_form($DOWNLOAD_CATS[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit,
			'IMG_ICON' => !empty($DOWNLOAD_CATS[$id_edit]['icon']) ? '<img src="' . $DOWNLOAD_CATS[$id_edit]['icon'] . '" alt="" class="valign_middle" />' : '',
			'IMG_PATH' => !$in_dir_icon ? $DOWNLOAD_CATS[$id_edit]['icon'] : '',
			'JS_SPECIAL_AUTH' => !empty($DOWNLOAD_CATS[$id_edit]['auth']) ? 'true' : 'false',
			'DISPLAY_SPECIAL_AUTH' => !empty($DOWNLOAD_CATS[$id_edit]['auth']) ? 'block' : 'none',
			'SPECIAL_CHECKED' => !empty($DOWNLOAD_CATS[$id_edit]['auth']) ? 'checked="checked"' : '',
			'READ_AUTH' => $Group->Generate_select_auth(READ_CAT_DOWNLOAD, !empty($DOWNLOAD_CATS[$id_edit]['auth']) ? $DOWNLOAD_CATS[$id_edit]['auth'] : $CONFIG_DOWNLOAD['global_auth']),
			'WRITE_AUTH' => $Group->Generate_select_auth(WRITE_CAT_DOWNLOAD, !empty($DOWNLOAD_CATS[$id_edit]['auth']) ? $DOWNLOAD_CATS[$id_edit]['auth'] : $CONFIG_DOWNLOAD['global_auth'])
		));
	}
	else
	{
		$id_edit = '0';
		$Template->Assign_vars(array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'CATEGORIES_TREE' => $download_categories->Build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => 'false',
			'DISPLAY_SPECIAL_AUTH' => 'none',
			'SPECIAL_CHECKED' => '',
			'READ_AUTH' => $Group->Generate_select_auth(READ_CAT_DOWNLOAD, $CONFIG_DOWNLOAD['global_auth']),
			'WRITE_AUTH' => $Group->Generate_select_auth(WRITE_CAT_DOWNLOAD, $CONFIG_DOWNLOAD['global_auth'])
		));
	}
	
	include_once('../kernel/framework/content/bbcode.php');
	include_once('admin_download_menu.php');
	
	$Template->Pparse('admin_download_cat_edition');
}
else
{
	$Template->Set_filenames(array(
		'admin_download_cat'=> 'download/admin_download_cat.tpl'
	));
	
	include_once('admin_download_menu.php');

	if( !empty($error) )
	{
		switch($error)
		{
			case 'e_required_fields_empty' :
				$Errorh->Error_handler($DOWNLOAD_LANG['required_fields_empty'], E_USER_WARNING);
				break;
			case 'e_unexisting_category' :
				$Errorh->Error_handler($DOWNLOAD_LANG['unexisting_category'], E_USER_WARNING);
				break;
			case 'e_new_cat_does_not_exist' :
				$Errorh->Error_handler($DOWNLOAD_LANG['new_cat_does_not_exist'], E_USER_WARNING);
				break;
				case 'e_infinite_loop' :
				$Errorh->Error_handler($DOWNLOAD_LANG['infinite_loop'], E_USER_WARNING);
				break;
			case 'e_success' :
				$Errorh->Error_handler($DOWNLOAD_LANG['successful_operation'], E_USER_SUCCESS);
				break;
				case 'e_recount_success' :
				$Errorh->Error_handler($DOWNLOAD_LANG['recount_success'], E_USER_SUCCESS);
				break;
		}
	}
	
	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_download_cat.php',
		'url' => array(
			'unrewrited' => 'download.php?id=%d',
			'rewrited' => 'category-%d+%s.php'),
		);
		
	$download_categories->Set_displaying_configuration($cat_config);
	
	$Template->Assign_vars(array(
		'CATEGORIES' => $download_categories->Build_categories_administration_interface(),
		'L_RECOUNT_SUBFILES' => $DOWNLOAD_LANG['recount_subfiles'],
		'U_RECOUNT_SUBFILES' => transid('admin_download_cat.php?recount=1')
	));

	$Template->Pparse('admin_download_cat');
}

include_once('../kernel/admin_footer.php');

?>