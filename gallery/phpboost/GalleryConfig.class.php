<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 06 04
 * @since       PHPBoost 4.0 - 2013 06 30
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GalleryConfig extends AbstractConfigData
{
	const MINI_MAX_WIDTH = 'mini_max_width';
	const MINI_MAX_HEIGHT = 'mini_max_height';
	const MAX_WIDTH = 'max_width';
	const MAX_HEIGHT = 'max_height';
	const MAX_WEIGHT = 'max_weight';
	const QUALITY = 'quality';
	const LOGO_ENABLED = 'logo_enabled';
	const LOGO = 'logo';
	const LOGO_TRANSPARENCY = 'logo_transparency';
	const LOGO_HORIZONTAL_DISTANCE = 'logo_horizontal_distance';
	const LOGO_VERTICAL_DISTANCE = 'logo_vertical_distance';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER = 'columns_number';
	const PICS_NUMBER_PER_PAGE = 'pics_number_per_page';
	const TITLE_ENABLED = 'title_enabled';
	const NOTES_NUMBER_DISPLAYED = 'notes_number_displayed';
	const VIEWS_COUNTER_ENABLED = 'views_counter_enabled';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const MEMBER_MAX_PICS_NUMBER = 'member_max_pics_number';
	const MODERATOR_MAX_PICS_NUMBER = 'moderator_max_pics_number';
	const PICS_ENLARGEMENT_MODE = 'pics_enlargement_mode';
	const SCROLL_TYPE = 'scroll_type';
	const PICS_NUMBER_IN_MINI = 'pics_number_in_mini';
	const MINI_PICS_SPEED = 'mini_pics_speed';
	const AUTHORIZATIONS = 'authorizations';

	//Pics enlargement modes
	const NEW_PAGE = 'new_page';
	const RESIZE = 'resize';
	const POPUP = 'popup';
	const FULL_SCREEN = 'full_screen';

	//Scroll types
	const STATIC_SCROLL = 'static_scroll';
	const VERTICAL_DYNAMIC_SCROLL = 'vertical_dynamic_scroll';
	const HORIZONTAL_DYNAMIC_SCROLL = 'horizontal_dynamic_scroll';
	const NO_SCROLL = 'no_scroll';

	public function get_mini_max_width()
	{
		return $this->get_property(self::MINI_MAX_WIDTH);
	}

	public function set_mini_max_width($value)
	{
		$this->set_property(self::MINI_MAX_WIDTH, $value);
	}

	public function get_mini_max_height()
	{
		return $this->get_property(self::MINI_MAX_HEIGHT);
	}

	public function set_mini_max_height($value)
	{
		$this->set_property(self::MINI_MAX_HEIGHT, $value);
	}

	public function get_max_width()
	{
		return $this->get_property(self::MAX_WIDTH);
	}

	public function set_max_width($value)
	{
		$this->set_property(self::MAX_WIDTH, $value);
	}

	public function get_max_height()
	{
		return $this->get_property(self::MAX_HEIGHT);
	}

	public function set_max_height($value)
	{
		$this->set_property(self::MAX_HEIGHT, $value);
	}

	public function get_max_weight()
	{
		return $this->get_property(self::MAX_WEIGHT);
	}

	public function set_max_weight($value)
	{
		$this->set_property(self::MAX_WEIGHT, $value);
	}

	public function get_quality()
	{
		return $this->get_property(self::QUALITY);
	}

	public function set_quality($value)
	{
		$this->set_property(self::QUALITY, $value);
	}

	public function enable_logo()
	{
		$this->set_property(self::LOGO_ENABLED, true);
	}

	public function disable_logo()
	{
		$this->set_property(self::LOGO_ENABLED, false);
	}

	public function is_logo_enabled()
	{
		return $this->get_property(self::LOGO_ENABLED);
	}

	public function get_logo()
	{
		return $this->get_property(self::LOGO);
	}

	public function set_logo($value)
	{
		$this->set_property(self::LOGO, $value);
	}

	public function get_logo_transparency()
	{
		return $this->get_property(self::LOGO_TRANSPARENCY);
	}

	public function set_logo_transparency($value)
	{
		$this->set_property(self::LOGO_TRANSPARENCY, $value);
	}

	public function get_logo_horizontal_distance()
	{
		return $this->get_property(self::LOGO_HORIZONTAL_DISTANCE);
	}

	public function set_logo_horizontal_distance($value)
	{
		$this->set_property(self::LOGO_HORIZONTAL_DISTANCE, $value);
	}

	public function get_logo_vertical_distance()
	{
		return $this->get_property(self::LOGO_VERTICAL_DISTANCE);
	}

	public function set_logo_vertical_distance($value)
	{
		$this->set_property(self::LOGO_VERTICAL_DISTANCE, $value);
	}

	public function get_categories_number_per_page()
	{
		return $this->get_property(self::CATEGORIES_NUMBER_PER_PAGE);
	}

	public function set_categories_number_per_page($value)
	{
		$this->set_property(self::CATEGORIES_NUMBER_PER_PAGE, $value);
	}

	public function get_columns_number()
	{
		return $this->get_property(self::COLUMNS_NUMBER);
	}

	public function set_columns_number($value)
	{
		$this->set_property(self::COLUMNS_NUMBER, $value);
	}

	public function get_pics_number_per_page()
	{
		return $this->get_property(self::PICS_NUMBER_PER_PAGE);
	}

	public function set_pics_number_per_page($value)
	{
		$this->set_property(self::PICS_NUMBER_PER_PAGE, $value);
	}

	public function enable_title()
	{
		$this->set_property(self::TITLE_ENABLED, true);
	}

	public function disable_title()
	{
		$this->set_property(self::TITLE_ENABLED, false);
	}

	public function is_title_enabled()
	{
		return $this->get_property(self::TITLE_ENABLED);
	}

	public function display_notes_number()
	{
		$this->set_property(self::NOTES_NUMBER_DISPLAYED, true);
	}

	public function hide_notes_number()
	{
		$this->set_property(self::NOTES_NUMBER_DISPLAYED, false);
	}

	public function are_notes_number_displayed()
	{
		return $this->get_property(self::NOTES_NUMBER_DISPLAYED);
	}

	public function enable_views_counter()
	{
		$this->set_property(self::VIEWS_COUNTER_ENABLED, true);
	}

	public function disable_views_counter()
	{
		$this->set_property(self::VIEWS_COUNTER_ENABLED, false);
	}

	public function is_views_counter_enabled()
	{
		return $this->get_property(self::VIEWS_COUNTER_ENABLED);
	}

	public function enable_author_display()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, true);
	}

	public function disable_author_display()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, false);
	}

	public function is_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function get_member_max_pics_number()
	{
		return $this->get_property(self::MEMBER_MAX_PICS_NUMBER);
	}

	public function set_member_max_pics_number($value)
	{
		$this->set_property(self::MEMBER_MAX_PICS_NUMBER, $value);
	}

	public function get_moderator_max_pics_number()
	{
		return $this->get_property(self::MODERATOR_MAX_PICS_NUMBER);
	}

	public function set_moderator_max_pics_number($value)
	{
		$this->set_property(self::MODERATOR_MAX_PICS_NUMBER, $value);
	}

	public function get_pics_enlargement_mode()
	{
		return $this->get_property(self::PICS_ENLARGEMENT_MODE);
	}

	public function set_pics_enlargement_mode($value)
	{
		$this->set_property(self::PICS_ENLARGEMENT_MODE, $value);
	}

	public function get_scroll_type()
	{
		return $this->get_property(self::SCROLL_TYPE);
	}

	public function set_scroll_type($value)
	{
		$this->set_property(self::SCROLL_TYPE, $value);
	}

	public function get_pics_number_in_mini()
	{
		return $this->get_property(self::PICS_NUMBER_IN_MINI);
	}

	public function set_pics_number_in_mini($value)
	{
		$this->set_property(self::PICS_NUMBER_IN_MINI, $value);
	}

	public function get_mini_pics_speed()
	{
		return $this->get_property(self::MINI_PICS_SPEED);
	}

	public function set_mini_pics_speed($value)
	{
		$this->set_property(self::MINI_PICS_SPEED, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_default_values()
	{
		return array(
			self::MINI_MAX_WIDTH => 150,
			self::MINI_MAX_HEIGHT => 150,
			self::MAX_WIDTH => 800,
			self::MAX_HEIGHT => 600,
			self::MAX_WEIGHT => 1024,
			self::QUALITY => 80,
			self::LOGO_ENABLED => true,
			self::LOGO => 'protect.png',
			self::LOGO_TRANSPARENCY => 40,
			self::LOGO_HORIZONTAL_DISTANCE => 5,
			self::LOGO_VERTICAL_DISTANCE => 5,
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER => 4,
			self::PICS_NUMBER_PER_PAGE => 16,
			self::TITLE_ENABLED => true,
			self::NOTES_NUMBER_DISPLAYED => true,
			self::VIEWS_COUNTER_ENABLED => true,
			self::AUTHOR_DISPLAYED => true,
			self::MEMBER_MAX_PICS_NUMBER => 10,
			self::MODERATOR_MAX_PICS_NUMBER => 25,
			self::PICS_ENLARGEMENT_MODE => self::FULL_SCREEN,
			self::SCROLL_TYPE => self::VERTICAL_DYNAMIC_SCROLL,
			self::PICS_NUMBER_IN_MINI => 6,
			self::MINI_PICS_SPEED => 6,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 3, 'r1' => 11)
		);
	}

	/**
	 * Returns the configuration.
	 * @return GalleryConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'gallery', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('gallery', self::load(), 'config');
	}
}
?>
