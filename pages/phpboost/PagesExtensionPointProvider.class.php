<?php
/*##################################################
 *                         pagesExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
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
 
define('PAGES_MAX_SEARCH_RESULTS', 100);

class PagesExtensionPointProvider extends ExtensionPointProvider
{
    public function __construct() //Constructeur de la classe
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('pages');
    }
	
	//Récupération du cache.
	public function get_cache()
	{
		$pages_config = PagesConfig::load();
		
		//Catégories des pages
		$code = 'global $_PAGES_CATS;' . "\n";
		$code .= '$_PAGES_CATS = array();' . "\n\n";

		$result = $this->sql_querier->query_while("SELECT c.id, c.id_parent, c.id_page, p.title, p.auth
		FROM " . PREFIX . "pages_cats c
		LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
		ORDER BY p.title", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$code .= '$_PAGES_CATS[' . $row['id'] . '] = ' .
			var_export(array(
			'id_parent' => !empty($row['id_parent']) ? $row['id_parent'] : '0',
			'name' => $row['title'],
			'auth' => unserialize($row['auth'])
			), true)
			. ';' . "\n";
		}
		$this->sql_querier->query_close($result);
		
		return $code;
	}
    
	public function home_page()
	{
		return new PagesHomePageExtensionPoint();
	}
	
	public function sitemap()
	{
		return new PagesSitemapExtensionPoint();
	}
	
	public function feeds()
	{
		return new PagesFeedProvider();
	}
	
	public function css_files()
	{
		return new PagesCssFilesExtensionPoint();
	}
	
	public function search()
	{
		return new PagesSearchable();
	}
}
?>
