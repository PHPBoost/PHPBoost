<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class DownloadConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const CATEGORY_DISPLAY_TYPE = 'category_display_type';
	const ITEMS_DEFAULT_SORT_FIELD = 'items_default_sort_field';
	const ITEMS_DEFAULT_SORT_MODE = 'items_default_sort_mode';
    
        const DEFAULT_CONTENTS = 'default_contents'; 
    
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const NB_VIEW_ENABLED = 'nb_view_enabled';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const SORT_TYPE = 'sort_type';
	const FILES_NUMBER_IN_MENU = 'files_number_in_menu';
	const LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED = 'limit_oldest_file_day_in_menu_enabled';
	const OLDEST_FILE_DAY_IN_MENU = 'oldest_file_day_in_menu';
	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_SUMMARY = 'summary';
	const DISPLAY_ALL_CONTENT = 'all_content';
	const DISPLAY_TABLE = 'table';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const NUMBER_CARACTERS_BEFORE_CUT = 150;

	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}

	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}

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

	public function get_category_display_type()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE);
	}

	public function set_category_display_type($value)
	{
		$this->set_property(self::CATEGORY_DISPLAY_TYPE, $value);
	}

	public function is_category_displayed_summary()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE) == self::DISPLAY_SUMMARY;
	}

	public function is_category_displayed_table()
	{
		return $this->get_property(self::CATEGORY_DISPLAY_TYPE) == self::DISPLAY_TABLE;
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

	public function get_nb_view_enabled()
	{
		return $this->get_property(self::NB_VIEW_ENABLED);
	}

	public function set_nb_view_enabled($nb_view_enabled)
	{
		$this->set_property(self::NB_VIEW_ENABLED, $nb_view_enabled);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_sort_type()
	{
		return $this->get_property(self::SORT_TYPE);
	}

	public function set_sort_type($value)
	{
		$this->set_property(self::SORT_TYPE, $value);
	}

	public function is_sort_type_date()
	{
		return $this->get_property(self::SORT_TYPE) == DownloadFile::SORT_DATE || $this->get_property(self::SORT_TYPE) == DownloadFile::SORT_UPDATED_DATE;
	}

	public function is_sort_type_number_downloads()
	{
		return $this->get_property(self::SORT_TYPE) == DownloadFile::SORT_NUMBER_DOWNLOADS;
	}

	public function is_sort_type_number_views()
	{
		return $this->get_property(self::SORT_TYPE) == DownloadFile::SORT_NUMBER_VIEWS;
	}

	public function is_sort_type_notation()
	{
		return $this->get_property(self::SORT_TYPE) == DownloadFile::SORT_NOTATION;
	}

	public function get_files_number_in_menu()
	{
		return $this->get_property(self::FILES_NUMBER_IN_MENU);
	}

	public function set_files_number_in_menu($value)
	{
		$this->set_property(self::FILES_NUMBER_IN_MENU, $value);
	}

	public function enable_limit_oldest_file_day_in_menu()
	{
		$this->set_property(self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED, true);
	}

	public function disable_limit_oldest_file_day_in_menu()
	{
		$this->set_property(self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED, false);
	}

	public function is_limit_oldest_file_day_in_menu_enabled()
	{
		return $this->get_property(self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED);
	}

	public function get_oldest_file_day_in_menu()
	{
		return $this->get_property(self::OLDEST_FILE_DAY_IN_MENU);
	}

	public function set_oldest_file_day_in_menu($value)
	{
		$this->set_property(self::OLDEST_FILE_DAY_IN_MENU, $value);
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

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER_PER_LINE => 3,
			self::CATEGORY_DISPLAY_TYPE => self::DISPLAY_SUMMARY,
			self::ITEMS_DEFAULT_SORT_FIELD => DownloadFile::SORT_UPDATED_DATE,
			self::ITEMS_DEFAULT_SORT_MODE => DownloadFile::DESC,
                        self::DEFAULT_CONTENTS => '',
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::AUTHOR_DISPLAYED => true,
			self::NB_VIEW_ENABLED => false,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'download'),
			self::SORT_TYPE => DownloadFile::SORT_NUMBER_DOWNLOADS,
			self::FILES_NUMBER_IN_MENU => 5,
			self::LIMIT_OLDEST_FILE_DAY_IN_MENU_ENABLED => false,
			self::OLDEST_FILE_DAY_IN_MENU => 30,
			self::AUTHORIZATIONS => array('r-1' => 33, 'r0' => 53, 'r1' => 61),
			self::DEFERRED_OPERATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return DownloadConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'download', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('download', self::load(), 'config');
	}
}
?>
