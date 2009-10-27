<?php
/*##################################################
 *                               admin_articles_models.php
 *                            -------------------
 *   begin                : November 27, 2009
 *   copyright          : (C) 2009 Maurel Nicolas
 *   email                : crunchfamily@free.fr
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
$model_to_del = retrieve(GET, 'del', 0,TINTEGER);
$new_model = retrieve(GET, 'new', false);
$id_edit = retrieve(GET, 'edit', 0,TINTEGER);
$error = retrieve(GET, 'error', '');

require_once('articles_cats.class.php');
$articles_categories = new ArticlesCats();

$tpl = new Template('articles/admin_articles_models.tpl');
$Errorh->set_template($tpl);

require_once('admin_articles_menu.php');
$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));

if ($model_to_del > 0)
{
	$Session->csrf_get_protect();
	
	$Sql->query_inject("DELETE FROM " . DB_TABLE_ARTICLES_MODEL . " WHERE id = '" . $model_to_del . "'", __LINE__, __FILE__);
	
	$error_string = 'e_success';
	redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
}
elseif ($new_model XOR $id_edit > 0)
{
	$tpl->assign_vars(array(
		'KERNEL_EDITOR' => display_editor(),
		'L_MODEL' => $ARTICLES_LANG['model'],
		'L_DESCRIPTION' => $ARTICLES_LANG['model_desc'],
		'L_NAME' => $ARTICLES_LANG['model_name'],
		'L_REQUIRE' => $LANG['require'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset'],
		'L_SUBMIT' => $id_edit > 0 ? $LANG['edit'] : $LANG['add'],
		'L_ARTICLES_TPL'=>$ARTICLES_LANG['articles_tpl'],
		'L_CAT_TPL'=>$ARTICLES_LANG['cat_tpl'],
		'L_TPL'=>$ARTICLES_LANG['tpl'],
		'L_ARTICLES_TPL_EXPLAIN'=>$ARTICLES_LANG['tpl_model_explain'],
		'L_EXTEND_FIELD'=>$ARTICLES_LANG['extend_field'],
		'L_EXTEND_FIELD_EXPLAIN'=>$ARTICLES_LANG['extend_field_model_explain'],
		'L_FIELD_NAME'=>$ARTICLES_LANG['extend_field_name'],
		'L_FIELD_TYPE'=>$ARTICLES_LANG['extend_field_type'],
		'L_ADD_FIELD'=>$ARTICLES_LANG['extend_field_add'],
		'L_SPECIAL_OPTION' => $ARTICLES_LANG['special_option'],
		'L_SPECIAL_OPTION_MODEL_EXPLAIN' => $ARTICLES_LANG['special_option_model_explain'],
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
		'L_USE_TAB'=>$ARTICLES_LANG['use_tab'],
		
	));

	if ($id_edit > 0)
	{

		$models = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);
		$options = unserialize($models['options']);
		$special_options = $options  !== unserialize($CONFIG_ARTICLES['options']) ? true : false;
		$options  = $special_options ? $options  : unserialize($CONFIG_ARTICLES['options']);
		
		
		import('io/filesystem/Folder');
		
		// articles templates
		$tpl_articles_list = '';
		$tpl_folder_path = new Folder('./templates/models');
		foreach ($tpl_folder_path->get_files('`\.tpl$`i') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			$selected = $tpl_articles == $models['tpl_articles'] ? ' selected="selected"' : '';
			$tpl_articles_list .= '<option value="' . $tpl_articles . '"' .  $selected . '>' . $tpl_articles . '</option>';
		}
		// category templates
		$tpl_cat_list = '';
		$tpl_folder_path = new Folder('./templates/models');
		foreach ($tpl_folder_path->get_files('`\.tpl$`i') as $tpl_cat)
		{
			$tpl_cat = $tpl_cat->get_name();
			$selected = $tpl_cat == $models['tpl_cats'] ? ' selected="selected"' : '';
			$tpl_cat_list .= '<option value="' . $tpl_cat. '"' .  $selected . '>' . $tpl_cat . '</option>';
		}
		// extend field
		$extend_field_tab=unserialize($models['extend_field']);
		$extend_field = !empty($extend_field_tab) ? true : false;
		
		$i=0;
		if(	$extend_field )
		{
			foreach ($extend_field_tab as $field)
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
			'NAME' => $models['name'],
			'DESCRIPTION' => unparse($models['description']),
			'ID_MODEL'=>$models['id'],
			'TAB'=> $models['pagination_tab'] == 1 ? ' checked ' : '',
			'NO_TAB'=>  $models['pagination_tab'] != 1 ? ' checked ' : '',
			'TPL_ARTICLES_LIST'=>$tpl_articles_list,
			'TPL_CAT_LIST'=>$tpl_cat_list,
			'ARTICLES_TPL_CHECKED'=>$models['tpl_articles'] != 'articles.tpl'  ? 'checked="checked"' : '',
			'DISPLAY_ARTICLES_TPL' => $models['tpl_articles'] != 'articles.tpl' ? 'block' : 'none',
			'JS_SPECIAL_ARTICLES_TPL' => $models['tpl_articles'] != 'articles.tpl' ? 'true' : 'false',		
			'JS_SPECIAL_OPTION' => $special_options ? 'true' : 'false',
			'DISPLAY_SPECIAL_OPTION'=> $special_options ? 'block' : 'none',
			'OPTION_CHECKED' => $special_options ? 'checked="checked"' : '',
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
			'JS_EXTEND_FIELD' => $extend_field ? 'true' : 'false',
			'EXTEND_FIELD_CHECKED'=> $extend_field ? 'checked="checked"' : '',
			'DISPLAY_EXTEND_FIELD' =>$extend_field ? 'block' : 'none',
			'NB_FIELD'=>$i == 0 ? 1 : $i,
		));
	}
	else
	{
		$id_edit = 0;
		
		import('io/filesystem/Folder');
		
		$tpl_default_name = 'articles.tpl';
		$tpl_articles_list = '<option value=""' . ($tpl_default_name ? ' selected="selected"' : '') . '>'.$tpl_default_name.'</option>';
		$tpl_folder_path = new Folder('./templates/models');
		foreach ($tpl_folder_path->get_files('`\.tpl$`i') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			if($tpl_articles != $tpl_default_name)
			$tpl_articles_list.= '<option value="' . $tpl_articles . '">' . $tpl_articles. '</option>';
		}

		$tpl_default_name = 'articles_cat.tpl';
		$tpl_cat_list = '<option value=""' . ($tpl_default_name ? ' selected="selected"' : '') . '>'.$tpl_default_name.'</option>';
		$tpl_folder_path = new Folder('./templates/models');
		foreach ($tpl_folder_path->get_files('`\.tpl$`i') as $tpl_cat)
		{
			$tpl_cat = $tpl_cat->get_name();
			if($tpl_cat != $tpl_default_name)
			$tpl_cat_list.= '<option value="' . $tpl_cat . '">' . $tpl_cat. '</option>';
		}
				
		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'TAB'=> $CONFIG_ARTICLES['tab'] == 1 ? ' checked ' : '',
			'NO_TAB'=>  $CONFIG_ARTICLES['tab'] != 1 ? ' checked ' : '',
			'TPL_ARTICLES_LIST'=>$tpl_articles_list,
			'TPL_CAT_LIST'=>$tpl_cat_list,
			'JS_SPECIAL_ARTICLES_TPL' => 'false',
			'ARTICLES_TPL_CHECKED'=> '',
			'DISPLAY_ARTICLES_TPL' =>'none',
			'JS_EXTEND_FIELD' => 'false',
			'EXTEND_FIELD_CHECKED'=> '',
			'DISPLAY_EXTEND_FIELD' =>'none',
			'JS_SPECIAL_OPTION' => 'false',
			'DISPLAY_SPECIAL_OPTION'=>'none',
			'OPTION_CHECKED' => '',
			'SELECTED_NOTATION_HIDE'=> '',
			'SELECTED_COM_HIDE'=> '',
			'SELECTED_DATE_HIDE'=> '',
			'SELECTED_AUTHOR_HIDE'=> '',
			'SELECTED_IMPR_HIDE'=> '',
			'SELECTED_MAIL_HIDE'=> '',
			'SELECTED_NOTATION_DISPLAY'=>' selected="selected"' ,
			'SELECTED_COM_DISPLAY'=>'selected="selected"',
			'SELECTED_DATE_DISPLAY'=>' selected="selected"',
			'SELECTED_AUTHOR_DISPLAY'=>' selected="selected"' ,
			'SELECTED_IMPR_DISPLAY'=> ' selected="selected"' ,
			'SELECTED_MAIL_DISPLAY'=> ' selected="selected"',
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

	$id_model = retrieve(POST, 'id_model', 0,TINTEGER);
	$name = retrieve(POST, 'name', '');	
	$description = retrieve(POST, 'description', '', TSTRING_PARSE);
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
	$pagination_tab=retrieve(POST, 'tab', 0);
	
	if (empty($name))
	redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');
		
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
			
	if ($id_model > 0)
	{
		$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES_MODEL. " SET name = '".$name."', description = '" . $description . "',tpl_articles='".$tpl_articles."',tpl_cats='".$tpl_cat."',extend_field='".addslashes(serialize($extend_field))."', options = '".serialize($options)."' , pagination_tab =  '".$pagination_tab."' WHERE id = '" . $id_model . "'", __LINE__, __FILE__);
		$error_string = 'e_success';
	}
	else
	{
		$Sql->query_inject("INSERT INTO " . DB_TABLE_ARTICLES_MODEL . " SET name = '".$name."', description = '" . $description . "',tpl_articles='".$tpl_articles."',tpl_cats='".$tpl_cat."',extend_field='".addslashes(serialize($extend_field))."', options = '".serialize($options)."' , pagination_tab =  '".$pagination_tab."'", __LINE__, __FILE__);
		$error_string = 'e_success';
	}

	// Feeds Regeneration
	import('content/feed/Feed');
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
	$nbr_models = $Sql->count_table('articles_models' , __LINE__, __FILE__);

	//On crée une pagination si le nombre d'articles est trop important.
	import('util/Pagination');
	$Pagination = new Pagination();

	$get_sort = retrieve(GET, 'sort', '');
	switch ($get_sort)
	{
		case 'alpha' :
			$sort = 'title';
			break;
		case 'date' :
			$sort = 'timestamp';
			break;
		case 'cat' :
			$sort = 'idcat';
			break;
		case 'user_id' :
			$sort = 'user_id';
			break;
		case 'visible' :
			$sort = 'visible';
			break;
		default :
			$sort = 'id';
	}

	$get_mode = retrieve(GET, 'mode', '');
	$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';

	$tpl->assign_block_vars('models_management', array(
			'L_MODELS_MANAGEMENT' => $ARTICLES_LANG['models_management'],
			'L_ARTICLES_TPL' => $ARTICLES_LANG['articles_tpl'],
			'L_CAT_TPL' => $ARTICLES_LANG['cat_tpl'],
			'L_MODELS_MANAGEMENT' => $ARTICLES_LANG['models_management'],
			'L_DESCRIPTION'=>$LANG['description'],
			'L_EXTEND_FIELD'=>$ARTICLES_LANG['extend_field'],
			'L_USE_TAB'=>$ARTICLES_LANG['use_tab'],
			'L_SPECIAL_OPTION' => $ARTICLES_LANG['special_option'],
			'L_AUTHOR'=>$ARTICLES_LANG['author'],
			'L_COM'=>$LANG['title_com'],
			'L_NOTE'=>$LANG['notes'],
			'L_PRINTABLE'=>$LANG['printable_version'],
			'L_DATE'=>$LANG['date'],
			'L_LINK_MAIL'=>$ARTICLES_LANG['admin_link_mail'],
			'L_MODEL_INO'=>$ARTICLES_LANG['model_info'],
		));
		
	$result = $Sql->query_while("SELECT id, name,description ,extend_field,tpl_articles ,tpl_cats ,options,pagination_tab
	FROM " . DB_TABLE_ARTICLES_MODEL . " a
	ORDER BY " . $sort . " " . $mode .
	$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);

	while ($row = $Sql->fetch_assoc($result))
	{	
		$extend_field="";
		$c_extend_field=!empty($row['extend_field']) ? true : false;
		if($c_extend_field)
		{
			$extend_field_tab=unserialize($row['extend_field']);
			foreach ($extend_field_tab as $field)
			{	
				$extend_field.=$field['name'].' - ';
			}	
		}
		
		$options=unserialize($row['options']);
		$tpl->assign_block_vars('models', array(
			'NAME' => $row['name'],
			'DESC' => second_parse($row['description']),
			'USE_TAB'=> $row['pagination_tab'] == 1 ? $LANG['yes'] :  $LANG['no'],
			'TPL_ARTICLES' => $row['tpl_articles'],
			'TPL_CATS' => unparse($row['tpl_cats']),
			'EXTEND_FIELD'=>!empty($extend_field) ? $extend_field : 'Aucun',
			'U_ADMIN_EDIT_MODEL' => url('admin_articles_models.php?edit=' . $row['id']),
			'U_ADMIN_DELETE_MODEL' => url('admin_articles_models.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
			'NOTE'=>$options['note'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'COM'=>$options['com'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'DATE'=>$options['date'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'AUTHOR'=>$options['author'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'IMPR'=>$options['impr'] ? $ARTICLES_LANG['enable'] : $ARTICLES_LANG['desable'],
			'MAIL'=>$options['mail'] ? $LANG['display']: $ARTICLES_LANG['hide'],
		));

	}
}

$tpl->parse();
require_once('../admin/admin_footer.php');

?>