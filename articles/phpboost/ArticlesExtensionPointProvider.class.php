<?php
/*##################################################
 *                        ArticlesExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loic Rouchon
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

define('ARTICLES_MAX_SEARCH_RESULTS', 100);
require_once PATH_TO_ROOT . '/articles/articles_constants.php';

class ArticlesExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
		parent::__construct('articles');
	}

	//Deprecated
	function get_cache()
	{
		global $LANG;
		
		$config_articles = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'articles'", __LINE__, __FILE__));

		$string = 'global $CONFIG_ARTICLES, $ARTICLES_CAT;' . "\n\n" . '$CONFIG_ARTICLES = $ARTICLES_CAT = array();' . "\n\n";
		$string .= '$CONFIG_ARTICLES = ' . var_export($config_articles, true) . ';' . "\n\n";

		//List of categories and their own properties
		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, description
			FROM " . DB_TABLE_ARTICLES_CAT . "
			ORDER BY id_parent, c_order", __LINE__, __FILE__);

		$string .= '$ARTICLES_CAT[0] = ' . var_export(array('name' => $LANG['root'], 'order' => 0, 'visible' => true, 'auth' => $config_articles['global_auth']), true) . ';' . "\n\n";
		
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
					'auth' => !empty($row['auth']) ? unserialize($row['auth']) : $config_articles['global_auth']
			), true) . ';' . "\n\n";
		}
		return $string;
	}

	public function search()
	{
		return new ArticlesSearchable();
	}

	public function feeds()
	{
		return new ArticlesFeedProvider();
	}
	
	public function css_files()
	{
		return new ModuleCssFiles(array('articles.css'));
	}

	public function home_page()
	{
		return new ArticlesHomePageExtensionPoint();
	}
	
	public function comments()
	{
		return new ArticlesComments();
	}
}
?>