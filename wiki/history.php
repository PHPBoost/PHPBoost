<?php
/*##################################################
 *                               history.php
 *                            -------------------
 *   begin                : October 09, 2006
 *   copyright            : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

require_once('../kernel/begin.php'); 
load_module_lang('wiki');

$id_article = AppContext::get_request()->get_getint('id', 0);
$field = TextHelper::strprotect(AppContext::get_request()->get_getstring('field', ''));
$order = TextHelper::strprotect(AppContext::get_request()->get_getstring('order', ''));

define('TITLE' , $LANG['wiki_history']);

if (!empty($id_article))
{
	$article_infos = $Sql->query_array(PREFIX . 'wiki_articles', 'title', 'auth', 'encoded_title', 'id_cat', 'WHERE id = ' . $id_article, __LINE__, __FILE__);
}

$bread_crumb_key = !empty($id_article) ? 'wiki_history_article' : 'wiki_history';
require_once('../wiki/wiki_bread_crumb.php');

require_once('../kernel/header.php'); 

if (!empty($id_article))
{
	$Template = new FileTemplate('wiki/history.tpl');

	$Template->assign_block_vars('article', array(
		'L_TITLE' => $LANG['wiki_history'] . ': <a href="' . $article_infos['encoded_title'] . '">' . $article_infos['title'] . '</a>',
	));
	
	$general_auth = empty($article_infos['auth']) ? true : false;
	$article_auth = !empty($article_infos['auth']) ? unserialize($article_infos['auth']) : array();
	$restore_auth = (!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_RESTORE_ARCHIVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_RESTORE_ARCHIVE)) ? true : false;
	$delete_auth = (!$general_auth || $User->check_auth($_WIKI_CONFIG['auth'], WIKI_DELETE_ARCHIVE)) && ($general_auth || $User->check_auth($article_auth , WIKI_DELETE_ARCHIVE)) ? true : false;
	
	//on va chercher le contenu de la page
	$result = $Sql->query_while("SELECT a.title, a.encoded_title, c.timestamp, c.id_contents, c.user_id, c.user_ip, m.login, c.id_article, c.activ
		FROM " . PREFIX . "wiki_contents c
		LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.id_article
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
		WHERE c.id_article = '" . $id_article . "'
		ORDER BY c.timestamp DESC", __LINE__, __FILE__);
	
	while ($row = $Sql->fetch_assoc($result))
	{
		//Restauration
		$actions = ($row['activ'] != 1 && $restore_auth) ? '<a href="' . url('action.php?restore=' . $row['id_contents']. '&amp;token=' . $Session->get_token()) . '" title="' . $LANG['wiki_restore_version'] . '"><img src="templates/images/restore.png" alt="' . $LANG['wiki_restore_version'] . '" /></a> &nbsp; ' : '';
		
		//Suppression
		$actions .= ($row['activ'] != 1 && $delete_auth) ? '<a href="' . url('action.php?del_contents=' . $row['id_contents']. '&amp;token=' . $Session->get_token()) . '" title="' . $LANG['delete'] . '" onclick="javascript: return confirm(\'' . str_replace('\'', '\\\'', $LANG['wiki_confirm_delete_archive']) . '\');"><img src="' . $Template->get_pictures_data_path() . '/images/delete.png" alt="' . $LANG['delete'] . '" /></a>' : '';
		
		
		$Template->assign_block_vars('article.list', array(
			'TITLE' => $LANG['wiki_consult_article'],
			'AUTHOR' => !empty($row['login']) ? '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>' : $row['user_ip'],
			'DATE' => gmdate_format('date_format', $row['timestamp']),
			'U_ARTICLE' => $row['activ'] == 1 ? url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']) : url('wiki.php?id_contents=' . $row['id_contents']),
			'CURRENT_RELEASE' => $row['activ'] == 1 ? '(' . $LANG['wiki_current_version'] . ')' : '',
			'ACTIONS' => !empty($actions) ? $actions : $LANG['wiki_no_possible_action']
		));
	}
	$Sql->query_close($result);
	
	$Template->put_all(array(
		'L_VERSIONS' => $LANG['wiki_version_list'],
		'L_DATE' => $LANG['date'],
		'L_AUTHOR' => $LANG['wiki_author'],
		'L_ACTIONS' => $LANG['wiki_possible_actions'],
		));
	
	$Template->display();	
}
else //On affiche la liste des modifications 
{
	$_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY = 25;
	
	//Champs sur lesquels on ordonne
	$field = ($field == 'title') ? 'title' : 'timestamp';
	$order = $order == 'asc' ? 'asc' : 'desc';
	
	//On compte le nombre d'articles
	$nbr_articles = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE redirect = '0'", __LINE__, __FILE__);
	
	//On instancie la classe de pagination
	
	$Pagination = new DeprecatedPagination();
	$show_pagin = $Pagination->display(url('history.php?field=' . $field . '&amp;order=' . $order . '&amp;p=%d'), $nbr_articles, 'p', $_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY, 3); 
	
	$Template = new FileTemplate('wiki/history.tpl');

	$Template->assign_block_vars('index', array(
		'L_HISTORY' => $LANG['wiki_history'],
		'L_TITLE' => $LANG['wiki_article_title'],
		'L_AUTHOR' => $LANG['wiki_author'],
		'L_DATE' => $LANG['date'],
		'ARROW_TOP_TITLE' => ($field == 'title' && $order == 'asc') ? '' : '<a href="' . url('history.php?p=' . $Pagination->page . '&amp;field=title&amp;order=asc') . '"><img src="../templates/' . get_utheme() . '/images/top.png" alt="asc" /></a>',
		'ARROW_BOTTOM_TITLE' => ($field == 'title' && $order == 'desc') ? '' : '<a href="' . url('history.php?p=' . $Pagination->page . '&amp;field=title&amp;order=desc') . '"><img src="../templates/' . get_utheme() . '/images/bottom.png" alt="desc" /></a>',
		'ARROW_TOP_DATE' => ($field == 'timestamp' && $order == 'asc') ? '' : '<a href="' . url('history.php?p=' . $Pagination->page . '&amp;field=timestamp&amp;order=asc') . '"><img src="../templates/' . get_utheme() . '/images/top.png" alt="asc" /></a>',
		'ARROW_BOTTOM_DATE' => ($field == 'timestamp' && $order == 'desc') ? '' : '<a href="' . url('history.php?p=' . $Pagination->page . '&amp;field=timestamp&amp;order=desc') . '"><img src="../templates/' . get_utheme() . '/images/bottom.png" alt="desc" /></a>',
		'PAGINATION' => ($nbr_articles > $_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY  ?  $show_pagin : '') //Affichage de la pagination si il le faut
	));	

	$result = $Sql->query_while("SELECT a.title, a.encoded_title, c.timestamp, c.id_contents AS id, c.user_id, c.user_ip, m.login, c.id_article, c.activ,  a.id_contents
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = c.user_id
		WHERE a.redirect = 0
		ORDER BY " . ($field == 'title' ? 'a' : 'c') . "." . $field . " " . $order . "
		" . $Sql->limit($Pagination->get_first_msg($_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY, 'p'),$_WIKI_NBR_ARTICLES_A_PAGE_IN_HISTORY), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('index.list', array(
			'TITLE' => $row['title'],
			'AUTHOR' => !empty($row['login']) ? '<a href="../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '">' . $row['login'] . '</a>' : $row['user_ip'],
			'DATE' => gmdate_format('date_format', $row['timestamp']),
			'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
		));
	}
	
	$Template->display();
}

require_once('../kernel/footer.php'); 
?>
