<?php
/*##################################################
*                               management.php
*                            -------------------
*   begin                : December 1, 2007
*   copyright          : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
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
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
###################################################*/

include_once('../includes/begin.php'); 

include_once('faq_begin.php');

$id_faq = !empty($_GET['faq']) ? numeric($_GET['faq']) : 0;
$edit_question = !empty($_GET['edit']) ? numeric($_GET['edit']) : 0;
$cat_of_new_question = !empty($_GET['idcat']) ? numeric($_GET['idcat']) : 0;
$new = !empty($_GET['new']) ? true : false;
$new_after_id = !empty($_GET['after']) ? numeric($_GET['after']) : 0;

$cache->load_file('faq');

if( $edit_question > 0 )
{
	define('TITLE', $FAQ_LANG['question_edition']);
	$question_infos = $sql->query_array("faq", "*", "WHERE id = '" . $edit_question . "'", __LINE__, __FILE__);
	$id_cat4speed_bar = $question_infos['idcat'];
}
elseif( $cat_of_new_question >= 0 && $new )
{
	define('TITLE', $FAQ_LANG['question_creation']);
	$id_cat4speed_bar = $cat_of_new_question;
}
else
{
	define('TITLE', $FAQ_LANG['category_management']);
	$id_cat4speed_bar = $id_faq;
}

//Speed bar generation
$speed_bar = array($FAQ_CONFIG['faq_name'] => transid('faq.php'));
if( $id_cat4speed_bar > 0 )
{
	foreach($FAQ_CATS as $id => $array_info_cat)
	{
		if( $id > 0 && $FAQ_CATS[$id_cat4speed_bar]['id_left'] >= $array_info_cat['id_left'] && $FAQ_CATS[$id_cat4speed_bar]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $FAQ_CATS[$id_cat4speed_bar]['level'] )
		{
			$speed_bar[$array_info_cat['name']] = transid('faq.php?id=' . $id);
		}
	}
}

if( $edit_question > 0 )
{
	$speed_bar[$FAQ_LANG['category_management']] = transid('management.php?faq=' . $question_infos['idcat']);
	$speed_bar[$FAQ_LANG['question_edition']] = transid('management.php?edit=' . $edit_question);
}
elseif( $cat_of_new_question >= 0 && $new )
{
	$speed_bar[$FAQ_LANG['category_management']] = transid('management.php?faq=' . $cat_of_new_question);
	$speed_bar[$FAQ_LANG['question_creation']] = transid('management.php?new=1&amp;idcat' . $cat_of_new_question . '&amp;after=' . $new_after_id);
}
else
	$speed_bar[$FAQ_LANG['category_management']] = transid('management.php' . ($id_faq > 0 ? '?faq=' . $id_faq : ''));
	
include_once('../includes/header.php');

$template->set_filenames(array(
	'faq' => '../templates/' . $CONFIG['theme'] . '/faq/management.tpl'
));

if( $edit_question > 0 )
{
	$template->assign_block_vars('edit_question', array(
		'ENTITLED' => $question_infos['question'],
		'ANSWER' => unparse($question_infos['answer']),
		'TARGET' => transid('action.php'),
		'ID_QUESTION' => $edit_question
	));
	$template->assign_vars(array(
		'L_QUESTION' => $FAQ_LANG['question'],
		'L_ENTITLED' => $FAQ_LANG['entitled'],
		'L_ANSWER' => $FAQ_LANG['answer'],
		'L_REQUIRE_ENTITLED' => $FAQ_LANG['require_entitled'],
		'L_REQUIRE_ANSWER' => $FAQ_LANG['require_answer']
	));
	include_once('../includes/bbcode.php');

	$template->assign_var_from_handle('BBCODE', 'bbcode');
}
elseif( $cat_of_new_question >= 0 && $new )
{
	$template->assign_block_vars('edit_question', array(
		'ENTITLED' => '',
		'ANSWER' => '',
		'TARGET' => transid('action.php'),
		'ID_AFTER' => $new_after_id,
		'ID_CAT' => $cat_of_new_question
	));
	$template->assign_vars(array(
		'L_QUESTION' => $FAQ_LANG['question'],
		'L_ENTITLED' => $FAQ_LANG['entitled'],
		'L_ANSWER' => $FAQ_LANG['answer'],
		'L_REQUIRE_ENTITLED' => $FAQ_LANG['require_entitled'],
		'L_REQUIRE_ANSWER' => $FAQ_LANG['require_answer']
	));
	include_once('../includes/bbcode.php');

	$template->assign_var_from_handle('BBCODE', 'bbcode');
}
else
{
	//Création du tableau des groupes.
	$array_groups = array();
	foreach($_array_groups_auth as $idgroup => $array_group_info)
		$array_groups[$idgroup] = $array_group_info[0];
		
	//Création du tableau des rangs.
	$array_ranks = array(-1 => $LANG['guest'], 0 => $LANG['member'], 1 => $LANG['modo'], 2 => $LANG['admin']);

	//Génération d'une liste à sélection multiple des rangs et groupes
	function generate_select_groups($array_auth, $auth_id, $auth_level)
	{
		global $array_groups, $array_ranks, $LANG, $FAQ_LANG;

		$j = 0;
		//Liste des rangs
		$select_groups = '<select id="groups_auth' . $auth_id . '" name="groups_auth' . $auth_id . '[]" size="8" multiple="multiple" onclick="document.getElementById(\'' . $auth_id . 'r3\').selected = true;"><optgroup label="' . $FAQ_LANG['ranks'] . '">';
		foreach($array_ranks as $idgroup => $group_name)
		{
			$selected = '';	
			if( isset($array_auth['r' . $idgroup]) && ((int)$array_auth['r' . $idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';
							
			$select_groups .=  '<option value="r' . $idgroup . '" id="' . $auth_id . 'r' . $j . '" ' . $selected . ' onclick="check_select_multiple_ranks(\'' . $auth_id . 'r\', ' . $j . ')">' . $group_name . '</option>';
			$j++;
		}
		$select_groups .=  '</optgroup>';
		
		//Liste des groupes.
		$j = 0;
		$select_groups .= '<optgroup label="' . $LANG['groups'] . '">';
		foreach($array_groups as $idgroup => $group_name)
		{
			$selected = '';		
			if( isset($array_auth[$idgroup]) && ((int)$array_auth[$idgroup] & (int)$auth_level) !== 0 )
				$selected = 'selected="selected"';

			$select_groups .= '<option value="' . $idgroup . '" id="' . $auth_id . 'g' . $j . '" ' . $selected . '>' . $group_name . '</option>';
			$j++;
		}
		$select_groups .= '</optgroup></select>';
		
		return $select_groups;
	}
	$template->assign_vars(array(
		'L_CAT_PROPERTIES' => $FAQ_LANG['cat_properties'],
		'L_DESCRIPTION' => $FAQ_LANG['cat_description'],
		'L_DISPLAY_MODE' => $FAQ_LANG['display_mode'],
		'L_DISPLAY_BLOCK' => $FAQ_LANG['display_block'],
		'L_DISPLAY_INLINE' => $FAQ_LANG['display_inline'],
		'L_DISPLAY_AUTO' => $FAQ_LANG['display_auto'],
		'L_DISPLAY_EXPLAIN' => $FAQ_LANG['display_explain'],
		'L_GLOBAL_AUTH' => $FAQ_LANG['global_auth'],
		'L_GLOBAL_AUTH_EXPLAIN' => $FAQ_LANG['global_auth_explain'],
		'L_READ_AUTH' => $FAQ_LANG['read_auth'],
		'L_WRITE_AUTH' => $FAQ_LANG['write_auth'],
		'L_QUESTIONS_LIST' => $FAQ_LANG['questions_list'],
		'L_INSERT_QUESTION' => $FAQ_LANG['insert_question'],
		'L_INSERT_QUESTION_BEFORE' => $FAQ_LANG['insert_question_begening'],
		'L_EDIT' => $FAQ_LANG['update'],
		'L_DELETE' => $FAQ_LANG['delete'],
		'L_DOWN' => $FAQ_LANG['down'],
		'L_CONFIRM_DELETE' => addslashes($FAQ_LANG['confirm_delete']),
		'L_GO_BACK_TO_CAT' => $FAQ_LANG['go_back_to_cat'],
		'L_PREVIEW' => $LANG['preview'],
		'L_CAT_NAME' => $FAQ_LANG['cat_name'],
		'L_REQUIRE_CAT_NAME' => $FAQ_LANG['require_cat_name'],
		'U_GO_BACK_TO_CAT' => transid('faq.php' . ($id_faq > 0 ? '?id=' . $id_faq : ''), $id_faq > 0 ? 'faq-' . $id_faq . '+' . url_encode_rewrite($FAQ_CATS[$id_faq]['name']) . '.php' : 'faq.php'),
		'TARGET' => transid('action.php?idcat=' . $id_faq . '&amp;cat_properties=1'),
		'AUTO_SELECTED' => $FAQ_CATS[$id_faq]['display_mode'] == 0 ? 'selected="selected"' : '',
		'INLINE_SELECTED' => $FAQ_CATS[$id_faq]['display_mode'] == 1 ? 'selected="selected"' : '',
		'BLOCK_SELECTED' => $FAQ_CATS[$id_faq]['display_mode'] == 2 ? 'selected="selected"' : '',
		'DESCRIPTION' => unparse($FAQ_CATS[$id_faq]['description'])
	));
	
	//Special authorization
	if( !empty($FAQ_CATS[$id_faq]['auth']) )
	{
		$template->assign_vars(array(
			'GLOBAL_CHECKED' => 'checked="checked"',
			'DISPLAY_GLOBAL' => 'block',
			'JS_GLOBAL' => 'true'
		));
	}
	else
	{
		$template->assign_vars(array(
			'GLOBAL_CHECKED' => '',
			'DISPLAY_GLOBAL' => 'none',
			'JS_GLOBAL' => 'false'
		));
	}
	
	//Category properties
	$template->assign_block_vars('category', array(
		'READ_AUTH' => generate_select_groups(!empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth'], 1, AUTH_READ),
		'WRITE_AUTH' => generate_select_groups(!empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth'], 2, AUTH_WRITE),
		'NBR_GROUP' => count($array_groups),
		'U_CREATE_BEFORE' => transid('management.php?new=1&amp;idcat=' . $id_faq . '&amp;after=0'),
		'ID_FAQ' => $id_faq
	));
	
	if( $id_faq > 0 )
		$template->assign_block_vars('category.not_root', array(
			'CAT_TITLE' => $FAQ_CATS[$id_faq]['name'],
		));
	
	//Questions management
	$result = $sql->query_while("SELECT id, q_order, question, answer
	FROM ".PREFIX."faq
	WHERE idcat = '" . $id_faq . "' 
	ORDER BY q_order",
	__LINE__, __FILE__);
	
	if( $FAQ_CATS[$id_faq]['num_questions'] > 0 || $id_faq == 0 )
	{
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			$template->assign_block_vars('category.questions', array(
				'QUESTION' => $row['question'],
				'ID' => $row['q_order'],
				'U_DEL' => 'action.php?del=' . $row['id'],
				'U_DOWN' => 'action.php?down=' . $row['id'],
				'U_UP' => 'action.php?up=' . $row['id'],
				'U_EDIT' => 'management.php?edit=' . $row['id'],
				'U_CREATE_AFTER' => 'management.php?new=1&amp;idcat=' . $id_faq . '&after=' . $row['q_order'],
			));
			if( $row['q_order'] > 1 )
				$template->assign_block_vars('category.questions.up', array());
			if( $row['q_order'] < $FAQ_CATS[$id_faq]['num_questions'] )
				$template->assign_block_vars('category.questions.down', array());
		}
	}
	include_once('../includes/bbcode.php');

	$template->assign_var_from_handle('BBCODE', 'bbcode');
}

$template->assign_vars(array(
	'THEME' => $CONFIG['theme'],
	'MODULE_DATA_PATH' => $template->module_data_path('faq'),
	'L_UP' => $FAQ_LANG['up'],
	'L_SUBMIT' => $LANG['submit'],
	'L_UPDATE' => $LANG['update'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset']
));


$template->pparse('faq');

include_once('../includes/footer.php'); 

?>