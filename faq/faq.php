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
	$errorh->error_handler('e_auth', E_USER_REDIRECT);
	exit;
}

include_once('../includes/header.php');

$template->set_filenames(array(
	'faq' => '../templates/' . $CONFIG['theme'] . '/faq/faq.tpl'
));
$template->assign_vars(array(
	'THEME' => $CONFIG['theme'],
	'MODULE_DATA_PATH' => $template->module_data_path('faq')
));


//generation of unauthorized cats in the corresponding level
$unauthorized_cats = array();
foreach( $FAQ_CATS as $id => $value )
{
	if( $id > 0 && $value['level'] == ($id_faq > 0 ? $FAQ_CATS[$id_faq]['level'] + 1 : 0) && !empty($value['auth']) && !$groups->check_auth($value['auth'], AUTH_READ) )
		$unauthorized_cats[] = $id;
}

//List of FAQs cats
if( array_key_exists($id_faq, $FAQ_CATS) && $id_faq > 0 )
{
	$where_condition = "WHERE level = '" . ($FAQ_CATS[$id_faq]['level'] + 1) . "' AND id_left BETWEEN '" . $FAQ_CATS[$id_faq]['id_left'] . "' AND '" . $FAQ_CATS[$id_faq]['id_right'] . "'";
	if( !empty($FAQ_CATS[$id_faq]['desc']) )
		$template->assign_block_vars('description', array(
			'DESCRIPTION' => second_parse($FAQ_CATS[$id_faq]['desc'])
		));
}
else
{
	$id_faq = 0;
	$where_condition = "WHERE level = '0'";
}
if( count($unauthorized_cats) > 0 )
	$where_condition .= ' AND id NOT IN (' . implode(', ', $unauthorized_cats) . ') ';

if( $auth_write )
	$template->assign_block_vars('management', array());
	
$result = $sql->query_while("SELECT id, name, image FROM ".PREFIX."faq_cats " . $where_condition . " AND visible = '1' ORDER BY id_left", __LINE__, __FILE__);

//if at least one subcat exists
if( $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."faq_cats " . $where_condition . " AND visible = '1' ORDER BY id_left", __LINE__, __FILE__) > 0 )
{
	$template->assign_block_vars('cats', array());
	$i = 1;
	while ($row = $sql->sql_fetch_assoc($result))
	{	
		if ( $i % $FAQ_CONFIG['num_cols'] == 1 )
			$template->assign_block_vars('cats.row', array());
		$template->assign_block_vars('cats.row.col', array(
			'ID' => $row['id'],
			'NAME' => $row['name'],
			'U_CAT' => transid('faq.php?id=' . $row['id'], 'faq-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php'),
			'WIDTH' => floor(100 / (double)$FAQ_CONFIG['num_cols'])
		));
		if( !empty($row['image']) )
			$template->assign_block_vars('cats.row.col.image', array(
				'SRC' => $row['image'],
				'NAME' => addslashes($row['name'])
			));
		$i++;
	}
}

//Displaying the contents of a cat
$result = $sql->query_while("SELECT id, question, answer
FROM ".PREFIX."faq
WHERE idcat = '" . $id_faq . "'
ORDER BY q_order",
__LINE__, __FILE__);

if( $sql->sql_num_rows($result, "SELECT COUNT(*) FROM ".PREFIX."faq_cats WHERE idcat = '" . $id_faq . "'", __LINE__, __FILE__) > 0 )
{
	//Display mode : if this category has a particular display mode we use it, else we use default display mode. If the category is the root we use default mode.
	$faq_display_block = $FAQ_CATS[$id_faq]['display_mode'] > 0 ? ($FAQ_CATS[$id_faq]['display_mode'] == 2 ? true : false ) : $FAQ_CONFIG['display_block'];

	if( !$faq_display_block )
		$template->assign_block_vars('questions', array());
	else
		$template->assign_block_vars('questions_block', array());
		
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		if( !$faq_display_block )
			$template->assign_block_vars('questions.faq', array(
				'ID_QUESTION' => $row['id'],
				'QUESTION' => $row['question'],
				'DISPLAY_ANSWER' => $row['id'] != $id_question ? 'display:none' : 'display:block', //If user has disabled javascript $id_question corresponds to the answer he wants to read
				'ANSWER' => $row['answer'],
				'U_QUESTION' => transid('faq.php?id=' . $id_faq . '&amp;question=' . $row['id'], 'faq-' . $id_faq . '+' . url_encode_rewrite($TITLE) . '.php?question=' . $row['id'])
			));
		else
		{
			$template->assign_block_vars('questions_block.header', array(
				'QUESTION' => $row['question'],
				'ID' => $row['id']
			));
			$template->assign_block_vars('questions_block.contents', array(
				'ANSWER' => $row['answer'],
				'QUESTION' => $row['question'],
				'ID' => $row['id']
			));
		}
	}
}
else
{
	$template->assign_block_vars('no_question', array());
}

$template->assign_vars(array(
	'L_NO_QUESTION_THIS_CATEGORY' => $FAQ_LANG['faq_no_question_here'],
	'L_CAT_MANAGEMENT' => $FAQ_LANG['category_manage'],
	'U_MANAGEMENT' => transid('management.php?faq=' . $id_faq)
));

$template->pparse('faq');

include_once('../includes/footer.php'); 

?>