<?php
/*##################################################
 *                              video_interface.class.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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
require_once(PATH_TO_ROOT . '/kernel/framework/modules/module_interface.class.php');

define('VIDEO_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class VideoInterface extends ModuleInterface
{
    ## Public Methods ##
    function VideoInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('video');
    }
    
	//Récupération du cache.
	function get_cache()
	{
		global $Sql;
	
		//Configuration
		$config = sunserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'video'", __LINE__, __FILE__));
		$root_config = $config['root'];
		unset($config['root']);
		$string = 'global $VIDEO_CONFIG, $VIDEO_CATS;' . "\n\n" . '$VIDEO_CONFIG = $VIDEO_CATS = array();' . "\n\n";
		$string .= '$VIDEO_CONFIG = ' . var_export($config, true) . ';' . "\n\n";
		
		//List of categories and their own properties
		$string .= '$VIDEO_CATS[0] = ' . var_export($root_config, true) . ';' . "\n";
		$result = $Sql->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, num_video, description
		FROM ".PREFIX."video_cat
		ORDER BY id_parent, c_order ASC", __LINE__, __FILE__);
		
		while ($row = $Sql->fetch_assoc($result))
		{
			$string .= '$VIDEO_CATS[' . $row['id'] . '] = ' . 
				var_export(array(
				'id_parent' => (int)$row['id_parent'],
				'order' => (int)$row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'image' => $row['image'],
				'num_video' => (int)$row['num_video'],
				'description' => $row['description'],
				'auth' => sunserialize($row['auth'])
				),
			true)
			. ';' . "\n";
		}
		
		return $string;
	}

	//Changement de jour.
	/*
	function on_changeday()
	{
	
	}
	*/
	
	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql, $Cache;
		$Cache->load('video');
		
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        require_once(PATH_TO_ROOT . '/video/video_cats.class.php');
        $Cats = new VideoCats();
        $auth_cats = array();
        $Cats->build_children_id_list(0, $list);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS `id_search`,
            f.id AS `id_content`,
            f.question AS `title`,
            ( 2 * MATCH(f.question) AGAINST('" . $args['search'] . "') + MATCH(f.answer) AGAINST('" . $args['search'] . "') ) / 3 * " . $weight . " AS `relevance`, "
            . $Sql->concat("'../video/video.php?id='","f.idcat","'&amp;question='","f.id","'#q'","f.id") . " AS `link`
            FROM " . PREFIX . "video f
            WHERE ( MATCH(f.question) AGAINST('" . $args['search'] . "') OR MATCH(f.answer) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY `relevance` " . $Sql->limit(0, VIDEO_MAX_SEARCH_RESULTS);
        
        return $request;
    }
	
    function parse_search_results(&$args)
    /**
     *  Return the string to print the results
     */
    {
        global $Sql;
        
        require_once(PATH_TO_ROOT . '/kernel/begin.php');
        
        $Tpl = new Template('video/search_result.tpl');
        
        if( $this->get_attribute('ResultsReqExecuted') === false  || $this->got_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST) )
        {
            $ids = array();
            $results =& $args['results'];
            $newResults = array();
            $nbResults = count($results);
            for( $i = 0; $i < $nbResults; $i++ )
                $newResults[$results[$i]['id_content']] =& $results[$i];
            
            $results =& $newResults;
            
            $request = "SELECT `idcat`,`id`,`question`,`answer`
            FROM " . PREFIX . "video
            WHERE `id` IN (" . implode(',', array_keys($results)) . ")";
            $requestResults = $Sql->query_while($request, __LINE__, __FILE__);
            while( $row = $Sql->fetch_assoc($requestResults) )
            {
                $results[$row['id']] = $row;
            }
            $Sql->query_close($requestResults);
            
            $this->set_attribute('ResultsReqExecuted', true);
            $this->set_attribute('Results', $results);
            $this->set_attribute('ResultsIndex', 0);
        }
        
        $results = $this->get_attribute('Results');
        $indexes = array_keys($results);
        $indexSize = count($indexes);
        $resultsIndex = $this->get_attribute('ResultsIndex');
        $resultsIndex = $resultsIndex < $indexSize ? $resultsIndex : ($indexSize > 0 ? $indexSize - 1 : 0);
        $index = $indexes[$resultsIndex];
        $result =& $results[$index];
        
        $Tpl->assign_vars(array(
            'U_QUESTION' => PATH_TO_ROOT . '/video/video.php?id=' . $result['idcat'] . '&amp;question=' . $result['id'] . '#q' . $result['id'],
            'QUESTION' => $result['question'],
            'ANSWER' => second_parse($result['answer'])
        ));
        
        $this->set_attribute('ResultsIndex', ++$resultsIndex);
        
        return $Tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    // Returns the module map objet to build the global sitemap
	function get_module_map($auth_mode = SITE_MAP_AUTH_GUEST)
	{
		global $Cache, $VIDEO_LANG;
		include_once(PATH_TO_ROOT . '/kernel/framework/sitemap/modulemap.class.php');
		include_once(PATH_TO_ROOT . '/video/video_begin.php');
		
		$module_map = new ModuleMap($VIDEO_LANG['faq']);
		$module_map->push_element($this->_create_module_map_sections(0, $auth_mode));
		
		$this->_create_module_map_sections(0, $module_map);
		
		return $module_map;
	}
	
	#Private#
	function _create_module_map_sections($id_cat, $auth_mode)
	{
		global $VIDEO_CATS, $VIDEO_LANG, $LANG, $User, $VIDEO_CONFIG;
		
		if( $id_cat > 0 )
			$this_category = new SitemapLink($VIDEO_CATS[$id_cat]['name'], HOST . DIR . '/video/' . transid('video.php?cat=' . $id_cat, 'video-' . $id_cat . '+' . url_encode_rewrite($VIDEO_CATS[$id_cat]['name']) . '.php'));
		else
			$this_category = new SitemapLink($VIDEO_LANG['all_cats'], HOST . DIR . '/video/video.php');
			
		$category = new SitemapSection($this_category);
		
		$i = 0;
		
		$keys = array_keys($VIDEO_CATS);
		$num_cats = count($VIDEO_CATS);
		$properties = array();
		for( $j = 0; $j < $num_cats; $j++)
		{
			$id = $keys[$j];
			$properties = $VIDEO_CATS[$id];
			if( $auth_mode == SITE_MAP_AUTH_GUEST )
			{
				$this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], AUTH_READ) : Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $VIDEO_CATS[0]['auth'], AUTH_READ);
			}
			else
			{
				$this_auth = is_array($properties['auth']) ? $User->check_auth($properties['auth'], AUTH_READ) : $User->check_auth($VIDEO_CATS[0]['auth'], AUTH_READ);
			}
			if( $this_auth && $id != 0 && $properties['visible'] && $properties['id_parent'] == $id_cat )
			{
				$category->push_element($this->_create_module_map_sections($id, $auth_mode));
				$i++;
			}
		}
		
		if( $i == 0	)
			$category = $this_category;
		
		return $category;
	}
}

?>
