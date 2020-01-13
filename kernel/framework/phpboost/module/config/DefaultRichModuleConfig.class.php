<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 13
 * @since       PHPBoost 5.3 - 2020 01 10
*/

class DefaultRichModuleConfig extends DefaultModuleConfig
{
	const CATEGORIES_PER_PAGE = 'categories_per_page';
	const CATEGORIES_PER_ROW = 'categories_per_row';
	const ITEMS_PER_PAGE = 'items_per_page';
	const ITEMS_PER_ROW = 'items_per_row';
	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE = 'items_default_sort_mode';

	const DEFAULT_CONTENT = 'default_content';
	const SUMMARY_DISPLAYED_TO_GUESTS = 'summary_displayed_to_guests';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const VIEWS_NUMBER_ENABLED = 'views_number_enabled';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';
	const TABLE_VIEW = 'table_view';

	const DEFERRED_OPERATIONS = 'deferred_operations';

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

	public function display_summary_to_guests()
	{
		$this->set_property(self::SUMMARY_DISPLAYED_TO_GUESTS, true);
	}

	public function hide_summary_to_guests()
	{
		$this->set_property(self::SUMMARY_DISPLAYED_TO_GUESTS, false);
	}

	public function is_summary_displayed_to_guests()
	{
		return $this->get_property(self::SUMMARY_DISPLAYED_TO_GUESTS);
	}

	public function display_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, true);
	}

	public function hide_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, false);
	}

	public function is_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function enable_views_number()
	{
		$this->set_property(self::VIEWS_NUMBER_ENABLED, true);
	}

	public function disable_views_number()
	{
		$this->set_property(self::VIEWS_NUMBER_ENABLED, false);
	}

	public function are_views_number_enabled()
	{
		return $this->get_property(self::VIEWS_NUMBER_ENABLED);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($value)
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array_merge(
			parent::get_default_values(),
			array(
			self::CATEGORIES_PER_PAGE         => 10,
			self::CATEGORIES_PER_ROW          => 3,
			self::ITEMS_PER_PAGE              => 15,
			self::ITEMS_PER_ROW               => 2,
			self::ITEMS_DEFAULT_SORT_FIELD    => Item::SORT_DATE,
			self::ITEMS_DEFAULT_SORT_MODE     => Item::DESC,
			self::DEFAULT_CONTENT             => '',
			self::SUMMARY_DISPLAYED_TO_GUESTS => false,
			self::AUTHOR_DISPLAYED            => true,
			self::VIEWS_NUMBER_ENABLED        => false,
			self::ROOT_CATEGORY_DESCRIPTION   => CategoriesService::get_default_root_category_description($this->module_id),
			self::DISPLAY_TYPE                => self::GRID_VIEW,
			self::DEFERRED_OPERATIONS         => array()
		);
	}
}
?>
