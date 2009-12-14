<?php
/*##################################################
 *                              articles_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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


define('ARTICLES_MAX_SEARCH_RESULTS', 100);
require_once PATH_TO_ROOT . '/articles/articles_constants.php';

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ArticlesInterface extends ModuleInterface
{
	## Public Methods ##
	function ArticlesInterface() //Constructeur de la classe ForumInterface
	{
		parent::__construct('articles');
	}

	//Récupération du cache.
	function get_cache()
	{
		$config_articles = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'articles'", __LINE__, __FILE__));
		
		$string = 'global $CONFIG_ARTICLES, $ARTICLES_CAT;' . "\n\n" . '$CONFIG_ARTICLES = $ARTICLES_CAT = array();' . "\n\n";
		$string .= '$CONFIG_ARTICLES = ' . var_export($config_articles, true) . ';' . "\n\n";

		//List of categories and their own properties
		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, description,id_models,tpl_cat
			FROM " . DB_TABLE_ARTICLES_CAT . "
			ORDER BY id_parent, c_order", __LINE__, __FILE__);

		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$string .= '$ARTICLES_CAT[' . $row['id'] . '] = ' .
			var_export(array(
					'id_parent' => (int)$row['id_parent'],
					'order' => (int)$row['c_order'],
					'name' => $row['name'],
					'desc' => $row['description'],
					'visible' => (bool)$row['visible'],
					'image' => !empty($row['image']) ? $row['image'] : '/articles/articles.png',
					'description' => $row['description'],
					'auth' => !empty($row['auth']) ? unserialize($row['auth']) : $config_articles['global_auth'],
					'models'=>$row['id_models'],
					'tpl_cat'=>$row['tpl_cat']
			), true) . ';' . "\n\n";
		}
		return $string;

	}

	//Changement de jour.
	function on_changeday()
	{
		//Publication des articles en attente pour la date donnée.
		$result = $this->sql_querier->query_while("SELECT id, start, end
		FROM " . DB_TABLE_ARTICLES . "
		WHERE visible != 0", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			if ($row['start'] <= time() && $row['start'] != 0)
			$this->sql_querier->query_inject("UPDATE " . DB_TABLE_ARTICLES . " SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($row['end'] <= time() && $row['end'] != 0)
			$this->sql_querier->query_inject("UPDATE " . DB_TABLE_ARTICLES . " SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}

	function get_search_request($args = null)
	{
		global $Cache, $CONFIG_ARTICLES, $ARTICLES_CAT, $User, $LANG,$ARTICLES_LANG;
		$Cache->load('articles');
		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');

		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($ARTICLES_CAT as $idcat => $key)
		{
			if ($ARTICLES_CAT[$idcat]['visible'] == 1)
			{
				if (!$User->check_auth($ARTICLES_CAT[$idcat]['auth'], AUTH_ARTICLES_READ))
				{
					$clause_level = !empty($g_idcat) ? ($ARTICLES_CAT[$idcat]['order'] == ($ARTICLES_CAT[$g_idcat]['order'] + 1)) : ($ARTICLES_CAT[$idcat]['order'] == 0);
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
	             ( 2 * FT_SEARCH_RELEVANCE(a.title, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(a.contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
	             . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/articles/articles.php?id='","a.id","'&amp;cat='","a.idcat") . " AS link
				FROM " . PREFIX . "articles a
				LEFT JOIN " . PREFIX . "articles_cats ac ON ac.id = a.idcat
				WHERE
					a.visible = 1 AND ((ac.visible = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%') OR a.idcat = 0)
					AND (FT_SEARCH(a.title, '" . $args['search'] . "') OR FT_SEARCH(a.contents, '" . $args['search'] . "'))
				ORDER BY relevance DESC " . $this->sql_querier->limit(0, $CONFIG_ARTICLES['nbr_articles_max']);

	             return $request;
	}

    function get_feeds_list()
	{
        $articles_cats = new ArticlesCats();
        return $articles_cats->get_feeds_list();
	}
	
	function get_feed_data_struct($idcat = 0, $name = '')
	{

		global $Cache, $LANG, $CONFIG, $CONFIG_ARTICLES, $ARTICLES_CAT,$ARTICLES_LANG;

		$Cache->load('articles');

		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');

		
		
		

		$data = new FeedData();

		$data->set_title($ARTICLES_LANG['xml_articles_desc']);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=articles&amp;cat=' . $idcat));
		$data->set_host(HOST);
		$data->set_desc($ARTICLES_LANG['xml_articles_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(AUTH_ARTICLES_READ);

		$cat_clause = !empty($idcat) ? " AND a.idcat = '". $idcat . "'" : '';
		$result = $this->sql_querier->query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp, a.icon, ac.auth
        FROM " . DB_TABLE_ARTICLES . " a
        LEFT JOIN " . DB_TABLE_ARTICLES_CAT . " ac ON ac.id = a.idcat
        WHERE a.visible = 1 AND (ac.visible = 1 OR a.idcat = 0) " . $cat_clause . "
        ORDER BY a.timestamp DESC
        " . $this->sql_querier->limit(0, 2 * $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);

		// Generation of the feed's items
		while ($row = $this->sql_querier->fetch_assoc($result))
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
                $item->set_auth($row['idcat'] == 0 ? $CONFIG_ARTICLES['global_auth'] : unserialize($row['auth']));

                $data->add_item($item);
		}
		$this->sql_querier->query_close($result);

		return $data;
	}

	function get_cat()
	{
		$result = $this->sql_querier->query_while("SELECT *
	            FROM " . DB_TABLE_ARTICLES_CAT, __LINE__, __FILE__);
		$data = array();
		while ($row = $this->sql_querier->fetch_assoc($result)) {
			$data[$row['id']] = $row['name'];
		}
		$this->sql_querier->query_close($result);
		return $data;
	}

	function get_home_page()
	{
		global $idartcat, $Session,$User,$invisible, $Cache, $Bread_crumb, $Errorh, $ARTICLES_CAT, $CONFIG_ARTICLES,$CONFIG, $LANG,$ARTICLES_LANG;
		require_once('../articles/articles_begin.php');

		if ($idartcat > 0)
		{
			if (!isset($ARTICLES_CAT[$idartcat]) || $ARTICLES_CAT[$idartcat]['visible'] == 0)
			$Errorh->handler('e_auth', E_USER_REDIRECT);

			$cat_links = '';
			$cat_links .= ' <a href="articles' . url('.php?cat=' . $idartcat, '-' . $idartcat . '.php') . '">' . $ARTICLES_CAT[$idartcat]['name'] . '</a>';
			$clause_cat = " WHERE ac.id_parent = '" . $idartcat . "'  AND ac.visible = 1";		
		}
		else //Racine.
		{	
			$cat_links = ' <a href="articles.php">' . $ARTICLES_LANG['title_articles'] . '</a>';
			$clause_cat = " WHERE ac.id_parent = '0' AND ac.visible = 1";
		}

		$models = $this->sql_querier->query_array(DB_TABLE_ARTICLES_MODEL, '*', "WHERE id = '" . $ARTICLES_CAT[$idartcat]['models'] . "'", __LINE__, __FILE__);

		$tpl = new Template('articles/'.$ARTICLES_CAT[$idartcat]['tpl_cat']);

		//Niveau d'autorisation de la catégorie
		if (!isset($ARTICLES_CAT[$idartcat]) || !$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ))
		$Errorh->handler('e_auth', E_USER_REDIRECT);
			
		$nbr_articles = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE visible = 1 AND idcat = '" . $idartcat . "'", __LINE__, __FILE__);
		$nbr_articles_invisible = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE visible = 0 AND idcat = '" . $idartcat . "' AND user_id != -1", __LINE__, __FILE__);
		$total_cat = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES_CAT . " ac " . $clause_cat, __LINE__, __FILE__);
			
		$rewrite_title = url_encode_rewrite($ARTICLES_CAT[$idartcat]['name']);

		$get_sort = retrieve(GET, 'sort', '');
		$get_mode = retrieve(GET, 'mode', '');
		$selected_fields = array(
			'alpha' => '',
			'view' => '',
			'date' => '',
			'com' => '',
			'note' => '',
			'author'=>'',
			'asc' => '',
			'desc' => '',
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
			case 'author' :
				$sort = 'a.user_id';
				$selected_fields['author'] = ' selected="selected"';
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

		//Colonnes des catégories.
		$nbr_column_cats = ($total_cat > $CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : $total_cat;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);

		$group_color = User::get_group_color($User->get_attribute('user_groups'), $User->get_attribute('level'));
		$array_class = array('member', 'modo', 'admin');
			
		$tpl->assign_vars(array(
		'C_WRITE'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE),
		'C_IS_ADMIN' => $User->check_level(ADMIN_LEVEL) ? true : false,
		'C_ADD' => $User->check_auth($CONFIG_ARTICLES['global_auth'], AUTH_ARTICLES_CONTRIBUTE) || $User->check_auth($CONFIG_ARTICLES['global_auth'], AUTH_ARTICLES_WRITE),
		'C_EDIT' => $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_MODERATE) || $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE) ,
		'IDCAT' => $idartcat,
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'SELECTED_ALPHA' => $selected_fields['alpha'],
		'SELECTED_COM' => $selected_fields['com'],
		'SELECTED_DATE' => $selected_fields['date'],
		'SELECTED_VIEW' => $selected_fields['view'],
		'SELECTED_NOTE' => $selected_fields['note'],
		'SELECTED_AUTHOR' => $selected_fields['author'],
		'SELECTED_ASC' => $selected_fields['asc'],
		'SELECTED_DESC' => $selected_fields['desc'],
		'TARGET_ON_CHANGE_ORDER' => $CONFIG['rewrite'] ? 'category-' . $idartcat . '.php?' : 'articles.php?cat=' . $idartcat . '&',
		'L_DATE' => $LANG['date'],
		'L_VIEW' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_COM' => $LANG['com'],
		'L_DESC' => $LANG['desc'],
		'L_ASC' => $LANG['asc'],
		'L_TITLE'=> $LANG['title'],
		'L_WRITTEN' => $LANG['written_by'],
		'L_ARTICLES' => $ARTICLES_LANG['articles'],
		'L_AUTHOR' => $ARTICLES_LANG['author'],
		'L_ORDER_BY' => $ARTICLES_LANG['order_by'],
		'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
		'L_TOTAL_ARTICLE' => ($nbr_articles > 0) ? sprintf($ARTICLES_LANG['nbr_articles_info'], $nbr_articles) : '',
		'L_NO_ARTICLES' => ($nbr_articles == 0) ? $ARTICLES_LANG['none_article'] : '',
		'L_ARTICLES_INDEX' => $ARTICLES_LANG['title_articles'],
		'L_CATEGORIES' => ($ARTICLES_CAT[$idartcat]['order'] >= 0) ? $ARTICLES_LANG['sub_categories'] : $LANG['categories'],
		'U_ADD' => url('management.php?new=1&amp;cat=' . $idartcat),
		'U_EDIT'=> url('admin_articles_cat.php?edit='.$idartcat),
		'U_ARTICLES_CAT_LINKS' => trim($cat_links, ' &raquo;'),
		'U_ARTICLES_WAITING'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE) ? ' <a href="articles.php?invisible=1&amp;cat='.$idartcat.'">' . $ARTICLES_LANG['waiting_articles'] . '</a>' : '',
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

		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

		//On crée une pagination si le nombre de fichiers est trop important.
		
		$Pagination = new Pagination();

		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($ARTICLES_CAT as $id => $key)
		{
			if (!$User->check_auth($ARTICLES_CAT[$id]['auth'], AUTH_ARTICLES_READ))
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
			$result = $this->sql_querier->query_while("SELECT ac.id, ac.name, ac.auth,ac.description, ac.image, ac.nbr_articles_visible AS nbr_articles
			FROM " . DB_TABLE_ARTICLES_CAT . " ac
			" . $clause_cat . $clause_unauth_cats . "
			ORDER BY ac.id_parent
			" . $this->sql_querier->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_cat_max'], 'pcat'), $CONFIG_ARTICLES['nbr_cat_max']), __LINE__, __FILE__);
			
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				$tpl->assign_block_vars('cat_list', array(
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'DESC' => second_parse($row['description']),
				'ICON_CAT' => !empty($row['image']) ? '<a href="articles' . url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php') . '"><img src="' . $row['image'] . '" alt="" class="valign_middle" /></a><br />' : '',
				'U_CAT' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . url_encode_rewrite($row['name']) . '.php'),
				'L_NBR_ARTICLES' => sprintf($ARTICLES_LANG['nbr_articles_info'], $row['nbr_articles']),
				));
			}
			$this->sql_querier->query_close($result);
		}

		##### Affichage des articles #####

		if ($nbr_articles > 0 ||  $invisible)
		{
			$tpl->assign_vars(array(
			'C_ARTICLES_LINK' => true,
			'PAGINATION' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;p=%d', '-' . $idartcat . '-0-%d+' . $rewrite_title . '.php' . $unget), $nbr_articles , 'p', $CONFIG_ARTICLES['nbr_articles_max'], 3),
			'CAT' => $ARTICLES_CAT[$idartcat]['name']
			));

			
			$result = $this->sql_querier->query_while("SELECT a.id, a.title,a.description, a.icon, a.timestamp, a.views, a.note, a.nbrnote, a.nbr_com,a.user_id,m.user_id,m.login,m.level
			FROM " . DB_TABLE_ARTICLES . " a
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
			WHERE a.visible = 1 AND a.idcat = '" . $idartcat .	"'
			ORDER BY " . $sort . " " . $mode .
			$this->sql_querier->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
			
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				//On reccourci le lien si il est trop long.
				$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

				$tpl->assign_block_vars('articles', array(
				'NAME' => $row['title'],
				'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
				'CAT' => $ARTICLES_CAT[$idartcat]['name'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'COMPT' => $row['views'],
				'NOTE' => ($row['nbrnote'] > 0) ? Note::display_img($row['note'], $CONFIG_ARTICLES['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>',
				'COM' => $row['nbr_com'],
				'DESCRIPTION'=>second_parse($row['description']),
				'U_ARTICLES_PSEUDO'=>'<a href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . wordwrap_html($row['login'], 19) . '</a>',
				'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php'),
				'U_ARTICLES_LINK_COM' => url('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;com=%s', '-' . $idartcat . '-' . $row['id'] . '.php?com=0'),
				'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
				'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				));
			}
				
			if($invisible && $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE))
			{
				$tpl->assign_vars(array(
					'C_INVISIBLE'=>true,
					'L_WAITING_ARTICLES' => $ARTICLES_LANG['waiting_articles'],
					'L_NO_ARTICLES_WAITING'=>($nbr_articles_invisible == 0) ? $ARTICLES_LANG['no_articles_waiting'] : '',
					'U_ARTICLES_WAITING'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) ? ' <a href="articles.php?cat='.$idartcat.'">' . $ARTICLES_LANG['publicate_articles'] . '</a>' : ''
				));

				
				$result = $this->sql_querier->query_while("SELECT a.id, a.title, a.icon, a.timestamp, a.views, a.note, a.nbrnote, a.nbr_com,a.user_id,m.user_id,m.login,m.level
				FROM " . DB_TABLE_ARTICLES . " a
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
				WHERE a.visible = 0 AND a.idcat = '" . $idartcat .	"'  AND a.user_id != -1
				ORDER BY " . $sort . " " . $mode .
				$this->sql_querier->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
				
				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					//On reccourci le lien si il est trop long.
					$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

					$tpl->assign_block_vars('articles_invisible', array(
						'NAME' => $row['title'],
						'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
						'CAT' => $ARTICLES_CAT[$idartcat]['name'],
						'DATE' => gmdate_format('date_format_short', $row['timestamp']),
						'COMPT' => $row['views'],
						'NOTE' => ($row['nbrnote'] > 0) ? Note::display_img($row['note'], $CONFIG_ARTICLES['note_max'], 5) : '<em>' . $LANG['no_note'] . '</em>',
						'COM' => $row['nbr_com'],
						'U_ARTICLES_PSEUDO'=>'<a href="' . TPL_PATH_TO_ROOT . '/member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . wordwrap_html($row['login'], 19) . '</a>',
						'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . url_encode_rewrite($fichier) . '.php'),
						'U_ARTICLES_LINK_COM' => url('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;com=%s', '-' . $idartcat . '-' . $row['id'] . '.php?com=0'),
						'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
						'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
					));		
				}
			}
			$this->sql_querier->query_close($result);
		}			
		return $tpl->parse(TRUE);
	}
}

?>