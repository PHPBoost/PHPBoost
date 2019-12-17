<?php
/**
* Create a Menu with children.
* Children could be Menu or LinksMenuLink objects
 * @package     PHPBoost
 * @subpackage  Menu\links
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 07 08
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class LinksMenu extends LinksMenuElement
{
	const LINKS_MENU__CLASS = 'LinksMenu';

	## Menu types ##
	const AUTOMATIC_MENU = 'automatic';
	const VERTICAL_MENU = 'vertical';
	const HORIZONTAL_MENU = 'horizontal';
	const STATIC_MENU = 'static';

	/* Deprecated */
	const VERTICAL_SCROLLING_MENU = 'vertical_scrolling';
	const HORIZONTAL_SCROLLING_MENU = 'horizontal_scrolling';

	/**
	* @access protected
	* @var string menu's type
	*/
	public $type;
	/**
	* @access protected
	* @var LinksMenuElement[] Direct menu children list
	*/
	public $elements = array();

	/**
	* Constructor
	* @param string $title Menu title
	* @param string $url Destination url
	* @param string $image Menu's image url relative to the website root or absolute
	* @param string $type Menu's type
	* @param int $id The Menu's id in the database
	*/
	public function __construct($title, $url, $image = '', $type = self::AUTOMATIC_MENU)
	{
		// Set the menu type

		$this->type = $this->type == self::HORIZONTAL_SCROLLING_MENU ? self::HORIZONTAL_MENU : $this->type;
		$this->type = $this->type == self::VERTICAL_SCROLLING_MENU ? self::VERTICAL_MENU : $this->type;
		$this->type = in_array($type, self::get_menu_types_list()) ? $type : self::AUTOMATIC_MENU;

		// Build the menu element on witch is based the menu
		parent::__construct($title, $url, $image);
	}

	/**
	* Add a list of LinksMenu or (sub)Menu to the current one
	* @param LinksMenuElement[] $menu_elements A reference to a list of LinksMenuLink and / or Menu to add
	*/
	public function add_array($menu_elements)
	{
		foreach ($menu_elements as $element)
		{
			$this->add($element);
		}
	}

	/**
	* Add a single LinksMenuLink or (sub) Menu
	* @param LinksMenuElement $element the LinksMenuLink or Menu to add
	*/
	public function add($element)
	{
		if ($element instanceof _CLASS_)
		{
			$element->_parent($this->type);
		}
		else
		{
			$element->_parent($this->type);
		}

		$this->elements[] = $element;
	}

	/**
	* Update the menu uid
	*/
	public function update_uid()
	{
		parent::update_uid();
		foreach ($this->elements as $element)
		{
			$element->update_uid();
		}
	}

	/**
	* Display the menu
	* @param Template $template the template to use
	* @return string the menu parsed in xHTML
	*/
	public function display($template = false, $mode = LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING)
	{
		$filters = $this->get_filters();
		$is_displayed = empty($filters) || $filters[0]->get_pattern() == '/' || $mode != LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING;

		foreach ($filters as $key => $filter)
		{
			if ($filter->get_pattern() != '/' && $filter->match())
			{
				$is_displayed = true;
				break;
			}
		}

		if ($is_displayed && $this->check_auth())
		{
			// Get the good Template object
			if (!is_object($template) || !($template instanceof Template))
			{
				$tpl = new FileTemplate('framework/menus/links.tpl');
			}
			else
			{
				$tpl = $template;
			}
			$original_tpl = clone $tpl;

			// Children assignment
			$menu_with_submenu = false;
			$elements_number = 0;
			foreach ($this->elements as $element)
			{
				if ($element->check_auth())
				{
					// We use a new Tpl to avoid overwrite issues
					$tpl->assign_block_vars('elements', array('DISPLAY' => $element->display(clone $original_tpl, $mode)));
					$elements_number++;
				}
				if (get_class($element) == self::LINKS_MENU__CLASS)
				{
					$menu_with_submenu = true;
				}
			}

			// Menu assignment
			parent::_assign($tpl, $mode);
			$tpl->put_all(array(
				'C_MENU' => true,
				'C_MENU_WITH_SUBMENU' => $menu_with_submenu,
				'C_NEXT_MENU' => $this->depth > 0,
				'C_FIRST_MENU' => $this->depth == 0,
				'C_HAS_CHILD' => $elements_number,
				'C_HIDDEN_WITH_SMALL_SCREENS' => $this->hidden_with_small_screens,
				'DEPTH' => $this->depth
			));

			if ($this->type == self::AUTOMATIC_MENU)
			{
				$tpl->put_all(array(
					'C_MENU_CONTAINER' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT)),
					'C_MENU_HORIZONTAL' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__HEADER, Menu::BLOCK_POSITION__SUB_HEADER, Menu::BLOCK_POSITION__TOP_CENTRAL, Menu::BLOCK_POSITION__BOTTOM_CENTRAL)),
					'C_MENU_VERTICAL' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT)),
					'C_MENU_STATIC' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__TOP_FOOTER, Menu::BLOCK_POSITION__FOOTER)),
					'C_MENU_LEFT' => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
					'C_MENU_RIGHT' => $this->get_block() == Menu::BLOCK_POSITION__RIGHT
				));
			}
			else
			{
				$tpl->put_all(array(
					'C_MENU_CONTAINER' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT)),
					'C_MENU_HORIZONTAL' => $this->type == self::HORIZONTAL_MENU,
					'C_MENU_VERTICAL' => $this->type == self::VERTICAL_MENU,
					'C_MENU_STATIC' => $this->type == self::STATIC_MENU,
					'C_MENU_LEFT' => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
					'C_MENU_RIGHT' => $this->get_block() == Menu::BLOCK_POSITION__RIGHT
				));
			}

			return $tpl->render();
		}
		return '';
	}


	/**
	* @return string the string to write in the cache file
	*/
	public function cache_export($template = false)
	{
		// Get the good Template object
		if (!is_object($template) || !($template instanceof Template))
		{
			$tpl = new FileTemplate('framework/menus/links/links.tpl');
		}
		else
		{
			$tpl = clone $template;
		}
		$original_tpl = clone $tpl;

		// Children assignment
		$menu_with_submenu = false;
		$elements_number = 0;
		foreach ($this->elements as $element)
		{
			// We use a new Tpl to avoid overwrite issues
			$tpl->assign_block_vars('elements', array('DISPLAY' => $element->cache_export(clone $original_tpl)));
			$elements_number++;
			if (get_class($element) == self::LINKS_MENU__CLASS)
			{
				$menu_with_submenu = true;
			}
		}

		// Menu assignment
		parent::_assign($tpl, LinksMenuElement::LINKS_MENU_ELEMENT__CLASSIC_DISPLAYING);
		$tpl->put_all(array(
			'C_MENU' => true,
			'C_MENU_WITH_SUBMENU' => $menu_with_submenu,
			'C_NEXT_MENU' => $this->depth > 0,
			'C_FIRST_MENU' => $this->depth == 0,
			'C_HAS_CHILD' => $elements_number,
			'C_HIDDEN_WITH_SMALL_SCREENS' => $this->hidden_with_small_screens,
			'DEPTH' => $this->depth,
			'ID' => '##.#GET_UID#.##',
			'ID_VAR' => '##.#GET_UID_VAR#.##'
		));

		if ($this->type == self::AUTOMATIC_MENU)
		{
			$tpl->put_all(array(
				'C_MENU_CONTAINER' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT)),
				'C_MENU_HORIZONTAL' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__HEADER, Menu::BLOCK_POSITION__SUB_HEADER, Menu::BLOCK_POSITION__TOP_CENTRAL, Menu::BLOCK_POSITION__BOTTOM_CENTRAL)),
				'C_MENU_VERTICAL' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT)),
				'C_MENU_STATIC' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__TOP_FOOTER, Menu::BLOCK_POSITION__FOOTER)),
				'C_MENU_LEFT' => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
				'C_MENU_RIGHT' => $this->get_block() == Menu::BLOCK_POSITION__RIGHT
			));
		}
		else
		{
			$tpl->put_all(array(
				'C_MENU_CONTAINER' => in_array($this->get_block(), array(Menu::BLOCK_POSITION__LEFT, Menu::BLOCK_POSITION__RIGHT)),
				'C_MENU_HORIZONTAL' => $this->type == self::HORIZONTAL_MENU,
				'C_MENU_VERTICAL' => $this->type == self::VERTICAL_MENU,
				'C_MENU_STATIC' => $this->type == self::STATIC_MENU,
				'C_MENU_LEFT' => $this->get_block() == Menu::BLOCK_POSITION__LEFT,
				'C_MENU_RIGHT' => $this->get_block() == Menu::BLOCK_POSITION__RIGHT
			));
		}

		if ($this->depth == 0)
		{   // We protect and unprotect only on the top level
			$cache_str = parent::cache_export_begin() . '\'.' .
				var_export($tpl->render(), true) .
				'.\'' . parent::cache_export_end();
			$cache_str = str_replace(
				array('#GET_UID#', '#GET_UID_VAR#', '##'),
				array('($__uid = AppContext::get_uid())', '$__uid', '\''),
				$cache_str
			);
			return $cache_str;
		}
		return parent::cache_export_begin() . $tpl->render() . parent::cache_export_end();
	}

	/**
	* static method which returns all the menu types
	*
	* @return string[] The list of the menu types
	* @static
	*/
	public static function get_menu_types_list()
	{
		return array(self::AUTOMATIC_MENU, self::VERTICAL_MENU, self::HORIZONTAL_MENU, self::STATIC_MENU);
	}

	/**
	* Increase the Menu Depth and set the menu type to its parent one
	* @access protected
	* @param string $type the type of the menu
	*/
	protected function _parent($type)
	{
		parent::_parent($type);

		$this->type = $type;
		foreach ($this->elements as $element)
		{
			$element->_parent($type);
		}
	}

	## Getters ##
	/**
	* @return string the menu type
	*/
	public function get_type() { return $this->type; }

	/**
	* Sets the type of the menu
	*
	* @param string $type Type of the menu
	*/
	public function set_type($type) { $this->type = $type; }

	/**
	* @return LinksMenuElement[] the menu children elements
	*/
	public function get_children() { return $this->elements; }
}
?>
