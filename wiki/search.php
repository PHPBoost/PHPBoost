<?php
/*##################################################
 *                               search.php
 *                            -------------------
 *   begin                : June 16, 2007
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

require_once('../kernel/begin.php');
load_module_lang('wiki');

define('TITLE' , $LANG['wiki'] . ' - ' . $LANG['wiki_search']);

$bread_crumb_key = 'wiki_search';
require_once('../wiki/wiki_bread_crumb.php');

require_once('../kernel/header.php');

if (!$User->check_level(MEMBER_LEVEL))
	$Errorh->handler('e_auth', E_USER_REDIRECT);

$search_string = retrieve(GET, 'search', '');
$where_search = retrieve(GET, 'where', '');
$where_search = !(empty($where_search) || ($where_search == 'contents')) ? 'contents' : 'title';
$page = retrieve(GET, 'page', 1);
$page = $page <= 0 ? 1 : $page;

$Template->set_filenames(array('wiki_search'=> 'wiki/search.tpl'));

$Template->assign_vars(array(
	'L_SEARCH' => $LANG['wiki_search'],
	'L_KEY_WORDS' => $LANG['wiki_search_key_words'],
	'TARGET' => url('search.php?token=' . $Session->get_token()),
	'KEY_WORDS' => $search_string,
	'L_SEARCH_RESULT' => $LANG['wiki_search_result'],
	'ARTICLE_TITLE' => $LANG['title'],
	'RELEVANCE' => $LANG['wiki_search_relevance'],
	'SELECTED_TITLE' => $where_search == 'title' ? 'checked="checked"' : '',
	'SELECTED_CONTENTS' => $where_search != 'title' ? 'checked="checked"' : '',
	'L_TITLE' => $LANG['title'],
	'L_CONTENTS' => $LANG['content']
));

if (!empty($search_string)) //recherche
{
	$title_search = "SELECT title, encoded_title, MATCH(title) AGAINST('" . $search_string . "') AS relevance
		FROM " . PREFIX . "wiki_articles
		WHERE MATCH(title) AGAINST('" . $search_string . "') 
		ORDER BY relevance DESC";
	
	$contents_search = "SELECT a.title, a.encoded_title, MATCH(c.content) AGAINST('" . $search_string . "') AS relevance
		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
		WHERE MATCH(c.content) AGAINST('" . $search_string . "') 
		ORDER BY relevance DESC";
	
	$query = ($where_search == 'title' ? $title_search : $contents_search);
	
	$query_rows = $where_search == 'title' ? "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE MATCH(title) AGAINST('" . $search_string . "')" : "SELECT COUNT(*) 		FROM " . PREFIX . "wiki_articles a
		LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
		WHERE MATCH(c.content) AGAINST('" . $search_string . "')";
	
	$result = $Sql->query_while ($query, __LINE__, __FILE__);
	
	$num_rows = $Sql->num_rows($result, $query_rows, __LINE__, __FILE__);
	
	import('util/pagination'); 
	$Pagination = new Pagination();
	$pages_links = $Pagination->display('search' . url('.php?search=' . $search_string . '&amp;where=' . $where_search . '&amp;page=%d'), $num_rows, 'page', 10, 3);
	
	if ($num_rows > 0)
		$Template->assign_block_vars('search_result', array(
			'PAGES' => !empty($pages_links) ? $pages_links : '&nbsp;'
		));
	else
		$Errorh->handler($LANG['wiki_empty_search'], E_NOTICE);
	
	$i = 1; //On �mule le "limit" 10 r�sultats par page
	while ($row = $Sql->fetch_assoc($result))
	{
		if ($i > ($page - 1) * 10 && $i <= $page * 10) //On affiche
			$Template->assign_block_vars('search_result.item', array(
				'TITLE' => $row['title'],
				'U_TITLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
				'RELEVANCE' => number_round(($row['relevance'] / 5.5), 2) * 100 . ' %'
			));	
		$i++;
		if ($i > $page * 10)
			break;
	}	
}

$Template->pparse('wiki_search');


require_once('../kernel/footer.php'); 

?>
