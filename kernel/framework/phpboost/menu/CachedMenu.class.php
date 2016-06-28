<?php
/*##################################################
 *                      	 CachedMenu.class.php
 *                            -------------------
 *   begin                : August 10, 2014
 *   copyright            : (C) 2014 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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
		$cached_link_menu = false;
		if ($menu instanceof LinksMenu)
		{
			$cached_link_menu = true;
			foreach ($menu->get_children() as $child)
			{
				if ($child->get_auth() != array('r-1' => Menu::MENU_AUTH_BIT, 'r0' => Menu::MENU_AUTH_BIT, 'r1' => Menu::MENU_AUTH_BIT))
				{
					$cached_link_menu = false;
					break;
				}
			}
		}
		return $menu instanceof SearchModuleMiniMenu || $cached_link_menu || $menu->get_auth() === null;
	}
}
?>