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
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
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
		global $LANG;

		$tpl = new FileTemplate('wiki/wiki_search_form.tpl');

		if ( !isset($args['WikiWhere']) || !in_array($args['WikiWhere'], explode(',','title,contents,all')) )
		$args['WikiWhere'] = 'title';

		$tpl->put_all(Array(
            'L_WHERE' => $LANG['wiki_search_where'],
            'IS_TITLE_SELECTED' => $args['WikiWhere'] == 'title'? ' selected="selected"': '',
            'IS_CONTENTS_SELECTED' => $args['WikiWhere'] == 'contents'? ' selected="selected"': '',
            'IS_ALL_SELECTED' => $args['WikiWhere'] == 'all'? ' selected="selected"': '',
            'L_TITLE' => $LANG['wiki_search_where_title'],
            'L_CONTENTS' => $LANG['wiki_search_where_contents']
		));

		return $tpl->render();
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

	public static function _build_wiki_cat_children($cats_tree, $cats, $id_parent = 0)
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

				self::_build_wiki_cat_children($feeds_cat, $cats, $id);
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

	public function home_page()
	{
		return new WikiHomePageExtensionPoint();
	}

	public function sitemap()
	{
		return new WikiSitemapExtensionPoint();
	}
	
	public function css_files()
	{
		return new WikiCssFilesExtensionPoint();
	}
}
?>