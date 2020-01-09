<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 09
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqConfig extends AbstractConfigData
{
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE = 'items_default_sort_mode';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_TYPE_BASIC = 'display_type_basic';
	const DISPLAY_TYPE_SIBLINGS = 'display_type_siblings';
	const DISPLAY_CONTROLS = 'display_controls';

	public function get_categories_number_per_page()
	{
		return $this->get_property(self::CATEGORIES_NUMBER_PER_PAGE);
	}

	public function set_categories_number_per_page($value)
	{
		$this->set_property(self::CATEGORIES_NUMBER_PER_PAGE, $value);
	}

	public function get_columns_number_per_line()
	{
		return $this->get_property(self::COLUMNS_NUMBER_PER_LINE);
	}

	public function set_columns_number_per_line($value)
	{
		$this->set_property(self::COLUMNS_NUMBER_PER_LINE, $value);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($value)
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function display_control_buttons()
	{
		$this->set_property(self::DISPLAY_CONTROLS, true);
	}

	public function hide_control_buttons()
	{
		$this->set_property(self::DISPLAY_CONTROLS, false);
	}

	public function are_control_buttons_displayed()
	{
		return $this->get_property(self::DISPLAY_CONTROLS);
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

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER_PER_LINE => 4,
			self::DISPLAY_TYPE => self::DISPLAY_TYPE_BASIC,
			self::DISPLAY_CONTROLS => true,
			self::ITEMS_DEFAULT_SORT_FIELD => FaqQuestion::SORT_ALPHABETIC,
			self::ITEMS_DEFAULT_SORT_MODE => FaqQuestion::ASC,
			self::ROOT_CATEGORY_DESCRIPTION => CategoriesService::get_default_root_category_description('faq', 2, 2),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration.
	 * @return FaqConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'faq', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('faq', self::load(), 'config');
	}
}
?>
