<?php
/*##################################################
 *                      admin_articles_management.php
 *                            -------------------
 *   begin                : July 10, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('articles'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('articles_constants.php');
//On recupère les variables.
$id = retrieve(GET, 'id', 0);
$idcat = retrieve(GET, 'idcat', 0);
$id_post = retrieve(POST, 'id', 0);
$del = !empty($_GET['delete']) ? true : false;



if ($del && !empty($id)) //Suppression de l'article.
{    
	$Session->csrf_get_protect(); //Protection csrf
	
	//Visibilité de l'article.
	$visible = $Sql->query("SELECT visible FROM " . DB_TABLE_ARTICLES. " WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	
	//On supprime dans la bdd.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_ARTICLES . " WHERE id = '" . $id . "'", __LINE__, __FILE__);	
	
	$Cache->load('articles');
	if (empty($idcat))//Racine.
	{
		$CAT_ARTICLES[0]['id_left'] = 0;
		$CAT_ARTICLES[0]['id_right'] = 0;
	}
	
	//Mise à jours du nombre d'articles des parents.
	$clause_update = ($visible == '1') ? 'nbr_articles_visible = nbr_articles_visible - 1' : 'nbr_articles_unvisible = nbr_articles_unvisible - 1';
	$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES_CAT. " SET " . $clause_update . " WHERE id_left <= '" . $CAT_ARTICLES[$idcat]['id_left'] . "' AND id_right >= '" . $CAT_ARTICLES[$idcat]['id_right'] . "'", __LINE__, __FILE__);
	
	//On supprimes les éventuels commentaires associés.
	$Sql->query_inject("DELETE FROM " . DB_TABLE_COM . " WHERE idprov = " . $id . " AND script = 'articles'", __LINE__, __FILE__);
	
	// Feeds Regeneration
    import('content/syndication/feed');
    Feed::clear_cache('articles');
    
	redirect(HOST . SCRIPT);
}	
elseif (!empty($id))
{
	$tpl = new Template('articles/admin_articles_management.tpl');
	require_once('admin_articles_menu.php');
	$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));
	

	$articles = $Sql->query_array(DB_TABLE_ARTICLES, '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);	

	$tpl->assign_vars(array(	
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_EDIT_ARTICLE' => $LANG['edit_article'],
		'L_REQUIRE' => $LANG['require'],
		'L_PAGE_PROMPT' => $LANG['page_prompt'],
		'L_CATEGORY' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_ARTICLE_ICON' => $LANG['article_icon'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_VIEWS' => $LANG['views'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_ARTICLES_DATE' => $LANG['articles_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_TEXT' => $LANG['content'],
		'L_EXPLAIN_PAGE' => $LANG['explain_page'],
		'L_PREVIEW' => $LANG['preview'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
		
	//Catégories.
	$categories = '<option value="0">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, c_order, name 
	FROM " . DB_TABLE_ARTICLES_CAT . "
	ORDER BY id_parent", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['c_order'] > 0) ? str_repeat('--------', $row['c_order']) : '--';
		$selected = ($row['id'] == $articles['idcat']) ? 'selected="selected"' : '';
		$categories .= '<option value="' . $row['id'] . '" ' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
	}
	$Sql->query_close($result);
	
	//Images disponibles
	$img_direct_path = (strpos($articles['icon'], '/') !== false);
	$rep = './';
	$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
	if (is_dir($rep)) //Si le dossier existe
	{
		$img_array = array();
		$dh = @opendir( $rep);
		while (! is_bool($lang = readdir($dh)))
		{
			if (preg_match('`\.(gif|png|jpg|jpeg|tiff)+$`i', $lang))
				$img_array[] = $lang; //On crée un tableau, avec les different fichiers.
		}
		closedir($dh); //On ferme le dossier

		foreach ($img_array as $key => $img_path)
		{	
			$selected = $img_path == $articles['icon'] ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $img_path . '"' . ($img_direct_path ? '' : $selected) . '>' . $img_path . '</option>';
		}
	}

	$tpl->assign_block_vars('articles', array(
		'TITLE' => $articles['title'],
		'IMG_ICON' => !empty($articles['icon']) ? '<img src="' . $articles['icon'] . '" alt="" class="valign_middle" />' : '',
		'IMG_LIST' => $image_list,
		'IMG_PATH' => $img_direct_path ? $articles['icon'] : '',
		'IDARTICLES' => $articles['id'],
		'USER_ID' => $articles['user_id'],
		'CATEGORIES' => $categories,
		'CONTENTS' => unparse($articles['contents']),
		'CURRENT_DATE' => gmdate_format('date_format_short', $articles['timestamp']),
		'DAY_RELEASE_S' => !empty($articles['start']) ? gmdate_format('d', $articles['start']) : '',
		'MONTH_RELEASE_S' => !empty($articles['start']) ? gmdate_format('m', $articles['start']) : '',
		'YEAR_RELEASE_S' => !empty($articles['start']) ? gmdate_format('Y', $articles['start']) : '',
		'DAY_RELEASE_E' => !empty($articles['end']) ? gmdate_format('d', $articles['end']) : '',
		'MONTH_RELEASE_E' => !empty($articles['end']) ? gmdate_format('m', $articles['end']) : '',
		'YEAR_RELEASE_E' => !empty($articles['end']) ? gmdate_format('Y', $articles['end']) : '',
		'DAY_DATE' => !empty($articles['timestamp']) ? gmdate_format('d', $articles['timestamp']) : '',
		'MONTH_DATE' => !empty($articles['timestamp']) ? gmdate_format('m', $articles['timestamp']) : '',
		'YEAR_DATE' => !empty($articles['timestamp']) ? gmdate_format('Y', $articles['timestamp']) : '',
		'USER_ID' => $articles['user_id'],
		'VISIBLE_WAITING' => (($articles['visible'] == 2 || !empty($articles['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($articles['visible'] == 1 && empty($articles['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($articles['visible'] == 0) ? 'checked="checked"' : ''),
		'START' => ((!empty($articles['start'])) ? gmdate_format('date_format_short', $articles['start']) : ''),
		'END' => ((!empty($articles['end'])) ? gmdate_format('date_format_short', $articles['end']) : ''),
		'HOUR' => gmdate_format('H', $articles['timestamp']),
		'MIN' => gmdate_format('i', $articles['timestamp']),
		'DATE' => gmdate_format('date_format_short', $articles['timestamp'])	
	));
	
	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	
	$tpl->parse();
}	
elseif (!empty($_POST['previs']) && !empty($id_post))
{

	$tpl = new Template('articles/admin_articles_management.tpl');
	
	$title = retrieve(POST, 'title', '', TSTRING_UNCHANGE);
	$icon = retrieve(POST, 'icon', '', TSTRING_UNCHANGE);
	$icon_path = retrieve(POST, 'icon_path', '', TSTRING_UNCHANGE);
	$compt = retrieve(POST, 'views', 0);
	$contents = retrieve(POST, 'contents', '', TSTRING_AS_RECEIVED);
	$contents_preview = retrieve(POST, 'contents', '', TSTRING_UNCHANGE);
	$user_id = retrieve(POST, 'user_id', 0);
	$idcat = retrieve(POST, 'idcat', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);	
	$get_visible = retrieve(POST, 'visible', 0);
	$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
	$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
	$current_date_timestamp = strtotimestamp($current_date, $LANG['date_format_short']);
	
	if (!empty($icon_path))
		$icon = $icon_path;	
	
    $visible = 0;
	switch ($get_visible)
	{
        case 1:
            $visible = 1;
            break;
        case 2:
    		if ($start_timestamp > time())
    			$visible = 2;
    		else
    			$start = '';
    	
    		if ($end_timestamp > time() && $end_timestamp > $start_timestamp)
    			$visible = 2;
    		else
    			$end = '';
            break;
        default:
            break;
	}
	
	//Catégories.	
	$i = 0;	
	$categories = '<option value="0">' . $LANG['root'] . '</option>';
	$result = $Sql->query_while("SELECT id, level, name 
	FROM " . DB_TABLE_ARTICLES_CAT . "
	ORDER BY id_left", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$categories .= '<option value="' . $row['id'] . '" ' . $selected . '>' . $margin . ' ' . $row['name'] . '</option>';
		$i++;
	}
	$Sql->query_close($result);
	
	//Images disponibles
	$img_direct_path = (strpos($icon, '/') !== false);
	$rep = './';
	$image_list = '<option value=""' . ($img_direct_path ? ' selected="selected"' : '') . '>--</option>';
	if (is_dir($rep)) //Si le dossier existe
	{
		$img_array = array();
		$dh = @opendir( $rep);
		while (! is_bool($lang = readdir($dh)))
		{	
			if (preg_match('`\.(gif|png|jpg|jpeg|tiff)+$`i', $lang))
				$img_array[] = $lang; //On crée un tableau, avec les different fichiers.
		}
		closedir($dh); //On ferme le dossier

		foreach ($img_array as $key => $img_path)
		{
			$selected = $img_path == $icon ? ' selected="selected"' : '';
			$image_list .= '<option value="' . $img_path . '"' . ($img_direct_path ? '' : $selected) . '>' . $img_path . '</option>';
		}
	}
	
	$tpl->assign_block_vars('articles', array(
		'IDARTICLES' => $id_post,
		'TITLE' => $title,
		'CATEGORIES' => $categories,
		'IMG_PATH' => $img_direct_path ? $icon : '',
		'IMG_ICON' => !empty($icon) ? '<img src="' . $icon . '" alt="" class="valign_middle" />' : '',
		'IMG_LIST' => $image_list,
		'CONTENTS' => $contents_preview,
		'USER_ID' => $user_id,
		'CURRENT_DATE' => $current_date,
		'START' => ((!empty($start) && $visible == 2) ? $start : ''),
		'END' => ((!empty($end) && $visible == 2) ? $end : ''),
		'HOUR' => $hour,
		'MIN' => $min,
		'DAY_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('d', $start_timestamp) : '',
		'MONTH_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('m', $start_timestamp) : '',
		'YEAR_RELEASE_S' => !empty($start_timestamp) ? gmdate_format('Y', $start_timestamp) : '',
		'DAY_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('d', $end_timestamp) : '',
		'MONTH_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('m', $end_timestamp) : '',
		'YEAR_RELEASE_E' => !empty($end_timestamp) ? gmdate_format('Y', $end_timestamp) : '',
		'DAY_DATE' => !empty($current_date_timestamp) ? gmdate_format('d', $current_date_timestamp) : '',
		'MONTH_DATE' => !empty($current_date_timestamp) ? gmdate_format('m', $current_date_timestamp) : '',
		'YEAR_DATE' => !empty($current_date_timestamp) ? gmdate_format('Y', $current_date_timestamp) : '',
		'VISIBLE_WAITING' => (($visible == 2) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($visible == 1) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($visible == 0) ? 'checked="checked"' : '')
	));
	
	$pseudo = $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	$tpl->assign_block_vars('articles.preview', array(
		'USER_ID' => $user_id,
		'TITLE' => $title,
		'CONTENTS' => second_parse(stripslashes(strparse($contents))),
		'PSEUDO' => $pseudo,
		'DATE' => gmdate_format('date_format_short')
	));
	
	$tpl->assign_vars(array(	
		'KERNEL_EDITOR' => display_editor(),
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_TEXT' => $LANG['require_text'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_ARTICLES_ADD' => $LANG['articles_add'],
		'L_ARTICLES_CAT' => $LANG['cat_management'],
		'L_ARTICLES_CONFIG' => $LANG['articles_config'],
		'L_ARTICLES_CAT_ADD' => $LANG['articles_cats_add'],
		'L_PREVIEW' => $LANG['preview'],
		'L_COM' => $LANG['com'],
		'L_WRITTEN_BY' => $LANG['written_by'],
		'L_ON' => $LANG['on'],
		'L_EDIT_ARTICLE' => $LANG['edit_article'],
		'L_REQUIRE' => $LANG['require'],
		'L_PAGE_PROMPT' => $LANG['page_prompt'],
		'L_CATEGORY' => $LANG['category'],
		'L_TITLE' => $LANG['title'],
		'L_ARTICLE_ICON' => $LANG['article_icon'],
		'L_OR_DIRECT_PATH' => $LANG['or_direct_path'],
		'L_VIEWS' => $LANG['views'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_ARTICLES_DATE' => $LANG['articles_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_TEXT' => $LANG['content'],
		'L_EXPLAIN_PAGE' => $LANG['explain_page'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset']
	));
	
	$tpl->parse();
}
elseif (!empty($_POST['valid']) && !empty($id_post)) //inject
{
	$title = retrieve(POST, 'title', '');
	$icon = retrieve(POST, 'icon', '');
	$icon_path = retrieve(POST, 'icon_path', '');
	$contents = retrieve(POST, 'contents', '', TSTRING_PARSE);
	$idcat = retrieve(POST, 'idcat', 0);
	$current_date = retrieve(POST, 'current_date', '', TSTRING_UNCHANGE);
	$start = retrieve(POST, 'start', '', TSTRING_UNCHANGE);
	$end = retrieve(POST, 'end', '', TSTRING_UNCHANGE);
	$hour = retrieve(POST, 'hour', '', TSTRING_UNCHANGE);
	$min = retrieve(POST, 'min', '', TSTRING_UNCHANGE);	
	$get_visible = retrieve(POST, 'visible', 0);
	
	if (!empty($icon_path))
		$icon = $icon_path;
			
	//On met à jour la config de base du sondage
	if (!empty($title) && !empty($contents))
	{
		$start_timestamp = strtotimestamp($start, $LANG['date_format_short']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format_short']);
		
		$visible = 1;		
		if ($get_visible == 2)
		{	
			if ($start_timestamp > time())
				$visible = 2;
			elseif ($start_timestamp == 0)
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if ($end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0)
				$visible = 2;
			elseif ($start_timestamp != 0) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif ($get_visible == 1)
		{	
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		else
		{	
			$visible = 0;
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		
		$timestamp = strtotimestamp($current_date, $LANG['date_format_short']);
		if ($timestamp > 0)
			//Ajout des heures et minutes
			$timestamp += ($hour * 3600) + ($min * 60);
		else
			$timestamp = time();
		
		$cat_clause = ' ';
		//Changement de catégorie parente?
		$articles_info = $Sql->query_array(DB_TABLE_ARTICLES, "id", "idcat", "visible", "WHERE id = '" . $id_post . "'", __LINE__, __FILE__);		
		if ($articles_info['idcat'] != $idcat && !empty($articles_info['id']))
		{
			if ($articles_info['visible'] == 1)
				$is_visible = 'nbr_articles_visible';
			else
				$is_visible = 'nbr_articles_unvisible';
			$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES_CAT . " SET " . $is_visible . " = " . $is_visible . " - 1 WHERE id = '" . $articles_info['idcat'] . "'", __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES_CAT . " SET " . $is_visible . " = " . $is_visible . " + 1 WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
			$cat_clause = " idcat = '" . $idcat . "', ";
		}
		
		$Sql->query_inject("UPDATE " . DB_TABLE_ARTICLES . " SET" . $cat_clause . "title = '" . $title . "', contents = '" . str_replace('[page][/page]', '', $contents) . "', icon = '" . $icon . "', visible = '" . $visible . "', start = '" .  $start_timestamp . "', end = '" . $end_timestamp . "', timestamp = '" . $timestamp . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);
		
		// Feeds Regeneration
        import('content/syndication/feed');
        Feed::clear_cache('articles');
		
		redirect(HOST . SCRIPT);
	}
	else
		redirect(HOST . DIR . '/articles/admin_articles.php?id= ' . $id_post . '&error=incomplete#errorh');
}		
else
{
	$tpl = new Template('articles/admin_articles_management.tpl');
	require_once('admin_articles_menu.php');
	$tpl->assign_vars(array('ADMIN_MENU' => $admin_menu));

	
	$nbr_articles = $Sql->count_table('articles', __LINE__, __FILE__);
	
	//On crée une pagination si le nombre d'articles est trop important.
	import('util/pagination');
	$Pagination = new Pagination();
	
	$tpl->assign_vars(array(
		'THEME' => get_utheme(),
		'LANG' => get_ulang(),
		'PAGINATION' => $Pagination->display('admin_articles.php?p=%d', $nbr_articles, 'p', 25, 3),
		'CHEMIN' => SCRIPT,
		'L_CONFIRM_DEL_ARTICLE' => $LANG['confirm_del_article'],
		'L_ARTICLES_MANAGEMENT' => $LANG['articles_management'],
		'L_NAME' => $LANG['name'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_DATE' => $LANG['date'],
		'L_APROB' => $LANG['aprob'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
		'L_SHOW' => $LANG['show']
	));

	$tpl->assign_block_vars('list', array(
	));
	
	$result = $Sql->query_while("SELECT a.id, a.idcat, a.title, a.timestamp, a.visible, a.start, a.end, ac.name, m.login 
	FROM " . DB_TABLE_ARTICLES . " a
	LEFT JOIN " . DB_TABLE_ARTICLES_CAT . " ac ON ac.id = a.idcat
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON a.user_id = m.user_id
	ORDER BY a.timestamp DESC " .
	$Sql->limit($Pagination->get_first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		if ($row['visible'] == 2)
			$aprob = $LANG['waiting'];
		elseif ($row['visible'] == 1)
			$aprob = $LANG['yes'];
		else
			$aprob = $LANG['no'];

		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$title = strlen($row['title']) > 45 ? substr_html($row['title'], 0, 45) . '...' : $row['title'];

		$visible = '';
		if ($row['start'] > 0)
			$visible .= gmdate_format('date_format_short', $row['start']);
		if ($row['end'] > 0 && $row['start'] > 0)
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . gmdate_format('date_format_short', $row['end']);
		elseif ($row['end'] > 0)
			$visible .= $LANG['until'] . ' ' . gmdate_format('date_format_short', $row['end']);
		
		$tpl->assign_block_vars('list.articles', array(
			'TITLE' => $title,
			'IDCAT' => $row['idcat'],
			'ID' => $row['id'],
			'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],
			'DATE' => gmdate_format('date_format_short', $row['timestamp']),
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : ''),
			'U_CAT' => '<a href="../articles/articles' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '">' . (!empty($row['idcat']) ? $row['name'] : '<em>' . $LANG['root'] . '</em>') . '</a>'
		));
	}
	$Sql->query_close($result);
	
	$tpl->parse();
}

require_once('../admin/admin_footer.php');

?>