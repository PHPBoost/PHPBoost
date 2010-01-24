<?php
/*##################################################
 *                              media_interface.class.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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

// Inclusion du fichier contenant la classe ModuleInterface


define('MEDIA_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class MediaInterface extends ModuleInterface
{
    ## Public Methods ##
    function MediaInterface() //Constructeur de la classe ForumInterface
    {
        parent::__construct('media');
    }

	//Récupération du cache.
	function get_cache()
	{
		require_once PATH_TO_ROOT . '/media/media_constant.php';

		//Configuration
		$i = 0;
		$config = array();
		$config = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'media'", __LINE__, __FILE__));
		$root_config = $config['root'];
		unset($config['root']);

		$string = 'global $MEDIA_CONFIG, $MEDIA_CATS;' . "\n\n" . '$MEDIA_CONFIG = $MEDIA_CATS = array();' . "\n\n";
		$string .= '$MEDIA_CONFIG = ' . var_export($config, true) . ';' . "\n\n";

		//List of categories and their own properties
		$string .= '$MEDIA_CATS[0] = ' . var_export($root_config, true) . ';' . "\n\n";
		$result = $this->sql_querier->query_while("SELECT * FROM " . PREFIX . "media_cat ORDER BY id_parent, c_order ASC", __LINE__, __FILE__);

		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$string .= '$MEDIA_CATS[' . $row['id'] . '] = ' . var_export(array(
				'id_parent' => (int)$row['id_parent'],
				'order' => (int)$row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'image' => $row['image'],
				'num_media' => (int)$row['num_media'],
				'mime_type' => (int)$row['mime_type'],
				'active' => (int)$row['active'],
				'auth' => (array)unserialize($row['auth'])
			), true) . ';' . "\n\n";
		}

		$this->sql_querier->query_close($result);

		return $string;
	}

	// Generate the feed data structure used by RSS, ATOM and feed informations on the website
    function get_feed_data_struct($idcat = 0)
    {
    	global $Cache, $LANG, $MEDIA_LANG, $CONFIG, $MEDIA_CONFIG, $MEDIA_CATS;
        
        $Cache->load('media');
		load_module_lang('media');

        require_once(PATH_TO_ROOT . '/media/media_constant.php');
		
		
		
        
        $data = new FeedData();
        
        // Meta-informations generation
        $data->set_title($MEDIA_LANG['xml_media_desc']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=media&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($MEDIA_LANG['xml_media_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(MEDIA_AUTH_READ);

        // Building Categories to look in
        $cats = new MediaCats();
        $children_cats = array();
        $cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);

        $result = $this->sql_querier->query_while ("SELECT id, idcat, name, contents, timestamp FROM " . PREFIX . "media WHERE infos = '" . MEDIA_STATUS_APROBED . "' AND idcat IN (" . implode($children_cats, ','). " ) ORDER BY timestamp DESC" . $this->sql_querier->limit(0, $MEDIA_CONFIG['pagin']), __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $this->sql_querier->fetch_assoc($result))
        {
            $item = new FeedItem();
            
            // Rewriting
            $link = new Url('/media/media' . url(
                '.php?id=' . $row['id'],
                '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'
            ));
            
            // Adding item's informations
            $item->set_title($row['name']);
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(FormatingHelper::second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
            $item->set_image_url($MEDIA_CATS[$row['idcat']]['image']);
            $item->set_auth($cats->compute_heritated_auth($row['idcat'], MEDIA_AUTH_READ, Authorizations::AUTH_PARENT_PRIORITY));
            
            // Adding the item to the list
            $data->add_item($item);
        }

        $this->sql_querier->query_close($result);
        
        return $data;
    }
    
    function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Cache;
		$Cache->load('media');
		
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        $Cats = new MediaCats();
        $auth_cats = array();
        $Cats->build_children_id_list(0, $auth_cats);
        
        $auth_cats = !empty($auth_cats) ? " AND f.idcat IN (" . implode($auth_cats, ',') . ") " : '';
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            f.id AS id_content,
            f.name AS title,
            ( 2 * FT_SEARCH_RELEVANCE(f.name, '" . $args['search'] . "') + FT_SEARCH_RELEVANCE(f.contents, '" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
            . $this->sql_querier->concat("'../media/media.php?id='","f.id","'&amp;cat='","f.idcat") . " AS link
            FROM " . PREFIX . "media f
            WHERE ( FT_SEARCH(f.name, '" . $args['search'] . "') OR FT_SEARCH(f.contents, '" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $this->sql_querier->limit(0, MEDIA_MAX_SEARCH_RESULTS);
        
        return $request;
    }
    
	/**
     * @desc Return the list of the feeds available for this module. 
     * @return FeedsList The list
     */
    function get_feeds_list()
	{
        $media_cats = new MediaCats();
        return $media_cats->get_feeds_list();	    
	}
}

?>
