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
		$module_css_files->adding_running_module_displayed_file('news.css');
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
}
?>