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

define('MEDIA_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class MediaInterface extends ModuleInterface
{
    ## Public Methods ##
    function MediaInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('media');
    }

	//Récupération du cache.
	function get_cache()
	{
		global $Sql;
		
		require_once('media_constant.php');

		//Configuration
		$i = 0;
		$config = $auth_cats = array();
		$config = unserialize($Sql->query("SELECT value FROM " . PREFIX . "configs WHERE name = 'media'", __LINE__, __FILE__));
		$root_config = $config['root'];
		unset($config['root']);
		
		if (!empty($root_config['auth']['r-1']) && ($root_config['auth']['r-1'] & 1))
		{
			$auth_cats[] = 0;
		}

		$string = 'global $MEDIA_CONFIG, $MEDIA_CATS, $MEDIA_MINI;' . "\n\n" . '$MEDIA_CONFIG = $MEDIA_CATS = $MEDIA_MINI = array();' . "\n\n";
		$string .= '$MEDIA_CONFIG = ' . var_export($config, true) . ';' . "\n\n";

		//List of categories and their own properties
		$string .= '$MEDIA_CATS[0] = ' . var_export($root_config, true) . ';' . "\n\n";
		$result = $Sql->query_while("SELECT * FROM " . PREFIX . "media_cat ORDER BY id_parent, c_order ASC", __LINE__, __FILE__);

		while ($row = $Sql->fetch_assoc($result))
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
				'auth' => (array)($auth = sunserialize($row['auth']))
			), true) . ';' . "\n\n";
			
			if (!empty($auth['r-1']) && ($auth['r-1'] & 1) !== 0)
			{
				$auth_cats[] = $row['id'];
			}
		}

		$Sql->query_close($result);

		$result = $Sql->query_while("SELECT id, idcat, name FROM " . PREFIX . "media WHERE infos = '" . MEDIA_STATUS_APROBED . "' AND idcat IN (" . implode($auth_cats, ',') . ") ORDER BY timestamp DESC" . $Sql->limit(0, NUM_MEDIA), __LINE__, __FILE__);
		
		while ($mini = $Sql->fetch_assoc($result))
		{
			$string .= '$MEDIA_MINI[' . $i . '] = ' . var_export($mini, true) . ';' . "\n\n";
			$i++;
		}
		
		$Sql->query_close($result);

		return $string;
	}

/*
	//Changement de jour.
	function on_changeday()
	{

	}
*/

	// Generate the feed data structure used by RSS, ATOM and feed informations on the website
    function get_feed_data_struct($idcat = 0)
    {
        require_once(PATH_TO_ROOT . '/media/media_constant.php');
        require_once(PATH_TO_ROOT . '/media/media_cats.class.php');
        import('util/date');
        import('content/syndication/feed_data');
        
        global $Cache, $Sql, $LANG, $MEDIA_LANG, $CONFIG, $MEDIA_CONFIG, $MEDIA_CATS;
        $Cache->load('media');
		load_module_lang('media');
        $data = new FeedData();
        $date = new Date();
        
        // Meta-informations generation
        $data->set_title($MEDIA_LANG['xml_media_desc']);
        $data->set_date($date);
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'syndication.php?m=media');
        $data->set_host(HOST);
        $data->set_desc($MEDIA_LANG['xml_media_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(MEDIA_AUTH_READ);

        // Building Categories to look in
        $cats = new MediaCats();
        $children_cats = array();
        $cats->build_children_id_list($idcat, $children_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST);
        
        $req = "SELECT id, idcat, name, contents, timestamp
        FROM " . PREFIX . "media
        WHERE infos = '" . MEDIA_STATUS_APROBED . "' AND idcat IN (" . implode($children_cats, ','). " )
        ORDER BY timestamp DESC" . $Sql->limit(0, $MEDIA_CONFIG['pagin']);
        $result = $Sql->query_while ($req, __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
            
            // Rewriting
            $link = HOST . DIR . '/media/media' . url('.php?id=' . $row['id'], '-' . $row['id'] .  '+' . url_encode_rewrite($row['name']) . '.php');
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
            
            // Adding item's informations
            $item->set_title(htmlspecialchars(html_entity_decode($row['name'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
            $item->set_date($date);
            $item->set_image_url($MEDIA_CATS[$row['idcat']]['image']);
            $item->set_auth($cats->compute_heritated_auth($row['idcat'], MEDIA_AUTH_READ, AUTH_PARENT_PRIORITY));
            
            // Adding the item to the list
            $data->add_item($item);
        }
        $Sql->query_close($result);
        
        return $data;
    }
}

?>
