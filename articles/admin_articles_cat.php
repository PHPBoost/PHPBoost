<?php
/*##################################################
 *                               admin_articles_cat.php
 *                            -------------------
 *   begin                : August 27, 2007
 *   copyright          : (C) 2007 Viarre Régis
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
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('articles_constants.php');

$id = retrieve(GET, 'id', 0,TINTEGER);
$cat_to_del = retrieve(GET, 'del', 0,TINTEGER);
$cat_to_del_post = retrieve(POST, 'cat_to_del', 0,TINTEGER);
$id_up = retrieve(GET, 'id_up', 0,TINTEGER);
$id_down = retrieve(GET, 'id_down', 0,TINTEGER);
$id_show = retrieve(GET, 'show', 0,TINTEGER);
$id_hide = retrieve(GET, 'hide', 0,TINTEGER);
$new_cat = retrieve(GET, 'new', false);
$id_edit = retrieve(GET, 'edit', 0,TINTEGER);
$error = retrieve(GET, 'error', '');

require_once('articles_cats.class.php');
$articles_categories = new ArticlesCats();

$tpl = new Template('articles/admin_articles_cat.tpl');
$Errorh->set_template($tpl);

require_once('admin_articles_menu.php');
$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));

if ($id_up > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->move($id_up, MOVE_CATEGORY_UP);
	redirect(url('admin_articles_cat.php'));
}
elseif ($id_down > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->move($id_down, MOVE_CATEGORY_DOWN);
	redirect(url('admin_articles_cat.php'));
}
elseif ($id_show > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	redirect(url('admin_articles_cat.php'));
}
elseif ($id_hide > 0)
{
	$Session->csrf_get_protect();
	$articles_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	redirect(url('admin_articles_cat.php'));
}
elseif ($cat_to_del > 0)
{
	$tpl->assign_vars(array(
		'L_REMOVING_CATEGORY' => $ARTICLES_LANG['removing_category'],
		'L_EXPLAIN_REMOVING' => $ARTICLES_LANG['explain_removing_category'],
		'L_DELETE_CATEGORY_AND_CONTENT' => $ARTICLES_LANG['delete_category_and_its_content'],
		'L_MOVE_CONTENT' => $ARTICLES_LANG['move_category_content'],
		'L_SUBMIT' => $LANG['delete']
	));

	$tpl->assign_block_vars('removing_interface', array(
		'CATEGORY_TREE' => $articles_categories->build_select_form(0, 'id_parent', 'id_parent', $cat_to_del),
		'IDCAT' => $cat_to_del,
	));
}
elseif ($new_cat XOR $id_edit > 0)
{
	$tpl->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
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
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_REQUIRE_TITLE' => $LANG['required_field'].' : '.$ARTICLES_LANG['category_name'],
		'L_OR_DIRECT_PATH' => $ARTICLES_LANG['or_direct_path'],
		'L_ARTICLES_TPL'=>$ARTICLES_LANG['articles_tpl'],
		'L_CAT_TPL'=>$ARTICLES_LANG['cat_tpl'],
		'L_CAT_ICON'=>$ARTICLES_LANG['cat_icon'],
		'L_TPL'=>$ARTICLES_LANG['tpl'],
		'L_ARTICLES_TPL_EXPLAIN'=>$ARTICLES_LANG['tpl_explain'],
	));

	if ($id_edit > 0 && array_key_exists($id_edit, $ARTICLES_CAT))
	{
		$special_auth = $ARTICLES_CAT[$id_edit]['auth'] !== $CONFIG_ARTICLES['global_auth'] ? true : false;
		$ARTICLES_CAT[$id_edit]['auth'] = $special_auth ? $ARTICLES_CAT[$id_edit]['auth'] : $CONFIG_ARTICLES['global_auth'];

		$img_direct_path = (strpos($ARTICLES_CAT[$id_edit]['image'], '/') !== false);
		$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
		import('io/filesystem/folder');
		$image_folder_path = new Folder('./');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
		{
			$image = $images->get_name();
			$selected = $image == $ARTICLES_CAT[$id_edit]['image'] ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $image . '"' . ($img_direct_path ? '' : $selected) . '>' . $image . '</option>';
		}


		$tpl_articles_list = '';
		$tpl_folder_path = new Folder('./templates');
		foreach ($tpl_folder_path->get_files('`^articles.*\.tpl$`') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			$selected = $tpl_articles == $ARTICLES_CAT[$id_edit]['tpl_articles'] ? ' selected="selected"' : '';
			if($tpl_articles != 'articles_cat.tpl')
			$tpl_articles_list .= '<option value="' . $tpl_articles . '"' .  $selected . '>' . $tpl_articles . '</option>';
		}

		$tpl_cat_list = '';
		$tpl_folder_path = new Folder('./templates');
		foreach ($tpl_folder_path->get_files('`^articles_cat.*\.tpl$`') as $tpl_cat)
		{
			$tpl_cat = $tpl_cat->get_name();
			$selected = $tpl_cat == $ARTICLES_CAT[$id_edit]['tpl_cat'] ? ' selected="selected"' : '';
			$tpl_cat_list .= '<option value="' . $tpl_cat. '"' .  $selected . '>' . $tpl_cat . '</option>';
		}

		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => $ARTICLES_CAT[$id_edit]['name'],
			'DESCRIPTION' => unparse($ARTICLES_CAT[$id_edit]['description']),
			'IMG_PATH' => $img_direct_path ? $ARTICLES_CAT[$id_edit]['image'] : '',
			'IMG_ICON' => !empty($ARTICLES_CAT[$id_edit]['image']) ? '<img src="' . $ARTICLES_CAT[$id_edit]['image'] . '" alt="" class="valign_middle" />' : '',		
			'IMG_LIST'=>$image_list,
			'TPL_ARTICLES_LIST'=>$tpl_articles_list,
			'TPL_CAT_LIST'=>$tpl_cat_list,
			'CATEGORIES_TREE' => $articles_categories->build_select_form($ARTICLES_CAT[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => $special_auth ? 'true' : 'false',
			'DISPLAY_SPECIAL_AUTH' => $special_auth ? 'block' : 'none',
			'SPECIAL_CHECKED' => $special_auth ? 'checked="checked"' : '',
			'AUTH_READ' => Authorizations::generate_select(AUTH_ARTICLES_READ, $ARTICLES_CAT[$id_edit]['auth']),
			'AUTH_WRITE' => Authorizations::generate_select(AUTH_ARTICLES_WRITE, $ARTICLES_CAT[$id_edit]['auth']),
			'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_ARTICLES_CONTRIBUTE, $ARTICLES_CAT[$id_edit]['auth']),
			'AUTH_MODERATION' => Authorizations::generate_select(AUTH_ARTICLES_MODERATE, $ARTICLES_CAT[$id_edit]['auth']),
			'ARTICLES_TPL_CHECKED'=>$ARTICLES_CAT[$id_edit]['tpl_articles'] != 'articles.tpl' || $ARTICLES_CAT[$id_edit]['tpl_cat'] != 'articles_cat.tpl' ? 'checked="checked"' : '',
			'DISPLAY_ARTICLES_TPL' => $ARTICLES_CAT[$id_edit]['tpl_articles'] != 'articles.tpl'  || $ARTICLES_CAT[$id_edit]['tpl_cat'] != 'articles_cat.tpl' ? 'block' : 'none',
			'JS_SPECIAL_ARTICLES_TPL' => $ARTICLES_CAT[$id_edit]['tpl_articles'] != 'articles.tpl'  || $ARTICLES_CAT[$id_edit]['tpl_cat'] != 'articles_cat.tpl' ? 'true' : 'false',
		));
	}
	else
	{
		$id_edit = 0;
		$img_default = '../articles/articles.png';
		$img_default_name = 'articles.png';
		$image_list = '<option value="'.$img_default.'" selected="selected">'.$img_default_name.'</option>';
		import('io/filesystem/folder');
		$image_folder_path = new Folder('./');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
		{
			$image = $images->get_name();
			if($image != $img_default_name)
			$image_list .= '<option value="' . $image . '">' . $image . '</option>';
		}

		$tpl_default_name = 'articles.tpl';
		$tpl_articles_list = '<option value=""' . ($tpl_default_name ? ' selected="selected"' : '') . '>'.$tpl_default_name.'</option>';
		$tpl_folder_path = new Folder('./templates');
		foreach ($tpl_folder_path->get_files('`^articles.*\.tpl$`') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			if($tpl_articles != $tpl_default_name & $tpl_articles != 'articles_cat.tpl' )
			$tpl_articles_list.= '<option value="' . $tpl_articles . '">' . $tpl_articles. '</option>';
		}

		$tpl_default_name = 'articles_cat.tpl';
		$tpl_cat_list = '<option value=""' . ($tpl_default_name ? ' selected="selected"' : '') . '>'.$tpl_default_name.'</option>';
		$tpl_folder_path = new Folder('./templates');
		foreach ($tpl_folder_path->get_files('`^articles_cat.*\.tpl$`') as $tpl_cat)
		{
			$tpl_cat = $tpl_cat->get_name();
			if($tpl_cat != $tpl_default_name)
			$tpl_cat_list.= '<option value="' . $tpl_cat . '">' . $tpl_cat. '</option>';
		}
		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMG_PATH' => '',
			'IMG_ICON' => '',	
			'IMG_LIST' => $image_list,
			'TPL_ARTICLES_LIST'=>$tpl_articles_list,
			'TPL_CAT_LIST'=>$tpl_cat_list,
			'IMG_PREVIEW' => second_parse_url($img_default),
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
		$id_parent = retrieve(POST, 'id_parent', 0,TINTEGER);

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
		$tpl_articles=retrieve(POST, 'tpl_articles', 'articles.tpl', TSTRING);
		$tpl_cat=retrieve(POST, 'tpl_cat', 'articles_cat.tpl', TSTRING);

		if(retrieve(POST,'icon_path',false))
		$icon=retrieve(POST,'icon_path','');
			
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		$auth = !empty($_POST['special_auth']) ? addslashes(serialize(Authorizations::build_auth_array_from_form(AUTH_ARTICLES_READ, AUTH_ARTICLES_CONTRIBUTE, AUTH_ARTICLES_WRITE, AUTH_ARTICLES_MODERATE))) : '';

		if (empty($name))
		redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');
		if ($id_cat > 0)
		$error_string = $articles_categories->Update_category($id_cat, $id_parent, $name, $description, $icon, $auth,$tpl_articles,$tpl_cat);
		else
		$error_string = $articles_categories->add($id_parent, $name, $description, $icon, $auth,$tpl_articles,$tpl_cat);
	}

	// Feeds Regeneration
	import('content/syndication/feed');
	Feed::clear_cache('articles');

	$Cache->Generate_module_file('articles');

	redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
}
else
{
	if (!empty($error))
	{
		switch ($error)
		{
			case 'e_required_fields_empty' :
				$Errorh->handler($ARTICLES_LANG['required_fields_empty'], E_USER_WARNING);
				break;
			case 'e_unexisting_category' :
				$Errorh->handler($ARTICLES_LANG['unexisting_category'], E_USER_WARNING);
				break;
			case 'e_new_cat_does_not_exist' :
				$Errorh->handler($ARTICLES_LANG['new_cat_does_not_exist'], E_USER_WARNING);
				break;
			case 'e_infinite_loop' :
				$Errorh->handler($ARTICLES_LANG['infinite_loop'], E_USER_WARNING);
				break;
			case 'e_success' :
				$Errorh->handler($ARTICLES_LANG['successful_operation'], E_USER_SUCCESS);
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

$tpl->parse();
require_once('../admin/admin_footer.php');

?>