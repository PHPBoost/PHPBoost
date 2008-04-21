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
$id_move = !empty($_GET['move']) ? numeric($_GET['move']) : 0;

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
elseif( $id_move > 0 )
{
	define('TITLE', $FAQ_LANG['moving_a_question']);
	$question_infos = $Sql->Query_array("faq", "*", "WHERE id = '" . $id_move . "'", __LINE__, __FILE__);
	$id_cat_for_speed_bar = $question_infos['idcat'];
}
else
{
	define('TITLE', $FAQ_LANG['category_management']);
	$id_cat_for_speed_bar = $id_faq;
}
//Generation of speed_bar
include_once('faq_speed_bar.php');

//checking authorization
if( !$auth_write )
{
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	exit;
}

if( $edit_question > 0 )
{
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php?faq=' . $question_infos['idcat'])); 
	$Speed_bar->Add_link($FAQ_LANG['question_edition'], transid('management.php?edit=' . $edit_question)); 
}
elseif( $cat_of_new_question >= 0 && $new )
{
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php?faq=' . $cat_of_new_question)); 
	$Speed_bar->Add_link($FAQ_LANG['question_creation'], transid('management.php?new=1&amp;idcat=' . $cat_of_new_question . '&amp;after=' . $new_after_id)); 
}
//Moving interface
elseif( $id_move > 0 )
{
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php?faq=' . $cat_of_new_question)); 
	$Speed_bar->Add_link($FAQ_LANG['moving_a_question'], transid('management.php?move=' . $id_move)); 
}
else
{
	$Speed_bar->Add_link($FAQ_LANG['category_management'], transid('management.php' . ($id_faq > 0 ? '?faq=' . $id_faq : ''))); 
}
	
include_once('../includes/header.php');

$Template->Set_filenames(array(
	'faq'=> 'faq/management.tpl'
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
	include_once('../includes/framework/content/bbcode.php');
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
	include_once('../includes/framework/content/bbcode.php');
}
elseif( $id_move > 0 )
{
	include_once('faq_cats.class.php');
	$faq_cats = new Faqcats();
	
	$Template->Assign_block_vars('move_question', array(
		'CATEGORIES_TREE' => $faq_cats->Build_select_form(0, 'target', 'target', 0, AUTH_WRITE, $FAQ_CONFIG['global_auth']),
		'ID_QUESTION' => $id_move
	));
	$Template->Assign_vars(array(
		'L_TARGET' => $FAQ_LANG['target_category'],
		'L_MOVE' => $FAQ_LANG['move'],
		'ID_QUESTION' => $id_move,
		'U_FORM_TARGET' => transid('action.php')
	));
}
else
{
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
		'L_UP' => $FAQ_LANG['up'],
		'L_DOWN' => $FAQ_LANG['down'],
		'L_MOVE' => $FAQ_LANG['move'],
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
		'READ_AUTH' => 	$Group->Generate_select_auth(AUTH_READ, !empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth']),
		'WRITE_AUTH' => $Group->Generate_select_auth(AUTH_WRITE, !empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth']),
		'U_CREATE_BEFORE' => transid('management.php?new=1&amp;idcat=' . $id_faq . '&amp;after=0'),
		'ID_FAQ' => $id_faq
	));
	
	if( $id_faq > 0 )
	{
		$Template->Assign_block_vars('category.not_root_name', array(
			'CAT_TITLE' => $FAQ_CATS[$id_faq]['name'],
		));
		$Template->Assign_block_vars('category.not_root_auth', array(
			'WRITE_AUTH' => $Group->Generate_select_auth(AUTH_WRITE, !empty($FAQ_CATS[$id_faq]['auth']) ? $FAQ_CATS[$id_faq]['auth'] : $FAQ_CONFIG['global_auth'])
		));
	}
	
	//Questions management
	$result = $Sql->Query_while("SELECT id, q_order, question, answer
	FROM ".PREFIX."faq
	WHERE idcat = '" . $id_faq . "' 
	ORDER BY q_order",
	__LINE__, __FILE__);
	
	$num_rows = $Sql->Sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."faq WHERE idcat = '" . $id_faq . "'", __LINE__, __FILE__);
	
	if( $num_rows > 0 || $id_faq == 0 )
	{
		$Template->Assign_vars(array(
			'C_DISPLAY_ANSWERS' => true,
			'NUM_QUESTIONS' => $num_rows,
			'L_HIDE_ANSWERS' => addslashes($FAQ_LANG['hide_all_answers']),
			'L_DISPLAY_ANSWERS' => addslashes($FAQ_LANG['show_all_answers'])
		));
		
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$Template->Assign_block_vars('category.questions', array(
				'QUESTION' => $row['question'],
				'ID' => $row['q_order'],
				'U_DEL' => transid('action.php?del=' . $row['id']),
				'U_DOWN' => transid('action.php?down=' . $row['id']),
				'U_UP' => transid('action.php?up=' . $row['id']),
				'U_EDIT' => transid('management.php?edit=' . $row['id']),
				'U_MOVE' => transid('management.php?move=' . $row['id']),
				'U_CREATE_AFTER' => transid('management.php?new=1&amp;idcat=' . $id_faq . '&after=' . $row['q_order']),
				'ANSWER' => $row['answer']
			));
			if( $row['q_order'] > 1 )
				$Template->Assign_block_vars('category.questions.up', array());
			if( $row['q_order'] < $num_rows )
				$Template->Assign_block_vars('category.questions.down', array());
		}
	}
	include_once('../includes/framework/content/bbcode.php');
}

$Template->Assign_vars(array(
	'THEME' => $CONFIG['theme'],
	'MODULE_DATA_PATH' => $Template->Module_data_path('faq'),
	'L_SUBMIT' => $edit_question > 0 ? $LANG['update'] : $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset'],
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme']
));


$Template->Pparse('faq');

include_once('../includes/footer.php'); 

?>
