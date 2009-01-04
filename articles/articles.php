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
require_once('../articles/articles_begin.php');
require_once('../kernel/header.php');

$page = retrieve(GET, 'p', 1, TUNSIGNED_INT);
$cat = retrieve(GET, 'cat', 0);

if (!empty($idart) && isset($_GET['cat']) )
{
	//Niveau d'autorisation de la catégorie
	if (!isset($CAT_ARTICLES[$idartcat]) || !$User->check_auth($CAT_ARTICLES[$idartcat]['auth'], READ_CAT_ARTICLES) || $CAT_ARTICLES[$idartcat]['aprob'] == 0) 
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	if (empty($articles['id']))
		$Errorh->handler('e_unexist_articles', E_USER_REDIRECT); 
	
	$Template->set_filenames(array('articles'=> 'articles/articles.tpl'));		
	
	//MAJ du compteur.
	$Sql->query_inject("UPDATE " . LOW_PRIORITY . " ".PREFIX."articles SET views = views + 1 WHERE id = " . $idart, __LINE__, __FILE__); 
	
	if ($User->check_level(ADMIN_LEVEL))
	{
		$java = '<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("' . $LANG['alert_delete_article'] . '");
		}
		-->
		</script>';
		
		$edit = '&nbsp;&nbsp;<a href="../articles/admin_articles' . url('.php?id=' . $articles['id']) . '" title="'  . $LANG['edit'] . '"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" class="valign_middle" alt="'  . $LANG['edit'] . '" /></a>';
		$del = '&nbsp;&nbsp;<a href="../articles/admin_articles.php?delete=1&amp;id=' . $articles['id'] . '" title="' . $LANG['delete'] . '" onclick="javascript:return Confirm();"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" class="valign_middle" alt="' . $LANG['delete'] . '" /></a>';
		
		$Template->assign_vars(array(
			'JAVA' => $java,
			'EDIT' => $edit,
			'DEL' => $del
		));
	}
	
	//On crée une pagination si il y plus d'une page.
	include_once('../kernel/framework/util/pagination.class.php'); 
	$Pagination = new Pagination();

	//Si l'article ne commence pas par une page on l'ajoute.
	if (substr(trim($articles['contents']), 0, 6) != '[page]')
		$articles['contents'] = ' [page]&nbsp;[/page]' . $articles['contents'];
	else
		$articles['contents'] = ' ' . $articles['contents'];
		
	//Pagination des articles.
	$array_contents = preg_split('`\[page\][^[]+\[/page\](.*)`Us', $articles['contents'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	//Récupération de la liste des pages.
	preg_match_all('`\[page\]([^[]+)\[/page\]`U', $articles['contents'], $array_page);
	$page_list = '<option value="1">' . $LANG['select_page'] . '</option>';
	$page_list .= '<option value="1"></option>';
	$i = 1;
	foreach ($array_page[1] as $page_name)
	{
		if ($page_name != '&nbsp;')
		{
			$selected = ($i == $page) ? 'selected="selected"' : '';
			$page_list .= '<option value="' . $i++ . '"' . $selected . '>' . $page_name . '</option>';
		}
	}
	
	//Nombre de pages
	$nbr_page = count($array_page[1]);
	$nbr_page = !empty($nbr_page) ? $nbr_page : 1;
	
	//Affichage notation
	include_once('../kernel/framework/content/note.class.php'); 
	$Note = new Note('articles', $idart, url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart, 'articles-' . $idartcat . '-' . $idart . '.php'), $CONFIG_ARTICLES['note_max'], '', NOTE_DISPLAY_NOTE);
	
	$Template->assign_vars(array(
		'C_DISPLAY_ARTICLE' => true,
		'IDART' => $articles['id'],
		'IDCAT' => $idartcat,
		'NAME' => $articles['title'],
		'PSEUDO' => $Sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $articles['user_id'] . "'", __LINE__, __FILE__),		
		'CONTENTS' => isset($array_contents[$page]) ? second_parse($array_contents[$page]) : '',
		'CAT' => $CAT_ARTICLES[$idartcat]['name'],
		'DATE' => gmdate_format('date_format_short', $articles['timestamp']),
		'PAGES_LIST' => $page_list,
		'PAGINATION_ARTICLES' => $Pagination->display('articles' . url('.php?cat=' . $idartcat . '&amp;id='. $idart . '&amp;p=%d', '-' . $idartcat . '-'. $idart . '-%d+' . url_encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3, 11, NO_PREVIOUS_NEXT_LINKS),
		'PAGE_NAME' => (isset($array_page[1][($page-1)]) && $array_page[1][($page-1)] != '&nbsp;') ? $array_page[1][($page-1)] : '',
		'PAGE_PREVIOUS_ARTICLES' => ($page > 1 && $page <= $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=' . ($page - 1), 'articles-' . $idartcat . '-' . $idart . '-' . ($page - 1) . '+' . url_encode_rewrite($articles['title']) . '.php') . '">&laquo; ' . $LANG['previous_page'] . '</a><br />' . $array_page[1][($page-2)] : '',
		'PAGE_NEXT_ARTICLES' => ($page > 0 && $page < $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=' . ($page + 1), 'articles-' . $idartcat . '-' . $idart . '-' . ($page + 1) . '+' . url_encode_rewrite($articles['title']) . '.php') . '">' . $LANG['next_page'] . ' &raquo;</a><br />' . $array_page[1][$page] : '',
		'COM' => com_display_link($articles['nbr_com'], '../articles/articles' . url('.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;com=0', '-' . $idartcat . '-' . $idart . '+' . url_encode_rewrite($articles['title']) . '.php?com=0'), $articles['id'], 'articles'),
		'KERNEL_NOTATION' => $Note->display_form(),
		'U_USER_ID' => url('.php?id=' . $articles['user_id'], '-' . $articles['user_id'] . '.php'),
		'U_ONCHANGE_ARTICLE' => "'" . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=\' + this.options[this.selectedIndex].value', 'articles-' . $idartcat . '-' . $idart . '-\'+ this.options[this.selectedIndex].value + \'+' . url_encode_rewrite($articles['title']) . '.php' . "'"),
		'L_SUMMARY' => $LANG['summary'],
		'L_SUBMIT' => $LANG['submit'],
		'L_WRITTEN' =>  $LANG['written_by'],
		'L_ON' => $LANG['on'],
		'L_PRINTABLE_VERSION' => $LANG['printable_version'],
		'U_PRINT_ARTICLE' => url('print.php?id=' . $idart)
	));

	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$Template->assign_vars(array(
			'COMMENTS' => display_comments('articles', $idart, url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;com=%s', 'articles-' . $idartcat . '-' . $idart . '.php?com=%s'))
		));
	}	

	$Template->pparse('articles');	
}
else
{
	$Template->set_filenames(array(
		'articles_cat'=> 'articles/articles_cat.tpl'
	));	

	if ($idartcat > 0)
	{
		if (!isset($CAT_ARTICLES[$idartcat]) || $CAT_ARTICLES[$idartcat]['aprob'] == 0) 
			$Errorh->handler('e_auth', E_USER_REDIRECT); 

		$cat_links = '';
		foreach ($CAT_ARTICLES as $id => $array_info_cat)
		{
			if ($CAT_ARTICLES[$idartcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_ARTICLES[$idartcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_ARTICLES[$idartcat]['level'])
				$cat_links .= ' <a href="articles' . url('.php?cat=' . $id, '-' . $id . '.php') . '">' . $array_info_cat['name'] . '</a> &raquo;';
		}
		$clause_cat = " WHERE ac.id_left > '" . $CAT_ARTICLES[$idartcat]['id_left'] . "' AND ac.id_right < '" . $CAT_ARTICLES[$idartcat]['id_right'] . "' AND ac.level = '" . ($CAT_ARTICLES[$idartcat]['level'] + 1) . "' AND ac.aprob = 1";
	}
	else //Racine.
	{
		$cat_links = '';
		$clause_cat = " WHERE ac.level = '0' AND ac.aprob = 1";
	}

	//Niveau d'autorisation de la catégorie
	if (!$User->check_auth($CAT_ARTICLES[$idartcat]['auth'], READ_CAT_ARTICLES)) 
		$Errorh->handler('e_auth', E_USER_REDIRECT); 
	
	$nbr_articles = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."articles WHERE visible = 1 AND idcat = '" . $idartcat . "'", __LINE__, __FILE__);	
	$total_cat = $Sql->query("SELECT COUNT(*) FROM ".PREFIX."articles_cats ac " . $clause_cat, __LINE__, __FILE__);	
		
	$rewrite_title = url_encode_rewrite($CAT_ARTICLES[$idartcat]['name']);
	
	//Colonnes des catégories.
	$nbr_column_cats = ($total_cat > $CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : $total_cat;
	$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
	$column_width_cats = floor(100/$nbr_column_cats);
	
	$is_admin = $User->check_level(ADMIN_LEVEL) ? true : false;	
	$Template->assign_vars(array(
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'ADD_ARTICLES' => $is_admin ? (!empty($idartcat) ? '&raquo; ' : '') . '<a href="admin_articles_add.php"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" alt="" class="valign_middle" /></a>' : '',
		'L_ARTICLES' => $LANG['articles'],
		'L_DATE' => $LANG['date'],
		'L_VIEW' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_COM' => $LANG['com'],
		'L_TOTAL_ARTICLE' => ($nbr_articles > 0) ? sprintf($LANG['nbr_articles_info'], $nbr_articles) : '', 
		'L_NO_ARTICLES' => ($nbr_articles == 0) ? $LANG['none_article'] : '',
		'L_ARTICLES_INDEX' => $LANG['title_articles'],
		'L_CATEGORIES' => ($CAT_ARTICLES[$idartcat]['level'] >= 0) ? $LANG['sub_categories'] : $LANG['categories'],	
		'U_ARTICLES_CAT_LINKS' => trim($cat_links, ' &raquo;'),
		'U_ARTICLES_ALPHA_TOP' => url('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=desc'),
		'U_ARTICLES_ALPHA_BOTTOM' => url('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=asc'),
		'U_ARTICLES_DATE_TOP' => url('.php?sort=date&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=desc'),
		'U_ARTICLES_DATE_BOTTOM' => url('.php?sort=date&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=asc'),
		'U_ARTICLES_VIEW_TOP' => url('.php?sort=view&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=desc'),
		'U_ARTICLES_VIEW_BOTTOM' => url('.php?sort=view&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=asc'),
		'U_ARTICLES_NOTE_TOP' => url('.php?sort=note&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=desc'),
		'U_ARTICLES_NOTE_BOTTOM' => url('.php?sort=note&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=asc'),
		'U_ARTICLES_COM_TOP' => url('.php?sort=com&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=desc'),
		'U_ARTICLES_COM_BOTTOM' => url('.php?sort=com&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=asc')
	));		
	
	$get_sort = retrieve(GET, 'sort', '');	
	switch ($get_sort)
	{
		case 'alpha' : 
		$sort = 'title';
		break;		
		case 'date' : 
		$sort = 'timestamp';
		break;		
		case 'view' : 
		$sort = 'views';
		break;		
		case 'note' :
		$sort = 'note/' . $CONFIG_ARTICLES['note_max'];
		break;	
		case 'com' :
		$sort = 'nbr_com';
		break;			
		default :
		$sort = 'timestamp';
	}

	$get_mode = retrieve(GET, 'mode', '');	
	$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';	
	$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

	//On crée une pagination si le nombre de fichiers est trop important.
	include_once('../kernel/framework/util/pagination.class.php'); 
	$Pagination = new Pagination();

	//Catégories non autorisées.
	$unauth_cats_sql = array();
	foreach ($CAT_ARTICLES as $id => $key)
	{
		if (!$User->check_auth($CAT_ARTICLES[$id]['auth'], READ_CAT_ARTICLES))
			$unauth_cats_sql[] = $id;
	}
	$nbr_unauth_cats = count($unauth_cats_sql);
	$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND ac.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

	##### Catégories disponibles #####	
	if ($total_cat > 0)
	{
		$Template->assign_vars(array(			
			'C_ARTICLES_CAT' => true,
			'PAGINATION_CAT' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;pcat=%d', '-' . $idartcat . '-0+' . $rewrite_title . '.php?pcat=%d' . $unget), $total_cat , 'pcat', $CONFIG_ARTICLES['nbr_cat_max'], 3),
			'EDIT_CAT' => $is_admin ? '<a href="admin_articles_cat.php"><img class="valign_middle" src="../templates/' . get_utheme() .  '/images/' . get_ulang() . '/edit.png" alt="" /></a>' : ''
		));	
			
		$i = 0;	
		$result = $Sql->query_while("SELECT ac.id, ac.name, ac.contents, ac.icon, ac.nbr_articles_visible AS nbr_articles
		FROM ".PREFIX."articles_cats ac
		" . $clause_cat . $clause_unauth_cats . "
		ORDER BY ac.id_left
		" . $Sql->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_cat_max'], 'pcat'), $CONFIG_ARTICLES['nbr_cat_max']), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$Template->assign_block_vars('cat_list', array(
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'DESC' => $row['contents'],
				'ICON_CAT' => !empty($row['icon']) ? '<a href="articles' . url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a><br />' : '',
				'EDIT' => $is_admin ? '<a href="admin_articles_cat.php?id=' . $row['id'] . '"><img class="valign_middle" src="../templates/' . get_utheme() .  '/images/' . get_ulang() . '/edit.png" alt="" /></a>' : '',
				'L_NBR_ARTICLES' => sprintf($LANG['nbr_articles_info'], $row['nbr_articles']),
				'U_CAT' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php')
			));
		}
		$Sql->query_close($result);	
	}
	
	##### Affichage des articles #####	
	if ($nbr_articles > 0)
	{
		$Template->assign_vars(array(		
			'C_ARTICLES_LINK' => true,
			'PAGINATION' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;p=%d', '-' . $idartcat . '-0-%d+' . $rewrite_title . '.php' . $unget), $nbr_articles , 'p', $CONFIG_ARTICLES['nbr_articles_max'], 3),
			'CAT' => $CAT_ARTICLES[$idartcat]['name'],
			'EDIT' => ($is_admin && !empty($idartcat)) ? '<a href="admin_articles_cat.php?id=' . $idartcat . '"><img class="valign_middle" src="../templates/' . get_utheme() .  '/images/' . get_ulang() . '/edit.png" alt="" /></a>' : ''
		));

		include_once('../kernel/framework/content/note.class.php');
		$Note = new Note(null, null, null, null, '', NOTE_NO_CONSTRUCT);
		$result = $Sql->query_while("SELECT id, title, icon, timestamp, views, note, nbrnote, nbr_com
		FROM ".PREFIX."articles
		WHERE visible = 1 AND idcat = '" . $idartcat .	"' 
		ORDER BY " . $sort . " " . $mode . 
		$Sql->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			//On reccourci le lien si il est trop long.
			$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

			$Template->assign_block_vars('articles', array(			
				'NAME' => $fichier,
				'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
				'CAT' => $CAT_ARTICLES[$idartcat]['name'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'COMPT' => $row['views'],
				'NOTE' => ($row['nbrnote'] > 0) ? $Note->display_img($row['note'], $CONFIG_ARTICLES['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>',
				'COM' => $row['nbr_com'],
				'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php')
			));

		}
		$Sql->query_close($result);
	}
	 
	$Template->pparse('articles_cat');
}
			
require_once('../kernel/footer.php'); 

?>