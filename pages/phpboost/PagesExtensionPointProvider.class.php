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
    public function __construct() //Constructeur de la classe WikiInterface
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('pages');
    }
	
	//Récupération du cache.
	public function get_cache()
	{
		//Catégories des pages
		$config = 'global $_PAGES_CATS;' . "\n";
		$config .= '$_PAGES_CATS = array();' . "\n";
		$result = $this->sql_querier->query_while("SELECT c.id, c.id_parent, c.id_page, p.title, p.auth
		FROM " . PREFIX . "pages_cats c
		LEFT JOIN " . PREFIX . "pages p ON p.id = c.id_page
		ORDER BY p.title", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$config .= '$_PAGES_CATS[\'' . $row['id'] . '\'] = ' . var_export(array(
				'id_parent' => !empty($row['id_parent']) ? $row['id_parent'] : '0',
				'name' => $row['title'],
				'auth' => unserialize($row['auth'])
			), true) . ';' . "\n";
		}

		//Configuration du module de pages
		$code = 'global $_PAGES_CONFIG;' . "\n";
		$CONFIG_PAGES = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'pages'", __LINE__, __FILE__));
								
		if (is_array($CONFIG_PAGES))
			$CONFIG_PAGES['auth'] = unserialize($CONFIG_PAGES['auth']);
		else
			$CONFIG_PAGES = array(
			'count_hits' => 1,
			'activ_com' => 1,
			'auth' => array (
				'r-1' => 5,
				'r0' => 5,
				'r1' => 7,
				'r2' => 7,
			));
		
		$code .=  '$_PAGES_CONFIG = ' . var_export($CONFIG_PAGES, true) . ';' . "\n";
		
		return $config . "\n\r" . $code;	
	}
    
	public function get_module_map($auth_mode)
	{
		global $_PAGES_CATS, $LANG, $User, $_PAGES_CONFIG, $Cache;
		
		include(PATH_TO_ROOT.'/pages/pages_defines.php');
		load_module_lang('pages');
		$Cache->load('pages');
		
		$pages_link = new SitemapLink($LANG['pages'], new Url('/pages/explorer.php'), Sitemap::FREQ_DEFAULT, Sitemap::PRIORITY_MAX);
		$module_map = new ModuleMap($pages_link, 'pages');
		
		$id_cat = 0;
	    $keys = array_keys($_PAGES_CATS);
		$num_cats = count($_PAGES_CATS);
		$properties = array();
		
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $_PAGES_CATS[$id];
			if ($auth_mode == Sitemap::AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $_PAGES_CONFIG['auth'], READ_PAGE);
			}
			elseif ($auth_mode == Sitemap::AUTH_USER)
			{
				if($User->get_attribute('level') == ADMIN_LEVEL)
					$this_auth = true;
				else
					$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, $User->get_attribute('level'), $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, $User->get_attribute('level'), $_PAGES_CONFIG['auth'], READ_PAGE);
			}
			if ($this_auth && $id != 0 && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->create_module_map_sections($id, $auth_mode));
			}
		}
		
		return $module_map; 
	}
	
	private function create_module_map_sections($id_cat, $auth_mode)
	{
		global $_PAGES_CATS, $LANG, $User, $_PAGES_CONFIG;
		
		$this_category = new SitemapLink($_PAGES_CATS[$id_cat]['name'], new Url('/pages/' . url('pages.php?title='.Url::encode_rewrite($_PAGES_CATS[$id_cat]['name']), Url::encode_rewrite($_PAGES_CATS[$id_cat]['name']))));
			
		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($_PAGES_CATS);
		$num_cats = count($_PAGES_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $_PAGES_CATS[$id];
			if ($auth_mode == Sitemap::AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $_PAGES_CONFIG['auth'], READ_PAGE);
			}
			elseif ($auth_mode == Sitemap::AUTH_USER)
			{
				if($User->get_attribute('level') == ADMIN_LEVEL)
					$this_auth = true;
				else
					$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, $User->get_attribute('level'), $properties['auth'], READ_PAGE) : Authorizations::check_auth(RANK_TYPE, $User->get_attribute('level'), $_PAGES_CONFIG['auth'], READ_PAGE);
			}
			if ($this_auth && $id != 0 && $properties['id_parent'] == $id_cat)
			{
				$category->add($this->create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if ($i == 0	)
			$category = $this_category;
		
		return $category;
	}
}

?>
