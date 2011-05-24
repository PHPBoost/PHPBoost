<?php
/*##################################################
 *                               admin_news_cat.php
 *                            -------------------
 *   begin                : January 24, 2007
 *   copyright            : (C) 2007 Viarre Régis, Roguelon Geoffrey
 *   email                : crowkait@phpboost.com, liaght@gmail.com
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
require_once('news_constants.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
load_module_lang('news'); //Chargement de la langue du module.

$news_categories = new NewsCats();

$id_up = AppContext::get_request()->get_getint('id_up', 0);
$id_down = AppContext::get_request()->get_getint('id_down', 0);
$id_show = AppContext::get_request()->get_getint('show', 0);
$id_hide = AppContext::get_request()->get_getint('hide', 0);
$cat_to_del = AppContext::get_request()->get_getint('del', 0);
$cat_to_del_post = AppContext::get_request()->get_postint('cat_to_del', 0);
$id_edit = AppContext::get_request()->get_getint('edit', 0);
$new_cat = AppContext::get_request()->get_getbool('new', false);
$error = TextHelper::strprotect(AppContext::get_request()->get_getstring('error', ''));

$tpl = new FileTemplate('news/admin_news_cat.tpl');


// Chargement du menu de l'administration.
require_once('admin_news_menu.php');
$tpl->put_all(array('ADMIN_MENU' => $admin_menu));

if ($id_up > 0)
{
	$Session->csrf_get_protect();
	$news_categories->move($id_up, MOVE_CATEGORY_UP);
	AppContext::get_response()->redirect(url('admin_news_cat.php'));
}
elseif ($id_down > 0)
{
	$Session->csrf_get_protect();
	$news_categories->move($id_down, MOVE_CATEGORY_DOWN);
	AppContext::get_response()->redirect(url('admin_news_cat.php'));
}
elseif ($id_show > 0)
{
	$Session->csrf_get_protect();
	$news_categories->change_visibility($id_show, CAT_VISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_news_cat.php'));
}
elseif ($id_hide > 0)
{
	$Session->csrf_get_protect();
	$news_categories->change_visibility($id_hide, CAT_UNVISIBLE, LOAD_CACHE);
	AppContext::get_response()->redirect(url('admin_news_cat.php'));
}
elseif ($cat_to_del > 0)
{
	$array_cat = array();
	$nbr_cat = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE idcat = '" . $cat_to_del . "'", __LINE__, __FILE__);
	$news_categories->build_children_id_list($cat_to_del, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST);

	if (empty($array_cat) && $nbr_cat === 0)
	{
		$Session->csrf_get_protect();
		$news_categories->delete($cat_to_del);
		
		// Feeds Regeneration
		Feed::clear_cache('news');

		AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_success#message_helper'), '', '&');
	}
	else
	{
		
		$tpl->put_all(array(
			'EMPTY_CATS' => count($NEWS_CAT) < 2 ? true : false,
			// 'EMPTY_CATS' => false,
			'L_REMOVING_CATEGORY' => $NEWS_LANG['removing_category'],
			'L_EXPLAIN_REMOVING' => $NEWS_LANG['explain_removing_category'],
			'L_DELETE_CATEGORY_AND_CONTENT' => $NEWS_LANG['delete_category_and_its_content'],
			'L_MOVE_CONTENT' => $NEWS_LANG['move_category_content'],
			'L_SUBMIT' => $LANG['delete']
		));

		$tpl->assign_block_vars('removing_interface', array(
			'IDCAT' => $cat_to_del,
		));
		
		$news_categories->build_select_form(0, 'idcat', 'idcat', $cat_to_del, 0, array(), RECURSIVE_EXPLORATION, $tpl);
	}
}
elseif (!empty($_POST['submit']))
{
	$error_string = 'e_success';
	//Deleting a category
	if (!empty($cat_to_del_post))
	{
		$delete_content = (!empty($_POST['action']) && $_POST['action'] == 'move') ? false : true;
		$id_parent = AppContext::get_request()->get_postint('id_parent', 0);

		if ($delete_content)
		{
			$news_categories->delete_category_recursively($cat_to_del_post);
		}
		else
		{
			$news_categories->delete_category_and_move_content($cat_to_del_post, $id_parent);
		}
	}
	else
	{
		$id_cat = AppContext::get_request()->get_postint('idcat', 0);
		$id_parent = AppContext::get_request()->get_postint('id_parent', 0);
		$name = AppContext::get_request()->get_poststring('name', '');
		$image = AppContext::get_request()->get_poststring('image', '');
		$description = FormatingHelper::strparse(AppContext::get_request()->get_poststring('description', ''));
		$auth = !empty($_POST['special_auth']) ? addslashes(serialize(Authorizations::build_auth_array_from_form(AUTH_NEWS_READ, AUTH_NEWS_CONTRIBUTE, AUTH_NEWS_WRITE, AUTH_NEWS_MODERATE))) : '';

		if (empty($name))
			AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#message_helper'), '', '&');

		if ($id_cat > 0)
			$error_string = $news_categories->Update_category($id_cat, $id_parent, $name, $description, $image, $auth);
		else
			$error_string = $news_categories->add_category($id_parent, $name, $description, $image, $auth);
	}

	// Feeds Regeneration
	
	Feed::clear_cache('news');

	$Cache->Generate_module_file('news');

	AppContext::get_response()->redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#message_helper'), '', '&');
}
elseif ($new_cat XOR $id_edit > 0)
{
	$tpl->put_all(array(
		'KERNEL_EDITOR' => display_editor(),
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
		'L_NAME' => $NEWS_LANG['category_name'],
		'L_LOCATION' => $NEWS_LANG['category_location'],
		'L_DESCRIPTION' => $NEWS_LANG['category_desc'],
		'L_IMAGE' => $NEWS_LANG['category_image'],
		'L_SPECIAL_AUTH' => $NEWS_LANG['special_auth'],
		'L_SPECIAL_AUTH_EXPLAIN' => $NEWS_LANG['special_auth_explain'],
		'L_AUTH_READ' => $NEWS_LANG['auth_read'],
		'L_AUTH_WRITE' => $NEWS_LANG['auth_write'],
		'L_AUTH_MODERATION' => $NEWS_LANG['auth_moderate'],
		'L_AUTH_CONTRIBUTION' => $NEWS_LANG['auth_contribute'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_REQUIRE_TITLE' => sprintf($LANG['required_field'], $NEWS_LANG['category_name'])
	));

	if ($id_edit > 0 && array_key_exists($id_edit, $NEWS_CAT))
	{
		$special_auth = $NEWS_CAT[$id_edit]['auth'] !== $NEWS_CONFIG['global_auth'] ? true : false;
		$NEWS_CAT[$id_edit]['auth'] = $special_auth ? $NEWS_CAT[$id_edit]['auth'] : $NEWS_CONFIG['global_auth'];

		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => $NEWS_CAT[$id_edit]['name'],
			'DESCRIPTION' => FormatingHelper::unparse($NEWS_CAT[$id_edit]['description']),
			'IMAGE' => $NEWS_CAT[$id_edit]['image'],
			'IMG_PREVIEW' => FormatingHelper::second_parse_url($NEWS_CAT[$id_edit]['image']),
			'CATEGORIES_TREE' => $news_categories->build_select_form($NEWS_CAT[$id_edit]['id_parent'], 'id_parent', 'id_parent', $id_edit),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => $special_auth ? 'true' : 'false',
			'DISPLAY_SPECIAL_AUTH' => $special_auth ? 'block' : 'none',
			'SPECIAL_CHECKED' => $special_auth ? 'checked="checked"' : '',
			'AUTH_READ' => Authorizations::generate_select(AUTH_NEWS_READ, $NEWS_CAT[$id_edit]['auth']),
			'AUTH_WRITE' => Authorizations::generate_select(AUTH_NEWS_WRITE, $NEWS_CAT[$id_edit]['auth']),
			'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_NEWS_CONTRIBUTE, $NEWS_CAT[$id_edit]['auth']),
			'AUTH_MODERATION' => Authorizations::generate_select(AUTH_NEWS_MODERATE, $NEWS_CAT[$id_edit]['auth']),
		));
	}
	else
	{
		$id_edit = 0;
		$img_default = '/news/news.png';

		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'IMAGE' => '',
			'IMG_PREVIEW' => FormatingHelper::second_parse_url($img_default),
			'CATEGORIES_TREE' => $news_categories->build_select_form($id_edit, 'id_parent', 'id_parent'),
			'IDCAT' => $id_edit,
			'JS_SPECIAL_AUTH' => 'false',
			'DISPLAY_SPECIAL_AUTH' => 'none',
			'SPECIAL_CHECKED' => '',
			'AUTH_READ' => Authorizations::generate_select(AUTH_NEWS_READ, $NEWS_CONFIG['global_auth']),
			'AUTH_WRITE' => Authorizations::generate_select(AUTH_NEWS_WRITE, $NEWS_CONFIG['global_auth']),
			'AUTH_CONTRIBUTION' => Authorizations::generate_select(AUTH_NEWS_CONTRIBUTE, $NEWS_CONFIG['global_auth']),
			'AUTH_MODERATION' => Authorizations::generate_select(AUTH_NEWS_MODERATE, $NEWS_CONFIG['global_auth']),
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
				$tpl->put('message_helper', MessageHelper::display($NEWS_LANG['required_fields_empty'], E_USER_WARNING));
				break;
			case 'e_unexisting_category' :
				$tpl->put('message_helper', MessageHelper::display($NEWS_LANG['unexisting_category'], E_USER_WARNING));
				break;
			case 'e_new_cat_does_not_exist' :
				$tpl->put('message_helper', MessageHelper::display($NEWS_LANG['new_cat_does_not_exist'], E_USER_WARNING));
				break;
			case 'e_infinite_loop' :
				$tpl->put('message_helper', MessageHelper::display($NEWS_LANG['infinite_loop'], E_USER_WARNING));
				break;
			case 'e_success' :
				$tpl->put('message_helper', MessageHelper::display($NEWS_LANG['successful_operation'], E_USER_SUCCESS, 4));
				break;
		}
	}

	$cat_config = array(
		'xmlhttprequest_file' => 'xmlhttprequest_cats.php',
		'administration_file_name' => 'admin_news_cat.php',
		'url' => array(
			'unrewrited' => 'news.php?cat=%d',
			'rewrited' => 'news-%d+%s.php'),
		);

	$news_categories->set_display_config($cat_config);

	$tpl->assign_block_vars('categories_management', array(
		'L_CATS_MANAGEMENT' => $NEWS_LANG['category_news'],
		'CATEGORIES' => $news_categories->build_administration_interface()
	));
}

$tpl->display();

require_once('../admin/admin_footer.php');

?>
