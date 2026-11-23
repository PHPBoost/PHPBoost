<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\feed
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 22
 * @since       PHPBoost 2.0 - 2009 01 14
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FeedMenu extends Menu
{
	const FEED_MENU__CLASS = 'FeedMenu';

	/**
	 * @var string the feed url
	 */
	public string $url = '';
	public string $module_id = '';
	public string $name = '';
	public int|string $category = 0;
	public int $number = 10;
	public int $begin_at = 0;

	public function __construct(string $title, string $module_id, int|string $category = 0, string $name = Feed::DEFAULT_FEED_NAME, int $number = 10, int $begin_at = 0)
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
	 * @param int $id The feed id
	 * @param string $name The feed name
	 * @param int $block_position The indentifier block position defined in the inherit class menu
	 * @return Template the tpl to parse a feed
     * @static
	 */
	public static function get_template(int $id, string $name = '', int $block_position = Menu::BLOCK_POSITION__LEFT, bool $hidden_with_small_screens = false, string $module_id = ''): Template
	{
		$theme_id = AppContext::get_current_user()->get_theme();
		if (!empty($module_id) && file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/' . $module_id . '/feed.tpl'))
        {
			$tpl = new FileTemplate('templates/' . $theme_id . '/modules/' . $module_id . '/feed.tpl');
        }
		elseif (!empty($module_id))
		{
			// Check new /modules structure with fallback to root
			$module_template_path = FileTemplateLoader::get_module_template_path($module_id);
			if (file_exists(PATH_TO_ROOT . $module_template_path . '/feed.tpl'))
            {
				$tpl = new FileTemplate($module_template_path . '/feed.tpl');
            }
			else
            {
				$tpl = self::get_default_feed_template();
            }
		}
		else
        {
			$tpl = self::get_default_feed_template();
        }

		$tpl->put_all([
			'NAME' => $name,
			'ID' => $id,
			'C_NAME' => !empty($name),
			'C_VERTICAL_BLOCK' => ($block_position == Menu::BLOCK_POSITION__LEFT || $block_position == Menu::BLOCK_POSITION__RIGHT),
			'C_HIDDEN_WITH_SMALL_SCREENS' => $hidden_with_small_screens
		]);

		return $tpl;
	}

	public function get_default_template(): FileTemplate
	{
		return self::get_default_feed_template();
	}

	private static function get_default_feed_template(): FileTemplate
	{
		return new FileTemplate('framework/menus/feed.tpl');
	}

	## Getters ##
	/**
	 * @return string the feed menu module id
	 */
	public function get_module_id(): string
    {
        return $this->module_id;
    }

	/**
	* @param bool $relative If false, compute the absolute url, else, returns the relative one
	* @return string the absolute feed Url
	*/
	public function get_url(bool $relative = false): string
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
	public function set_module_id(string $value)
    {
        $this->module_id = $value;
    }
	/**
	 * @param int $value the feed's category
	 */
	public function set_cat(int $value)
    {
        $this->category = is_numeric($value) ? NumberHelper::numeric($value) : 0;
    }
	/**
	 * @param string $value the feed's name
	 */
	public function set_name(string $value)
    {
        $this->name = $value;
    }

	/**
	* @return int the number of elements displayed in the menu
	*/
	public function get_number(): int
	{
		return $this->number;
	}

	/**
	 * @param string $value the number of elements displayed in the menu
	 */
	public function set_number(string $value)
    {
        $this->number = $value;
    }

	public function display(): string
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
