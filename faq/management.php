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

if( $edit_question > 0 )
{
	define('TITLE', $FAQ_LANG['question_edition']);
	$question_infos = $Sql->Query_array("faq", "*", "WHERE id = '" . $edit_question . "'", __LINE__, __FILE__);
	$id_cat_for_speed_bar = $question_infos['idcat'];
}
elseif( $cat_of_new_question >= 0 && $new )
{
	define('TITLE', $FAQ_LANG['question_creation']);
	$id_cat_for_speed_bar = $cat_of_new_question;
}
else
{
	define('TITLE', $FAQ_LANG['category_management']);
	$id_cat_for_speed_bar = $id_faq;
}
//Generation of speed_bar
include_once('faq_speed_bar.php');

if( $edit_question > 0 )
{
	//checking authorization
	if( !$auth_write )
	{
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php?faq=' . $question_infos['idcat'])); 
	$Speed_bar->Add_link($FAQ_LANG['question_edition'], transid('management.php?edit=' . $edit_question)); 
}
elseif( $cat_of_new_question >= 0 && $new )
{
	//checking authorization
	if( !$auth_write )
	{
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		exit;
	}
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php?faq=' . $cat_of_new_question)); 
	$Speed_bar->Add_link($FAQ_LANG['question_creation'], transid('management.php?new=1&amp;idcat' . $cat_of_new_question . '&amp;after=' . $new_after_id)); 
}
else
{
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php' . ($id_faq > 0 ? '?faq=' . $id_faq : ''))); 
	//checking authorization
	if( !$auth_write )
	{
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
		exit;
	}
}
	
include_once('../includes/header.php');

$Template->Set_filenames(array(
	'faq' => '../templates/' . $CONFIG['theme'] . '/faq/management.tpl'
));

if( $edit_question > 0 )
{
	$Template->Assign_block_vars('edit_question', array(
		'ENTITLED' => $question_infos['question'],
		'ANSWER' => unparse($question_infos['answer']),
		'TARGET' => transid('action.php'),
		'ID_QUESTION' => $edit_question
	));
	$Template->Assign_vars(array(
		'L_QUESTION' => $FAQ_LANG['question'],
		'L_ENTITLED' => $FAQ_LANG['entitled'],
		'L_ANSWER' => $FAQ_LANG['answer'],
		'L_REQUIRE_ENTITLED' => $FAQ_LANG['require_entitled'],
		'L_REQUIRE_ANSWER' => $FAQ_LANG['require_answer']
	));
	include_once('../includes/bbcode.php');
}
elseif( $cat_of_new_question >= 0 && $new )
{
	$Template->Assign_block_vars('edit_question', array(
		'ENTITLED' => '',
		'ANSWER' => '',
		'TARGET' => transid('action.php'),
		'ID_AFTER' => $new_after_id,
		'ID_CAT' => $cat_of_new_question
	));
	$Template->Assign_vars(array(
		'L_QUESTION' => $FAQ_LANG['question'],
		'L_ENTITLED' => $FAQ_LANG['entitled'],
		'L_ANSWER' => $FAQ_LANG['answer'],
		'L_REQUIRE_ENTITLED' => $FAQ_LANG['require_entitled'],
		'L_REQUIRE_ANSWER' => $FAQ_LANG['require_answer']
	));
	include_once('../includes/bbcode.php');
}
else
{
	//Création du tableau des groupes.
	$array_groups = $Group->Create_groups_array();
	
	$Template->Assign_vars(array(
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
		$Template->Assign_vars(array(
			'GLOBAL_CHECKED' => 'checked="checked"',
			'DISPLAY_GLOBAL' => 'block',
			'JS_GLOBAL' => 'true'
		));
	}
	else
	{
		$Template->Assign_vars(array(
			'GLOBAL_CHECKED' => '',
			'DISPLAY_GLOBAL' => 'none',
			'JS_GLOBAL' => 'false'
		));
	}

	//Category properties
	$Template->Assign_block_vars('category', array(
		'READ_AUTH' => 	$Group->Generate_select_groups(1, !empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth'], AUTH_READ),
		'WRITE_AUTH' => $Group->Generate_select_groups(2, !empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth'], AUTH_WRITE),
		'NBR_GROUP' => count($array_groups),
		'U_CREATE_BEFORE' => transid('management.php?new=1&amp;idcat=' . $id_faq . '&amp;after=0'),
		'ID_FAQ' => $id_faq
	));
	
	if( $id_faq > 0 )
	{
		$Template->Assign_block_vars('category.not_root_name', array(
			'CAT_TITLE' => $FAQ_CATS[$id_faq]['name'],
		));
		$Template->Assign_block_vars('category.not_root_auth', array('WRITE_AUTH' => $Group->Generate_select_groups(2, !empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth'], AUTH_WRITE)));
	}
	
	//Questions management
	$result = $Sql->Query_while("SELECT id, q_order, question, answer
	FROM ".PREFIX."faq
	WHERE idcat = '" . $id_faq . "' 
	ORDER BY q_order",
	__LINE__, __FILE__);
	
	if( $FAQ_CATS[$id_faq]['num_questions'] > 0 || $id_faq == 0 )
	{
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('category.questions', array(
				'QUESTION' => $row['question'],
				'ID' => $row['q_order'],
				'U_DEL' => 'action.php?del=' . $row['id'],
				'U_DOWN' => 'action.php?down=' . $row['id'],
				'U_UP' => 'action.php?up=' . $row['id'],
				'U_EDIT' => 'management.php?edit=' . $row['id'],
				'U_CREATE_AFTER' => 'management.php?new=1&amp;idcat=' . $id_faq . '&after=' . $row['q_order'],
			));
			if( $row['q_order'] > 1 )
				$Template->Assign_block_vars('category.questions.up', array());
			if( $row['q_order'] < $FAQ_CATS[$id_faq]['num_questions'] )
				$Template->Assign_block_vars('category.questions.down', array());
		}
	}
	include_once('../includes/bbcode.php');
}

$Template->Assign_vars(array(
	'THEME' => $CONFIG['theme'],
	'MODULE_DATA_PATH' => $Template->Module_data_path('faq'),
	'L_UP' => $FAQ_LANG['up'],
	'L_SUBMIT' => $LANG['submit'],
	'L_UPDATE' => $LANG['update'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset'],
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme']
));


$Template->Pparse('faq');

include_once('../includes/footer.php'); 

?>
