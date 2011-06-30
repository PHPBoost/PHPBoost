<?php
/*##################################################
 *                          feed_menu.class.php
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
	private $url = '';
	private $module_id = '';
	private $name = '';
	private $category = 0;
	private $number = 10;
	private $begin_at = 0;
	
	public function __construct($title, $module_id, $category = 0, $name = Feed::DEFAULT_FEED_NAME, $number = 10, $begin_at = 0)
	{
		parent::__construct($title);
		$this->module_id = $module_id;
		$this->category = $category;
		$this->name = $name;
		$this->number = $number;
		$this->begin_at = $begin_at;
	}

	public function cache_export($template = false)
	{
        return parent::cache_export_begin() .
            '\';import(\'content/syndication/feed\');$__menu=Feed::get_parsed(' .
		    var_export($this->module_id, true) . ',' . var_export($this->name, true) . ',' .
		    $this->category . ',FeedMenu::get_template(' . var_export($this->get_title(), true) . ', ' . var_export($this->get_block(), true) . '),' . $this->number . ',' . $this->begin_at . ');' .
            '$__menu.=\'' . parent::cache_export_end();
	}

	/**
	 * @desc Returns the tpl to parse a feed
	 * @param string $name The feed name
	 * @param string $block_position The indentifier block position defined in the inherit class menu
	 * @return the tpl to parse a feed
     * @static
	 */
	public static function get_template($name = '', $block_position = Menu::BLOCK_POSITION__LEFT)
	{
		$tpl = new FileTemplate('framework/menus/feed/feed.tpl');

		$tpl->put_all(array(
			'NAME' => $name,
			'C_NAME' => !empty($name),
			'C_VERTICAL_BLOCK' => ($block_position == Menu::BLOCK_POSITION__LEFT || $block_position == Menu::BLOCK_POSITION__RIGHT)
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
		
		$url = new Url('/syndication.php?m=' . $this->module_id . '&amp;cat=' . $this->category . '&amp;name=' . $this->name);
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

	public function display($template = false)
	{
		return Feed::get_parsed($this->module_id, $this->name, $this->category,
		    self::get_template($this->get_title(), $this->get_block()), $this->number, $this->begin_at
		);
	}

}

?>