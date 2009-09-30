<?php
/*##################################################
 *                              faq_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 LoÃ¯c Rouchon
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

define('FAQ_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class FaqInterface extends ModuleInterface
{
    ## Public Methods ##
    function FaqInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('faq');
    }
    
	//Récupération du cache.
	function get_cache()
	{
		//Configuration
		$config = unserialize($this->db_connection->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'faq'", __LINE__, __FILE__));
		$root_config = $config['root'];
		$root_config['auth'] = $config['global_auth'];
		unset($config['root']);
		$string = 'global $FAQ_CONFIG, $FAQ_CATS, $RANDOM_QUESTIONS;' . "\n\n";
		$string .= '$FAQ_CONFIG = ' . var_export($config, true) . ';' . "\n\n";
		
		//List of categories and their own properties
		$string .= '$FAQ_CATS = array();' . "\n\n";
		$string .= '$FAQ_CATS[0] = ' . var_export($root_config, true) . ';' . "\n";
		$string .= '$FAQ_CATS[0][\'name\'] = \'\';' . "\n";
		$result = $this->db_connection->query_while("SELECT id, id_parent, c_order, auth, name, visible, display_mode, image, num_questions, description
		FROM " . PREFIX . "faq_cats
		ORDER BY id_parent, c_order", __LINE__, __FILE__);
		while ($row = $this->db_connection->fetch_assoc($result))
		{
			$string .= '$FAQ_CATS[' . $row['id'] . '] = ' .
				var_export(array(
				'id_parent' => $row['id_parent'],
				'order' => $row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'display_mode' => $row['display_mode'],
				'image' => $row['image'],
				'num_questions' => $row['num_questions'],
				'description' => $row['description'],
				'auth' => unserialize($row['auth'])
				),
			true)
			. ';' . "\n";
		}
		
		//Random questions
		$query = $this->db_connection->query_while ("SELECT id, question, idcat FROM " . PREFIX . "faq LIMIT 0, 20", __LINE__, __FILE__);
		$questions = array();
		while ($row = $this->db_connection->fetch_assoc($query))
			$questions[] = array('id' => $row['id'], 'question' => $row['question'], 'idcat' => $row['idcat']);
		
		$string .= "\n" . '$RANDOM_QUESTIONS = ' . var_export($questions, true) . ';';
		
		return $string;
	}

	/**
	 * @desc Returns the SQL query which will return the result of the researched keywords 
	 * @param $args string[] parameters of the research
	 * @return string The SQL query corresponding to the research.
	 */
	function get_search_request($args)
    {
        global $Cache;
		$Cache->load('faq');
		
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        require_once(PATH_TO_ROOT . '/faq/faq_cats.class.php');
        $Cats = new FaqCats();
        $auth_cats = array();
        $Cats->build_children_id_list(0, $auth_cats);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            f.id AS id_content,
            f.question AS title,
            ( 2 * MATCH(f.question) AGAINST('" . $args['search'] . "') + MATCH(f.answer) AGAINST('" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
            . $this->db_connection->concat("'../faq/faq.php?id='","f.idcat","'&amp;question='","f.id","'#q'","f.id") . " AS link
            FROM " . PREFIX . "faq f
            WHERE ( MATCH(f.question) AGAINST('" . $args['search'] . "') OR MATCH(f.answer) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $this->db_connection->limit(0, FAQ_MAX_SEARCH_RESULTS);
        
        return $request;
    }
	
    
    /**
     * @desc Return the array containing the result's data list
     * @param &string[][] $args The array containing the result's id list
     * @return string[] The array containing the result's data list
     */
    function compute_search_results(&$args)
    {
        global $CONFIG;
        
        $results_data = array();
        
        $results =& $args['results'];
        $nb_results = count($results);
        
        $ids = array();
        for ($i = 0; $i < $nb_results; $i++)
            $ids[] = $results[$i]['id_content'];
        
        $request = "SELECT idcat, id, question, answer
            FROM " . PREFIX . "faq
            WHERE id IN (" . implode(',', $ids) . ")";
        
        $request_results = $this->db_connection->query_while ($request, __LINE__, __FILE__);
        while ($row = $this->db_connection->fetch_assoc($request_results))
        {
            $results_data[] = $row;
        }
        $this->db_connection->query_close($request_results);
        
        return $results_data;
    }
    
    /**
     *  @desc Return the string to print the result
     *  @param &string[] $result_data the result's data
     *  @return string[] The string to print the result of a search element
     */
    function parse_search_result(&$result_data)
    {
        $tpl = new Template('faq/search_result.tpl');
        
        $tpl->assign_vars(array(
            'U_QUESTION' => PATH_TO_ROOT . '/faq/faq.php?id=' . $result_data['idcat'] . '&amp;question=' . $result_data['id'] . '#q' . $result_data['id'],
            'QUESTION' => $result_data['question'],
            'ANSWER' => second_parse($result_data['answer'])
        ));
        
        return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
    }
    
    // Returns the module map objet to build the global sitemap
    /**
	 * @desc 
	 * @param $auth_mode
	 * @return unknown_type
     */
	function get_module_map($auth_mode = SITE_MAP_AUTH_GUEST)
	{
		global $FAQ_CATS, $FAQ_LANG, $LANG, $User, $FAQ_CONFIG, $Cache;
		
		import('content/sitemap/module_map');
		import('util/url');
		
		include_once(PATH_TO_ROOT . '/faq/faq_begin.php');
		
		$faq_link = new SiteMapLink($FAQ_LANG['faq'], new Url('/faq/faq.php'), SITE_MAP_FREQ_DEFAULT, SITE_MAP_PRIORITY_MAX);
		
		$module_map = new ModuleMap($faq_link);
		$module_map->set_description('<em>Test</em>');
		
		$id_cat = 0;
	    $keys = array_keys($FAQ_CATS);
		$num_cats = count($FAQ_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $FAQ_CATS[$id];
			if ($auth_mode == SITE_MAP_AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
			{
				$module_map->add($this->_create_module_map_sections($id, $auth_mode));
			}
		}
		
		return $module_map;
	}
	
	#Private#
	function _create_module_map_sections($id_cat, $auth_mode)
	{
		global $FAQ_CATS, $FAQ_LANG, $LANG, $User, $FAQ_CONFIG;
		
		$this_category = new SiteMapLink($FAQ_CATS[$id_cat]['name'], new Url('/faq/' . url('faq.php?id=' . $id_cat, 'faq-' . $id_cat . '+' . url_encode_rewrite($FAQ_CATS[$id_cat]['name']) . '.php')));
			
		$category = new SiteMapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($FAQ_CATS);
		$num_cats = count($FAQ_CATS);
		$properties = array();
		for ($j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $FAQ_CATS[$id];
			if ($auth_mode == SITE_MAP_AUTH_GUEST)
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($FAQ_CONFIG['global_auth'], AUTH_READ);
			}
			if ($this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat)
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
