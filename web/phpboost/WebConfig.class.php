<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 05
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebConfig extends AbstractConfigData
{
	const CATEGORIES_PER_PAGE = 'categories_per_page';
	const CATEGORIES_PER_ROW = 'categories_per_row';
	const ITEMS_PER_PAGE = 'items_per_page';
	const ITEMS_PER_ROW = 'items_per_row';
	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE = 'items_default_sort_mode';
	const DEFAULT_CONTENT = 'default_content';
	const FULL_ITEM_DISPLAY = 'full_item_display';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const PARTNERS_SORT_FIELD = 'partners_sort_field';
	const PARTNERS_SORT_MODE = 'partners_sort_mode';
	const PARTNERS_NUMBER_IN_MENU = 'partners_number_in_menu';
	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';
	const TABLE_VIEW = 'table_view';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const AUTO_CUT_CHARACTERS_NUMBER = 'auto_cut_characters_number';

	public function get_categories_per_page()
	{
		return $this->get_property(self::CATEGORIES_PER_PAGE);
	}

	public function set_categories_per_page($value)
	{
		$this->set_property(self::CATEGORIES_PER_PAGE, $value);
	}

	public function get_categories_per_row()
	{
		return $this->get_property(self::CATEGORIES_PER_ROW);
	}

	public function set_categories_per_row($value)
	{
		$this->set_property(self::CATEGORIES_PER_ROW, $value);
	}

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page($value)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_items_per_row()
	{
		return $this->get_property(self::ITEMS_PER_ROW);
	}

	public function set_items_per_row($value)
	{
		$this->set_property(self::ITEMS_PER_ROW, $value);
	}

	public function display_full_item()
	{
		$this->set_property(self::FULL_ITEM_DISPLAY, true);
	}

	public function display_condensed_item()
	{
		$this->set_property(self::FULL_ITEM_DISPLAY, false);
	}

	public function is_full_item_displayed()
	{
		return $this->get_property(self::FULL_ITEM_DISPLAY);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($value)
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function get_items_default_sort_field()
	{
		return $this->get_property(self::ITEMS_DEFAULT_SORT_FIELD);
	}

	public function set_items_default_sort_field($value)
	{
		$this->set_property(self::ITEMS_DEFAULT_SORT_FIELD, $value);
	}

	public function get_items_default_sort_mode()
	{
		return $this->get_property(self::ITEMS_DEFAULT_SORT_MODE);
	}

	public function set_items_default_sort_mode($value)
	{
		$this->set_property(self::ITEMS_DEFAULT_SORT_MODE, $value);
	}

	public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
	}

	public function display_descriptions_to_guests()
	{
		$this->set_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS, true);
	}

	public function hide_descriptions_to_guests()
	{
		$this->set_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS, false);
	}

	public function are_descriptions_displayed_to_guests()
	{
		return $this->get_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_partners_sort_field()
	{
		return $this->get_property(self::PARTNERS_SORT_FIELD);
	}

	public function set_partners_sort_field($value)
	{
		$this->set_property(self::PARTNERS_SORT_FIELD, $value);
	}

	public function get_partners_sort_mode()
	{
		return $this->get_property(self::PARTNERS_SORT_MODE);
	}

	public function set_partners_sort_mode($value)
	{
		$this->set_property(self::PARTNERS_SORT_MODE, $value);
	}

	public function get_partners_number_in_menu()
	{
		return $this->get_property(self::PARTNERS_NUMBER_IN_MENU);
	}

	public function set_partners_number_in_menu($value)
	{
		$this->set_property(self::PARTNERS_NUMBER_IN_MENU, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	public function get_auto_cut_characters_number()
	{
		return $this->get_property(self::AUTO_CUT_CHARACTERS_NUMBER);
	}

	public function set_auto_cut_characters_number($number)
	{
		$this->set_property(self::AUTO_CUT_CHARACTERS_NUMBER, $number);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::CATEGORIES_PER_PAGE => 10,
			self::CATEGORIES_PER_ROW => 3,
			self::ITEMS_PER_PAGE => 15,
			self::ITEMS_PER_ROW => 2,
			self::FULL_ITEM_DISPLAY => false,
			self::DISPLAY_TYPE => self::GRID_VIEW,
			self::ITEMS_DEFAULT_SORT_FIELD => WebItem::SORT_ALPHABETIC,
			self::ITEMS_DEFAULT_SORT_MODE => WebItem::ASC,
			self::DEFAULT_CONTENT => '',
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::ROOT_CATEGORY_DESCRIPTION => CategoriesService::get_default_root_category_description('web'),
			self::AUTO_CUT_CHARACTERS_NUMBER => 128,
			self::PARTNERS_SORT_FIELD => WebItem::SORT_ALPHABETIC,
			self::PARTNERS_SORT_MODE => WebItem::ASC,
			self::PARTNERS_NUMBER_IN_MENU => 5,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return WebConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'web', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('web', self::load(), 'config');
	}
}
?>
