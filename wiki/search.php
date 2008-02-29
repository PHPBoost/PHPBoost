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

require_once('../includes/begin.php'); 
load_module_lang('wiki');

define('TITLE' , $LANG['wiki'] . ' - ' . $LANG['wiki_search']);

$speed_bar_key = 'wiki_search';
require_once('../wiki/wiki_speed_bar.php');

require_once('../includes/header.php'); 

if( !$Member->Check_auth($SECURE_MODULE['wiki'], ACCESS_MODULE) || !$Member->Check_level(MEMBER_LEVEL) )
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT); 

$search_string = !empty($_GET['search']) ? securit($_GET['search']) : '';
$where_search = !empty($_GET['where']) ? ($_GET['where'] == 'contents' ? 'contents' : 'title') : 'title';
$page = !empty($_GET['page']) ? numeric($_GET['page']) : 1;
$page = $page <= 0 ? 1 : $page;

$Template->Set_filenames(array('wiki_search' => '../templates/' . $CONFIG['theme'] . '/wiki/search.tpl'));

$Template->Assign_vars(array(
	'L_SEARCH' => $LANG['wiki_search'],
	'L_KEY_WORDS' => $LANG['wiki_search_key_words'],
	'TARGET' => transid('search.php'),
	'KEY_WORDS' => $search_string,
	'L_SEARCH_RESULT' => $LANG['wiki_search_result'],
	'ARTICLE_TITLE' => $LANG['title'],
	'RELEVANCE' => $LANG['wiki_search_relevance'],
	'SELECTED_TITLE' => $where_search == 'title' ? 'checked="checked"' : '',
	'SELECTED_CONTENTS' => $where_search != 'title' ? 'checked="checked"' : '',
	'L_TITLE' => $LANG['title'],
	'L_CONTENTS' => $LANG['contents']
));

if( $search_string != '' ) //recherche
{
	$title_search = "SELECT title, encoded_title, MATCH(title) AGAINST('" . $search_string . "') AS relevance
		FROM ".PREFIX."wiki_articles
		WHERE MATCH(title) AGAINST('" . $search_string . "') 
		ORDER BY relevance DESC";
	
	$contents_search = "SELECT a.title, a.encoded_title, MATCH(c.content) AGAINST('" . $search_string . "') AS relevance
		FROM ".PREFIX."wiki_articles a
		LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id
		WHERE MATCH(c.content) AGAINST('" . $search_string . "') 
		ORDER BY relevance DESC";
	
	$query = ($where_search == 'title' ? $title_search : $contents_search);
	
	$query_rows = $where_search == 'title' ? "SELECT COUNT(*) FROM ".PREFIX."wiki_articles WHERE MATCH(title) AGAINST('" . $search_string . "')" : "SELECT COUNT(*) 		FROM ".PREFIX."wiki_articles a
		LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id
		WHERE MATCH(c.content) AGAINST('" . $search_string . "')";
	
	$result = $Sql->Query_while($query, __LINE__, __FILE__);
	
	$num_rows = $Sql->Sql_num_rows($result, $query_rows, __LINE__, __FILE__);
	
	include_once('../includes/pagination.class.php'); 
	$Pagination = new Pagination();
	$pages_links = $Pagination->Display_pagination('search' . transid('.php?search=' . $search_string . '&amp;where=' . $where_search . '&amp;page=%d'), $num_rows, 'page', 10, 3);
	
	$Template->Assign_block_vars('search_result', array(
		'PAGES' => !empty($pages_links) ? $pages_links : '&nbsp;'
	));
	
	$i = 1; //On émule le "limit" 10 résultats par page
	while( $row = $Sql->Sql_fetch_assoc($result) )
	{
		if( $i > ($page - 1) * 10 && $i <= $page * 2 ) //On affiche
			$Template->Assign_block_vars('search_result.item', array(
				'TITLE' => $row['title'],
				'U_TITLE' => transid('wiki.php?title=' . $row['encoded_title'], $row['encoded_title']),
				'RELEVANCE' => number_round(($row['relevance'] / 5.5), 2) * 100 . ' %'
			));	
		$i++;
		if( $i > $page * 2 )
			break;
	}
	
	if( $num_rows == 0 )
		$Errorh->Error_handler($LANG['wiki_empty_search'], E_NOTICE);
	
}

$Template->Pparse('wiki_search');


require_once('../includes/footer.php'); 

?>