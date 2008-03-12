<?php
/*##################################################
*                               faq.php
*                            -------------------
*   begin                : November 10, 2007
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

$id_faq = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
//For users who have disabled javascript
$id_question = !empty($_GET['question']) ? numeric($_GET['question']) : 0;

if( $id_faq > 0 )
	$TITLE = array_key_exists($id_faq, $FAQ_CATS) ? $FAQ_CATS[$id_faq]['name'] : $FAQ_LANG['faq'];
else
	$TITLE = $FAQ_CONFIG['faq_name'];

define('TITLE', $FAQ_CONFIG['faq_name'] . ($id_faq > 0 ? ' - ' . $TITLE : ''));

$id_cat_for_speed_bar = $id_faq;

include_once('faq_speed_bar.php');

//checking authorization
if( !$auth_read )
{
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	exit;
}

include_once('../includes/header.php');

$Template->Set_filenames(array(
	'faq' => '../templates/' . $CONFIG['theme'] . '/faq/faq.tpl'
));
$Template->Assign_vars(array(
	'THEME' => $CONFIG['theme'],
	'MODULE_DATA_PATH' => $Template->Module_data_path('faq')
));

if( !empty($FAQ_CATS[$id_faq]['description']) )
	$Template->Assign_block_vars('description', array(
		'DESCRIPTION' => second_parse($FAQ_CATS[$id_faq]['description'])
	));

if( $auth_write )
	$Template->Assign_block_vars('management', array());

//let's check if there are some subcategories
$num_subcats = 0;
foreach( $FAQ_CATS as $id => $value )
{
	if( $id != 0 && $value['id_parent'] == $id_faq )
		$num_subcats ++;
}

//listing of subcategories
if( $num_subcats > 0 )
{	
	$Template->Assign_vars(array(
		'C_FAQ_CATS' => true
	));	
	
	$i = 1;
	foreach( $FAQ_CATS as $id => $value )
	{
		//List of children categories
		if( $id != 0 && $value['visible'] == 1 && $value['id_parent'] == $id_faq && (empty($value['auth']) || $Member->Check_auth($value['auth'], AUTH_READ)) )
		{
			if ( $i % $FAQ_CONFIG['num_cols'] == 1 )
				$Template->Assign_block_vars('row', array());
			$Template->Assign_block_vars('row.list_cats', array(
				'ID' => $id,
				'NAME' => $value['name'],
				'WIDTH' => floor(100 / (float)$FAQ_CONFIG['num_cols']),
				'SRC' => $value['image'],
				'IMG_NAME' => addslashes($value['name']),
				'NUM_QUESTIONS' => sprintf(((int)$value['num_questions'] > 1 ? $FAQ_LANG['num_questions_plural'] : $FAQ_LANG['num_questions_singular']), (int)$value['num_questions']),
				'U_CAT' => transid('faq.php?id=' . $id, 'faq-' . $id . '+' . url_encode_rewrite($value['name']) . '.php'),
				'U_ADMIN_CAT' => transid('admin_faq_cats.php?edit=' . $id)
			));
			
			if( !empty($value['image']) )
				$Template->Assign_vars(array(
					'C_CAT_IMG' => true
				));
				
			$i++;
		}
	}
}

//Displaying the questions that this cat contains
$result = $Sql->Query_while("SELECT id, question, q_order, answer
FROM ".PREFIX."faq
WHERE idcat = '" . $id_faq . "'
ORDER BY q_order",
__LINE__, __FILE__);

if( ($num_rows = $Sql->Sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."faq_cats WHERE idcat = '" . $id_faq . "'", __LINE__, __FILE__)) > 0 )
{
	//Display mode : if this category has a particular display mode we use it, else we use default display mode. If the category is the root we use default mode.
	$faq_display_block = $FAQ_CATS[$id_faq]['display_mode'] > 0 ? ($FAQ_CATS[$id_faq]['display_mode'] == 2 ? true : false ) : $FAQ_CONFIG['display_block'];
	if( $id_question > 0 )
		$faq_display_block = false;
	
	//Displaying administration tools
	$Template->Assign_vars(array(
		'C_ADMIN_TOOLS' => $auth_write
	));
	
	if( !$faq_display_block )
		$Template->Assign_block_vars('questions', array());
	else
		$Template->Assign_block_vars('questions_block', array());
		
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		if( !$faq_display_block )
		{
			$Template->Assign_block_vars('questions.faq', array(
				'ID_QUESTION' => $row['id'],
				'QUESTION' => $row['question'],
				'ANSWER' => second_parse($row['answer']),
				'U_QUESTION' => transid('faq.php?id=' . $id_faq . '&amp;question=' . $row['id'], 'faq-' . $id_faq . '+' . url_encode_rewrite($TITLE) . '.php?question=' . $row['id']) . '#q' . $row['id'],
				'U_DEL' => transid('action.php?del=' . $row['id']),
				'U_DOWN' => transid('action.php?down=' . $row['id']),
				'U_UP' => transid('action.php?up=' . $row['id']),
				'U_MOVE' => transid('management.php?move=' . $row['id']),
				'U_EDIT' => transid('management.php?edit=' . $row['id']),
				'C_HIDE_ANSWER' => $row['id'] != $id_question,
				'C_SHOW_ANSWER' => $row['id'] == $id_question
			));
			if( $row['q_order'] > 1 )
				$Template->Assign_block_vars('questions.faq.up', array());
			if( $row['q_order'] < $num_rows )
				$Template->Assign_block_vars('questions.faq.down', array());
		}
		else
		{
			$Template->Assign_block_vars('questions_block.header', array(
				'QUESTION' => $row['question'],
				'ID' => $row['id']
			));
			$Template->Assign_block_vars('questions_block.contents', array(
				'ANSWER' => $row['answer'],
				'QUESTION' => $row['question'],
				'ID' => $row['id'],
				'U_DEL' => transid('action.php?del=' . $row['id']),
				'U_DOWN' => transid('action.php?down=' . $row['id']),
				'U_UP' => transid('action.php?up=' . $row['id']),
				'U_EDIT' => transid('management.php?edit=' . $row['id']),
				'U_MOVE' => transid('management.php?move=' . $row['id']),
				'U_QUESTION' => transid('faq.php?id=' . $id_faq . '&amp;question=' . $row['id'], 'faq-' . $id_faq . '+' . url_encode_rewrite($TITLE) . '.php?question=' . $row['id']) . '#q' . $row['id']
			));
			if( $row['q_order'] > 1 )
				$Template->Assign_block_vars('questions_block.contents.up', array());
			if( $row['q_order'] < $num_rows )
				$Template->Assign_block_vars('questions_block.contents.down', array());
		}
	}
}
else
{
	$Template->Assign_block_vars('no_question', array());
}

$Template->Assign_vars(array(
	'L_NO_QUESTION_THIS_CATEGORY' => $FAQ_LANG['faq_no_question_here'],
	'L_CAT_MANAGEMENT' => $FAQ_LANG['category_manage'],
	'L_EDIT' => $FAQ_LANG['update'],
	'L_DELETE' => $FAQ_LANG['delete'],
	'L_UP' => $FAQ_LANG['up'],
	'L_DOWN' => $FAQ_LANG['down'],
	'L_MOVE' => $FAQ_LANG['move'],
	'L_CONFIRM_DELETE' => $FAQ_LANG['confirm_delete'],
	'L_QUESTION_URL' => 'URL de la question',
	'LANG' => $CONFIG['lang'],
	'THEME' => $CONFIG['theme'],
	'C_ADMIN' => $Member->Check_level(ADMIN_LEVEL),
	'U_MANAGEMENT' => transid('management.php?faq=' . $id_faq),
	'U_ADMIN_CAT' => $Member->Check_level(ADMIN_LEVEL) ? ( $id_faq > 0 ? transid('admin_faq_cats.php?edit=' . $id_faq) : transid('admin_faq_cats.php')) : ''
));

$Template->Pparse('faq');

include_once('../includes/footer.php'); 

?>