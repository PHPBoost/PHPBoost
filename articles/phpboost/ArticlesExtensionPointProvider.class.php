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
		
		$articles_config = ArticlesConfig::load();
		$config_auth = $articles_config->get_authorization();

		$code = '$ARTICLES_CAT = array();' . "\n\n";

		//List of categories and their own properties
		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, description
			FROM " . DB_TABLE_ARTICLES_CAT . "
			ORDER BY id_parent, c_order", __LINE__, __FILE__);

		$code .= '$ARTICLES_CAT[0] = ' . var_export(array('name' => $LANG['root'], 'order' => 0, 'auth' => $config_auth), true) . ';' . "\n\n";
		
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$code .= '$ARTICLES_CAT[' . $row['id'] . '] = ' .
			var_export(array(
					'id_parent' => (int)$row['id_parent'],
					'order' => (int)$row['c_order'],
					'name' => $row['name'],
					'desc' => $row['description'],
					'visible' => (bool)$row['visible'],
					'image' => !empty($row['image']) ? $row['image'] : '/articles/articles.png',
					'description' => $row['description'],
					'auth' => !empty($row['auth']) ? unserialize($row['auth']) : $config_auth
			), true) . ';' . "\n\n";
		}
		
		return $code;
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
		return new ArticlesCssFilesExtensionPoint();
	}

	public function home_page()
	{
		return new ArticlesHomePageExtensionPoint();
	}
}
?>