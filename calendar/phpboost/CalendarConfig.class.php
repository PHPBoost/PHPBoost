<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 3.0 - 2010 08 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * 
*/

class CalendarConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const MEMBERS_BIRTHDAY_ENABLED = 'members_birthday_enabled';
	const EVENT_COLOR = 'event_color';
	const BIRTHDAY_COLOR = 'birthday_color';
    
    const DEFAULT_CONTENTS = 'default_contents';

	const AUTHORIZATIONS = 'authorizations';

	const NUMBER_CARACTERS_BEFORE_CUT = 150;

	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}

	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}
    
    public function get_default_contents()
	{
		return $this->get_property(self::DEFAULT_CONTENTS);
	}
    
	public function set_default_contents($value)
	{
		$this->set_property(self::DEFAULT_CONTENTS, $value);
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
			self::ITEMS_NUMBER_PER_PAGE => 15,
			self::MEMBERS_BIRTHDAY_ENABLED => false,
            self::DEFAULT_CONTENTS => '',
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
