<?php
/*##################################################
 *                              wikiExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2008 LoÃ¯c ROUCHON
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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



class WikiExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('wiki');
	}

	public function get_cache()
	{
		//Catégories du wiki
		$config = 'global $_WIKI_CATS;' . "\n";
		$config .= '$_WIKI_CATS = array();' . "\n";
		$result = $this->sql_querier->query_while("SELECT c.id, c.id_parent, c.article_id, a.title
			FROM " . PREFIX . "wiki_cats c
			LEFT JOIN " . PREFIX . "wiki_articles a ON a.id = c.article_id
			ORDER BY a.title", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$config .= '$_WIKI_CATS[\'' . $row['id'] . '\'] = array(\'id_parent\' => ' . ( !empty($row['id_parent']) ? $row['id_parent'] : '0') . ', \'name\' => ' . var_export($row['title'], true) . ');' . "\n";
		}

		//Configuration du wiki
		$code = 'global $_WIKI_CONFIG;' . "\n" . '$_WIKI_CONFIG = array();' . "\n";
		$CONFIG_WIKI = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'wiki'", __LINE__, __FILE__));

		$CONFIG_WIKI = is_array($CONFIG_WIKI) ? $CONFIG_WIKI : array();
		$CONFIG_WIKI['auth'] = unserialize($CONFIG_WIKI['auth']);

		$code .= '$_WIKI_CONFIG = ' . var_export($CONFIG_WIKI, true) . ';' . "\n";

		return $config . "\n\r" . $code;
	}

	public function get_search_form($args=null)
	{
		require_once(PATH_TO_ROOT . '/kernel/begin.php');
		load_module_lang('wiki');
		global $CONFIG, $LANG;

		$tpl = new FileTemplate('wiki/wiki_search_form.tpl');

		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
		$args['WikiWhere'] = 'title';

		$tpl->assign_vars(Array(
            'L_WHERE' => $LANG['wiki_search_where'],
            'IS_TITLE_SELECTED' => $args['WikiWhere'] == 'title'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['WikiWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['WikiWhere'] == 'all'? ' selected="selected"': '',
            'L_TITLE' => $LANG['wiki_search_where_title'],
            'L_CONTENTS' => $LANG['wiki_search_where_contents']
		));

		return $tpl->to_string();
	}

	public function get_search_args()
	{
		return Array('WikiWhere');
	}

	public function get_search_request($args)
	{
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
		$args['WikiWhere'] = 'title';

		if ( $args['WikiWhere'] == 'all' )
		$req = "SELECT ".
		$args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                ( 4 * FT_SEARCH_RELEVANCE(a.title, '".$args['search']."') +
                FT_SEARCH_RELEVANCE(c.content, '".$args['search']."') ) / 5 * " . $weight . " AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM " . PREFIX . "wiki_articles a
                LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
                WHERE ( FT_SEARCH(a.title, '".$args['search']."') OR MATCH(c.content, '".$args['search']."') )";
		if ( $args['WikiWhere'] == 'contents' )
		$req = "SELECT ".
		$args['id_search']." AS `id_search`,
                a.id AS `id_content`,
                a.title AS `title`,
                FT_SEARCH_RELEVANCE(c.content, '".$args['search']."') * " . $weight . " AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',a.encoded_title) AS `link`
                FROM " . PREFIX . "wiki_articles a
                LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id
                WHERE FT_SEARCH(c.content, '".$args['search']."')";
		else
		$req = "SELECT ".
		$args['id_search']." AS `id_search`,
                `id` AS `id_content`,
                `title` AS `title`,
                ((FT_SEARCH_RELEVANCE(title, '".$args['search']."') )* " . $weight . ") AS `relevance`,
                CONCAT('" . PATH_TO_ROOT . "/wiki/wiki.php?title=',encoded_title) AS `link`
                FROM " . PREFIX . "wiki_articles
                WHERE FT_SEARCH(title, '".$args['search']."')";

		return $req;
	}

	private function _build_wiki_cat_children($cats_tree, $cats, $id_parent = 0)
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

	public function feeds()
	{
		return new WikiFeedProvider();
	}

	public function get_home_page()
	{
		global $User, $Template, $Cache, $Bread_crumb, $_WIKI_CONFIG, $_WIKI_CATS, $LANG;

		load_module_lang('wiki');
		include_once('../wiki/wiki_functions.php');
		$bread_crumb_key = 'wiki';
		require_once('../wiki/wiki_bread_crumb.php');

		unset($Template);
		$Template = new FileTemplate();
		$Template->set_filenames(array(
			'wiki'=> 'wiki/wiki.tpl',
			'index'=> 'wiki/index.tpl'
			));

			if ($_WIKI_CONFIG['last_articles'] > 1)
			{
				$result = $this->sql_querier->query_while("SELECT a.title, a.encoded_title, a.id
			FROM " . PREFIX . "wiki_articles a
			LEFT JOIN " . PREFIX . "wiki_contents c ON c.id_contents = a.id_contents
			WHERE a.redirect = 0
			ORDER BY c.timestamp DESC
			LIMIT 0, " . $_WIKI_CONFIG['last_articles'], __LINE__, __FILE__);
				$articles_number = $this->sql_querier->num_rows($result, "SELECT COUNT(*) FROM " . PREFIX . "wiki_articles WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
					
				$Template->assign_block_vars('last_articles', array(
				'L_ARTICLES' => $LANG['wiki_last_articles_list'],
				'RSS' => $articles_number > 0 ? '<a href="{PATH_TO_ROOT}/syndication.php?m=wiki"><img src="../templates/' . get_utheme() . '/images/rss.png" alt="RSS" /></a>' : ''
				));
					
				$i = 0;
				while ($row = $this->sql_querier->fetch_assoc($result))
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
						'U_CAT' => url('wiki.php?title=' . Url::encode_rewrite($infos['name']), Url::encode_rewrite($infos['name']))
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
			'INDEX_TEXT' => !empty($_WIKI_CONFIG['index_text']) ? FormatingHelper::second_parse(wiki_no_rewrite($_WIKI_CONFIG['index_text'])) : $LANG['wiki_empty_index'],
			'L_EXPLORER' => $LANG['wiki_explorer'],
			'U_EXPLORER' => url('explorer.php'),
			));

			$page_type = 'index';
			include('../wiki/wiki_tools.php');

			$tmp = $Template->pparse('wiki', TRUE);
			return $tmp;
	}
	
	public function get_module_map($auth_mode)
	{
		global $_WIKI_CATS, $LANG, $User, $_WIKI_CONFIG, $Cache;
		
		load_module_lang('wiki');
		$Cache->load('wiki');
		
		$wiki_link = new SitemapLink($LANG['wiki'], new Url('wiki/wiki.php'), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_LOW);
		$module_map = new ModuleMap($wiki_link, 'wiki');
		
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

	private function _create_module_map_sections($id_cat, $auth_mode)
	{
		global $_WIKI_CATS, $LANG, $User, $_WIKI_CONFIG;
		
		$this_category = new SitemapLink($_WIKI_CATS[$id_cat]['name'], new Url('/wiki/' . url('wiki.php?title='.Url::encode_rewrite($_WIKI_CATS[$id_cat]['name']), Url::encode_rewrite($_WIKI_CATS[$id_cat]['name']))));
			
		$category = new SitemapSection($this_category);
		
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