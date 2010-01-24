<?php
/*##################################################
 *                               xmlhttprequest.php
 *                            -------------------
 *   begin                : April 09, 2008
 *   copyright            : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
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

define('NO_SESSION_LOCATION', true); // Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
include_once('../articles/articles_begin.php');
require_once('../kernel/header_no_display.php');

$note=retrieve(GET,'note',0);
//Notation.
if (!empty($note) && $User->check_level(MEMBER_LEVEL)) //connected user
{
	$id = retrieve(POST, 'id', 0);
	$note = retrieve(POST, 'note', 0);

	// intialize management system class
	
	$Note = new Note('articles', $id, '', $CONFIG_ARTICLES['note_max'], '', NOTE_DISPLAY_NOTE);

	if (!empty($note) && !empty($id))
	echo $Note->add($note); //add a note
}
elseif (retrieve(GET,'img_preview',false)) // image preview
	echo FormatingHelper::second_parse_url(retrieve(GET, 'img_preview', '/articles/articles.png', TSTRING));
elseif (retrieve(POST,'preview',false))
{
	
	$level = array('', ' class="modo"', ' class="admin"');
	$preview = new Template('articles/articles.tpl');
	$Cache->load('articles');
	//loading module language
	load_module_lang('articles');
	
	$articles = array(
		'id' => retrieve(POST, 'id', 0, TINTEGER),
		'idcat' => retrieve(POST, 'idcat', 0, TINTEGER),
		'title' => utf8_decode(retrieve(POST, 'title', '', TSTRING)),
		'contents' => retrieve(POST, 'contents', '', TSTRING_PARSE),
		'user_id' => retrieve(POST, 'user_id', 0, TINTEGER),
		'date' => retrieve(POST, 'date', 0, TSTRING_UNCHANGE),
		'hour' => retrieve(POST, 'hour', 0, TINTEGER),
		'min' => retrieve(POST, 'min', 0, TINTEGER),
		'description' => retrieve(POST, 'description', '', TSTRING_PARSE),	);

	$user = $Sql->query_array(DB_TABLE_MEMBER, 'level', 'login', "WHERE user_id = '" . $articles['user_id'] . "'", __LINE__, __FILE__);

	if (!empty($articles['date']))
		$date = new Date(DATE_FROM_STRING, TIMEZONE_AUTO, $articles['date'], $LANG['date_format_short']);
	else
		$date = new Date(DATE_NOW, TIMEZONE_AUTO);

	if (!empty($articles['date']) && !empty($articles['hour']) && !empty($articles['min']))
		$date->set_hours($articles['hour']);
		
	$date->set_minutes($articles['min']);

	$preview->assign_vars(array(
		'C_DISPLAY_ARTICLE'=>true,
		'C_TAB'=>false,
		'ID' => $articles['id'],
		'IDCAT' => $articles['idcat'],
		'DESCRIPTION'=>$articles['description'],
		'NAME' => stripslashes($articles['title']),
		'CONTENTS' => FormatingHelper::second_parse(stripslashes($articles['contents'])),
		'PSEUDO' => !empty($user['login']) ? $user['login'] : $LANG['guest'],
		'DATE' =>   $date->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO),
		'U_USER_ID' => url('.php?id=' . $articles['user_id'], '-' . $articles['user_id'] . '.php'),
		'L_WRITTEN' =>  $ARTICLES_LANG['written_by'],
		'L_ON' => $LANG['on'],
	));

	echo $preview->parse(TEMPLATE_STRING_MODE);
}
elseif (retrieve(POST,'model_extend_field',false))
{
	$id_model = retrieve(POST, 'models', 1, TINTEGER);
	$id_art = retrieve(POST, 'id_art', 0, TINTEGER);	
	
	$tpl_model = new Template('articles/extend_field.tpl');

	$model = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE id = '" . $id_model . "'", __LINE__, __FILE__);
	
	if($id_art != 0)
	{
		$articles = $Sql->query_array(DB_TABLE_ARTICLES, '*', "WHERE id = '" . $id_art . "'", __LINE__, __FILE__);
		if(unserialize($model['extend_field']))
		{
			$extend_field_articles = unserialize($articles['extend_field']);
			$model_extend_tab=unserialize($model['extend_field']);
			$extend_field_tab = !empty($extend_field_articles) ? $extend_field_articles : $model_extend_tab;	
		}
		else
			$extend_field_tab='';
	}
	else
	{
		$model_extend_tab=unserialize($model['extend_field']);
		$extend_field_tab=unserialize($model['extend_field']);
	}

	if(!empty($extend_field_tab))
	{
		foreach ($model_extend_tab as $field)
		{	
			$tpl_model->assign_block_vars('extend_field', array(
				'NAME' => $field['name'],
				'CONTENTS'=>!empty($extend_field_articles[$field['name']]['contents']) ? $extend_field_articles[$field['name']]['contents']: '',
			));
		}	
	}

	$tpl_model->assign_vars(array(
		'C_EXTEND_FIELD'=>	!empty($extend_field_tab),
		'MODEL_DESCRIPTION'=>FormatingHelper::second_parse($model['description']),
		'L_MODELS_DESCRIPTION'=>$ARTICLES_LANG['model_desc'],
	));
	
	echo $tpl_model->parse(TEMPLATE_STRING_MODE);
}
elseif (retrieve(POST,'model_desc',false))
{
	$id_model = retrieve(POST, 'models', 1, TINTEGER);    
	
	$tpl_model = new Template('articles/extend_field.tpl');
	
	$model = $Sql->query_array(DB_TABLE_ARTICLES_MODEL, 'description', "WHERE id = '" . $id_model . "'", __LINE__, __FILE__);
	
	$tpl_model->assign_vars(array(
		'C_EXTEND_FIELD'=>	false,
		'MODEL_DESCRIPTION'=>FormatingHelper::second_parse($model['description']),
		'L_MODELS_DESCRIPTION'=>$ARTICLES_LANG['model_desc'],
	));
	
	echo $tpl_model->parse(TEMPLATE_STRING_MODE);
}
else
	echo -2;

?>