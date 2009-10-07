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
$user = retrieve(GET, 'user', false, TBOOL);

if (!empty($idart) && isset($cat) )
{
	//Niveau d'autorisation de la catégorie
	if (!isset($ARTICLES_CAT[$idartcat]) || !$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) || $ARTICLES_CAT[$idartcat]['visible'] == 0) 
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
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
	
	// If tab pagination is active
	$c_tab=$CONFIG_ARTICLES['tab'];
	//Nombre de pages
	$nbr_page = count($array_page[1]);
	$nbr_page = !empty($nbr_page) ? $nbr_page : 1;
	$tpl->assign_vars( array(
		'TOTAL_TAB'=> count($array_page[1]),
	));

	foreach ($array_page[1] as $page_name)
	{
		if($c_tab && $Pagination->display('articles' . url('.php?cat=' . $idartcat . '&amp;id='. $idart . '&amp;p=%d', '-' . $idartcat . '-'. $idart . '-%d+' . url_encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3, 11, NO_PREVIOUS_NEXT_LINKS) )
		{	
				$c_tab=true;
				$tpl->assign_block_vars('tab', array(
					'CONTENTS_TAB'=>isset($array_contents[$i]) ? second_parse($array_contents[$i]) : '',
					'ID_TAB' =>$i,
					'DISPLAY' => ( $i == 1 )? "yes" : "none",
					'STYLE' => ($i == 1)? 'style="margin-left: 1px"' : '',
					'ID_TAB_ACT' =>($i == 1)?'Active' : $i,
					'TOTAL_TAB'=> count($array_page[1]),
					'PAGE_NAME'=> Trim($page_name) == '' ? $LANG['page']." : ".$i : Trim($page_name),
				));
		}
		else
			$c_tab=false;
			
		$selected = ($i == $page) ? 'selected="selected"' : '';
		$page_list .= '<option value="' . $i++ . '"' . $selected . '>' . $page_name . '</option>';
		
	}
	
	//Affichage notation
	import('content/note'); 
	$Note = new Note('articles', $idart, url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart, 'articles-' . $idartcat . '-' . $idart . '.php'), $CONFIG_ARTICLES['note_max'], '', NOTE_DISPLAY_NOTE);
	
	import('content/comments');
	
	$tpl->assign_vars(array(
		'C_IS_ADMIN' => ($User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE)),
		'C_DISPLAY_ARTICLE' => true,
		'C_PRINT' => true,
		'C_TAB'=>$c_tab,
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
elseif ($user && isset($cat))
{
	if(!$User->check_auth($ARTICLES_CAT[$cat]['auth'], AUTH_ARTICLES_WRITE))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
		
	$tpl = new Template('articles/articles_cat.tpl');
	$i = 0;

	$now = new Date(DATE_NOW, TIMEZONE_AUTO);
	$array_cat = array();
	
	$articles_categories->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_ARTICLES_WRITE);
	
	if (!empty($array_cat))
	{
		$cat = $cat > 0 && $User->check_auth($ARTICLES_CAT[$cat]['auth'], AUTH_ARTICLES_WRITE) ? $cat : 0;
		
		$get_sort = retrieve(GET, 'sort', '');	
		$get_mode = retrieve(GET, 'mode', '');
		$selected_fields = array(
			'alpha' => '',
			'view' => '',
			'date' => '',
			'com' => '',
			'note' => '',
			'asc' => '',
			'desc' => ''
			);
			
		switch ($get_sort)
		{
			case 'alpha' : 
			$sort = 'title';
			$selected_fields['alpha'] = ' selected="selected"';
			break;	
			case 'com' :
			$sort = 'nbr_com';
			$selected_fields['com'] = ' selected="selected"';
			break;			
			case 'date' : 
			$sort = 'timestamp';
			$selected_fields['date'] = ' selected="selected"';
			break;		
			case 'view' :
			$sort = 'views';
			$selected_fields['view'] = ' selected="selected"';
			break;		
			case 'note' :
			$sort = 'note';
			$selected_fields['note'] = ' selected="selected"';
			break;
			default :
			$sort = 'timestamp';
			$selected_fields['date'] = ' selected="selected"';
		}

		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		if ($mode == 'ASC')
			$selected_fields['asc'] = ' selected="selected"';
		else
			$selected_fields['desc'] = ' selected="selected"';

			
		$result = $Sql->query_while("SELECT a.contents, a.note,a.views,a.nbr_com,a.title, a.id, a.idcat, a.timestamp, a.user_id, a.icon, a.nbr_com,a.start,a.visible, m.login, m.level
			FROM " . DB_TABLE_ARTICLES . " a
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
			WHERE (a.start > '" . $now->get_timestamp() . "' OR a.visible = '0') AND a.user_id = '" . $User->get_attribute('user_id') . "' AND a.idcat = '".$cat."'
			ORDER BY " . $sort . " " . $mode , __LINE__, __FILE__);
				
		$group_color = User::get_group_color($User->get_attribute('user_groups'), $User->get_attribute('level'));
		$array_class = array('member', 'modo', 'admin');

		while ($row = $Sql->fetch_assoc($result))
		{
			$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
			
			$tpl->assign_block_vars('articles', array(
				'ID' => $row['id'],
				'U_SYNDICATION' => url('../syndication.php?m=articles&amp;cat=' . $row['idcat']),
				'U_LINK' => 'articles' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
				'NAME' => $row['title'],
				'C_IMG' => !empty($row['icon']),
				'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
				'CONTENTS' => second_parse($row['contents']),
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			    'FEED_MENU' => Feed::get_feed_menu('syndication.php?m=articles'),
				'COMPT'=>$row['views'],
				'NOTE'=>$row['note'],
				'COM'=>$row['nbr_com'],
				'USER_ID'=>$row['user_id'],
				'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
				'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				'U_ARTICLES_PSEUDO'=> '<a href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . wordwrap_html($row['login'], 19) . '</a>',
				'U_EDIT'=> url('admin_articles_cat.php?edit='.$row['id']),
				'U_ARTICLES_LINK' => url('.php'),
				'U_ARTICLES_LINK_COM'=>url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;com=%s', '-' . $row['idcat'] . '-' . $row['id'] . '.php?com=0')
			));

			$i++;
		}
		
		$Sql->query_close($result);

		$articles_categories->build_select_form($cat, 'idcat', 'idcat', 0, AUTH_ARTICLES_WRITE, $CONFIG_ARTICLES['global_auth'], IGNORE_AND_CONTINUE_BROWSING_IF_A_CATEGORY_DOES_NOT_MATCH, $tpl);
	
		$tpl->assign_vars(array(
				'C_WRITE'=>$User->check_auth($ARTICLES_CAT[$cat]['auth'], AUTH_ARTICLES_WRITE),
				'C_ARTICLES_LINK' =>true,
				'C_WAITING'=> true,
				'L_NO_ARTICLES'=>$i == 0 ? $ARTICLES_LANG['no_articles_available']: '',
				'L_ALL'=>$ARTICLES_LANG['all'],
				'L_WRITTEN' =>  $LANG['written_by'],
				'L_CATEGORY' => $LANG['categories'],
				'L_ARTICLES' => $ARTICLES_LANG['articles'],
				'L_ARTICLES_INDEX' => $ARTICLES_LANG['title_articles'],
				'L_DELETE' => $LANG['delete'],
				'L_EDIT' => $LANG['edit'],
				'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
				'L_ALL'=>$ARTICLES_LANG['all'],
				'L_ME'=>$ARTICLES_LANG['me'],
				'L_ARTICLES' => $ARTICLES_LANG['articles'],
				'L_DATE' => $LANG['date'],
				'L_VIEW' => $LANG['views'],
				'L_NOTE' => $LANG['note'],
				'L_COM' => $LANG['com'],
				'L_DESC' => $LANG['desc'],
				'L_ASC' => $LANG['asc'],
				'L_TITLE'=>$LANG['title'],
				'L_PSEUDO' => $LANG['pseudo'],
				'L_WRITTEN' =>  $LANG['written_by'],
				'U_ARTICLES_CAT_LINKS'=>' <a href="articles.php?user=1">' . $ARTICLES_LANG['waiting_articles'] . '</a>',
				'U_ME_LINKS'=>'<span class="' . $array_class[$User->get_attribute('level')] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . wordwrap_html($User->get_attribute('login'), 19) . '</span>',
				'SELECTED_ALPHA' => $selected_fields['alpha'],
				'SELECTED_COM' => $selected_fields['com'],
				'SELECTED_DATE' => $selected_fields['date'],
				'SELECTED_VIEW' => $selected_fields['view'],
				'SELECTED_NOTE' => $selected_fields['note'],
				'SELECTED_ASC' => $selected_fields['asc'],
				'SELECTED_DESC' => $selected_fields['desc'],
				'TARGET_ON_CHANGE_ORDER' => url('articles.php?user=1&amp;cat=' . $idartcat ),
				
			));
				
		$tpl->parse();
	}
	else
	{
		redirect(get_start_page());
		exit;
	}
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