<?php
/*##################################################
 *   GalleryFeedProvider.class.php
 *   -----------------------------
 *   begin                : August 07, 2011
 *   copyright            : (C) 2011 Alain091
 *   email                : alain091@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

class GalleryFeedProvider implements FeedProvider
{
	function get_feeds_list()
	{
        global $LANG;

        $categories = $this->get_cats_tree();
        $cat_tree = new FeedsCat('gallery', 0, $LANG['root']);

        $this->feeds_add_category($cat_tree, $categories);

        $children = $cat_tree->get_children();
        $feeds = new FeedsList();
        if (count($children) > 0)
        {
            $feeds->add_feed($children[0], Feed::DEFAULT_FEED_NAME);
        }
        return $feeds;
	}
	
	/**
	 * @desc Returns an ordered tree with all categories informations
	 * @return array[] an ordered tree with all categories informations
	 */
	protected function get_cats_tree()
	{
		global $LANG,$CAT_GALLERY,$Cache;
		
		$Cache->load('gallery');
	  
		$CAT_GALLERY = is_array($CAT_GALLERY) ? $CAT_GALLERY : array();

		$ordered_cats = array();
		foreach ($CAT_GALLERY as $id => $cat)
		{   // Sort by id_left
			$cat['id'] = $id;
			$ordered_cats[(int)$cat['id_left']] = array('this' => $cat, 'children' => array());
		}
	  
		$level = 0;
		$cats_tree = array(array('this' => array('id' => 0, 'name' => $LANG['root']), 'children' => array()));
		$parent =& $cats_tree[0]['children'];
		$nb_cats = count($CAT_GALLERY);
		foreach ($ordered_cats as $cat)
		{
			if (($cat['this']['level'] == $level + 1) && count($parent) > 0)
			{   // The new parent is the previous cat
				$parent =& $parent[count($parent) - 1]['children'];
			}
			elseif ($cat['this']['level'] < $level)
			{   // Find the new parent (an ancestor)
				$j = 0;
				$parent =& $cats_tree[0]['children'];
				while ($j < $cat['this']['level'])
				{
					$parent =& $parent[count($parent) - 1]['children'];
					$j++;
				}
			}

			// Add the current cat at the good level
			$parent[] = $cat;
			$level = $cat['this']['level'];
		}
		return $cats_tree[0];
	}
	
	protected function feeds_add_category($cat_tree, $category)
	{
		$child = new FeedsCat('gallery', $category['this']['id'], $category['this']['name']);
		foreach ($category['children'] as $sub_category)
		{
			$this->feeds_add_category($child, $sub_category);
		}
		$cat_tree->add_child($child);
	}

	function get_feed_data_struct($idcat = 0, $name = '')
	{
		global $Cache,$LANG,$GALLERY_CAT,$GALLERY_LANG;

		$querier = PersistenceContext::get_querier();
		$gallery_config = GalleryConfig::load();
		
		if(!isset($gallery_config))
		{
			load_module_lang('gallery'); //Chargement de la langue du module.
			$Cache->load('gallery');
		}
		defined('READ_CAT_GALLERY') OR define('READ_CAT_GALLERY', 0x01);
			
		$data = new FeedData();

		$data->set_title($GALLERY_LANG['xml_gallery_desc']);
		$data->set_date(new Date());
		$data->set_link(SyndicationUrlBuilder::rss('gallery', $idcat));
		$data->set_host(HOST);
		$data->set_desc($GALLERY_LANG['xml_gallery_desc']);
		$data->set_lang($LANG['xml_lang']);
		$data->set_auth_bit(READ_CAT_GALLERY);

        $req_cats = (($idcat > 0) && isset($GALLERY_CAT[$idcat])) ? ' AND c.id_left >= :cat_left AND id_right <= :cat_right' : '';
        $parameters = array('limit' => 2 * $gallery_config->get_nbr_pics_max());
        if ($idcat > 0)
        {
        	$parameters['cat_left'] = $GALLERY_CAT[$idcat]['id_left'];
            $parameters['cat_right'] = $GALLERY_CAT[$idcat]['id_right'];
        }
        $req = 'SELECT g.*, gc.auth
			FROM ' . PREFIX . 'gallery g
			LEFT JOIN ' . PREFIX . 'gallery_cats gc ON gc.id = g.idcat
			WHERE gc.aprob = 1 ' .
				$req_cats . '
			ORDER BY g.timestamp DESC LIMIT :limit OFFSET 0';
        $results = $querier->select($req, $parameters);

		// Generation of the feed's items
		foreach ($results as $row)
		{
			$item = new FeedItem();

			$link = new Url(GalleryUrlBuilder::get_link_item($row['idcat'],$row['id']));

			$item->set_title($row['name']);
			$item->set_link($link);
			$item->set_guid($link);
			$item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
			$item->set_image_url($row['path']);
			$item->set_auth($row['idcat'] == 0 ? $gallery_config->get_authorization() : unserialize($row['auth']));

			$data->add_item($item);
		}
		$results->dispose();

		return $data;
	}
}
?>
