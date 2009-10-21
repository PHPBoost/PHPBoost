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

if ($cat_to_del > 0)
{
	$array_cat = array();
	$nbr_cat = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE idcat = '" . $cat_to_del . "'", __LINE__, __FILE__);
	$articles_categories->build_children_id_list($cat_to_del, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST);

	if (empty($array_cat) && $nbr_cat === 0)
	{

		$articles_categories->delete($cat_to_del);
		
		// Feeds Regeneration
		import('content/feed/feed');
		Feed::clear_cache('articles');

		redirect(url(HOST . SCRIPT . '?error=e_success#errorh'), '', '&');
	}
	else
	{
		$tpl->assign_vars(array(
			'EMPTY_CATS' => count($ARTICLES_CAT) < 2 ? true : false,
			'L_REMOVING_CATEGORY' => $ARTICLES_LANG['removing_category'],
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
	$tpl->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
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
		'L_ARTICLES_TPL'=>$ARTICLES_LANG['articles_tpl'],
		'L_CAT_TPL'=>$ARTICLES_LANG['cat_tpl'],
		'L_CAT_ICON'=>$ARTICLES_LANG['cat_icon'],
		'L_TPL'=>$ARTICLES_LANG['tpl'],
		'L_ARTICLES_TPL_EXPLAIN'=>$ARTICLES_LANG['tpl_explain'],
		'L_SPECIAL_OPTION' => $ARTICLES_LANG['special_option'],
		'L_SPECIAL_OPTION_EXPLAIN' => $ARTICLES_LANG['special_option_explain'],
		'L_HIDE'=>$ARTICLES_LANG['hide'],
		'L_DISPLAY'=>$LANG['display'],
		'L_ENABLE'=>$ARTICLES_LANG['enable'],
		'L_DESABLE'=>$ARTICLES_LANG['desable'],
		'L_AUTHOR'=>$ARTICLES_LANG['author'],
		'L_COM'=>$LANG['title_com'],
		'L_NOTE'=>$LANG['notes'],
		'L_PRINTABLE'=>$LANG['printable_version'],
		'L_DATE'=>$LANG['date'],
		'L_LINK_MAIL'=>$ARTICLES_LANG['admin_link_mail'],
		'L_EXTEND_FIELD'=>$ARTICLES_LANG['extend_field'],
		'L_EXTEND_FIELD_EXPLAIN'=>$ARTICLES_LANG['extend_field_explain'],
		'L_FIELD_NAME'=>$ARTICLES_LANG['extend_field_name'],
		'L_FIELD_TYPE'=>$ARTICLES_LANG['extend_field_type'],
		'L_ADD_FIELD'=>$ARTICLES_LANG['extend_field_add'],
	));

	if ($id_edit > 0 && array_key_exists($id_edit, $ARTICLES_CAT))
	{
		$special_auth = $ARTICLES_CAT[$id_edit]['auth'] !== $CONFIG_ARTICLES['global_auth'] ? true : false;
		$ARTICLES_CAT[$id_edit]['auth'] = $special_auth ? $ARTICLES_CAT[$id_edit]['auth'] : $CONFIG_ARTICLES['global_auth'];

		$special_options = $ARTICLES_CAT[$id_edit]['options'] !== unserialize($CONFIG_ARTICLES['options']) ? true : false;
		$ARTICLES_CAT[$id_edit]['options'] = $special_options ? $ARTICLES_CAT[$id_edit]['options'] : unserialize($CONFIG_ARTICLES['options']);
		
		$extend_field = !empty($ARTICLES_CAT[$id_edit]['extend_field']) ? true : false;
		// category icon
		$img_direct_path = (strpos($ARTICLES_CAT[$id_edit]['image'], '/') !== false);
		$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
		import('io/filesystem/Folder');
		$image_folder_path = new Folder('./');
		foreach ($image_folder_path->get_files('`\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $images)
		{
			$image = $images->get_name();
			$selected = $image == $ARTICLES_CAT[$id_edit]['image'] ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $image . '"' . ($img_direct_path ? '' : $selected) . '>' . $image . '</option>';
		}
		
		// articles templates
		$tpl_articles_list = '';
		$tpl_folder_path = new Folder('./templates');
		foreach ($tpl_folder_path->get_files('`^articles.*\.tpl$`') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			$selected = $tpl_articles == $ARTICLES_CAT[$id_edit]['tpl_articles'] ? ' selected="selected"' : '';
			if($tpl_articles != 'articles_cat.tpl')
			$tpl_articles_list .= '<option value="' . $tpl_articles . '"' .  $selected . '>' . $tpl_articles . '</option>';
		}
		// category templates
		$tpl_cat_list = '';
		$tpl_folder_path = new Folder('./templates');
		foreach ($tpl_folder_path->get_files('`^articles_cat.*\.tpl$`') as $tpl_cat)
		{
			$tpl_cat = $tpl_cat->get_name();
			$selected = $tpl_cat == $ARTICLES_CAT[$id_edit]['tpl_cat'] ? ' selected="selected"' : '';
			$tpl_cat_list .= '<option value="' . $tpl_cat. '"' .  $selected . '>' . $tpl_cat . '</option>';
		}
		// extend field

		$extend_field=!empty($ARTICLES_CAT[$id_edit]['extend_field']) ? true : false;
		$i=0;
		if(	$extend_field )
		{
			foreach ($ARTICLES_CAT[$id_edit]['extend_field'] as $field)
			{	
				$tpl->assign_block_vars('field', array(
					'I' =>$i,
					'NAME' => stripslashes($field['name']),
					'TYPE'=>stripslashes($field['type']),
				));
				$i++;
			}	
		}
		if($i==0)
		{
				$tpl->assign_block_vars('field', array(
					'I' =>0,
					'NAME' => '',
					'TYPE'=>'',
				));
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
			'JS_SPECIAL_OPTION' => $special_options ? 'true' : 'false',
			'DISPLAY_SPECIAL_OPTION'=> $special_options ? 'block' : 'none',
			'OPTION_CHECKED' => $special_options ? 'checked="checked"' : '',
			'SELECTED_NOTATION_HIDE'=> !$ARTICLES_CAT[$id_edit]['options']['note'] ? ' selected="selected"' : '',
			'SELECTED_COM_HIDE'=> !$ARTICLES_CAT[$id_edit]['options']['com'] ? ' selected="selected"' : '',
			'SELECTED_DATE_HIDE'=> !$ARTICLES_CAT[$id_edit]['options']['date'] ? ' selected="selected"' : '',
			'SELECTED_AUTHOR_HIDE'=> !$ARTICLES_CAT[$id_edit]['options']['author'] ? ' selected="selected"' : '',
			'SELECTED_IMPR_HIDE'=> !$ARTICLES_CAT[$id_edit]['options']['impr'] ? ' selected="selected"' : '',
			'SELECTED_MAIL_HIDE'=> !$ARTICLES_CAT[$id_edit]['options']['mail'] ? ' selected="selected"' : '',
			'SELECTED_NOTATION_DISPLAY'=>$ARTICLES_CAT[$id_edit]['options']['note'] ? ' selected="selected"' : '',
			'SELECTED_COM_DISPLAY'=>$ARTICLES_CAT[$id_edit]['options']['com'] ? ' selected="selected"' : '',
			'SELECTED_DATE_DISPLAY'=>$ARTICLES_CAT[$id_edit]['options']['date'] ? ' selected="selected"' : '',
			'SELECTED_AUTHOR_DISPLAY'=>$ARTICLES_CAT[$id_edit]['options']['author'] ? ' selected="selected"' : '',
			'SELECTED_IMPR_DISPLAY'=>$ARTICLES_CAT[$id_edit]['options']['impr'] ? ' selected="selected"' : '',
			'SELECTED_MAIL_DISPLAY'=>$ARTICLES_CAT[$id_edit]['options']['mail'] ? ' selected="selected"' : '',
			'JS_EXTEND_FIELD' => $extend_field ? 'true' : 'false',
			'EXTEND_FIELD_CHECKED'=> $extend_field ? 'checked="checked"' : '',
			'DISPLAY_EXTEND_FIELD' =>$extend_field ? 'block' : 'none',
			'NB_FIELD'=>$i == 0 ? 1 : $i,
		));
	}
	else
	{
		$id_edit = 0;
		$img_default = '../articles/articles.png';
		$img_default_name = 'articles.png';
		$image_list = '<option value="'.$img_default.'" selected="selected">'.$img_default_name.'</option>';
		import('io/filesystem/Folder');
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
		
		$options=unserialize($CONFIG_ARTICLES['options']);
			
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
			'JS_SPECIAL_ARTICLES_TPL' => 'false',
			'ARTICLES_TPL_CHECKED'=> '',
			'DISPLAY_ARTICLES_TPL' =>'none',
			'JS_SPECIAL_OPTION' => 'false',
			'DISPLAY_SPECIAL_OPTION'=>'none',
			'OPTION_CHECKED' => '',
			'JS_EXTEND_FIELD' => 'false',
			'EXTEND_FIELD_CHECKED'=> '',
			'DISPLAY_EXTEND_FIELD' =>'none',
			'SELECTED_NOTATION_HIDE'=> !$options['note'] ? ' selected="selected"' : '',
			'SELECTED_COM_HIDE'=> !$options['com'] ? ' selected="selected"' : '',
			'SELECTED_DATE_HIDE'=> !$options['date'] ? ' selected="selected"' : '',
			'SELECTED_AUTHOR_HIDE'=> !$options['author'] ? ' selected="selected"' : '',
			'SELECTED_IMPR_HIDE'=> !$options['impr'] ? ' selected="selected"' : '',
			'SELECTED_MAIL_HIDE'=> !$options['mail'] ? ' selected="selected"' : '',
			'SELECTED_NOTATION_DISPLAY'=>$options['note'] ? ' selected="selected"' : '',
			'SELECTED_COM_DISPLAY'=>$options['com'] ? ' selected="selected"' : '',
			'SELECTED_DATE_DISPLAY'=>$options['date'] ? ' selected="selected"' : '',
			'SELECTED_AUTHOR_DISPLAY'=>$options['author'] ? ' selected="selected"' : '',
			'SELECTED_IMPR_DISPLAY'=>$options['impr'] ? ' selected="selected"' : '',
			'SELECTED_MAIL_DISPLAY'=>$options['mail'] ? ' selected="selected"' : '',
			'NB_FIELD'=>1,
		));
	
		$tpl->assign_block_vars('field', array(
				'I'=>0,
				'NAME'=>'',
				'TYPE'=>'',
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
		$tpl_articles = empty($tpl_articles) ? 'articles.tpl' : $tpl_articles;
		$tpl_cat = empty($tpl_cat) ? 'articles_cat.tpl' : $tpl_cat;
		$options = array (
				'note'=>retrieve(POST, 'note', true, TBOOL),
				'com'=>retrieve(POST, 'com', true, TBOOL),
				'impr'=>retrieve(POST, 'impr', true, TBOOL),
				'date'=>retrieve(POST, 'date', true, TBOOL),
				'author'=>retrieve(POST, 'author', true, TBOOL),
				'mail'=>retrieve(POST, 'mail', true, TBOOL),
				);
				
		$extend_field = array();
		if(retrieve(POST,'extend_field_checkbox',false))
		{
			for ($i = 0;$i < 100; $i++)
			{	
				if (retrieve(POST,'a'.$i,false,TSTRING))
				{				
					$extend_field[$i]['name'] = strtoupper(retrieve(POST, 'a'.$i, '',TSTRING));
					$extend_field[$i]['type'] = retrieve(POST, 'v'.$i, '',TSTRING_UNCHANGE);
				}
			}
		}
		if(retrieve(POST,'icon_path',false))
		$icon=retrieve(POST,'icon_path','');
			
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		$auth = !empty($_POST['special_auth']) ? addslashes(serialize(Authorizations::build_auth_array_from_form(AUTH_ARTICLES_READ, AUTH_ARTICLES_CONTRIBUTE, AUTH_ARTICLES_WRITE, AUTH_ARTICLES_MODERATE))) : '';

		if (empty($name))
		redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');
		if ($id_cat > 0)
		$error_string = $articles_categories->Update_category($id_cat, $id_parent, $name, $description, $icon, $auth,$tpl_articles,$tpl_cat,serialize($options),addslashes(serialize($extend_field)));
		else
		$error_string = $articles_categories->add($id_parent, $name, $description, $icon, $auth,$tpl_articles,$tpl_cat,serialize($options),addslashes(serialize($extend_field)));
	}

	// Feeds Regeneration
	import('content/feed/feed');
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