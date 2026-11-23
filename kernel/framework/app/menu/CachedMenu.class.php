<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 4.1 - 2014 08 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
*/

class CachedMenu
{
	private ?Menu $menu = null;
	private string $cached_string;

	public function __construct(Menu $menu)
	{
		$this->menu = $menu;
		$this->build_cached_string();
	}

	private function build_cached_string(): void
	{
		if (self::need_cached_string($this->menu))
		{
			$this->cached_string = $this->menu->display();
		}
	}

	public function get_menu(): Menu
	{
		return $this->menu;
	}

	public function get_cached_string(): string
	{
		return $this->cached_string;
	}

	public function has_cached_string(): bool
	{
		return !empty($this->cached_string);
	}

	public static function need_cached_string(Menu $menu): string
	{
		return $menu->need_cached_string();
	}
}
?>
