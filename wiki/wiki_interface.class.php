<?php
/*##################################################
 *                              wiki_interface.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2008 LoÃ¯c ROUCHON
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

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

// Classe WikiInterface qui hérite de la classe ModuleInterface
class WikiInterface extends ModuleInterface
{
	## Public Methods ##
	function WikiInterface() //Constructeur de la classe WikiInterface
	{
		parent::ModuleInterface('wiki');
	}

	//Récupération du cache.
	function get_cache()
	{
		//Catégories du wiki
		$config = 'global $_WIKI_CATS;' . "\n";
		$config .= '$_WIKI_CATS = array();' . "\n";
		$result = $this->db_connection->query_while("SELECT c.id, c.id_parent, c.article_id, a.title
			FROM " . PREFIX . "wiki_cats c
			LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
			ORDER BY a.title", __LINE__, __FILE__);
		while ($row = $this->db_connection->fetch_assoc($result))
		{
			$config .= '$_WIKI_CATS[\'' . $row['id'] . '\'] = array(\'id_parent\' => ' . ( !empty($row['id_parent']) ? $row['id_parent'] : '0') . ', \'name\' => ' . var_export($row['title'], true) . ');' . "\n";
		}

		//Configuration du wiki
		$code = 'global $_WIKI_CONFIG;' . "\n" . '$_WIKI_CONFIG = array();' . "\n";
		$CONFIG_WIKI = unserialize($this->db_connection->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'wiki'", __LINE__, __FILE__));

		$CONFIG_WIKI = is_array($CONFIG_WIKI) ? $CONFIG_WIKI : array();
		$CONFIG_WIKI['auth'] = unserialize($CONFIG_WIKI['auth']);

		$code .= '$_WIKI_CONFIG = ' . var_export($CONFIG_WIKI, true) . ';' . "\n";

		return $config . "\n\r" . $code;
	}

	// Recherche
	function get_search_form($args=null)
	/**
	 *  Renvoie le formulaire de recherche du wiki
	 */
	{
		require_once(PATH_TO_ROOT . '/kernel/begin.php');
		load_module_lang('wiki');
		global $CONFIG, $LANG;

		$Tpl = new Template('wiki/wiki_search_form.tpl');

		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
		$args['WikiWhere'] = 'title';

		$Tpl->assign_vars(Array(
            'L_WHERE' => $LANG['wiki_search_where'],
            'IS_TITLE_SELECTED' => $args['WikiWhere'] == 'title'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['WikiWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['WikiWhere'] == 'all'? ' selected="selected"': '',
            'L_TITLE' => $LANG['wiki_search_where_title'],
            'L_CONTENTS' => $LANG['wiki_search_where_contents']
		));

		return $Tpl->parse(Template::TEMPLATE_PARSER_STRING);
	}

	function get_search_args()
	/**
	 *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
	 */
	{
		return Array('WikiWhere');
	}

	function get_search_request($args)
	/**
	 *  Renvoie la requÃªte de recherche dans le wiki
	 */
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
		$args['WikiWhere'] = 'title';

		if ( $args['WikiWhere'] == 'all' )
		$req = "SELECT ".
		$args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                ( 4 * MATCH(a.title) AGAINST('".$args['search']."') + MATCH(c.content) AGAINST('".$args['search']."') ) / 5 * " . $weight . " AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM " . PREFIX . "wiki_articles a
                LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
                WHERE ( MATCH(a.title) AGAINST('".$args['search']."') OR MATCH(c.content) AGAINST('".$args['search']."') )";
		if ( $args['WikiWhere'] == 'contents' )
		$req = "SELECT ".
		$args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                MATCH(c.content) AGAINST('".$args['search']."') * " . $weight . " AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM " . PREFIX . "wiki_articles a
                LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
                WHERE MATCH(c.content) AGAINST('".$args['search']."')";
		else
		$req = "SELECT ".
		$args['id_search']." AS `id_search`,
                `id` AS `id_content`,
                `title` AS `title`,
                ((MATCH(title) AGAINST('".$args['search']."') )* " . $weight . ") AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',encoded_title) AS `link`
                FROM " . PREFIX . "wiki_articles
                WHERE MATCH(title) AGAINST('".$args['search']."')";

		return $req;
	}

	function _build_wiki_cat_children(&$cats_tree, $cats, $id_parent = 0)
	{
		$i = 0;
		$nb_cats = count($cats);
		$children = array();
		while ($i < $nb_cats)
		{
			if ($cats[$i]['id_parent'] == $id_parent)
			{
				$id = $cats[$i]['id'];
				$feeds_cat = new FeedsCat('wiki', $id, $cats[$i]['title']);

				// Decrease the complexity
				unset($cats[$i]);
				$cats = array_merge($cats); // re-index the array
				$nb_cats = count($cats);

				WikiInterface::_build_wiki_cat_children($feeds_cat, $cats, $id);
				$cats_tree->add_child($feeds_cat);
			}
			else
			{
				$i++;
			}
		}
	}

	function get_feeds_list()
	{
		global $LANG;

		import('content/syndication/feeds_list');
		$cats_tree = new FeedsCat('wiki', 0, $LANG['root']);

		$result = $this->db_connection->query_while("SELECT c.id, c.id_parent, a.title
            FROM " . PREFIX . "wiki_cats c, " . PREFIX . "wiki_articles a
            WHERE c.article_id = a.id", __LINE__, __FILE__
		);
		$results = array();
		while ($row = $this->db_connection->fetch_assoc($result))
		{
			$results[] = $row;
		}
		$this->db_connection->query_close($result);

		WikiInterface::_build_wiki_cat_children($cats_tree, $results);
		$feeds = new FeedsList();
		$feeds->add_feed($cats_tree, DEFAULT_FEED_NAME);
		return $feeds;
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		global $Cache, $LANG, $CONFIG, $_WIKI_CATS, $_WIKI_CONFIG;

		load_module_lang('wiki');
		$Cache->load('wiki');

		if (($idcat > 0) && array_key_exists($idcat, $_WIKI_CATS))//Catégorie
		{
			$desc = sprintf($LANG['wiki_rss_cat'], $_WIKI_CATS[$idcat]['name']);
			$where = "AND a.id_cat = '" . $idcat . "'";
		}
		else //Sinon derniers messages
		{
			$desc = sprintf($LANG['wiki_rss_last_articles'], (!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']));
			$where = "";
		}

		import('content/syndication/feed_data');
		import('util/date');
		import('util/url');

		$data = new FeedData();

		$data->set_title(!empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki']);
		$data->set_date(new Date());
		$data->set_link(new Url('/syndication.php?m=wiki&amp;cat=' . $idcat));
		$data->set_host(HOST);
		$data->set_desc($desc);
		$data->set_lang($LANG['xml_lang']);

		// Load the new's config
		$Cache->load('wiki');

		// Last news
		$result = $this->db_connection->query_while("SELECT a.title, a.encoded_title, c.content, c.timestamp
            FROM " . PREFIX . "wiki_articles a
            LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
            WHERE a.redirect = 0 " . $where . "
            ORDER BY c.timestamp DESC
            " . $this->db_connection->limit(0, 2 * 10), __LINE__, __FILE__);

		// Generation of the feed's items
		while ($row = $this->db_connection->fetch_assoc($result))
		{
			$item = new FeedItem();

			$item->set_title($row['title']);
			$link = new Url('/wiki/' . url('wiki.php?title=' . url_encode_rewrite($row['title']), url_encode_rewrite($row['title'])));
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_desc(second_parse($row['content']));
			$item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));

			$data->add_item($item);
		}
		$this->db_connection->query_close($result);

		return $data;
	}

	function get_home_page()
	{
		global $User, $Template, $Cache, $Bread_crumb, $_WIKI_CONFIG, $_WIKI_CATS, $LANG;

		load_module_lang('wiki');
		include_once('../wiki/wiki_functions.php');
		$bread_crumb_key = 'wiki';
		require_once('../wiki/wiki_bread_crumb.php');

		unset($Template);
		$Template = new Template();
		$Template->set_filenames(array(
			'wiki'=> 'wiki/wiki.tpl',
			'index'=> 'wiki/index.tpl'
			));
			$Template->assign_vars(array(
			'WIKI_PATH' => $Template->get_module_data_path('wiki')
			));

			if ($_WIKI_CONFIG['last_articles'] > 1)
			{
				$result = $this->db_connection->query_while("SELECT a.title, a.encoded_title, a.id
			FROM " . PREFIX . "wiki_articles a
			LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
			WHERE a.redirect = 0
			ORDER BY c.timestamp DESC
			LIMIT 0, " . $_WIKI_CONFIG['last_articles'], __LINE__, __FILE__);
				$articles_number = $this->db_connection->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
					
				$Template->assign_block_vars('last_articles', array(
				'L_ARTICLES' => $LANG['wiki_last_articles_list'],
				'RSS' => $articles_number > 0 ? '<a href="{PATH_TO_ROOT}/syndication.php?m=wiki"><img src="../templates/' . get_utheme() . '/images/rss.png" alt="RSS" /></a>' : ''
				));
					
				$i = 0;
				while ($row = $this->db_connection->fetch_assoc($result))
				{
					$Template->assign_block_vars('last_articles.list', array(
					'ARTICLE' => $row['title'],
					'TR' => ($i > 0 && ($i%2 == 0)) ? '</tr><tr>' : '',
					'U_ARTICLE' => url('wiki.php?title=' . $row['encoded_title'], $row['encoded_title'])
					));
					$i++;
				}
					
				if ($articles_number == 0)
				{
					$Template->assign_vars(array(
					'L_NO_ARTICLE' => '<td style="text-align:center;" class="row2">' . $LANG['wiki_no_article'] . '</td>',
					));
				}
			}
			//Affichage de toutes les catégories si c'est activé
			if ($_WIKI_CONFIG['display_cats'] != 0)
			{
				$Template->assign_block_vars('cat_list', array(
				'L_CATS' => $LANG['wiki_cats_list']
				));
				$i = 0;
				foreach ($_WIKI_CATS as $id => $infos)
				{
					//Si c'est une catégorie mère
					if ($infos['id_parent'] == 0)
					{
						$Template->assign_block_vars('cat_list.list', array(
						'CAT' => $infos['name'],
						'U_CAT' => url('wiki.php?title=' . url_encode_rewrite($infos['name']), url_encode_rewrite($infos['name']))
						));
						$i++;
					}
				}
				if ($i == 0)
				$Template->assign_vars(array(
				'L_NO_CAT' => $LANG['wiki_no_cat'],
				));
			}

			$Template->assign_vars(array(
			'TITLE' => !empty($_WIKI_CONFIG['wiki_name']) ? $_WIKI_CONFIG['wiki_name'] : $LANG['wiki'],
			'INDEX_TEXT' => !empty($_WIKI_CONFIG['index_text']) ? second_parse(wiki_no_rewrite($_WIKI_CONFIG['index_text'])) : $LANG['wiki_empty_index'],
			'L_EXPLORER' => $LANG['wiki_explorer'],
			'U_EXPLORER' => url('explorer.php'),
			'WIKI_PATH' => $Template->get_module_data_path('wiki')
			));

			$page_type = 'index';
			include('../wiki/wiki_tools.php');

			$tmp = $Template->pparse('wiki', TRUE);
			return $tmp;
	}
	function get_module_map($auth_mode = SITE_MAP_AUTH_GUEST)
	{
		global $_WIKI_CATS, $LANG, $User, $_WIKI_CONFIG, $Cache;
		
		import('content/sitemap/module_map');
		import('util/url');
		
		load_module_lang('wiki');
		$Cache->load('wiki');
		
		$wiki_link = new SiteMapLink($LANG['wiki'], new Url('wiki/wiki.php'), SITE_MAP_FREQ_DEFAULT, SITE_MAP_PRIORITY_LOW);
		$module_map = new ModuleMap($wiki_link);
		
		$id_cat = 0;
	    $keys = array_keys($_WIKI_CATS);
		$num_cats = count($_WIKI_CATS);
		$properties = array();
		
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $_WIKI_CATS[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->_create_module_map_sections($id, $auth_mode));
			}
		}
		
		return $module_map; 
	}
	
	function _create_module_map_sections($id_cat, $auth_mode)
	{
		global $_WIKI_CATS, $LANG, $User, $_WIKI_CONFIG;
		
		$this_category = new SiteMapLink($_WIKI_CATS[$id_cat]['name'], new Url('/wiki/' . url('wiki.php?title='.url_encode_rewrite($_WIKI_CATS[$id_cat]['name']), url_encode_rewrite($_WIKI_CATS[$id_cat]['name']))));
			
		$category = new SiteMapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($_WIKI_CATS);
		$num_cats = count($_WIKI_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $_WIKI_CATS[$id];
			if ($id != 0 && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->_create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if ($i == 0	)
			$category = $this_category;
		
		return $category;
	} 
}

?>
