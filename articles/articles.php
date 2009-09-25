<?php
/*##################################################
 *                               articles.php
 *                            -------------------
 *   begin                : July 17, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/begin.php'); 
require_once('articles_begin.php');
require_once('../kernel/header.php');

require_once('articles_cats.class.php');
$articles_categories = new ArticlesCats();
$page = retrieve(GET, 'p', 1, TUNSIGNED_INT);
$cat = retrieve(GET, 'cat', 0);
$idart = retrieve(GET, 'id', 0);	


if (!empty($idart) && isset($cat) )
{

	//Niveau d'autorisation de la catégorie
	if (!isset($ARTICLES_CAT[$idartcat]) || !$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) || $ARTICLES_CAT[$idartcat]['visible'] == 0) 
		{
			$Errorh->handler('e_auth', E_USER_REDIRECT);
		}
		
	$result = $Sql->query_while("SELECT a.contents, a.title, a.id, a.idcat, a.timestamp, a.start, a.visible, a.user_id, a.icon, a.nbr_com, m.login, m.level
		FROM " . DB_TABLE_ARTICLES . " a LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
		WHERE a.id = '" . $idart . "'", __LINE__, __FILE__);
	$articles = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	
	if (empty($articles['id']))
		$Errorh->handler('e_unexist_articles', E_USER_REDIRECT); 
	
	$tpl = new Template('articles/articles.tpl');
	
	//MAJ du compteur.
	$Sql->query_inject("UPDATE " . LOW_PRIORITY . " " . DB_TABLE_ARTICLES . " SET views = views + 1 WHERE id = " . $idart, __LINE__, __FILE__); 
	
	
	//On crée une pagination si il y plus d'une page.
	import('util/pagination'); 
	$Pagination = new Pagination();

	//Si l'article ne commence pas par une page on l'ajoute.
	if (substr(trim($articles['contents']), 0, 6) != '[page]')
		$articles['contents'] = ' [page]&nbsp;[/page]' . $articles['contents'];
	else
		$articles['contents'] = ' ' . $articles['contents'];
		
	//Pagination des articles.
	$array_contents = preg_split('`\[page\].+\[/page\](.*)`Us', $articles['contents'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	//Récupération de la liste des pages.
	preg_match_all('`\[page\]([^[]+)\[/page\]`U', $articles['contents'], $array_page);
	$page_list = '<option value="1">' . $ARTICLES_LANG['select_page'] . '</option>';
	$page_list .= '<option value="1"></option>';
	$i = 1;
	foreach ($array_page[1] as $page_name)
	{
		$selected = ($i == $page) ? 'selected="selected"' : '';
		$page_list .= '<option value="' . $i++ . '"' . $selected . '>' . $page_name . '</option>';
	}
	
	//Nombre de pages
	$nbr_page = count($array_page[1]);
	$nbr_page = !empty($nbr_page) ? $nbr_page : 1;
	
	//Affichage notation
	import('content/note'); 
	$Note = new Note('articles', $idart, url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart, 'articles-' . $idartcat . '-' . $idart . '.php'), $CONFIG_ARTICLES['note_max'], '', NOTE_DISPLAY_NOTE);
	
	import('content/comments');
	
	$tpl->assign_vars(array(
		'C_IS_ADMIN' => ($User->check_level(ADMIN_LEVEL)),
		'C_DISPLAY_ARTICLE' => true,
		'IDART' => $articles['id'],
		'IDCAT' => $idartcat,
		'NAME' => $articles['title'],
		'PSEUDO' => $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $articles['user_id'] . "'", __LINE__, __FILE__),		
		'CONTENTS' => isset($array_contents[$page]) ? second_parse($array_contents[$page]) : '',
		'CAT' => $ARTICLES_CAT[$idartcat]['name'],
		'DATE' => gmdate_format('date_format_short', $articles['timestamp']),
		'PAGES_LIST' => $page_list,
		'PAGINATION_ARTICLES' => $Pagination->display('articles' . url('.php?cat=' . $idartcat . '&amp;id='. $idart . '&amp;p=%d', '-' . $idartcat . '-'. $idart . '-%d+' . url_encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3, 11, NO_PREVIOUS_NEXT_LINKS),
		'PAGE_NAME' => (isset($array_page[1][($page-1)]) && $array_page[1][($page-1)] != '&nbsp;') ? $array_page[1][($page-1)] : '',
		'PAGE_PREVIOUS_ARTICLES' => ($page > 1 && $page <= $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=' . ($page - 1), 'articles-' . $idartcat . '-' . $idart . '-' . ($page - 1) . '+' . url_encode_rewrite($articles['title']) . '.php') . '">&laquo; ' . $LANG['previous_page'] . '</a><br />' . $array_page[1][($page-2)] : '',
		'PAGE_NEXT_ARTICLES' => ($page > 0 && $page < $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=' . ($page + 1), 'articles-' . $idartcat . '-' . $idart . '-' . ($page + 1) . '+' . url_encode_rewrite($articles['title']) . '.php') . '">' . $LANG['next_page'] . ' &raquo;</a><br />' . $array_page[1][$page] : '',
		'COM' => Comments::com_display_link($articles['nbr_com'], '../articles/articles' . url('.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;com=0', '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php?com=0'), $articles['id'], 'articles'),
		'KERNEL_NOTATION' => $Note->display_form(),
		'U_USER_ID' => url('.php?id=' . $articles['user_id'], '-' . $articles['user_id'] . '.php'),
		'U_ONCHANGE_ARTICLE' => "'" . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=\' + this.options[this.selectedIndex].value', 'articles-' . $idartcat . '-' . $idart . '-\'+ this.options[this.selectedIndex].value + \'+' . url_encode_rewrite($articles['title']) . '.php' . "'"),
		'U_PRINT_ARTICLE' => url('print.php?id=' . $idart),
		'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
		'L_SUMMARY' => $ARTICLES_LANG['summary'],
		'L_DELETE' => $LANG['delete'],
		'L_EDIT' => $LANG['edit'],
		'L_SUBMIT' => $LANG['submit'],
		'L_WRITTEN' =>  $LANG['written_by'],
		'L_ON' => $LANG['on'],
		'L_PRINTABLE_VERSION' => $LANG['printable_version'],
	));

	//Affichage commentaires.
	
	if (isset($_GET['com']))
	{
		$tpl->assign_vars(array(
			'COMMENTS' => display_comments('articles', $idart, url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;com=%s', 'articles-' . $idartcat . '-' . $idart . '.php?com=%s'))
		));
	}	

	$tpl->parse();
}
else
{

	import('modules/modules_discovery_service');
	$modulesLoader = new ModulesDiscoveryService();
	$module_name = 'articles';
	$module = $modulesLoader->get_module($module_name);
	if ($module->has_functionality('get_home_page')) {
		echo $module->functionality('get_home_page');
	} elseif (!$no_alert_on_error) {
		global $Errorh;
		$Errorh->handler('Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', E_USER_ERROR, __LINE__, __FILE__);
		exit;
	}
}
			
require_once('../kernel/footer.php'); 

?>