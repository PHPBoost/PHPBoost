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
		return new ModuleCssFiles(array('wiki.css'));
	}
	
	public function search()
	{
		return new WikiSearchable();
	}
	
	public function comments()
	{
		return new WikiComments();
	}
}
?>