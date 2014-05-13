<?php
/*##################################################
*                               management.php
*                            -------------------
*   begin                : December 1, 2007
*   copyright            : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
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

include_once('../kernel/begin.php');
include_once('faq_begin.php');

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$faq_categories = new FaqCats();

$edit_question = retrieve(GET, 'edit', 0);
$new = retrieve(GET, 'new', false);
$id_move = retrieve(GET, 'move', 0);
$parent_cat_id = retrieve(GET, 'parent_cat_id', 0);

if ($id_move > 0)
{
	define('TITLE', $FAQ_LANG['moving_a_question']);
	$question_infos = $Sql->query_array(PREFIX . "faq", "*", "WHERE id = '" . $id_move . "'", __LINE__, __FILE__);
	$id_cat_for_bread_crumb = $question_infos['idcat'];
}
elseif ($edit_question > 0)
{
	define('TITLE', $FAQ_LANG['question_edition']);
	$question_infos = $Sql->query_array(PREFIX . "faq", "*", "WHERE id = '" . $edit_question . "'", __LINE__, __FILE__);
	$id_cat_for_bread_crumb = $question_infos['idcat'];
}
else
{
	define('TITLE', $FAQ_LANG['question_creation']);
	$id_cat_for_bread_crumb = 0;
}

//Generation of bread_crumb
include_once('faq_bread_crumb.php');

//checking authorization
if (!$auth_write)
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

//Moving interface
if ($id_move > 0)
{
	$Bread_crumb->add($FAQ_LANG['moving_a_question'], url('management.php?move=' . $id_move));
}
elseif ($edit_question > 0)
{
	$Bread_crumb->add($FAQ_LANG['question_edition'], url('management.php?edit=' . $edit_question));
}
else
{
	$Bread_crumb->add($FAQ_LANG['question_creation'], url('management.php?new=1'));
}

include_once('../kernel/header.php');

$Template->set_filenames(array(
	'faq'=> 'faq/management.tpl'
));

$editor = AppContext::get_content_formatting_service()->get_default_editor();
$editor->set_identifier('contents');

if ($id_move > 0)
{
	$Template->assign_block_vars('move_question', array(
		'CATEGORIES_TREE' => $faq_categories->build_select_form($question_infos['idcat'], 'target', 'target', 0, FaqAuthorizationsService::WRITE_AUTHORIZATIONS, $faq_config->get_authorizations(), IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
		'ID_QUESTION' => $id_move
	));
}
elseif ($edit_question > 0)
{
	$Template->assign_block_vars('edit_question', array(
		'CATEGORIES_TREE' => $faq_categories->build_select_form($question_infos['idcat'], 'id_cat', 'id_cat', 0, FaqAuthorizationsService::WRITE_AUTHORIZATIONS, $faq_config->get_authorizations(), IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
		'ENTITLED' => $question_infos['question'],
		'ANSWER' => FormatingHelper::unparse($question_infos['answer']),
		'ID_QUESTION' => $edit_question
	));
}
else
{
	$Template->assign_block_vars('edit_question', array(
		'CATEGORIES_TREE' => $faq_categories->build_select_form($parent_cat_id, 'id_cat', 'id_cat', 0, FaqAuthorizationsService::WRITE_AUTHORIZATIONS, $faq_config->get_authorizations(), IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH),
		'ENTITLED' => '',
		'ANSWER' => ''
	));
}

$Template->put_all(array(
	'KERNEL_EDITOR' => $editor->display(),
	'L_TARGET' => $FAQ_LANG['target_category'],
	'L_MOVE' => $FAQ_LANG['move'],
	'L_CATEGORY' => $FAQ_LANG['category'],
	'L_REQUIRED_FIELDS' => $FAQ_LANG['required_fields'],
	'L_QUESTION' => $FAQ_LANG['question'],
	'L_ENTITLED' => $FAQ_LANG['entitled'],
	'L_ANSWER' => $FAQ_LANG['answer'],
	'L_REQUIRE_ENTITLED' => $FAQ_LANG['require_entitled'],
	'L_REQUIRE_ANSWER' => $FAQ_LANG['require_answer'],
	'L_SUBMIT' => $edit_question > 0 ? $LANG['update'] : $LANG['submit'],
	'L_PREVIEW' => $LANG['preview'],
	'L_RESET' => $LANG['reset'],
	'TITLE' => TITLE
));


$Template->pparse('faq');

include_once('../kernel/footer.php');

?>
