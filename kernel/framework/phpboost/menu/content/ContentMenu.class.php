<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\content
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 03 26
 * @since       PHPBoost 2.0 - 2008 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Kevin MASSY <reidlos@phpboost.com>
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
	 * Display the content menu.
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
				'ID' => $this->id,
				'TITLE' => $this->title,
				'CONTENT' => FormatingHelper::second_parse(TextHelper::htmlspecialchars_decode($this->content)),
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
	public function set_content($content) { 
        $this->content = FormatingHelper::strparse($content, array(), false); 
    }

	## Getters ##
	/**
	 * Returns true if the title will be displayed
	 * @return bool true if the title will be displayed
	 */
	public function get_display_title() { return $this->display_title; }

	/**
	 * @return string the menu content
	 */
	public function get_content() { return $this->content; }
}
?>
