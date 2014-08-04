<?php
/*##################################################
 *                               admin_faq.php
 *                            -------------------
 *   begin                : February 27, 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
include_once('faq_begin.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$page = retrieve(GET, 'p', 0);

if (retrieve(POST, 'submit', false))
{
	$faq_config->set_number_columns(retrieve(POST, 'num_cols', 3));
	$faq_config->set_display_mode((!empty($_POST['display_mode']) && $_POST['display_mode'] == 'inline') ? 'inline' : 'block');
	$faq_config->set_root_cat_description(stripslashes(retrieve(POST, 'root_contents', '', TSTRING_PARSE)));
	$faq_config->set_authorizations(Authorizations::build_auth_array_from_form(FaqAuthorizationsService::READ_AUTHORIZATIONS, FaqAuthorizationsService::WRITE_AUTHORIZATIONS));
	
	FaqConfig::save();
	
	//Régénération du cache
	$Cache->Generate_module_file('faq');
	
	AppContext::get_response()->redirect(url('admin_faq.php', '', '&'));
}

//Questions list
if ($page > 0)
{
	$_NBR_QUESTIONS_PER_PAGE = 25;
	
	$Template->set_filenames(array(
		'admin_faq_questions'=> 'faq/admin_faq_questions.tpl'
	));
	
	$nbr_questions = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "faq");
	
	//On instancie la classe de pagination
	$pagination = new ModulePagination($page, $nbr_questions, $_NBR_QUESTIONS_PER_PAGE);
	$pagination->set_url(new Url('/faq/admin_faq.php?p=%d'));
	
	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
	
	$result = $Sql->query_while("SELECT q.id, q.question, q.timestamp, q.idcat, c.name
	FROM " . PREFIX . "faq q
	LEFT JOIN " . PREFIX . "faq_cats c ON c.id = q.idcat
	ORDER BY q.timestamp DESC
	" . $Sql->limit($pagination->get_display_from(), $_NBR_QUESTIONS_PER_PAGE));
	
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('question', array(
			'QUESTION' => $row['question'],
			'CATEGORY' => !empty($row['idcat']) ? $row['name'] : $LANG['root'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'U_DEL' => url('action.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
			'U_EDIT' => url('management.php?edit=' . $row['id']),
			'U_QUESTION' => url('faq.php?id=' . $row['idcat'] . '&amp;question=' . $row['id'], 'faq-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php?question=' . $row['id']) . '#q' . $row['id'],
			'U_CATEGORY' => !empty($row['idcat']) ? url('faq.php?id=' . $row['idcat'], 'faq-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php') : url('faq.php')
		));
	}
	
	$Template->put_all(array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION' => $pagination->display(),
		'L_QUESTION' => $FAQ_LANG['question'],
		'L_CATEGORY' => $FAQ_LANG['category'],
		'L_DATE' => LangLoader::get_message('date', 'date-common'),
		'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
		'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
		'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
		'L_QUESTIONS_LIST' => $FAQ_LANG['faq_questions_list'],
		'L_ADD_QUESTION' => $FAQ_LANG['add_question'],
		'L_ADD_CAT' => $FAQ_LANG['add_cat'],
	));
	
	$Template->pparse('admin_faq_questions');
}
else
{
	$Template->set_filenames(array(
		'admin_faq'=> 'faq/admin_faq.tpl'
	));
	
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('contents');
	
	$Template->put_all(array(
		'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
		'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
		'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
		'L_QUESTIONS_LIST' => $FAQ_LANG['faq_questions_list'],
		'L_ADD_QUESTION' => $FAQ_LANG['add_question'],   
		'L_ADD_CAT' => $FAQ_LANG['add_cat'],
		'L_REQUIRE' => $LANG['require'],
		'L_REQUIRE_NBR_COLS' => $FAQ_LANG['require_nbr_cols'],
		'L_NBR_COLS' => $FAQ_LANG['nbr_cols'],
		'L_NBR_COLS_EXPLAIN' => $FAQ_LANG['nbr_cols_explain'],
		'L_DISPLAY_MODE' => $FAQ_LANG['display_mode'],
		'L_DISPLAY_MODE_EXPLAIN' => $FAQ_LANG['display_mode_admin_explain'],
		'L_BLOCKS' => $FAQ_LANG['display_block'],
		'L_INLINE' => $FAQ_LANG['display_inline'],
		'L_ROOT_DESCRIPTION' => $FAQ_LANG['root_description'],
		'L_AUTH' => $FAQ_LANG['general_auth'],
		'L_AUTH_EXPLAIN' => $FAQ_LANG['general_auth_explain'],
		'L_AUTH_READ' => $FAQ_LANG['read_auth'],
		'L_AUTH_WRITE' => $FAQ_LANG['write_auth'],
		'L_SUBMIT' => $LANG['submit'],
		'KERNEL_EDITOR' => $editor->display(),
		'ROOT_CAT_DESCRIPTION' => FormatingHelper::unparse($faq_config->get_root_cat_description()),
		'AUTH_READ' => Authorizations::generate_select(FaqAuthorizationsService::READ_AUTHORIZATIONS, $faq_config->get_authorizations()),
		'AUTH_WRITE' => Authorizations::generate_select(FaqAuthorizationsService::WRITE_AUTHORIZATIONS, $faq_config->get_authorizations()),
		'NUM_COLS' => $faq_config->get_number_columns(),
		'SELECTED_BLOCK' => $faq_config->get_display_mode() == FaqConfig::DISPLAY_MODE_BLOCK ? ' selected="selected"' : '',
		'SELECTED_INLINE' => $faq_config->get_display_mode() == FaqConfig::DISPLAY_MODE_INLINE ? ' selected="selected"' : ''
	));
	
	$Template->pparse('admin_faq');
}

require_once('../admin/admin_footer.php');

?>