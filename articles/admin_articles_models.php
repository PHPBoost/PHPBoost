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
$model_to_del_move = retrieve(POST, 'model_to_del', 0,TINTEGER);
$new_model = retrieve(GET, 'new', false);
$id_edit = retrieve(GET, 'edit', 0,TINTEGER);
$error = retrieve(GET, 'error', '');

$articles_categories = new ArticlesCats();

$tpl = new Template('articles/admin_articles_models.tpl');

require_once('admin_articles_menu.php');
$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));

if ($model_to_del > 0)
{
	$Session->csrf_get_protect();
	
	$model_default = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE id = '" . $model_to_del . "' AND model_default = 1", __LINE__, __FILE__);
	
	if(!empty($model_default['id']))
	{
		$error_string = 'e_del_default_model';
		redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
	}
	
	$nbr_models_articles = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE id_models = '" . $model_to_del . "'", __LINE__, __FILE__);
	$nbr_models_cats = (int)$Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES_CAT . " WHERE id_models = '" . $model_to_del . "'", __LINE__, __FILE__);

	if($nbr_models_cats == 0 && $nbr_models_articles == 0)
	{
		$Sql->query_inject("DELETE FROM " . DB_TABLE_ARTICLES_MODEL . " WHERE id = '" . $model_to_del . "'", __LINE__, __FILE__);
	
		$error_string = 'e_success';
		redirect(url(HOST . SCRIPT . '?error=' . $error_string  . '#errorh'), '', '&');
	}
	else
	{
		$nbr_models = (int)$Sql->count_table('articles_models' , __LINE__, __FILE__);
		
		$tpl->assign_vars(array(
			'EMPTY_CATS' => count($ARTICLES_CAT) < 2 ? true : false,
			'L_REMOVING_MODEL' => $ARTICLES_LANG['removing_model'],
			'L_EXPLAIN_REMOVING_MODEL' => $ARTICLES_LANG['explain_removing_model'],
			'L_AFFECT_DEFAULT' => $ARTICLES_LANG['affect_default'],
			'L_AFFECT_MODEL' => $ARTICLES_LANG['affect_model'],
			'L_SUBMIT' => $LANG['delete'],
			'L_WARNING_EXTEND_FIELD_DEL'=>$ARTICLES_LANG['warning_extend_field_del'],
		));
		//extend field lost
		$lost_field="";
		$save_field="";
		$model_default = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE model_default = 1", __LINE__, __FILE__);			
		$model_default_extend_field=unserialize($model_default['extend_field']);
	
		$result = $Sql->query_while("SELECT id,id_models,extend_field
		FROM " . DB_TABLE_ARTICLES . " a
		WHERE id_models = ".$model_to_del
		, __LINE__, __FILE__);

		while ($row = $Sql->fetch_assoc($result))
		{
			$article_extend_field=!empty($row['extend_field']) ? unserialize(stripslashes($row['extend_field'])) : '';
			if(!empty($model_default_extend_field))
			{
				if(!empty($article_extend_field))
				{
					$new_article_extend_field=array();
					foreach ($article_extend_field as $article_field)
					{	
						foreach ($model_default_extend_field as $model_field)
						{
							if($article_field['name'] == $model_field['name'])
								$save_field.=$article_field['name'] . " - ";
							else
								$lost_field .= $article_field['name'] . " - ";
						}
					}
				}
			}
			else
				$lost_field = "on perd tout";
		}	
		if($nbr_models > 2)
		{
			$result = $Sql->query_while("SELECT id, name,description,model_default
			FROM " . DB_TABLE_ARTICLES_MODEL 
			, __LINE__, __FILE__);
			
			$models="";
			while ($row = $Sql->fetch_assoc($result))
			{
				if($row['id'] != $model_to_del && $row['model_default'] != 1)
					$models.='<option value="' . $row['id'] . '">' . $row['name']. '</option>';	
			}
			
			$tpl->assign_block_vars('removing_interface', array(
				'ID_MODEL' =>$model_to_del,
				'MODELS'=>$models,
				'DISABLED' => '',
				'LOST_FIELD' =>$lost_field,
				'SAVE_FIELD' => $save_field,
				'C_EXTEND_FIELD'=>true,
			));
		}
		else
		{
			$tpl->assign_block_vars('removing_interface', array(
				'ID_MODEL' =>$model_to_del,
				'DISABLED' => 'disabled',
				'MODELS'=>'',
				'LOST_FIELD' =>$lost_field,
				'SAVE_FIELD' => $save_field,
								'C_EXTEND_FIELD'=>true,
			));		
		}
	}
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
		'L_DEFAULT_MODELS'=>$ARTICLES_LANG['default_model'],
		'L_YES'=>$LANG['yes'],
		'L_NO'=>$LANG['no'],
		'L_REQUIRE_TITLE' => $LANG['require_name'],
		
	));
	if ($id_edit > 0)
	{
		$models = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE id = '" . $id_edit . "'", __LINE__, __FILE__);

		$default_model = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE model_default = 1", __LINE__, __FILE__);	

		$options = unserialize($models['options']);
		$special_options = $options  !== unserialize($default_model['options']) ? true : false;
		$options  = $special_options ? $options  : unserialize($default_model['options']);
		
		// articles templates
		$tpl_articles_list = '<option value="articles.tpl" '.($models['tpl_articles'] == "articles.tpl" ? "selected='selected'" : "").' >articles.tpl</option>';
		$tpl_folder_path = new Folder('./templates/models');
		foreach ($tpl_folder_path->get_files('`\.tpl$`i') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			$selected = ($tpl_articles == $models['tpl_articles'] || './models/'.$tpl_articles == $models['tpl_articles'] ) ? ' selected="selected"' : '';
			$tpl_articles_list .= '<option value="' . $tpl_articles . '"' .  $selected . '>' . $tpl_articles . '</option>';
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
			'ARTICLES_TPL_CHECKED'=>($models['tpl_articles'] != $default_model['tpl_articles']) ? 'checked="checked"' : '',
			'DISPLAY_ARTICLES_TPL' => ($models['tpl_articles'] != $default_model['tpl_articles']) ? 'block' : 'none',
			'JS_SPECIAL_ARTICLES_TPL' => ($models['tpl_articles'] != $default_model['tpl_articles'])? 'true' : 'false',		
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
			'DEFAULT_MODEL'=>$models['model_default'] ? 'disabled checked="checked"' : '',
			'NOT_DEFAULT_MODEL'=>$models['model_default'] ? 'disabled' : 'checked="checked"',
		));
	}
	else
	{
		$id_edit = 0;
	
		$tpl_default_name = 'articles.tpl';
		$tpl_articles_list = '<option value="articles.tpl" selected >articles.tpl</option>';
		$tpl_folder_path = new Folder('./templates/models');
		foreach ($tpl_folder_path->get_files('`\.tpl$`i') as $tpl_articles)
		{
			$tpl_articles = $tpl_articles->get_name();
			if($tpl_articles != $tpl_default_name)
			$tpl_articles_list.= '<option value="' . $tpl_articles . '">' . $tpl_articles. '</option>';
		}		
		$tpl->assign_block_vars('edition_interface', array(
			'NAME' => '',
			'DESCRIPTION' => '',
			'TAB'=>  'checked ',
			'NO_TAB'=> '',
			'TPL_ARTICLES_LIST'=>$tpl_articles_list,
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
			'DEFAULT_MODEL'=>'',
			'NOT_DEFAULT_MODEL'=> 'checked="checked"',
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
	
	if (!empty($model_to_del_move))
	{
		$Session->csrf_get_protect();
		
		$move_default =(retrieve(POST,'action','affect_default') == 'affect_default') ? true : false;
		$id_models_move = retrieve(POST, 'models', 0,TINTEGER);
			
		if ($move_default)
		{					
			$model_default = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE model_default = 1", __LINE__, __FILE__);
			
			$model_default_extend_field=unserialize($model_default['extend_field']);
			
			//Affect default model to category  and recover comon extend field
			$result = $Sql->query_while("SELECT id,id_models
			FROM " . DB_TABLE_ARTICLES_CAT . " a
			WHERE id_models = ".$model_to_del_move
			, __LINE__, __FILE__);
	
			while ($row = $Sql->fetch_assoc($result))
			{
				$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES_CAT. " SET id_models = '".$model_default['id']."' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
				
			}

			//Affect default model to articles and recover comon extend field
			$result = $Sql->query_while("SELECT id,id_models,extend_field
			FROM " . DB_TABLE_ARTICLES . " a
			WHERE id_models = ".$model_to_del_move
			, __LINE__, __FILE__);

			while ($row = $Sql->fetch_assoc($result))
			{
				$article_extend_field=unserialize(stripslashes($row['extend_field']));
				$new_article_extend_field=array();
				if(!empty($article_extend_field))
				{

					foreach ($article_extend_field as $article_field)
					{	
						foreach ($model_default_extend_field as $model_field)
						{
							if($article_field['name'] == $model_field['name'])
							{
								$new_article_extend_field[$article_field['name']]['name']=$article_field['name'];
								$new_article_extend_field[$article_field['name']]['contents']=$article_field['contents'];
							}
						}
					}
				}
				$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES. " SET id_models = '".$model_default['id']."',extend_field = '".addslashes(serialize($new_article_extend_field))."' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			}			
		}
		else
		{
			$result = $Sql->query_while("SELECT id,id_models
			FROM " . DB_TABLE_ARTICLES_CAT . " a
			WHERE id_models = ".$model_to_del_move
			, __LINE__, __FILE__);

			while ($row = $Sql->fetch_assoc($result))
			{	
				$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES_CAT. " SET id_models = '".$id_models_move."' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			}
			
			$model_move = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE id = '".$id_models_move."'", __LINE__, __FILE__);
			
			$model_move_extend_field=unserialize(stripslashes($model_move['extend_field']));

			$result = $Sql->query_while("SELECT id,id_models,extend_field
			FROM " . DB_TABLE_ARTICLES . " a
			WHERE id_models = ".$model_to_del_move
			, __LINE__, __FILE__);

			while ($row = $Sql->fetch_assoc($result))
			{
				$article_extend_field=unserialize(stripslashes($row['extend_field']));
				$new_article_extend_field=array();
				if(!empty($article_extend_field))
				{
					foreach ($article_extend_field as $article_field)
					{	
						foreach ($model_move_extend_field as $model_field)
						{
							if($article_field['name'] == $model_field['name'])
							{
								$new_article_extend_field[$article_field['name']]['name']=$article_field['name'];
								$new_article_extend_field[$article_field['name']]['contents']=$article_field['contents'];
							}
						}
					}
				}				
				$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES. " SET id_models = '".$id_models_move."', extend_field = '".addslashes(serialize($new_article_extend_field))."' WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			}
		}
		
		$Sql->query_inject("DELETE FROM " . DB_TABLE_ARTICLES_MODEL . " WHERE id = '" . $model_to_del_move . "'", __LINE__, __FILE__);
		$error_string = 'e_success';
	}
	else
	{
		$id_model = retrieve(POST, 'id_model', 0,TINTEGER);
		$name = retrieve(POST, 'name', '');	
		$description = retrieve(POST, 'description', '', TSTRING_PARSE);
		$tpl_articles=retrieve(POST, 'tpl_articles', 'articles.tpl', TSTRING);
		$tpl_articles = (empty($tpl_articles)) ? 'articles.tpl' : $tpl_articles;
		$tpl_articles = $tpl_articles != 'articles.tpl' ? "./models/".$tpl_articles : $tpl_articles;

		$options = array (
			'note'=>retrieve(POST, 'note', true, TBOOL),
			'com'=>retrieve(POST, 'com', true, TBOOL),
			'impr'=>retrieve(POST, 'impr', true, TBOOL),
			'date'=>retrieve(POST, 'date', true, TBOOL),
			'author'=>retrieve(POST, 'author', true, TBOOL),
			'mail'=>retrieve(POST, 'mail', true, TBOOL),
			);	
		$pagination_tab=retrieve(POST, 'tab', 0);
		$default_model=retrieve(POST, 'default', 1);
			
		if (empty($name))
			redirect(url(HOST . SCRIPT . '?error=e_required_fields_empty#errorh'), '', '&');
		
		if($default_model == 1)
		{
			$exist_model_default = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE model_default = 1 AND id != '" . $id_model . "'", __LINE__, __FILE__);
			if(!empty($exist_model_default['id']))
				$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES_MODEL. " SET model_default = 0 WHERE id = '" . $exist_model_default['id'] . "'", __LINE__, __FILE__);
		}

		$extend_field = array();
		if(retrieve(POST,'extend_field_checkbox',false))
		{
			for ($i = 0;$i < 100; $i++)
			{	
				if (retrieve(POST,'a'.$i,false,TSTRING))
				{				
					$extend_field[$i]['name'] = retrieve(POST, 'a'.$i, '',TSTRING);
					$extend_field[$i]['type'] = retrieve(POST, 'v'.$i, '',TSTRING_UNCHANGE);
				}
			}
		}
				
		if ($id_model > 0)
		{
			$Sql->query_inject("UPDATE "  .DB_TABLE_ARTICLES_MODEL. " SET name = '".$name."', description = '" . $description . "',model_default = '".$default_model."',tpl_articles='".$tpl_articles."',extend_field='".addslashes(serialize($extend_field))."', options = '".serialize($options)."' , pagination_tab =  '".$pagination_tab."' WHERE id = '" . $id_model . "'", __LINE__, __FILE__);
			$error_string = 'e_success';
		}
		else
		{
			$Sql->query_inject("INSERT INTO " . DB_TABLE_ARTICLES_MODEL . " SET name = '".$name."', description = '" . $description . "',model_default = '".$default_model."',tpl_articles='".$tpl_articles."',extend_field='".addslashes(serialize($extend_field))."', options = '".serialize($options)."' , pagination_tab =  '".$pagination_tab."'", __LINE__, __FILE__);
			$error_string = 'e_success';
		}
	}
	
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
			case 'e_del_default_model' :
				$Errorh->handler($ARTICLES_LANG['model_default_del_explain'], E_USER_WARNING);
				break;
		}
	}
	
	$nbr_models = $Sql->count_table(PREFIX . 'articles_models' , __LINE__, __FILE__);

	//On crée une pagination si le nombre d'articles est trop important.
	
	$Pagination = new Pagination();
	$tpl->assign_block_vars('models_management', array(
			'L_MODELS_MANAGEMENT' => $ARTICLES_LANG['models_management'],
			'L_ARTICLES_TPL' => $ARTICLES_LANG['articles_tpl'],
			'L_MODELS_MANAGEMENT' => $ARTICLES_LANG['models_management'],
			'L_DESCRIPTION'=>$LANG['description'],
			'L_EXTEND_FIELD'=>$ARTICLES_LANG['extend_field'],
			'L_USE_TAB'=>$ARTICLES_LANG['tab_pagination'],
			'L_SPECIAL_OPTION' => $ARTICLES_LANG['special_option'],
			'L_AUTHOR'=>$ARTICLES_LANG['author'],
			'L_COM'=>$LANG['title_com'],
			'L_NOTE'=>$LANG['notes'],
			'L_PRINT'=>$ARTICLES_LANG['print'],
			'L_DATE'=>$LANG['date'],
			'L_LINK_MAIL'=>$ARTICLES_LANG['model_link_mail'],
			'L_MODEL_INFO'=>$ARTICLES_LANG['model_info'],
			'L_MODEL_INFO_DISPLAY'=>$ARTICLES_LANG['model_info_display'],
			'L_CONFIRM_DEL_MODEL'=>$ARTICLES_LANG['confirm_del_model'],
			'L_HIDE'=>$ARTICLES_LANG['hide'],
			'L_DISPLAY'=>$LANG['display'],
			'L_MODEL_DEFAULT_DEL_EXPLAIN'=>$ARTICLES_LANG['model_default_del_explain'],
			'PAGINATION' => $Pagination->display('admin_articles_models.php?p=%d', $nbr_models, 'p', 10, 3),
		));
		
		$result = $Sql->query_while("SELECT id, name,description ,model_default,extend_field,tpl_articles ,options,pagination_tab,model_default
	FROM " . DB_TABLE_ARTICLES_MODEL . " a
	ORDER BY model_default DESC".
	$Sql->limit($Pagination->get_first_msg(10, 'p'), 10), __LINE__, __FILE__);

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
			'ID_MODEL'=>$row['id'],
			'DESC' => second_parse($row['description']),
			'USE_TAB'=> $row['pagination_tab'] == 1 ? $LANG['yes'] :  $LANG['no'],
			'TPL_ARTICLES' => $row['tpl_articles'],
			'EXTEND_FIELD'=>!empty($extend_field) ? $extend_field : 'Aucun',
			'U_ADMIN_EDIT_MODEL' => url('admin_articles_models.php?edit=' . $row['id']),
			'U_ADMIN_DELETE_MODEL' => url('admin_articles_models.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
			'NOTE'=>$options['note'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'COM'=>$options['com'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'DATE'=>$options['date'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'AUTHOR'=>$options['author'] ? $LANG['display'] : $ARTICLES_LANG['hide'],
			'IMPR'=>$options['impr'] ? $ARTICLES_LANG['enable'] : $ARTICLES_LANG['desable'],
			'MAIL'=>$options['mail'] ? $LANG['display']: $ARTICLES_LANG['hide'],
			'L_DEFAULT_MODEL'=>$ARTICLES_LANG['default_model'],
			'C_DEFAULT'=>$row['model_default'] == 1 ? true : false,
		));
	}
}

$tpl->parse();
require_once('../admin/admin_footer.php');

?>