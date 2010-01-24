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
	$FAQ_CONFIG['faq_name'] = stripslashes(retrieve(POST, 'faq_name', $FAQ_LANG['faq']));
	$FAQ_CONFIG['num_cols'] = retrieve(POST, 'num_cols', 3);
	$FAQ_CONFIG['display_block'] = (!empty($_POST['display_mode']) && $_POST['display_mode'] == 'inline') ? false : true;
	// unused auth variables ?
	$FAQ_CONFIG['global_auth'] = Authorizations::build_auth_array_from_form(AUTH_READ, AUTH_WRITE);
	$FAQ_CONFIG['root'] = $FAQ_CATS[0];
	
	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($FAQ_CONFIG)) . "' WHERE name = 'faq'", __LINE__, __FILE__);
	//Régénération du cache
	$Cache->Generate_module_file('faq');
	
	AppContext::get_response()->redirect(url('admin_faq.php', '', '&'));
}

//Questions list
if ($page > 0)
{
	$Template->set_filenames(array(
		'admin_faq_questions'=> 'faq/admin_faq_questions.tpl'
	));
	
	 
	$Pagination = new DeprecatedPagination();
	
	$result = $Sql->query_while("SELECT q.id, q.question, q.timestamp, q.idcat, c.name
	FROM " . PREFIX . "faq q
	LEFT JOIN " . PREFIX . "faq_cats c ON c.id = q.idcat
	ORDER BY q.timestamp DESC
	" . $Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	
	$nbr_questions = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "faq", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('question', array(
			'QUESTION' => $row['question'],
			'CATEGORY' => !empty($row['idcat']) ? $row['name'] : $LANG['root'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'U_QUESTION' => url('faq.php?id=' . $row['idcat'] . '&amp;question=' . $row['id'], 'faq-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php?question=' . $row['id']) . '#q' . $row['id'],
			'U_CATEGORY' => !empty($row['idcat']) ? url('faq.php?id=' . $row['idcat'], 'faq-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php') : url('faq.php')
		));
	}
	
	$Template->assign_vars(array(
		'PAGINATION' => $Pagination->display('admin_faq.php?p=%d', $nbr_questions, 'p', 25, 3),
		'L_QUESTION' => $FAQ_LANG['question'],
		'L_CATEGORY' => $FAQ_LANG['category'],
		'L_DATE' => $LANG['date'],
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

	$FAQ_CONFIG['global_auth'] = isset($FAQ_CONFIG['global_auth']) && is_array($FAQ_CONFIG['global_auth']) ? $FAQ_CONFIG['global_auth'] : array();
	$FAQ_CONFIG['faq_name'] = !empty($FAQ_CONFIG['faq_name']) ? $FAQ_CONFIG['faq_name'] : '';
	$FAQ_CONFIG['num_cols'] = isset($FAQ_CONFIG['num_cols']) ? $FAQ_CONFIG['num_cols'] : 4;
	$FAQ_CONFIG['display_block'] = isset($FAQ_CONFIG['display_block']) ? $FAQ_CONFIG['display_block'] : true;
	
	$Template->assign_vars(array(
		'L_FAQ_MANAGEMENT' => $FAQ_LANG['faq_management'],
		'L_CATS_MANAGEMENT' => $FAQ_LANG['cats_management'],
		'L_CONFIG_MANAGEMENT' => $FAQ_LANG['faq_configuration'],
		'L_QUESTIONS_LIST' => $FAQ_LANG['faq_questions_list'],
		'L_ADD_QUESTION' => $FAQ_LANG['add_question'],   
		'L_ADD_CAT' => $FAQ_LANG['add_cat'],
		'L_FAQ_NAME' => $FAQ_LANG['faq_name'],
		'L_FAQ_NAME_EXPLAIN' => $FAQ_LANG['faq_name_explain'],
		'L_NBR_COLS' => $FAQ_LANG['nbr_cols'],
		'L_NBR_COLS_EXPLAIN' => $FAQ_LANG['nbr_cols_explain'],
		'L_DISPLAY_MODE' => $FAQ_LANG['display_mode'],
		'L_DISPLAY_MODE_EXPLAIN' => $FAQ_LANG['display_mode_admin_explain'],
		'L_BLOCKS' => $FAQ_LANG['display_block'],
		'L_INLINE' => $FAQ_LANG['display_inline'],
		'L_AUTH' => $FAQ_LANG['general_auth'],
		'L_AUTH_EXPLAIN' => $FAQ_LANG['general_auth_explain'],
		'L_AUTH_READ' => $FAQ_LANG['read_auth'],
		'L_AUTH_WRITE' => $FAQ_LANG['write_auth'],
		'L_SUBMIT' => $LANG['submit'],
		'AUTH_READ' => Authorizations::generate_select(AUTH_READ, $FAQ_CONFIG['global_auth']),
		'AUTH_WRITE' => Authorizations::generate_select(AUTH_WRITE, $FAQ_CONFIG['global_auth']),
		'FAQ_NAME' => $FAQ_CONFIG['faq_name'],
		'NUM_COLS' => $FAQ_CONFIG['num_cols'],
		'SELECTED_BLOCK' => $FAQ_CONFIG['display_block'] ? ' selected="selected"' : '',
		'SELECTED_INLINE' => !$FAQ_CONFIG['display_block'] ? ' selected="selected"' : ''
	));
	
	$Template->pparse('admin_faq');
}

require_once('../admin/admin_footer.php');

?>