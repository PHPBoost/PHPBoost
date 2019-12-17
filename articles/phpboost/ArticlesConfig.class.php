<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 10 31
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ArticlesConfig extends AbstractConfigData
{
	const NUMBER_ARTICLES_PER_PAGE = 'number_articles_per_page';
	const NUMBER_CATEGORIES_PER_PAGE = 'number_categories_per_page';
	const NUMBER_COLS_DISPLAY_PER_LINE = 'number_cols_display_per_line';
	const NUMBER_CHARACTER_TO_CUT = 'number_character_to_cut';

	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE = 'items_default_sort_mode';

	const DEFAULT_CONTENTS = 'default_contents';

	const CATS_ICON_ENABLED = 'cats_icon_enabled';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const DATE_UPDATED_DISPLAYED = 'date_updated_displayed';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';

	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_GRID_VIEW = 'grid';
	const DISPLAY_LIST_VIEW = 'list';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const AUTHORIZATIONS = 'authorizations';

	public function get_number_articles_per_page()
	{
		return $this->get_property(self::NUMBER_ARTICLES_PER_PAGE);
	}

	public function set_number_articles_per_page($number)
	{
		$this->set_property(self::NUMBER_ARTICLES_PER_PAGE, $number);
	}

	public function get_number_categories_per_page()
	{
		return $this->get_property(self::NUMBER_CATEGORIES_PER_PAGE);
	}

	public function set_number_categories_per_page($number)
	{
		$this->set_property(self::NUMBER_CATEGORIES_PER_PAGE, $number);
	}

	public function get_number_cols_display_per_line()
	{
		return $this->get_property(self::NUMBER_COLS_DISPLAY_PER_LINE);
	}

	public function set_number_cols_display_per_line($number)
	{
		$this->set_property(self::NUMBER_COLS_DISPLAY_PER_LINE, $number);
	}

	public function get_number_character_to_cut()
	{
		return $this->get_property(self::NUMBER_CHARACTER_TO_CUT);
	}

	public function set_number_character_to_cut($number)
	{
		$this->set_property(self::NUMBER_CHARACTER_TO_CUT, $number);
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

	public function get_default_contents()
	{
		return $this->get_property(self::DEFAULT_CONTENTS);
	}

	public function set_default_contents($value)
	{
		$this->set_property(self::DEFAULT_CONTENTS, $value);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
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

	public function enable_cats_icon()
	{
		$this->set_property(self::CATS_ICON_ENABLED, true);
	}

	public function disable_cats_icon() {
		$this->set_property(self::CATS_ICON_ENABLED, false);
	}

	public function are_cats_icon_enabled()
	{
		return $this->get_property(self::CATS_ICON_ENABLED);
	}

	public function get_date_updated_displayed()
	{
		return $this->get_property(self::DATE_UPDATED_DISPLAYED);
	}

	public function set_date_updated_displayed($date_updated_displayed)
	{
		$this->set_property(self::DATE_UPDATED_DISPLAYED, $date_updated_displayed);
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

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	public function get_default_values()
	{
		return array(
			self::NUMBER_ARTICLES_PER_PAGE => 10,
			self::NUMBER_CATEGORIES_PER_PAGE => 10,
			self::NUMBER_COLS_DISPLAY_PER_LINE => 2,
			self::NUMBER_CHARACTER_TO_CUT => 150,
			self::ITEMS_DEFAULT_SORT_FIELD => Article::SORT_DATE,
			self::ITEMS_DEFAULT_SORT_MODE => Article::DESC,
			self::DEFAULT_CONTENTS => '',
			self::CATS_ICON_ENABLED => false,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::DATE_UPDATED_DISPLAYED => false,
			self::DISPLAY_TYPE => self::DISPLAY_GRID_VIEW,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'articles'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return ArticlesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'articles', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('articles', self::load(), 'config');
	}
}
?>
