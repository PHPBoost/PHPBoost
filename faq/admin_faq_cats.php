<?php
/*##################################################
 *                               admin_faq_cats.php
 *                            -------------------
 *   begin                : December 26, 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/admin_begin.php');
include_once('faq_begin.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

include_once('faq_cats.class.php');
$faq_categories = new FaqCats();

$id_up = !empty($_GET['id_up']) ? numeric($_GET['id_up']) : 0;
$id_down = !empty($_GET['id_down']) ? numeric($_GET['id_down']) : 0;
$cat_to_del = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$id_edit = !empty($_GET['edit']) ? numeric($_GET['edit']) : 0;
$new_cat = !empty($_GET['new']) ? true : false;
$error = !empty($_GET['error']) ? securit($_GET['error']) : '';

$Template->Set_filenames(array(
	'admin_faq_cat' => '../templates/' . $CONFIG['theme'] . '/faq/admin_faq_cats.tpl'
));

$Template->Assign_vars(array(
	'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
	'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
	'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
	'L_ADD_CAT' => $FAQ_LANG['add_cat']
));

if( $id_up > 0 )
{
	$faq_categories->Move_category($id_up, 'up');
	redirect(transid('admin_faq_cats.php'));
}
elseif( $id_down > 0 )
{
	$faq_categories->Move_category($id_down, 'down');
	redirect(transid('admin_faq_cats.php'));
}
elseif( $cat_to_del > 0 )
{
	
}
elseif( !empty($_POST['submit']) )
{
	$id_cat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$id_parent = !empty($_POST['id_parent']) ? numeric($_POST['id_parent']) : 0;
	$name = !empty($_POST['name']) ? securit($_POST['name']) : '';
	$image = !empty($_POST['image']) ? securit($_POST['image']) : '';
	$description = !empty($_POST['description']) ? parse($_POST['description']) : '';
	$error_string = '';
	
	if( $id_cat > 0 )
		$error_string = $faq_categories->Update_category($id_cat, $id_parent, $name, $description, $image);
	else
		$error_string = $faq_categories->Add_category($id_parent, $name, $description, $image);

	$Cache->Generate_module_file('faq');
	
	redirect(transid(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
}
elseif( $new_cat XOR $id_edit > 0 )
{
	$Template->Assign_vars(array(
		'L_CATEGORY' => $FAQ_LANG['category'],
		'L_REQUIRED_FIELDS' => $FAQ_LANG['required_fields'],
		'L_NAME' => $FAQ_LANG['category_name'],
		'L_LOCATION' => $FAQ_LANG['category_location'],
		'L_DESCRIPTION' => $FAQ_LANG['cat_description'],
		'L_IMAGE' => $FAQ_LANG['category_image'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add']
	));
	
	if( $id_edit > 0 && array_key_exists($id_edit, $FAQ_CATS) )	
		$Template->Assign_block_vars('edition_interface', array(
			'NAME' => $FAQ_CATS[$id_edit]['name'],
			'DESCRIPTION' => unparse($FAQ_CATS[$id_edit]['description']),
			'IMAGE' => $FAQ_CATS[$id_edit]['image'],
			'CATEGORIES_TREE' => $faq_categories->Build_select_form($FAQ_CATS[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit
		));
	else
	{
		$id_edit = 0;
		$Template->Assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'CATEGORIES_TREE' => $faq_categories->Build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit
		));
	}
	
	include_once('../includes/bbcode.php');
}
else
{
	if( !empty($error) )
	{
		switch($error)
		{
			case 'e_page_not_found' :
				$Errorh->Error_handler($LANG['pages_not_found'], E_USER_WARNING);
				break;
			case 'e_auth' :
				$Errorh->Error_handler($LANG['pages_error_auth_read'], E_USER_WARNING);
				break;
			case 'e_auth_com' :
				$Errorh->Error_handler($LANG['pages_error_auth_com'], E_USER_WARNING);
				break;
			case 'e_unactiv_com' :
				$Errorh->Error_handler($LANG['pages_error_unactiv_com'], E_USER_WARNING);
				break;
			case 'delete_success' :
				$Errorh->Error_handler($LANG['pages_delete_success'], E_USER_NOTICE);
				break;
			case 'e_success' :
				$Errorh->Error_handler('bingo', E_USER_SUCCESS);
				break;
		}
	}
	
	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_faq_cats.php',
		'url' => array(
			'unrewrited' => '../faq/faq.php?id=%d',
			'rewrited' => '../faq/faq-%d+%s.php'),
		);
		
	$faq_categories->Set_displaying_configuration($cat_config);
	
	$Template->Assign_block_vars('categories_management', array(
		'CATEGORIES' => $faq_categories->Build_administration_list()
	));
}

$Template->Pparse('admin_faq_cat');

include_once('../includes/admin_footer.php');

?>