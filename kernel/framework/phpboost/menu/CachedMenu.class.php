<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 01 28
 * @since       PHPBoost 4.1 - 2014 08 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class CachedMenu
{
	private $menu;
	private $cached_string;

	public function __construct(Menu $menu)
	{
		$this->menu = $menu;
		$this->build_cached_string();
	}

	private function build_cached_string()
	{
		if (self::need_cached_string($this->menu))
		{
			$this->cached_string = $this->menu->display();
		}
	}

	public function get_menu()
	{
		return $this->menu;
	}

	public function get_cached_string()
	{
		return $this->cached_string;
	}

	public function has_cached_string()
	{
		return !empty($this->cached_string);
	}

	public static function need_cached_string(Menu $menu)
	{
		return $menu->need_cached_string();
	}
}
?>
