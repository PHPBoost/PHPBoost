<?php
/*##################################################
 *                          download_interface.class.php
 *                            -------------------
 *   begin                : June 22, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
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

require_once(PATH_TO_ROOT . '/kernel/framework/modules/module_interface.class.php');

// Class DownloadInterface
//  Provides download module services to the kernel and extern modules
class DownloadInterface extends ModuleInterface
{
    ## Public Methods ##
    function DownloadInterface()
    {
        parent::ModuleInterface('download');
    }
  
	//Récupération du cache.
	function get_cache()
	{
		global $Sql;
	
		$code = 'global $DOWNLOAD_CATS;' . "\n" . 'global $CONFIG_DOWNLOAD;' . "\n\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_DOWNLOAD = unserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'download'", __LINE__, __FILE__));
		$CONFIG_DOWNLOAD['global_auth'] = sunserialize($CONFIG_DOWNLOAD['global_auth']);
		
		$code .= '$CONFIG_DOWNLOAD = ' . var_export($CONFIG_DOWNLOAD, true) . ';' . "\n";
		
		//Liste des catégories et de leurs propriétés
		$code .= '$DOWNLOAD_CATS = array();' . "\n\n";
		$result = $Sql->query_while("SELECT id, id_parent, c_order, auth, name, visible, icon, num_files, contents
		FROM ".PREFIX."download_cat
		ORDER BY id_parent, c_order", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$code .= '$DOWNLOAD_CATS[' . $row['id'] . '] = ' .
			var_export(array(
			'id_parent' => $row['id_parent'],
			'order' => $row['c_order'],
			'name' => $row['name'],
			'contents' => $row['contents'],
			'visible' => (bool)$row['visible'],
			'icon' => $row['icon'],
			'description' => $row['contents'],
			'num_files' => $row['num_files'],
			'auth' => sunserialize($row['auth'])
			), true)
			. ';' . "\n";
		}
		
		return $code;
	}

	//Changement de jour.
	function on_changeday()
	{
		global $Sql;
		
		//Publication des téléchargements en attente pour la date donnée.
		$result = $Sql->query_while("SELECT id, start, end
		FROM ".PREFIX."download
		WHERE start > 0 AND end > 0", __LINE__, __FILE__);
		$time = time();
		while($row = $Sql->fetch_assoc($result) )
		{
			//If the file wasn't visible and it becomes visible
			if( $row['start'] <= $time && $row['end'] >= $time && $row['visible'] = 0 )
				$Sql->query_inject("UPDATE ".PREFIX."download SET visible = 1 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			
			//If it's not visible anymore
			if( $row['start'] >= $time || $row['end'] <= $time && $row['visible'] = 1 )
				$Sql->query_inject("UPDATE ".PREFIX."download SET visible = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}

	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql, $Cache;
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		$Cache->load('download');
		
        require_once(PATH_TO_ROOT . '/download/download_cats.class.php');
        $cats = new DownloadCats();
        $auth_cats = array();
        $cats->build_children_id_list(0, $list);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS `id_search`,
            d.id AS `id_content`,
            d.title AS `title`,
            ( 3 * MATCH(d.title) AGAINST('" . $args['search'] . "') + 2 * MATCH(d.short_contents) AGAINST('" . $args['search'] . "') + MATCH(d.contents) AGAINST('" . $args['search'] . "') ) / 6 * " . $weight . " AS `relevance`, "
            . $Sql->concat("'" . PATH_TO_ROOT . "/download/download.php?id='","d.id") . " AS `link`
            FROM " . PREFIX . "download d
            WHERE ( MATCH(d.title) AGAINST('" . $args['search'] . "') OR MATCH(d.short_contents) AGAINST('" . $args['search'] . "') OR MATCH(d.contents) AGAINST('" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY `relevance` " . $Sql->limit(0, FAQ_MAX_SEARCH_RESULTS);
        
        return $request;

    }
    
    function parse_search_results(&$args)
    /**
     *  Return the string to print the results
     */
    {
        global $Sql, $Cache, $CONFIG, $LANG, $DOWNLOAD_LANG, $CONFIG_DOWNLOAD;
        $Cache->load('download');
        
        require_once(PATH_TO_ROOT . '/kernel/begin.php');
        load_module_lang('download'); //Chargement de la langue du module.
        
        $Tpl = new Template('download/download_generic_results.tpl');
        
        if( $this->get_attribute('ResultsReqExecuted') === false  || $this->got_error(MODULE_ATTRIBUTE_DOES_NOT_EXIST) )
        {
            $ids = array();
            $results =& $args['results'];
            $newResults = array();
            $nbResults = count($results);
            for( $i = 0; $i < $nbResults; $i++ )
                $newResults[$results[$i]['id_content']] =& $results[$i];
            
            $results =& $newResults;
            
            $request = "SELECT `id`,`idcat`,`title`,`short_contents`,`url`,`note`,`image`,`count`,`timestamp`,`nbr_com`
            FROM " . PREFIX . "download
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
        
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        $date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, $result['timestamp']);
        require_once(PATH_TO_ROOT . '/kernel/framework/content/note.class.php');
        $Note = new Note(null, null, null, null, '', NOTE_NO_CONSTRUCT);
        $Tpl->assign_vars(array(
            'L_ADDED_ON' => sprintf($DOWNLOAD_LANG['add_on_date'], $date->format(DATE_FORMAT_TINY, TIMEZONE_USER)),
            'U_LINK' => transid(PATH_TO_ROOT . '/download/download.php?id=' . $result['id']),
            'U_IMG' => $result['image'],
            'E_TITLE' => strprotect($result['title']),
            'TITLE' => $result['title'],
            'SHORT_DESCRIPTION' => $result['short_contents'],
            'L_NB_DOWNLOADS' => $DOWNLOAD_LANG['downloaded'] . ' ' . sprintf($DOWNLOAD_LANG['n_times'], $result['count']),
            'L_NB_COMMENTS' => $result['nbr_com'] > 1 ? sprintf($DOWNLOAD_LANG['num_com'], $result['nbr_com']) : sprintf($DOWNLOAD_LANG['num_coms'], $result['nbr_com']),
            'L_MARK' => $result['note'] > 0 ? $Note->display_img((int)$result['note'], $CONFIG_DOWNLOAD['note_max'], 5) : ('<em>' . $LANG['no_note'] . '</em>')
        ));
        
        $this->set_attribute('ResultsIndex', ++$resultsIndex);
        
        return $Tpl->parse(TEMPLATE_STRING_MODE);
    }
    
	
    // Generate the feed data structure used by RSS, ATOM and feed informations on the website
    function get_feed_data_struct($idcat = 0)
    {
        require_once(PATH_TO_ROOT . '/download/download_auth.php');
        require_once(PATH_TO_ROOT . '/download/download_cats.class.php');
        require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');
        require_once(PATH_TO_ROOT . '/kernel/framework/content/syndication/feed_data.class.php');
        
        global $Cache, $Sql, $LANG, $DOWNLOAD_LANG, $CONFIG, $CONFIG_DOWNLOAD, $DOWNLOAD_CATS;
		load_module_lang('download');
        $Cache->load('download');
        $data = new FeedData();
        $date = new Date();
        
        // Meta-informations generation
        $data->set_title($DOWNLOAD_LANG['xml_download_desc']);
        $data->set_date($date);
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'download/syndication.php');
        $data->set_host(HOST);
        $data->set_desc($DOWNLOAD_LANG['xml_download_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(READ_CAT_DOWNLOAD);
		
        
        // Building Categories to look in
        $cats = new DownloadCats();
        $children_cats = array();
        $cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);
        
        $req = "SELECT id, idcat, title, contents, timestamp, image
        FROM ".PREFIX."download
        WHERE visible = 1 AND idcat IN (" . implode($children_cats, ','). " )
        ORDER BY timestamp DESC" . $Sql->limit(0, $CONFIG_DOWNLOAD['nbr_file_max']);
        $result = $Sql->query_while($req, __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
            
            // Rewriting
            $link = HOST . DIR . '/download/download' . transid('.php?id=' . $row['id'], '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php');
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
            
            // Adding item's informations
            $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
            $item->set_date($date);
            $item->set_image_url($row['image']);
            $item->set_auth($cats->compute_heritated_auth($row['idcat'], READ_CAT_DOWNLOAD, AUTH_PARENT_PRIORITY));
            
            // Adding the item to the list
            $data->add_item($item);
        }
        $Sql->query_close($result);
        
        return $data;
    }
    
    ## Private ##
    function _check_cats_auth($id_cat, &$list)
    {
        global $DOWNLOAD_CATS, $CONFIG_DOWNLOAD;

        if( $id_cat == 0 )
        {
            if( Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $CONFIG_DOWNLOAD['global_auth'], READ_CAT_DOWNLOAD) )
                $list[] = 0;
            else
                return;
        }
        else
        {
			if( !empty($DOWNLOAD_CATS[$id_cat]) )
			{
				$auth = !empty($DOWNLOAD_CATS[$id_cat]['auth']) ? $DOWNLOAD_CATS[$id_cat]['auth'] : $CONFIG_DOWNLOAD['global_auth'];
				if( Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $auth, READ_CAT_DOWNLOAD) )
					$list[] = $id_cat;
            }
			else
                return;
        }
        
        $keys = array_keys($DOWNLOAD_CATS);
        $num_cats = count($DOWNLOAD_CATS);
        
        $properties = array();
        for( $j = 0; $j < $num_cats; $j++)
        {
            $id = $keys[$j];
            $properties = $DOWNLOAD_CATS[$id];
            
            if( $properties['id_parent'] == $id_cat )
            {
                $this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], READ_CAT_DOWNLOAD) :  Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $CONFIG_DOWNLOAD['global_auth'], READ_CAT_DOWNLOAD);
                
                if( $this_auth )
                {
                    $list[] = $id;
                    $this->_check_cats_auth($id, $list);
                }
            }
        }
    }
}

?>
