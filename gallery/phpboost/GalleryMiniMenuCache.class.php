<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 29
 * @since       PHPBoost 4.1 - 2015 06 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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

		$result = PersistenceContext::get_querier()->select("SELECT g.id, g.name, g.path, g.width, g.height, g.id_category, gc.auth
		FROM " . GallerySetup::$gallery_table . " g
		LEFT JOIN " . GallerySetup::$gallery_cats_table . " gc on gc.id = g.id_category
		WHERE g.aprob = 1
		ORDER BY RAND()
		LIMIT 30");

		while ($row = $result->fetch())
		{
			$row['auth'] = ($row['id_category'] == Category::ROOT_CATEGORY) ? $config->get_authorizations() : TextHelper::unserialize(stripslashes($row['auth']));
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
