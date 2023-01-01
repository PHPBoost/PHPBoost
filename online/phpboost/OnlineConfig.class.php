<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2011 09 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class OnlineConfig extends AbstractConfigData
{
	const DISPLAY_ORDER = 'display_order';
	const NUMBER_MEMBER_DISPLAYED = 'number_member_displayed';
	const NUMBER_MEMBERS_PER_PAGE = 'number_members_per_page';
	const ROBOTS_DISPLAYED = 'robots_displayed';

	const LEVEL_DISPLAY_ORDER = 'level_display_order';
	const SESSION_TIME_DISPLAY_ORDER = 'session_time_display_order';
	const LEVEL_AND_SESSION_TIME_DISPLAY_ORDER = 'level_and_session_time_display_order';

	const AUTHORIZATIONS = 'authorizations';

	public function get_display_order()
	{
		return $this->get_property(self::DISPLAY_ORDER);
	}

	public function set_display_order($value)
	{
		$this->set_property(self::DISPLAY_ORDER, $value);
	}

	public function get_display_order_request()
	{
		switch (self::DISPLAY_ORDER)
		{
			case self::LEVEL_DISPLAY_ORDER:
				return 'm.level DESC';
			break;
			case self::SESSION_TIME_DISPLAY_ORDER:
				return 's.timestamp DESC';
			break;
			case self::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER:
			default:
				return 'm.level DESC, s.timestamp DESC';
		}
	}

	public function get_members_number_displayed()
	{
		return $this->get_property(self::NUMBER_MEMBER_DISPLAYED);
	}

	public function set_number_member_displayed($number)
	{
		$this->set_property(self::NUMBER_MEMBER_DISPLAYED, $number);
	}

	public function get_number_members_per_page()
	{
		return $this->get_property(self::NUMBER_MEMBERS_PER_PAGE);
	}

	public function set_number_members_per_page($number)
	{
		$this->set_property(self::NUMBER_MEMBERS_PER_PAGE, $number);
	}

	public function display_robots()
	{
		$this->set_property(self::ROBOTS_DISPLAYED, true);
	}

	public function hide_robots() {
		$this->set_property(self::ROBOTS_DISPLAYED, false);
	}

	public function are_robots_displayed()
	{
		return $this->get_property(self::ROBOTS_DISPLAYED);
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
			self::DISPLAY_ORDER => self::LEVEL_AND_SESSION_TIME_DISPLAY_ORDER,
			self::NUMBER_MEMBER_DISPLAYED => 4,
			self::NUMBER_MEMBERS_PER_PAGE => 20,
			self::ROBOTS_DISPLAYED => false,
			self::AUTHORIZATIONS => array('r0' => 1, 'r1' => 1)
		);
	}

	/**
	 * Returns the configuration.
	 * @return OnlineConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'online', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('online', self::load(), 'config');
	}
}
?>
