<?php
/*##################################################
 *                              articles_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
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

if (defined('PHPBOOST') !== true) exit;

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

define('ARTICLES_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ArticlesInterface extends ModuleInterface
{
	## Public Methods ##
	function ArticlesInterface() //Constructeur de la classe ForumInterface
	{
		parent::ModuleInterface('articles');
	}

	//Récupération du cache.
	function get_cache()
	{
		global $Sql;

		$config_articles = 'global $CONFIG_ARTICLES;' . "\n";

		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_ARTICLES = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'articles'", __LINE__, __FILE__));
		$CONFIG_ARTICLES = is_array($CONFIG_ARTICLES) ? $CONFIG_ARTICLES : array();

		if (isset($CONFIG_ARTICLES['auth_root']))
		$CONFIG_ARTICLES['auth_root'] = unserialize($CONFIG_ARTICLES['auth_root']);

		$config_articles .= '$CONFIG_ARTICLES = ' . var_export($CONFIG_ARTICLES, true) . ';' . "\n";

		$cat_articles = 'global $CAT_ARTICLES;' . "\n";
		$result = $Sql->query_while("SELECT id, id_left, id_right, level, name, aprob, auth
		FROM " . PREFIX . "articles_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (empty($row['auth']))
			$row['auth'] = serialize(array());

			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
		}
		$Sql->query_close($result);

		return $config_articles . "\n" . $cat_articles;
	}

	//Changement de jour.
	function on_changeday()
	{
		global $Sql;

		//Publication des articles en attente pour la date donnée.
		$result = $Sql->query_while("SELECT id, start, end
		FROM " . PREFIX . "articles
		WHERE visible != 0", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['start'] <= time() && $row['start'] != 0)
			$Sql->query_inject("UPDATE " . PREFIX . "articles SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($row['end'] <= time() && $row['end'] != 0)
			$Sql->query_inject("UPDATE " . PREFIX . "articles SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}

	function get_search_request($args = null)
	{
		global $Sql, $Cache, $CONFIG_ARTICLES, $CAT_ARTICLES, $User, $LANG;
		$Cache->load('articles');
		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($CAT_ARTICLES as $idcat => $key)
		{
			if ($CAT_ARTICLES[$idcat]['aprob'] == 1)
			{
				if (!$User->check_auth($CAT_ARTICLES[$idcat]['auth'], READ_CAT_ARTICLES))
				{
					$clause_level = !empty($g_idcat) ? ($CAT_ARTICLES[$idcat]['level'] == ($CAT_ARTICLES[$g_idcat]['level'] + 1)) : ($CAT_ARTICLES[$idcat]['level'] == 0);
					if ($clause_level)
					$unauth_cats_sql[] = $idcat;
				}
			}
		}
		$clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

		$request = "SELECT
				" . $args['id_search'] . " AS id_search,
	             a.id AS id_content,
	             a.title AS title,
	             ( 2 * MATCH(a.title) AGAINST('" . $args['search'] . "') + MATCH(a.contents) AGAINST('" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
	             . $Sql->concat("'" . PATH_TO_ROOT . "/articles/articles.php?id='","a.id","'&amp;cat='","a.idcat") . " AS link
            FROM " . PREFIX . "articles a
            LEFT JOIN " . PREFIX . "articles_cats ac ON ac.id = a.idcat
            WHERE
            	a.visible = 1 AND ((ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%') OR a.idcat = 0)
            	AND (MATCH(a.title) AGAINST('" . $args['search'] . "') OR MATCH(a.contents) AGAINST('" . $args['search'] . "'))
            ORDER BY relevance DESC " . $Sql->limit(0, $CONFIG_ARTICLES['nbr_articles_max']);

	             return $request;
	}

	/**
	 * @desc Returns an ordered tree with all categories informations
	 * @return array[] an ordered tree with all categories informations
	 */
	function _get_cats_tree()
	{
		global $LANG, $CAT_ARTICLES;
		Cache::load('articles');

		if (!(isset($CAT_ARTICLES) && is_array($CAT_ARTICLES)))
		{
			$CAT_ARTICLES = array();
		}
		
        $ordered_cats = array();
		foreach ($CAT_ARTICLES as $id => $cat)
		{   // Sort by id_left
			$cat['id'] = $id;
			$ordered_cats[numeric($cat['id_left'])] = array('this' => $cat, 'children' => array());
		}

		$level = 0;
		$cats_tree = array(array('this' => array('id' => 0, 'name' => $LANG['root']), 'children' => array()));
		$parent =& $cats_tree[0]['children'];
		$nb_cats = count($CAT_ARTICLES);
		foreach ($ordered_cats as $cat)
		{
			if (($cat['this']['level'] == $level + 1) && count($parent) > 0)
			{   // The new parent is the previous cat
				$parent =& $parent[count($parent) - 1]['children'];
			}
			elseif ($cat['this']['level'] < $level)
			{   // Find the new parent (an ancestor)
				$j = 0;
				$parent =& $cats_tree[0]['children'];
				while ($j < $cat['this']['level'])
				{
					$parent =& $parent[count($parent) - 1]['children'];
					$j++;
				}
			}

			// Add the current cat at the good level
			$parent[] = $cat;
			$level = $cat['this']['level'];
		}
		return $cats_tree[0];
	}


	function _feeds_add_category(&$cat_tree, &$category)
	{
		$child = new FeedsCat('articles', $category['this']['id'], $category['this']['name']);
		foreach ($category['children'] as $sub_category)
		{
			ArticlesInterface::_feeds_add_category($child, $sub_category);
		}
		$cat_tree->add_child($child);
	}

	function get_feeds_list()
	{
		global $LANG;
		$feed = array();
		$categories = $this->_get_cats_tree();

		import('content/syndication/feeds_list');

		$cat_tree = new FeedsCat('articles', 0, $LANG['root']);

		ArticlesInterface::_feeds_add_category($cat_tree, $categories);

		$children = $cat_tree->get_children();
		$feeds = new FeedsList();
		if (count($children) > 0)
		{
			$feeds->add_feed($children[0], DEFAULT_FEED_NAME);
		}
		return $feeds;
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_ARTICLES, $CAT_ARTICLES;
		$Cache->load('articles');

		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');
		import('content/syndication/feed_data');
		import('util/date');
		import('util/url');

		$data = new FeedData();

		$data->set_title($LANG['xml_articles_desc']);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=articles&amp;cat=' . $idcat));
		$data->set_host(HOST);
		$data->set_desc($LANG['xml_articles_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(READ_CAT_ARTICLES);

		$cat_clause = !empty($idcat) ? ' AND a.idcat = ' . $idcat : '';
		$result = $Sql->query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp, a.icon, ac.auth
        FROM " . PREFIX . "articles a
        LEFT JOIN " . PREFIX . "articles_cats ac ON ac.id = a.idcat
        WHERE a.visible = 1 AND (ac.aprob = 1 OR a.idcat = 0) " . $cat_clause . "
        ORDER BY a.timestamp DESC
        " . $Sql->limit(0, 2 * $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);

		// Generation of the feed's items
		while ($row = $Sql->fetch_assoc($result))
		{
			$item = new FeedItem();

			$link = new Url('/articles/articles' . url(
                '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'],
                '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php'
                ));

            $item->set_title($row['title']);
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(preg_replace('`\[page\](.+)\[/page\]`U', '<br /><strong>$1</strong><hr />', second_parse($row['contents'])));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
            $item->set_image_url($row['icon']);
            $item->set_auth($row['idcat'] == 0 ? $CONFIG_ARTICLES['auth_root'] : unserialize($row['auth']));

            $data->add_item($item);
		}
		$Sql->query_close($result);

		return $data;
	}

	function get_cat()
	{
		global $Sql;

		$result = $Sql->query_while("SELECT *
	            FROM " . PREFIX . "articles_cats", __LINE__, __FILE__);
		$data = array();
		while ($row = $Sql->fetch_assoc($result)) {
			$data[$row['id']] = $row['name'];
		}
		$Sql->query_close($result);
		return $data;
	}

	function get_home_page()
	{
		global $Sql, $idartcat, $User, $Cache, $Bread_crumb, $Errorh, $CAT_ARTICLES, $CONFIG_ARTICLES, $LANG;
		require_once('../articles/articles_begin.php');

		$tpl = new Template('articles/articles_cat.tpl');

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

		$nbr_articles = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "articles WHERE visible = 1 AND idcat = '" . $idartcat . "'", __LINE__, __FILE__);
		$total_cat = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "articles_cats ac " . $clause_cat, __LINE__, __FILE__);
			
		$rewrite_title = url_encode_rewrite($CAT_ARTICLES[$idartcat]['name']);

		//Colonnes des catégories.
		$nbr_column_cats = ($total_cat > $CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : $total_cat;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);

		$is_admin = $User->check_level(ADMIN_LEVEL) ? true : false;
		$tpl->assign_vars(array(
			'IDCAT' => $idartcat,
			'C_IS_ADMIN' => $is_admin,
			'COLUMN_WIDTH_CAT' => $column_width_cats,
			'ADD_ARTICLES' => $is_admin ? (!empty($idartcat) ? '&raquo; ' : '') . '<a href="../articles/admin_articles_add.php"><img src="../templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" alt="" class="valign_middle" /></a>' : '',
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
		import('util/pagination');
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
			$tpl->assign_vars(array(
				'C_ARTICLES_CAT' => true,
				'PAGINATION_CAT' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;pcat=%d', '-' . $idartcat . '-0+' . $rewrite_title . '.php?pcat=%d' . $unget), $total_cat , 'pcat', $CONFIG_ARTICLES['nbr_cat_max'], 3)
			));

			$i = 0;
			$result = $Sql->query_while("SELECT ac.id, ac.name, ac.contents, ac.icon, ac.nbr_articles_visible AS nbr_articles
			FROM " . PREFIX . "articles_cats ac
			" . $clause_cat . $clause_unauth_cats . "
			ORDER BY ac.id_left
			" . $Sql->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_cat_max'], 'pcat'), $CONFIG_ARTICLES['nbr_cat_max']), __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$tpl->assign_block_vars('cat_list', array(
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
			$tpl->assign_vars(array(
				'C_ARTICLES_LINK' => true,
				'PAGINATION' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;p=%d', '-' . $idartcat . '-0-%d+' . $rewrite_title . '.php' . $unget), $nbr_articles , 'p', $CONFIG_ARTICLES['nbr_articles_max'], 3),
				'CAT' => $CAT_ARTICLES[$idartcat]['name']
			));

			import('content/note');
			$result = $Sql->query_while("SELECT id, title, icon, timestamp, views, note, nbrnote, nbr_com
			FROM " . PREFIX . "articles
			WHERE visible = 1 AND idcat = '" . $idartcat .	"'
			ORDER BY " . $sort . " " . $mode .
			$Sql->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//On reccourci le lien si il est trop long.
				$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

				$tpl->assign_block_vars('articles', array(
					'NAME' => $row['title'],
					'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
					'CAT' => $CAT_ARTICLES[$idartcat]['name'],
					'DATE' => gmdate_format('date_format_short', $row['timestamp']),
					'COMPT' => $row['views'],
					'NOTE' => ($row['nbrnote'] > 0) ? Note::display_img($row['note'], $CONFIG_ARTICLES['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>',
					'COM' => $row['nbr_com'],
					'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php')
				));

			}
			$Sql->query_close($result);
		}
			
		return $tpl->parse(TRUE);
	}
}

?>