<?php
/*##################################################
 *                              galleryExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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

if (defined('PHPBOOST') !== true) exit;

class GalleryExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('gallery');
    }

	//Récupération du cache.
	function get_cache()
	{
		$gallery_config = GalleryConfig::load();
		
		$cat_gallery = '$CAT_GALLERY = array();' . "\n\n";

		$result = $this->sql_querier->query_while("SELECT id, id_left, id_right, level, name, aprob, auth
		FROM " . PREFIX . "gallery_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$cat_gallery .= '$CAT_GALLERY[' . $row['id'] . '] = ' .
			var_export(array(
			'id_left' => $row['id_left'],
			'id_right' => $row['id_right'],
			'level' => $row['level'],
			'name' => $row['name'],
			'aprob' => $row['aprob'],
			'auth' => unserialize($row['auth'])
			), true)
			. ';' . "\n";
		}
		$this->sql_querier->query_close($result);
		
		$Gallery = new Gallery;

		$_array_random_pics = 'global $_array_random_pics;' . "\n" . '$_array_random_pics = array(';
		$result = $this->sql_querier->query_while("SELECT g.id, g.name, g.path, g.width, g.height, g.idcat, gc.auth
		FROM " . PREFIX . "gallery g
		LEFT JOIN " . PREFIX . "gallery_cats gc on gc.id = g.idcat
		WHERE g.aprob = 1 AND (gc.aprob = 1 OR g.idcat = 0)
		ORDER BY RAND()
		" . $this->sql_querier->limit(0, 30), __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			if ($row['idcat'] == 0)
				$row['auth'] = serialize($CONFIG_GALLERY['auth_root']);

			//Calcul des dimensions avec respect des proportions.
			list($width, $height) = $Gallery->get_resize_properties($row['width'], $row['height']);

			$_array_random_pics .= 'array(' . "\n" .
			'\'id\' => ' . var_export($row['id'], true) . ',' . "\n" .
			'\'name\' => ' . var_export($row['name'], true) . ',' . "\n" .
			'\'path\' => ' . var_export($row['path'], true) . ',' . "\n" .
			'\'width\' => ' . var_export($width, true) . ',' . "\n" .
			'\'height\' => ' . var_export($height, true) . ',' . "\n" .
			'\'idcat\' => ' . var_export($row['idcat'], true) . ',' . "\n" .
			'\'auth\' => ' . var_export(unserialize($row['auth']), true) . '),' . "\n";
		}
		$this->sql_querier->query_close($result);
		$_array_random_pics .= ');';

		return $cat_gallery . "\n" . $_array_random_pics;
	}
	
	function feeds()
	{
		return new GalleryFeedProvider();
	}
	
	public function css_files()
	{
		return new GalleryCssFilesExtensionPoint();
	}
	
	public function menus()
	{
		return new GalleryMenusExtensionPoint();
	}
	
	public function home_page()
	{
		return new GalleryHomePageExtensionPoint();
	}
}
?>