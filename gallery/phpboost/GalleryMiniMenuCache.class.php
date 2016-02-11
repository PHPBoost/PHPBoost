<?php
/*##################################################
 *                               GalleryMiniMenuCache.class.php
 *                            -------------------
 *   begin                : June 29, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class GalleryMiniMenuCache implements CacheData
{
	private $pictures = array();
	
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->pictures = array();
		$config = GalleryConfig::load();
		$Gallery = new Gallery();
		
		$result = PersistenceContext::get_querier()->select("SELECT g.id, g.name, g.path, g.width, g.height, g.idcat, gc.auth
		FROM " . GallerySetup::$gallery_table . " g
		LEFT JOIN " . GallerySetup::$gallery_cats_table . " gc on gc.id = g.idcat
		WHERE g.aprob = 1
		ORDER BY RAND()
		LIMIT 30");
		
		while ($row = $result->fetch())
		{
			$row['auth'] = ($row['idcat'] == 0) ? $config->get_authorizations() : unserialize(stripslashes($row['auth']));
			//Calcul des dimensions avec respect des proportions.
			list($row['width'], $row['height']) = $Gallery->get_resize_properties($row['width'], $row['height']);
			
			$this->pictures[$row['id']] = $row;
		}
		$result->dispose();
	}
	
	public function get_pictures()
	{
		return $this->pictures;
	}
	
	public function picture_exists($id)
	{
		return array_key_exists($id, $this->pictures);
	}
	
	public function get_picture($id)
	{
		if ($this->picture_exists($id))
		{
			return $this->pictures[$id];
		}
		return null;
	}
	
	public function get_number_pictures()
	{
		return count($this->pictures);
	}
	
	/**
	 * Loads and returns the gallery cached data.
	 * @return GalleryMiniMenuCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'gallery', 'minimenu');
	}
	
	/**
	 * Invalidates the current gallery cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('gallery', 'minimenu');
	}
}
?>
