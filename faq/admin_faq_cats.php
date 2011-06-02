<?php
/*##################################################
 *                               admin_faq_cats.php
 *                            -------------------
 *   begin                : December 26, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
include_once('faq_begin.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$faq_categories = new FaqCats();

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
	'admin_faq_cat'=> 'faq/admin_faq_cats.tpl'
));

$Template->put_all(array(
	'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
	'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
	'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
	'L_QUESTIONS_LIST' => $FAQ_LANG['faq_questions_list'],
    'L_ADD_QUESTION' => $FAQ_LANG['add_question'],
	'L_ADD_CAT' => $FAQ_LANG['add_cat']
));

if ($id_up > 0)
{
	$faq_categories->move($id_up, MOVE_CATEGORY_UP);
	AppContext::get_response()->redirect(url('admin_faq_cats.php'));
}
elseif ($id_down > 0)
{
	$faq_categories->move($id_down, MOVE_CATEGORY_DOWN);
	AppContext::get_response()->redirect(url('admin_faq_cats.php'));
}
elseif ($id_show > 0)
{
	$faq_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_faq_cats.php'));
}
elseif ($id_hide > 0)
{
	$faq_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_faq_cats.php'));
}
elseif ($cat_to_del > 0)
{
	$Template->put_all(array(
		'L_REMOVING_CATEGORY' => $FAQ_LANG['removing_category'],
		'L_EXPLAIN_REMOVING' => $FAQ_LANG['explain_removing_category'],
		'L_DELETE_CATEGORY_AND_CONTENT' => $FAQ_LANG['delete_category_and_its_content'],
		'L_MOVE_CONTENT' => $FAQ_LANG['move_category_content'],
		'L_SUBMIT' => $LANG['delete']
	));
	$Template->assign_block_vars('removing_interface', array(
		'CATEGORY_TREE' => $faq_categories->build_select_form(0, 'id_parent', 'id_parent', $cat_to_del),
		'IDCAT' => $cat_to_del,
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
			$faq_categories->Delete_category_recursively($cat_to_del_post);
		}
		else
		{
			$faq_categories->Delete_category_and_move_content($cat_to_del_post, $id_parent);
		}
	}
	else
	{
		$id_cat = retrieve(POST, 'idcat', 0);
		$id_parent = retrieve(POST, 'id_parent', 0);
		$name = retrieve(POST, 'name', '');
		$image = retrieve(POST, 'image', '');
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		
		if (empty($name))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#message_helper'), '', '&');
		
		if ($id_cat > 0)
			$error_string = $faq_categories->Update_category($id_cat, $id_parent, $name, $description, $image);
		else
			$error_string = $faq_categories->add_category($id_parent, $name, $description, $image);
	}

	$Cache->Generate_module_file('faq');
	
	AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#message_helper'), '', '&');
}
//Updating the number of subquestions of each category
elseif (!empty($_GET['recount']))
{
	$faq_categories->Recount_subquestions();
	AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_recount_success', '', '&'));
}
elseif ($new_cat XOR $id_edit > 0)
{
	$Template->put_all(array(
		'KERNEL_EDITOR' => display_editor(),
		'L_CATEGORY' => $FAQ_LANG['category'],
		'L_REQUIRED_FIELDS' => $FAQ_LANG['required_fields'],
		'L_NAME' => $FAQ_LANG['category_name'],
		'L_LOCATION' => $FAQ_LANG['category_location'],
		'L_DESCRIPTION' => $FAQ_LANG['cat_description'],
		'L_IMAGE' => $FAQ_LANG['category_image'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_REQUIRE_TITLE' => $LANG['require_title']
	));
		
	if ($id_edit > 0 && array_key_exists($id_edit, $FAQ_CATS))	
		$Template->assign_block_vars('edition_interface', array(
			'NAME' => $FAQ_CATS[$id_edit]['name'],
			'DESCRIPTION' => FormatingHelper::unparse($FAQ_CATS[$id_edit]['description']),
			'IMAGE' => $FAQ_CATS[$id_edit]['image'],
			'CATEGORIES_TREE' => $faq_categories->build_select_form($FAQ_CATS[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit
		));
	else
	{
		$id_edit = 0;
		$Template->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'CATEGORIES_TREE' => $faq_categories->build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit
		));
	}
}
else
{
	if (!empty($error))
	{
		switch ($error)
		{
			case 'e_required_fields_empty' :
				$Template->put('message_helper', MessageHelper::display($FAQ_LANG['required_fields_empty'], E_USER_WARNING));
			break;
			case 'e_unexisting_category' :
				$Template->put('message_helper', MessageHelper::display($FAQ_LANG['unexisting_category'], E_USER_WARNING));
			break;
			case 'e_new_cat_does_not_exist' :
				$Template->put('message_helper', MessageHelper::display($FAQ_LANG['new_cat_does_not_exist'], E_USER_WARNING));
			break;
			case 'e_infinite_loop' :
				$Template->put('message_helper', MessageHelper::display($FAQ_LANG['infinite_loop'], E_USER_WARNING));
			break;
			case 'e_success' :
				$Template->put('message_helper', MessageHelper::display($FAQ_LANG['successful_operation'], E_USER_SUCCESS, 4));
			break;
			case 'e_recount_success' :
				$Template->put('message_helper', MessageHelper::display($FAQ_LANG['recount_success'], E_USER_SUCCESS, 4));
			break;
		}
	}
	
	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_faq_cats.php',
		'url' => array(
			'unrewrited' => 'faq.php?id=%d',
			'rewrited' => 'faq-%d+%s.php'),
		);
		
	$faq_categories->set_display_config($cat_config);
	
	$Template->assign_block_vars('categories_management', array(
		'CATEGORIES' => $faq_categories->build_administration_interface()
	));
	
	$Template->put_all(array(
		'L_RECOUNT_QUESTIONS' => $FAQ_LANG['recount_questions_number'],
		'THEME' => get_utheme()
	));
}

$Template->pparse('admin_faq_cat');

require_once('../admin/admin_footer.php');

?>
