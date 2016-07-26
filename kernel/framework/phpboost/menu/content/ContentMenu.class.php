<?php
/*##################################################
 *                          ContentMenu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loic Rouchon
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
class ContentMenu extends Menu
{
	const CONTENT_MENU__CLASS = 'ContentMenu';

	/**
	 * @var string the menu's content
	 */
	public $content = '';

	/**
	 * @var bool If true, the content menu title will be displayed
	 */
	public $display_title = true;

	public function __construct($title)
	{
	   parent::__construct($title);
	}

	/**
	 * @desc Display the content menu.
	 * @return a string of the parsed template ready to be displayed
	 */
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
			$tpl = new FileTemplate('framework/menus/content.tpl');
			$tpl->put_all(array(
				'C_DISPLAY_TITLE' => $this->display_title,
				'C_VERTICAL_BLOCK' => ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT),
				'TITLE' => $this->title,
				'CONTENT' => FormatingHelper::second_parse($this->content),
				'C_HIDDEN_WITH_SMALL_SCREENS' => $this->hidden_with_small_screens
			));
			return $tpl->render();
		}
		return '';
	}

	## Setters ##
	/**
	 * @param bool $display_title if false, the title won't be displayed
	 */
	public function set_display_title($display_title) { $this->display_title = $display_title; }

	/**
	 * @param string $content the content to set
	 */
	public function set_content($content) { $this->content = FormatingHelper::strparse($content, array(), false); }

	## Getters ##
	/**
	 * @desc Returns true if the title will be displayed
	 * @return bool true if the title will be displayed
	 */
	public function get_display_title() { return $this->display_title; }

	/**
	 * @return string the menu content
	 */
	public function get_content() { return $this->content; }
	
	public function need_cached_string()
	{
		return true;
	}
}
?>