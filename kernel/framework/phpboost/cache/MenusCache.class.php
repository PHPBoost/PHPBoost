<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 4.1 - 2014 08 10
*/

class MenusCache implements CacheData
{
	private $menus = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$this->menus = array();

		$menus = MenuService::get_menu_list();
		foreach ($menus as $menu)
		{
			if ($menu->get_block() != Menu::BLOCK_POSITION__NOT_ENABLED && $menu->is_enabled())
			{
				$this->menus[] = new CachedMenu($menu);
			}
		}
	}

	public function get_menus()
	{
		return $this->menus;
	}

	/**
	 * Loads and returns the menus cached data.
	 * @return MenusCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'menus');
	}

	/**
	 * Invalidates the current menus cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'menus');
	}
}
?>
