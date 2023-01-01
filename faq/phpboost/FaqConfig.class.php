<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 23
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqConfig extends AbstractConfigData
{
	const CATEGORIES_PER_PAGE       = 'categories_per_page';
	const CATEGORIES_PER_ROW        = 'categories_per_row';
	// const ITEMS_DEFAULT_SORT_FIELD  = 'items_default_sort_field';
	// const ITEMS_DEFAULT_SORT_MODE   = 'items_default_sort_mode';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const AUTHORIZATIONS            = 'authorizations';

	const DISPLAY_TYPE     = 'display_type';
	const BASIC_VIEW       = 'basic_view';
	const SIBLINGS_VIEW    = 'siblings_view';
	const DISPLAY_CONTROLS = 'display_controls';

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

	// public function get_items_default_sort_field()
	// {
	// 	return $this->get_property(self::ITEMS_DEFAULT_SORT_FIELD);
	// }
	//
	// public function set_items_default_sort_field($value)
	// {
	// 	$this->set_property(self::ITEMS_DEFAULT_SORT_FIELD, $value);
	// }
	//
	// public function get_items_default_sort_mode()
	// {
	// 	return $this->get_property(self::ITEMS_DEFAULT_SORT_MODE);
	// }
	//
	// public function set_items_default_sort_mode($value)
	// {
	// 	$this->set_property(self::ITEMS_DEFAULT_SORT_MODE, $value);
	// }

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
			self::CATEGORIES_PER_PAGE => 10,
			self::CATEGORIES_PER_ROW => 4,
			self::DISPLAY_TYPE => self::BASIC_VIEW,
			self::DISPLAY_CONTROLS => true,
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
