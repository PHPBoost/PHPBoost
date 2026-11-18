<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 4.1 - 2014 08 10
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MenusCache implements CacheData
{
	private array $menus = [];

	public function synchronize(): void
    {
		$this->menus = [];

		$menus = MenuService::get_menu_list();
		foreach ($menus as $menu)
		{
			if ($menu->get_block() != Menu::BLOCK_POSITION__NOT_ENABLED && $menu->is_enabled())
			{
				$this->menus[] = new CachedMenu($menu);
			}
		}
	}

	public function get_menus(): array
	{
		return $this->menus;
	}

	/**
	 * Loads and returns the menus cached data.
	 * @return MenusCache The cached data
	 */
	public static function load(): MenusCache
	{
		return CacheManager::load(self::class, 'kernel', 'menus');
	}

	/**
	 * Invalidates the current menus cached data.
	 */
	public static function invalidate(): void
	{
		CacheManager::invalidate('kernel', 'menus');
	}
}
?>
