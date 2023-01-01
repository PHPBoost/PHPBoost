<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\feed
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 07
 * @since       PHPBoost 2.0 - 2009 01 14
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	 * Returns the tpl to parse a feed
	 * @param string $id The feed id
	 * @param string $name The feed name
	 * @param string $block_position The indentifier block position defined in the inherit class menu
	 * @return the tpl to parse a feed
     * @static
	 */
	public static function get_template($id, $name = '', $block_position = Menu::BLOCK_POSITION__LEFT, $hidden_with_small_screens = false, $module_id = '')
	{
		$theme_id = AppContext::get_current_user()->get_theme();
		if (!empty($module_id) && file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/' . $module_id . '/feed.tpl'))
			$tpl = new FileTemplate('/templates/' . $theme_id . '/modules/' . $module_id . '/feed.tpl');
		elseif (!empty($module_id) && file_exists(PATH_TO_ROOT . '/' . $module_id . '/templates/feed.tpl'))
			$tpl = new FileTemplate('/' . $module_id . '/templates/feed.tpl');
		else
			$tpl = new FileTemplate('framework/menus/feed.tpl');

		$tpl->put_all(array(
			'NAME' => $name,
			'ID' => $id,
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
			return Feed::get_parsed($this->module_id, $this->name, $this->category, self::get_template($this->id, $this->get_title(), $this->get_block(), $this->hidden_with_small_screens, $this->module_id), $this->number, $this->begin_at);
		}
		return '';
	}
}
?>
