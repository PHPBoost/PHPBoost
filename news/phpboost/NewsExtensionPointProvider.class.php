<?php
/*##################################################
 *                              newsExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loic Rouchon, Roguelon Geoffrey
 *   email                : loic.rouchon@phpboost.com, liaght@gmail.com
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



define('NEWS_MAX_SEARCH_RESULTS', 100);

require_once PATH_TO_ROOT . '/news/news_constants.php';

class NewsExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

	public function __construct() //Constructeur de la classe ForumInterface
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct('news');
	}

	//Récupération du cache.
	public function get_cache()
	{
		global $LANG;
		//Récupération du tableau linéarisé dans la bdd
		$news_config = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'news'", __LINE__, __FILE__));

		$string = 'global $NEWS_CONFIG, $NEWS_CAT;' . "\n\n" . '$NEWS_CONFIG = $NEWS_CAT = array();' . "\n\n";
		$string .= '$NEWS_CONFIG = ' . var_export($news_config, true) . ';' . "\n\n";

		//List of categories and their own properties
		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, description
			FROM " . DB_TABLE_NEWS_CAT . "
			ORDER BY id_parent, c_order", __LINE__, __FILE__);

		//Racine
		$string .= '$NEWS_CAT[0] = ' . var_export(array('name' => $LANG['root'], 'auth' => $news_config['global_auth'], 'image' => ''), true) . ';' . "\n\n";

		while ($row = $this->sql_querier->fetch_assoc($result))
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

	public function search()
	{
		return new NewsSearchable();
	}

	public function feeds()
	{
		return new NewsFeedProvider();
	}

	public function sitemap()
	{
		return new NewsSitemapExtensionPoint();
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->set_css_files_running_module_displayed(array('news.css'));
		return $module_css_files;
	}

	public function home_page()
	{
		return new NewsHomePageExtensionPoint();
	}
	
	public function comments()
	{
		return new CommentsTopics(array(
			new NewsCommentsTopic()
		));
	}

	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $NEWS_CAT, $NEWS_LANG, $LANG, $User, $NEWS_CONFIG;

		$this_category = new SitemapLink($NEWS_CAT[$id_cat]['name'], new Url('/news/' . url('news.php?cat=' . $id_cat, 'news-' . $id_cat . '+' . Url::encode_rewrite($NEWS_CAT[$id_cat]['name']) . '.php')), Sitemap::FREQ_WEEKLY);

		$category = new SitemapSection($this_category);

		$i = 0;

		$keys = array_keys($NEWS_CAT);
		$num_cats = count($NEWS_CAT);
		$properties = array();

		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $NEWS_CAT[$id];
			if ($auth_mode == Sitemap::AUTH_PUBLIC)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_NEWS_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_NEWS_READ) : $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}

		if ($i == 0)
		{
			$category = $this_category;
		}

		return $category;
	}
}
?>