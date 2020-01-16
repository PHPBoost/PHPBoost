<?php
/**
 * This class represents a menu element and is used to build any kind of menu
 * @package     PHPBoost
 * @subpackage  Menu
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 2.0 - 2008 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class Menu
{
	const MENU_AUTH_BIT = 1;
	const MENU_ENABLE_OR_NOT = 42;
	const MENU_ENABLED = true;
	const MENU_NOT_ENABLED = false;

	const BLOCK_POSITION__NOT_ENABLED = 0;
	const BLOCK_POSITION__HEADER = 1;
	const BLOCK_POSITION__SUB_HEADER = 2;
	const BLOCK_POSITION__TOP_CENTRAL = 3;
	const BLOCK_POSITION__BOTTOM_CENTRAL = 4;
	const BLOCK_POSITION__TOP_FOOTER = 5;
	const BLOCK_POSITION__FOOTER = 6;
	const BLOCK_POSITION__LEFT = 7;
	const BLOCK_POSITION__RIGHT = 8;
	const BLOCK_POSITION__ALL = 9;

	const PUSHMENU_LEFT = 'left';
	const PUSHMENU_RIGHT = 'right';
	const PUSHMENU_TOP = 'top';
	const PUSHMENU_BOTTOM = 'bottom';

	const PUSHMENU_OVERLAP = 'overlap';
	const PUSHMENU_EXPAND = 'expand';
	const PUSHMENU_NONE = 'false';

	const MENU__CLASS = 'Menu';

	/**
	 * @access protected
	 * @var int the element identifier, only used by the service
	 */
	public $id = 0;
	/**
	 * @access protected
	 * @var string the Menu title
	 */
	public $title = '';
	/**
	 * @access protected
	 * @var int[string] Represents the Menu authorisations array
	 */
	public $auth = null;
	/**
	 * @access protected
	 * @var bool true if the Menu is used
	 */
	public $enabled = self::MENU_NOT_ENABLED;
	/**
	 * @access protected
	 * @var int The Menu block position
	 */
	public $block = self::BLOCK_POSITION__NOT_ENABLED;
	/**
	 * @access protected
	 * @var int The Menu position on the website
	 */
	public $position = -1;
	/**
	* @access protected
	* @var bool menu hidden or not with small screens
	*/
	protected $hidden_with_small_screens = false;
	/**
	* @access protected
	* @var bool disabled body content or not with push menu
	*/
	protected $disabled_body = false;
	/**
	* @access protected
	* @var bool push body content or not with push menu
	*/
	protected $pushed_content = false;
	/**
	 * @access protected
	 * @var string the position of the push menu
	 */
	public $pushmenu_opening = self::PUSHMENU_LEFT;
	/**
	 * @access protected
	 * @var string the position of the push menu
	 */
	public $pushmenu_expanding = self::PUSHMENU_OVERLAP;
	/**
	 * @access protected
	 * @var Array<Filter> The filter list
	 */
	public $filters = array();
	/**
	 * @access protected
	 * @var Template the template of the menu
	 */
	protected $template = null;


	/**
	 * Build a Menu element.
	 * @param string $title the Menu title
	 * @param int $id its id in the database
	 */
	public function __construct($title)
	{
		$this->title = TextHelper::strprotect($title, TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE);
		$this->filters[] = new MenuStringFilter('/');
	}

	/**
	 * Check if the menu needs to be cached
	 * @return bool true if the menu need to be cached
	 */
	public function need_cached_string()
	{
		return false;
	}

	/**
	 * Display the menu
	 * @abstract
	 * @return string the menu parsed in xHTML
	 */
	abstract public function display();

	/**
	 * Display the menu admin gui
	 * @return string the menu parsed in xHTML
	 */
	public function admin_display()
	{
		return $this->display();
	}

	/**
	 * @param int $id Set the Menu database id
	 */
	public function id($id) { $this->id = $id; }

	/**
	 * Assign tpl vars
	 * @access protected
	 * @param Template $template the template on which we gonna assign vars
	 */
	protected function _assign($template)
	{
		MenuService::assign_positions_conditions($template, $this->get_block());
	}

	/**
	 * Assign default tpl vars
	 * @access protected
	 * @param Template $template the template on which we gonna assign vars
	 */
	protected function assign_common_template_variables(Template $template)
	{
		$template->put_all(array(
			'C_VERTICAL_BLOCK' => ($this->get_block() == Menu::BLOCK_POSITION__LEFT || $this->get_block() == Menu::BLOCK_POSITION__RIGHT),
			'C_HIDDEN_WITH_SMALL_SCREENS' => $this->hidden_with_small_screens,
			'C_PUSHMENU_DISABLED_BODY' => $this->disabled_body,
			'C_PUSHMENU_PUSHED_CONTENT' => $this->pushed_content,
		));
	}

	/**
	 * Check the user authorization to see the LinksMenuElement
	 * @return bool true if the user is authorised, false otherwise
	 */
	public function check_auth()
	{
		return $this->auth === null || AppContext::get_current_user()->check_auth($this->auth, self::MENU_AUTH_BIT);
	}

	## Setters ##
	/**
	 * @param string $image the value to set
	 */
	public function set_title($title) { $this->title = TextHelper::strprotect($title, TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE); }
	/**
	 * @param array $url the authorisation array to set
	 */
	public function set_auth($auth) { $this->auth = $auth; }
	/**
	 * @param bool $enabled Enable or not the Menu
	 */
	public function enabled($enabled = self::MENU_ENABLED) { $this->enabled = $enabled; }
	/**
	 * @return int the Menu $block position
	 */
	public function set_block($block) { $this->block = $block; }
	/**
	 * @param int $position the Menu position to set
	 */
	public function set_block_position($position) { $this->position = $position; }
	/*
	* @param bool $value true if menu hidden with small screens
	*/
	public function set_hidden_with_small_screens($value) { $this->hidden_with_small_screens = $value; }
	/*
	* @param bool $value true if body is disabled with push menu
	*/
	public function set_disabled_body($value) { $this->disabled_body = $value; }
	/*
	* @param bool $value true if content is pushed with push menu
	*/
	public function set_pushed_content($value) { $this->pushed_content = $value; }
	/*
	* @param string $value opening type of the push menu
	*/
	public function set_pushmenu_opening($value) { $this->pushmenu_opening = $value; }
	/*
	* @param string $value expanding type of the push menu
	*/
	public function set_pushmenu_expanding($value) { $this->pushmenu_expanding = $value; }

	## Getters ##
	/**
	 * @return string the displayable Menu $title
	 */
	public function get_formated_title() { return $this->title; }
	/**
	 * @return string the Menu $title
	 */
	public function get_title() { return $this->title; }
	/**
	 * @return array the authorization array $auth
	 */
	public function get_auth() { return is_array($this->auth) ? $this->auth : array('r-1' => self::MENU_AUTH_BIT, 'r0' => self::MENU_AUTH_BIT, 'r1' => self::MENU_AUTH_BIT); }
	/**
	 * @return int the $id of the menu in the database
	 */
	public function get_id() { return $this->id; }
	/**
	 * @return int the Menu $block position
	 */
	public function get_block() { return $this->block; }
	/**
	 * @return int the Menu $position
	 */
	public function get_block_position() { return $this->position; }
	/**
	 * @return bool true if the Menu is enabled, false otherwise
	 */
	public function is_enabled() { return $this->enabled; }
	/**
	* @return bool check if menu is hidden with small screens
	*/
	public function is_hidden_with_small_screens() { return $this->hidden_with_small_screens; }
	/**
	* @return bool check if body is disabled (pushmenu)
	*/
	public function is_disabled_body() { return $this->disabled_body; }
	/**
	* @return bool check if body is pushed (pushmenu)
	*/
	public function is_pushed_content() { return $this->pushed_content; }
	/**
	* @return string the opening of the pushmenu
	*/
	public function get_pushmenu_opening() { return $this->pushmenu_opening; }
	/**
	* @return string the expanding of the pushmenu
	*/
	public function get_pushmenu_expanding() { return $this->pushmenu_expanding; }
	/**
	* @return string the menu filters
	*/
	public function get_filters() { return $this->filters; }

	/**
	* Sets the filters of the menu
	*
	* @param Array<Filter> $filters Filters of the menu
	*/
	public function set_filters($filters) { $this->filters = $filters; }

	 /**
	* Sets the template of the menu
	*
	* @param Template $template Template of the menu
	*/
	public function set_template(Template $template)
	{
		$this->template = $template;
	}

	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		if ($this->template !== null)
		{
			return $this->template;
		}
		else
		{
			return $this->get_default_template();
		}
	}

	/**
	* Get the default template of the menu
	* @abstract
	* @return string the default template of the menu
	*/
	protected function get_default_template() {}
}
?>
