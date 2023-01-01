<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 06
 * @since       PHPBoost 3.0 - 2010 08 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 *
*/

class CalendarConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const ITEMS_PER_ROW = 'items_per_row';
	const MEMBERS_BIRTHDAY_ENABLED = 'members_birthday_enabled';
	const EVENT_COLOR = 'event_color';
	const BIRTHDAY_COLOR = 'birthday_color';

    const DEFAULT_CONTENT = 'default_content';

	const AUTHORIZATIONS = 'authorizations';

	const CHARACTERS_NUMBER_TO_CUT = 'characters_number_to_cut';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';
	const TABLE_VIEW = 'table_view';
	const FULL_ITEM_DISPLAY = 'full_item_display';

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

	public function get_characters_number_to_cut()
	{
		return $this->get_property(self::CHARACTERS_NUMBER_TO_CUT);
	}

	public function set_characters_number_to_cut($number)
	{
		$this->set_property(self::CHARACTERS_NUMBER_TO_CUT, $number);
	}

    public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
	}

	public function enable_members_birthday()
	{
		$this->set_property(self::MEMBERS_BIRTHDAY_ENABLED, true);
	}

	public function disable_members_birthday()
	{
		$this->set_property(self::MEMBERS_BIRTHDAY_ENABLED, false);
	}

	public function is_members_birthday_enabled()
	{
		return $this->get_property(self::MEMBERS_BIRTHDAY_ENABLED);
	}

	public function get_event_color()
	{
		return $this->get_property(self::EVENT_COLOR);
	}

	public function set_event_color($value)
	{
		$this->set_property(self::EVENT_COLOR, $value);
	}

	public function get_birthday_color()
	{
		return $this->get_property(self::BIRTHDAY_COLOR);
	}

	public function set_birthday_color($value)
	{
		$this->set_property(self::BIRTHDAY_COLOR, $value);
	}

	public function is_googlemaps_available()
	{
		return ModulesManager::is_module_installed('GoogleMaps') && ModulesManager::is_module_activated('GoogleMaps') && GoogleMapsConfig::load()->get_api_key();
	}

	 /**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 15,
			self::ITEMS_PER_ROW => 2,
			self::FULL_ITEM_DISPLAY => false,
			self::MEMBERS_BIRTHDAY_ENABLED => false,
			self::CHARACTERS_NUMBER_TO_CUT => 128,
			self::DISPLAY_TYPE => self::LIST_VIEW,
            self::DEFAULT_CONTENT => '',
			self::EVENT_COLOR => '#81A4C8',
			self::BIRTHDAY_COLOR => '#8F8ACF',
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 15)
		);
	}

	/**
	 * @method Load the calendar configuration.
	 * @return CalendarConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'calendar', 'config');
	}

	/**
	 * @method Saves the calendar configuration in the database. It becomes persistent.
	 */
	public static function save()
	{
		ConfigManager::save('calendar', self::load(), 'config');
	}
}
?>
