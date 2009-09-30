<?php
/*##################################################
 *                              news_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loïc Rouchon, Roguelon Geoffrey
 *   email                : horn@phpboost.com, liaght@gmail.com
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

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

define('NEWS_MAX_SEARCH_RESULTS', 100);

require_once PATH_TO_ROOT . '/news/news_constant.php';

// Classe ForumInterface qui hérite de la classe ModuleInterface
class NewsInterface extends ModuleInterface
{
    ## Public Methods ##
    function NewsInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('news');
    }

    //Récupération du cache.
	function get_cache()
	{
		//Récupération du tableau linéarisé dans la bdd.
		$news_config = unserialize($this->db_connection->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'news'", __LINE__, __FILE__));

		$string = 'global $NEWS_CONFIG, $NEWS_CAT;' . "\n\n" . '$NEWS_CONFIG = $NEWS_CAT = array();' . "\n\n";
		$string .= '$NEWS_CONFIG = ' . var_export($news_config, true) . ';' . "\n\n";

		//List of categories and their own properties
		$result = $this->db_connection->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, description
			FROM " . DB_TABLE_NEWS_CAT . "
			ORDER BY id_parent, c_order", __LINE__, __FILE__);

		while ($row = $this->db_connection->fetch_assoc($result))
		{
			$string .= '$NEWS_CAT[' . $row['id'] . '] = ' .
				var_export(array(
					'id_parent' => (int)$row['id_parent'],
					'order' => (int)$row['c_order'],
					'name' => $row['name'],
					'desc' => $row['description'],
					'visible' => (bool)$row['visible'],
					'image' => !empty($row['image']) ? $row['image'] : '/news/news.png',
					'description' => $row['description'],
					'auth' => !empty($row['auth']) ? unserialize($row['auth']) : $news_config['global_auth']
				), true) . ';' . "\n\n";
		}

		return $string;
	}

	//Actions journalière.
	// function on_changeday()
	// {
	// }

	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
		import('util/date');
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

		require_once PATH_TO_ROOT . '/news/news_cats.class.php';
		$news_cat = new NewsCats();

		// Build array with the children categories.
		$array_cat = array();
		$news_cat->build_children_id_list(0, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_READ);
		$where = !empty($array_cat) ? " AND idcat IN(" . implode(", ", $array_cat) . ")" : " AND idcat = '0'";

        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

        $request = "SELECT " . $args['id_search'] . " AS id_search,
            n.id AS id_content,
            n.title AS title,
            ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance, "
            . $this->db_connection->concat("'" . PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS link
            FROM " . DB_TABLE_NEWS . " n
            WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
                AND n.start <= '" . $now->get_timestamp() . "' AND n.visible = 1" . $where . "
            ORDER BY relevance DESC " . $this->db_connection->limit(0, NEWS_MAX_SEARCH_RESULTS);

        return $request;
    }

	/**
     * @desc Return the list of the feeds available for this module.
     * @return FeedsList The list
     */
    function get_feeds_list()
	{
        require_once PATH_TO_ROOT . '/news/news_cats.class.php';
        $news_cats = new NewsCats();
        return $news_cats->get_feeds_list();
	}

    function get_feed_data_struct($idcat = 0, $name = '')
    {
        global $Cache, $LANG, $CONFIG, $NEWS_CONFIG, $NEWS_CAT, $NEWS_LANG;

		// Load the new's config
        $Cache->load('news');

		$idcat = !empty($NEWS_CAT[$idcat]) ? $idcat : 0;

        import('content/syndication/feed_data');
        import('util/date');
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
        import('util/url');

        load_module_lang('news');

		$site_name = $idcat > 0 ? $CONFIG['site_name'] . ' : ' . $NEWS_CAT[$idcat]['name'] : $CONFIG['site_name'];

        $data = new FeedData();

        $data->set_title($NEWS_LANG['xml_news_desc'] . $site_name);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=news&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($NEWS_LANG['xml_news_desc'] . $site_name);
        $data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(AUTH_NEWS_READ);

		require_once PATH_TO_ROOT . '/news/news_cats.class.php';
		$news_cat = new NewsCats();

		// Build array with the children categories.
		$array_cat = array();
		$news_cat->build_children_id_list($idcat, $array_cat, RECURSIVE_EXPLORATION, ($idcat == 0 ? DO_NOT_ADD_THIS_CATEGORY_IN_LIST : ADD_THIS_CATEGORY_IN_LIST));

		if (!empty($array_cat))
		{
	        // Last news
	        $result = $this->db_connection->query_while("SELECT id, idcat, title, contents, extend_contents, timestamp, img
	            FROM " . DB_TABLE_NEWS . "
	            WHERE start <= '" . $now->get_timestamp() . "' AND visible = 1 AND idcat IN(" . implode(", ", $array_cat) . ")
	            ORDER BY timestamp DESC"
				. $this->db_connection->limit(0, 2 * $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

	        // Generation of the feed's items
	        while ($row = $this->db_connection->fetch_assoc($result))
	        {
				// Rewriting
	            $link = new Url('/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php'));

	            $item = new FeedItem();
	            $item->set_title($row['title']);
	            $item->set_link($link);
	            $item->set_guid($link);
	            $item->set_desc(second_parse($row['contents']) . (!empty($row['extend_contents']) ? '<br /><br /><a href="' . $link->absolute() . '">' . $NEWS_LANG['extend_contents'] . '</a><br /><br />' : ''));
	            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
	            $item->set_image_url($row['img']);
            	$item->set_auth($news_cat->compute_heritated_auth($row['idcat'], AUTH_NEWS_READ, AUTH_PARENT_PRIORITY));

	            $data->add_item($item);
	        }
	        $this->db_connection->query_close($result);
        }

        return $data;
    }

	// Returns the module map objet to build the global sitemap
    /**
	 * @desc
	 * @param $auth_mode
	 * @return unknown_type
     */
	function get_module_map($auth_mode = SITE_MAP_AUTH_GUEST)
	{
		global $NEWS_CAT, $NEWS_LANG, $LANG, $User, $NEWS_CONFIG, $Cache;

		import('content/sitemap/module_map');
		import('util/url');

		require_once PATH_TO_ROOT . '/news/news_begin.php';

		$news_link = new SiteMapLink($NEWS_LANG['news'], new Url('/news/news.php'), SITE_MAP_FREQ_DAILY, SITE_MAP_PRIORITY_MAX);

		$module_map = new ModuleMap($news_link);
		$module_map->set_description('<em>Test</em>');

		$id_cat = 0;
	    $keys = array_keys($NEWS_CAT);
		$num_cats = count($NEWS_CAT);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $NEWS_CAT[$id];

			if ($auth_mode == SITE_MAP_AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_NEWS_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_NEWS_READ) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}

			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->_create_module_map_sections($id, $auth_mode));
			}
		}

		return $module_map;
	}

	#Private#
	function _create_module_map_sections($id_cat, $auth_mode)
	{
		global $NEWS_CAT, $NEWS_LANG, $LANG, $User, $NEWS_CONFIG;

		$this_category = new SiteMapLink($NEWS_CAT[$id_cat]['name'], new Url('/news/' . url('news.php?cat=' . $id_cat, 'news-' . $id_cat . '+' . url_encode_rewrite($NEWS_CAT[$id_cat]['name']) . '.php')), SITE_MAP_FREQ_WEEKLY);

		$category = new SiteMapSection($this_category);

		$i = 0;

		$keys = array_keys($NEWS_CAT);
		$num_cats = count($NEWS_CAT);
		$properties = array();

		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $NEWS_CAT[$id];
			if ($auth_mode == SITE_MAP_AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_NEWS_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_NEWS_READ) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->_create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}

		if ($i == 0)
		{
			$category = $this_category;
		}

		return $category;
	}

	function get_home_page($cat = 0)
	{
		global $User, $Cache, $Bread_crumb, $NEWS_CONFIG, $NEWS_CAT, $NEWS_LANG, $LANG, $Session;

		// Begin.
		load_module_lang('news');
		$Cache->load('news');

		// Vérification de la présence des constantes.
		if (!defined('INCLUDE_CONSTANTS_NEWS'))
		{
			require_once PATH_TO_ROOT . '/news/news_constant.php';
		}

		// Import.
		import('util/date');
		import('content/comments');
		import('content/syndication/feed');
		import('util/pagination');

		// Initialisation des imports.
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$Pagination = new Pagination();

		// Classe des catégories.
		require_once PATH_TO_ROOT . '/news/news_cats.class.php';
		$news_cat = new NewsCats();

		// Variables.
		$arch = retrieve(GET, 'arch', false);
		$level = array('', ' class="modo"', ' class="admin"');

		// Gestion du tpl en fonction du type d'affichage.
		$tpl_path = $NEWS_CONFIG['type'] ? 'news/news_cat.tpl' : 'news/news_list.tpl';
		$tpl = new Template($tpl_path);

		// Affichage de l'édito
		if ($NEWS_CONFIG['activ_edito'] && $cat == 0)
		{
			$tpl->assign_vars(array(
				'C_EDITO' => true,
				'EDITO_NAME' => $NEWS_CONFIG['edito_title'],
				'EDITO_CONTENTS' => second_parse($NEWS_CONFIG['edito'])
			));
		}
		elseif ($cat > 0)
		{
			$tpl->assign_vars(array(
				'C_EDITO' => !empty($NEWS_CAT[$cat]['desc']) ? true : false,
				'C_CAT' => true,
				'EDITO_NAME' => $NEWS_CAT[$cat]['name'],
				'EDITO_CONTENTS' => !empty($NEWS_CAT[$cat]['desc']) ? second_parse($NEWS_CAT[$cat]['desc']) : ''
			));
		}
		else
		{
			$tpl->assign_vars(array('C_EDITO' => false));
		}

		$c_add = $cat > 0 ? $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_CONTRIBUTE) || $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_WRITE) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_CONTRIBUTE) || $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE);
		$c_writer = $cat > 0 ? $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_WRITE) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE);

		$tpl->assign_vars( array(
			'L_ALERT_DELETE_NEWS' => $NEWS_LANG['alert_delete_news'],
			'U_SYNDICATION' => url('../syndication.php?m=news' . ($cat > 0  ? '&amp;cat=' . $cat : '')),
			'L_SYNDICATION' => $LANG['syndication'],
			'C_ADD_OR_WRITER' => $c_add || $c_writer,
			'C_ADD' => $c_add,
			'U_ADD' => url(PATH_TO_ROOT . '/news/management.php?new=1'),
			'L_ADD' => $NEWS_LANG['add_news'],
			'C_WRITER' => $c_writer,
			'L_WRITER' => $NEWS_LANG['waiting_news'],
			'C_ADMIN' => $User->check_level(ADMIN_LEVEL),
			'U_ADMIN' => $cat > 0 ? url('admin_news_cat.php?edit=' . $cat) : url('admin_news_config.php#preview_description'),
			'L_ADMIN' => $LANG['edit'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'L_LAST_NEWS' => $NEWS_LANG['last_news'],
		    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
		));

		// Construction du tableau des catégories.
		$array_cat = array();
		if ($cat > 0)
			$array_cat[] = $cat;
		else
			$news_cat->build_children_id_list($cat, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_READ);

		// Gestion du where.
		$last_release = 0;
		$where = "WHERE n.start <= '" . $now->get_timestamp() . "' AND (n.end >= '" . $now->get_timestamp() . "' OR n.end = 0) AND n.visible = 1 AND n.idcat IN(" . implode(", ", $array_cat) . ")";

		// Comptage des news.
		$NEWS_CONFIG['nbr_news'] = !empty($array_cat) ? $this->db_connection->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " n " . $where, __LINE__, __FILE__) : 0;

		// Affichage d'un message d'erreur en cas d'absence de news.
		if ($NEWS_CONFIG['nbr_news'] == 0)
		{
			$tpl->assign_vars(array(
				'C_NEWS_NO_AVAILABLE' => true,
				'L_LAST_NEWS' => $NEWS_LANG['last_news'],
				'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available']
			));
		}
		// Sinon affichage des news.
		else
		{
			if ($NEWS_CONFIG['activ_pagin']) // Pagination activée, sinon affichage lien vers les archives.
			{
				$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?p=%d', '-0-0-%d.php'), $NEWS_CONFIG['nbr_news'], 'p', $NEWS_CONFIG['pagination_news'], 3);
				$first_msg = $Pagination->get_first_msg($NEWS_CONFIG['pagination_news'], 'p');
			}
			elseif ($arch) // Pagination des archives.
			{
				$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?arch=1&amp;p=%d', '-0-0-%d.php?arch=1'), $NEWS_CONFIG['nbr_news'] - $NEWS_CONFIG['pagination_news'], 'p', $NEWS_CONFIG['pagination_arch'], 3);
				$first_msg = $NEWS_CONFIG['pagination_news'] + $Pagination->get_first_msg($NEWS_CONFIG['pagination_arch'], 'p');
				$NEWS_CONFIG['pagination_news'] = $NEWS_CONFIG['pagination_arch'];
			}
			else // Affichage du lien vers les archives.
			{
				$show_pagin = (($NEWS_CONFIG['nbr_news'] > $NEWS_CONFIG['pagination_news']) && ($NEWS_CONFIG['nbr_news'] != 0)) ? '<a href="' . PATH_TO_ROOT . '/news/news.php' . '?arch=1" title="' . $NEWS_LANG['display_archive'] . '">' . $NEWS_LANG['display_archive'] . '</a>' : '';
				$first_msg = 0;
			}

			$tpl->assign_vars(array('PAGINATION' => $show_pagin));

			if ($NEWS_CONFIG['type']) // Si les news en block sont activées on recupère la page.
			{
				$column = $NEWS_CONFIG['nbr_column'] > 1 ? true : false;
				if ($column)
				{
					$i = 0;
					$NEWS_CONFIG['nbr_column'] = !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1;
					$column_width = floor(100 / $NEWS_CONFIG['nbr_column']);

					$tpl->assign_vars(array(
						'C_NEWS_BLOCK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}

				$result = $this->db_connection->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.user_id, n.img, n.alt, n.nbr_com, m.login, m.level
					FROM " . DB_TABLE_NEWS . " n
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
					" . $where . "
					ORDER BY n.timestamp DESC
					" . $this->db_connection->limit($first_msg, $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

				while ($row = $this->db_connection->fetch_assoc($result))
				{
					// Séparation des news en colonnes si activé.
					if ($column)
					{
						$new_row = (($i % $NEWS_CONFIG['nbr_column']) == 0 && $i > 0);
						$i++;
					}
					else
					{
						$new_row = false;
					}

					$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);
					$last_release = max($last_release, $row['start']);

					$tpl->assign_block_vars('news', array(
						'ID' => $row['id'],
						'C_NEWS_ROW' => $new_row,
						'U_SYNDICATION' => url('../syndication.php?m=news&amp;cat=' . $row['idcat']),
						'U_LINK' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
						'TITLE' => $row['title'],
						'U_COM' => $NEWS_CONFIG['activ_com'] == 1 ? Comments::com_display_link($row['nbr_com'], 'news' . url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;com=0', '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php?com=0'), $row['id'], 'news') : false,
						'C_EDIT' => $User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_WRITE) && $row['user_id'] == $User->get_attribute('user_id'),
						'C_DELETE' => $User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_MODERATE),
						'C_IMG' => !empty($row['img']),
						'IMG' => second_parse_url($row['img']),
						'IMG_DESC' => $row['alt'],
						'C_ICON' => $NEWS_CONFIG['activ_icon'],
						'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . url_encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
						'ICON' => second_parse_url($NEWS_CAT[$row['idcat']]['image']),
						'CONTENTS' => second_parse($row['contents']),
						'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
						'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($row['login']) ? $row['login'] : '',
						'U_USER_ID' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
						'LEVEL' =>	isset($row['level']) ? $level[$row['level']] : '',
						'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
					    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
					));
				}

				$this->db_connection->query_close($result);
			}
			else // News en liste
			{
				$column = $NEWS_CONFIG['nbr_column'] > 1 ? true : false;
				if ($column)
				{
					$i = 0;
					$NEWS_CONFIG['nbr_column'] = !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1;
					$column_width = floor(100 / $NEWS_CONFIG['nbr_column']);

					$tpl->assign_vars(array(
						'C_NEWS_LINK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}

				$result = $this->db_connection->query_while("SELECT n.id, n.idcat, n.title, n.timestamp, n.start, n.nbr_com
					FROM " . DB_TABLE_NEWS . " n " . $where . "
					ORDER BY n.timestamp DESC" . $this->db_connection->limit($first_msg, $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

				while ($row = $this->db_connection->fetch_assoc($result))
				{
					// Séparation des news en colonnes si activé.
					if ($column)
					{
						$new_row = ($i % $NEWS_CONFIG['nbr_column']) == 0 && $i > 0;
						$i++;
					}
					else
					{
						$new_row = false;
					}

					$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);
					$last_release = max($last_release, $row['start']);

					$tpl->assign_block_vars('list', array(
						'C_NEWS_ROW' => $new_row,
						'ICON' => $NEWS_CONFIG['activ_icon'] ? second_parse_url($NEWS_CAT[$row['idcat']]['image']) : 0,
						'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . url_encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
						'DATE' => $timestamp->format(DATE_FORMAT_TINY, TIMEZONE_AUTO),
						'U_NEWS' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
						'TITLE' => $row['title'],
						'C_COM' => $NEWS_CONFIG['activ_com'] ? true : false,
						'COM' => $row['nbr_com']
					));
				}

				$this->db_connection->query_close($result);
			}
		}

		// Vérification de la date de parution des news.
		if (file_exists(NEWS_MASTER_0))
		{
			$date_cache = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, filemtime(NEWS_MASTER_0));
			$date_release = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $last_release);

			if ($date_cache->get_timestamp() < $date_release->get_timestamp())
			{
				Feed::clear_cache('news');
			}
		}

		return $tpl->parse(true);
	}
}

?>