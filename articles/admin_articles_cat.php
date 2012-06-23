<?php
/*##################################################
 *                               admin_articles_cat.php
 *                            -------------------
 *   begin                : August 27, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('articles_constants.php');

$id_up = retrieve(GET, 'id_up', 0);
$id_down = retrieve(GET, 'id_down', 0);
$id_show = retrieve(GET, 'show', 0);
$id_hide = retrieve(GET, 'hide', 0);
$id = retrieve(GET, 'id', 0,TINTEGER);
$cat_to_del = retrieve(GET, 'del', 0,TINTEGER);
$cat_to_del_post = retrieve(POST, 'cat_to_del', 0,TINTEGER);
$new_cat = retrieve(GET, 'new', false);
$id_edit = retrieve(GET, 'edit', 0,TINTEGER);
$error = retrieve(GET, 'error', '');

$articles_categories = new ArticlesCats();

$tpl = new FileTemplate('articles/admin_articles_cat.tpl');

require_once('admin_articles_menu.php');
$tpl->put_all(array('ADMIN_MENU' => $admin_menu));

if ($id_up > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->move($id_up, MOVE_CATEGORY_UP);
	AppContext::get_response()->redirect(url('admin_articles_cat.php'));
}
elseif ($id_down > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->move($id_down, MOVE_CATEGORY_DOWN);
	AppContext::get_response()->redirect(url('admin_articles_cat.php'));
}
elseif ($id_show > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_articles_cat.php'));
}
elseif ($id_hide > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_articles_cat.php'));
}
if ($cat_to_del > 0)
{
	$array_cat = array();
	$nbr_cat = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE idcat = '" . $cat_to_del . "'", __LINE__, __FILE__);
	$articles_categories->build_children_id_list($cat_to_del, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST);

	if (empty($array_cat) && $nbr_cat === 0)
	{
		$articles_categories->delete($cat_to_del);
		
		// Feeds Regeneration		
		Feed::clear_cache('articles');
		AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_success#message_helper'), '', '&');
	}
	else
	{
		$tpl->put_all(array(
			'EMPTY_CATS' => count($ARTICLES_CAT) < 2 ? true : false,
			'L_REMOVING_MODEL' => $ARTICLES_LANG['removing_category'],
			'L_EXPLAIN_REMOVING' => $ARTICLES_LANG['explain_removing_category'],
			'L_DELETE_CATEGORY_AND_CONTENT' => $ARTICLES_LANG['delete_category_and_its_content'],
			'L_MOVE_CONTENT' => $ARTICLES_LANG['move_category_content'],
			'L_SUBMIT' => $LANG['delete']
		));

		$tpl->assign_block_vars('removing_interface', array(
			'IDCAT' => $cat_to_del,
		));
		
		$articles_categories->build_select_form(0, 'idcat', 'idcat', $cat_to_del, 0, array(), RECURSIVE_EXPLORATION, $tpl);
	}
}
elseif ($new_cat XOR $id_edit > 0)
{
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$tpl->put_all(array(
		'KERNEL_EDITOR' => $editor->display(),
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_NAME' => $ARTICLES_LANG['category_name'],
		'L_LOCATION' => $ARTICLES_LANG['category_location'],
		'L_DESCRIPTION' => $ARTICLES_LANG['category_desc'],
		'L_IMAGE' => $ARTICLES_LANG['category_image'],
		'L_SPECIAL_AUTH' => $ARTICLES_LANG['special_auth'],
		'L_SPECIAL_AUTH_EXPLAIN' => $ARTICLES_LANG['special_auth_explain'],
		'L_AUTH_READ' => $ARTICLES_LANG['auth_read'],
		'L_AUTH_WRITE' => $ARTICLES_LANG['auth_write'],
		'L_AUTH_MODERATION' => $ARTICLES_LANG['auth_moderate'],
		'L_AUTH_CONTRIBUTION' => $ARTICLES_LANG['auth_contribute'],
		'L_REQUIRE_TITLE' => $LANG['required_field'].' : '.$ARTICLES_LANG['category_name'],
		'L_OR_DIRECT_PATH' => $ARTICLES_LANG['or_direct_path'],
		'L_ARTICLES_MODELS'=>$ARTICLES_LANG['articles_models'],
		'L_CAT_ICON'=>$ARTICLES_LANG['cat_icon'],
	));

	if ($id_edit > 0 && array_key_exists($id_edit, $ARTICLES_CAT))
	{
		$special_auth = $ARTICLES_CAT[$id_edit]['auth'] !== $CONFIG_ARTICLES['global_auth'] ? true : false;
		$ARTICLES_CAT[$id_edit]['auth'] = $special_auth ? $ARTICLES_CAT[$id_edit]['auth'] : $CONFIG_ARTICLES['global_auth'];

		// category icon
		$img_direct_path = (strpos($ARTICLES_CAT[$id_edit]['image'], '/') !== false);
		$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
		
		$image_folder_path = new Folder('./');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
		{
			$image = $images->get_name();
			$selected = $image == $ARTICLES_CAT[$id_edit]['image'] ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $image . '"' . ($img_direct_path ? '' : $selected) . '>' . $image . '</option>';
		}
		
		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => $ARTICLES_CAT[$id_edit]['name'],
			'DESCRIPTION' => FormatingHelper::unparse($ARTICLES_CAT[$id_edit]['description']),
			'IMG_PATH' => $img_direct_path ? $ARTICLES_CAT[$id_edit]['image'] : '',
			'IMG_ICON' => !empty($ARTICLES_CAT[$id_edit]['image']) ? '<img src="' . $ARTICLES_CAT[$id_edit]['image'] . '" alt="" class="valign_middle" />' : '',		
			'IMG_LIST'=>$image_list,
			'CATEGORIES_TREE' => $articles_categories->build_select_form($ARTICLES_CAT[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => $special_auth ? 'true' : 'false',
			'DISPLAY_SPECIAL_AUTH' => $special_auth ? 'block' : 'none',
			'SPECIAL_CHECKED' => $special_auth ? 'checked="checked"' : '',
			'AUTH_READ' => Authorizations::generate_select(AUTH_ARTICLES_READ, $ARTICLES_CAT[$id_edit]['auth']),
			'AUTH_WRITE' => Authorizations::generate_select(AUTH_ARTICLES_WRITE, $ARTICLES_CAT[$id_edit]['auth']),
			'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_ARTICLES_CONTRIBUTE, $ARTICLES_CAT[$id_edit]['auth']),
			'AUTH_MODERATION' => Authorizations::generate_select(AUTH_ARTICLES_MODERATE, $ARTICLES_CAT[$id_edit]['auth']),
		));
	}
	else
	{
		$id_edit = 0;
		$img_default = '../articles/articles.png';
		$img_default_name = 'articles.png';
		$image_list = '<option value="'.$img_default.'" selected="selected">'.$img_default_name.'</option>';
		
		$image_folder_path = new Folder('./');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
		{
			$image = $images->get_name();
			if($image != $img_default_name)
			$image_list .= '<option value="' . $image . '">' . $image . '</option>';
		}
			
		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMG_PATH' => '',
			'IMG_ICON' => '',	
			'IMG_LIST' => $image_list,
			'IMG_PREVIEW' => FormatingHelper::second_parse_url($img_default),
			'CATEGORIES_TREE' => $articles_categories->build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => 'false',
			'DISPLAY_SPECIAL_AUTH' => 'none',
			'SPECIAL_CHECKED' => '',
			'AUTH_READ' => Authorizations::generate_select(AUTH_ARTICLES_READ, $CONFIG_ARTICLES['global_auth']),
			'AUTH_WRITE' => Authorizations::generate_select(AUTH_ARTICLES_WRITE, $CONFIG_ARTICLES['global_auth']),
			'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_ARTICLES_CONTRIBUTE, $CONFIG_ARTICLES['global_auth']),
			'AUTH_MODERATION' => Authorizations::generate_select(AUTH_ARTICLES_MODERATE, $CONFIG_ARTICLES['global_auth']),			
		));

	}
}
elseif (retrieve(POST,'submit',false))
{
	$error_string = 'e_success';
	//Deleting a category
	if (!empty($cat_to_del_post))
	{
		$delete_content =(retrieve(POST,'action','move') == 'move') ? false : true;
		$id_parent = retrieve(POST, 'idcat', 0,TINTEGER);
		
		$Session->csrf_get_protect();
		
		if ($delete_content)
			$articles_categories->delete_category_recursively($cat_to_del_post);
		else
			$articles_categories->delete_category_and_move_content($cat_to_del_post, $id_parent);
	}
	else
	{
		$id_cat = retrieve(POST, 'idcat', 0,TINTEGER);
		$id_parent = retrieve(POST, 'id_parent', 0,TINTEGER);
		$name = retrieve(POST, 'name', '');
		$icon=retrieve(POST, 'icon', '', TSTRING);	
		
		if(retrieve(POST,'icon_path',false))
			$icon=retrieve(POST,'icon_path','');
			
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		$auth = !empty($_POST['special_auth']) ? addslashes(serialize(Authorizations::build_auth_array_from_form(AUTH_ARTICLES_READ, AUTH_ARTICLES_CONTRIBUTE, AUTH_ARTICLES_WRITE, AUTH_ARTICLES_MODERATE))) : '';

		if (empty($name))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#message_helper'), '', '&');
	
		if ($id_cat > 0)
			$error_string = $articles_categories->update_category($id_cat, $id_parent, $name, $description, $icon, $auth);
		else
			$error_string = $articles_categories->add_category($id_parent, $name, $description, $icon, $auth);
	}

	// Feeds Regeneration
	
	Feed::clear_cache('articles');

	$Cache->Generate_module_file('articles');
	AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#message_helper'), '', '&');
}
else
{
	if (!empty($error))
	{
		switch ($error)
		{
			case 'e_required_fields_empty' :
				$tpl->put('message_helper', MessageHelper::display($ARTICLES_LANG['required_fields_empty'], E_USER_WARNING));
				break;
			case 'e_unexisting_category' :
				$tpl->put('message_helper', MessageHelper::display($ARTICLES_LANG['unexisting_category'], E_USER_WARNING));
				break;
			case 'e_new_cat_does_not_exist' :
				$tpl->put('message_helper', MessageHelper::display($ARTICLES_LANG['new_cat_does_not_exist'], E_USER_WARNING));
				break;
			case 'e_infinite_loop' :
				$tpl->put('message_helper', MessageHelper::display($ARTICLES_LANG['infinite_loop'], E_USER_WARNING));
				break;
			case 'e_success' :
				$tpl->put('message_helper', MessageHelper::display($ARTICLES_LANG['successful_operation'], E_USER_SUCCESS, 4));
				break;
		}
	}

	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_articles_cat.php',
		'url' => array(
			'unrewrited' => 'articles.php?cat=%d',
			'rewrited' => 'articles-%d+%s.php'),
	);

	$articles_categories->set_display_config($cat_config);

	$tpl->assign_block_vars('categories_management', array(
		'L_CATS_MANAGEMENT' => $ARTICLES_LANG['category_articles'],
		'CATEGORIES' => $articles_categories->build_administration_interface()
	));
}

$tpl->display();
require_once('../admin/admin_footer.php');

?>