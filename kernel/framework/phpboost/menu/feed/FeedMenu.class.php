<?php
/*##################################################
 *                          FeedMenu.class.php
 *                            -------------------
 *   begin                : January 14, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 * @package {@package}
 */
class FeedMenu extends Menu
{
	const FEED_MENU__CLASS = 'FeedMenu';
	
	/**
	 * @var string the feed url
	 */
	public $url = '';
	public $module_id = '';
	public $name = '';
	public $category = 0;
	public $number = 10;
	public $begin_at = 0;
	
	public function __construct($title, $module_id, $category = 0, $name = Feed::DEFAULT_FEED_NAME, $number = 10, $begin_at = 0)
	{
		parent::__construct($title);
		$this->module_id = $module_id;
		$this->category = $category;
		$this->name = $name;
		$this->number = $number;
		$this->begin_at = $begin_at;
	}

	/**
	 * @desc Returns the tpl to parse a feed
	 * @param string $name The feed name
	 * @param string $block_position The indentifier block position defined in the inherit class menu
	 * @return the tpl to parse a feed
     * @static
	 */
	public static function get_template($name = '', $block_position = Menu::BLOCK_POSITION__LEFT, $hidden_with_small_screens = false)
	{
		$tpl = new FileTemplate('framework/menus/feed.tpl');
		$tpl->put_all(array(
			'NAME' => $name,
			'C_NAME' => !empty($name),
			'C_VERTICAL_BLOCK' => ($block_position == Menu::BLOCK_POSITION__LEFT || $block_position == Menu::BLOCK_POSITION__RIGHT),
			'C_HIDDEN_WITH_SMALL_SCREENS' => $hidden_with_small_screens
		));
		
		return $tpl;
	}

	## Getters ##
	/**
	 * @return string the feed menu module id
	 */
	public function get_module_id() { return $this->module_id; }
	
	/**
	* @param bool $relative If false, compute the absolute url, else, returns the relative one
	* @return Return the absolute feed Url
	*/
	public function get_url($relative = false)
	{
		$url = DispatchManager::get_url('/syndication', '/rss/' . $this->module_id . '/' . $this->category . '/' . $this->name . '/');
		if ($relative)
		{
			return $url->relative();
		}
		return $url->absolute();
	}

	## Setters ##
	/**
	 * @param string $value the feed's module_id
	 */
	public function set_module_id($value) { $this->module_id = $value; }
	/**
	 * @param int $value the feed's category
	 */
	public function set_cat($value) { $this->category = is_numeric($value) ? NumberHelper::numeric($value) : 0; }
	/**
	 * @param string $value the feed's name
	 */
	public function set_name($value) { $this->name = $value; }
	
	/**
	* @return Return the number of elements displayed in the menu
	*/
	public function get_number()
	{
		return $this->number;
	}
	
	/**
	 * @param string $value the number of elements displayed in the menu
	 */
	public function set_number($value) { $this->number = $value; }
	
	public function display()
	{
		$filters = $this->get_filters();
		$is_displayed = empty($filters) || $filters[0]->get_pattern() == '/';
		
		foreach ($filters as $key => $filter) 
		{
			if ($filter->get_pattern() != '/' && $filter->match())
			{
				$is_displayed = true;
				break;
			}
		}
		
		if ($is_displayed)
		{
			return Feed::get_parsed($this->module_id, $this->name, $this->category, self::get_template($this->get_title(), $this->get_block(), $this->hidden_with_small_screens), $this->number, $this->begin_at);
		}
		return '';
	}

}
?>