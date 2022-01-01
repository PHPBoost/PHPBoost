<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 29
 * @since       PHPBoost 3.0 - 2010 07 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MaintenanceConfig extends AbstractConfigData
{
	const ENABLED = 'enabled';
	const UNLIMITED_DURATION = 'unlimited';
	const END_DATE = 'end_date';
	const MESSAGE = 'message';
	const AUTH = 'auth';
	const DISPLAY_DURATION = 'display_duration';
	const DISPLAY_DURATION_FOR_ADMIN = 'display_duration_admin';

	const ACCESS_WHEN_MAINTAIN_ENABLED_AUTHORIZATIONS = 1;

	public function is_maintenance_enabled()
	{
		return $this->get_property(self::ENABLED);
	}

	public function set_maintenance_enabled($enabled)
	{
		$this->set_property(self::ENABLED, $enabled);
	}

	public function enable_maintenance()
	{
		$this->set_maintenance_enabled(true);
	}

	public function disable_maintenance()
	{
		$this->set_maintenance_enabled(false);
	}

	public function is_unlimited_maintenance()
	{
		return $this->get_property(self::UNLIMITED_DURATION);
	}

	public function set_unlimited_maintenance($unlimited)
	{
		$this->set_property(self::UNLIMITED_DURATION, $unlimited);
	}

	/**
	 * @return Date
	 */
	public function get_end_date()
	{
		return $this->get_property(self::END_DATE);
	}

	public function set_end_date(Date $date)
	{
		$this->set_property(self::END_DATE, $date);
	}

	public function get_message()
	{
		return $this->get_property(self::MESSAGE);
	}

	public function set_message($message)
	{
		$this->set_property(self::MESSAGE, $message);
	}

	public function get_auth()
	{
		return $this->get_property(self::AUTH);
	}

	public function set_auth(array $auth)
	{
		$this->set_property(self::AUTH, $auth);
	}

	public function get_display_duration()
	{
		return $this->get_property(self::DISPLAY_DURATION);
	}

	public function set_display_duration($display)
	{
		$this->set_property(self::DISPLAY_DURATION, $display);
	}

	public function get_display_duration_for_admin()
	{
		return $this->get_property(self::DISPLAY_DURATION_FOR_ADMIN);
	}

	public function set_display_duration_for_admin($display)
	{
		$this->set_property(self::DISPLAY_DURATION_FOR_ADMIN, $display);
	}

	public function is_end_date_not_reached()
	{
		return $this->get_end_date()->is_posterior_to(new Date());
	}

	public function is_under_maintenance()
	{
		return $this->is_maintenance_enabled() && ($this->is_unlimited_maintenance() || $this->is_end_date_not_reached());
	}

	public function is_authorized_in_maintenance()
	{
		return AppContext::get_current_user()->check_auth($this->get_auth(), self::ACCESS_WHEN_MAINTAIN_ENABLED_AUTHORIZATIONS);
	}


	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ENABLED => false,
			self::UNLIMITED_DURATION => false,
			self::END_DATE => new Date(),
			self::MESSAGE => LangLoader::get_message('admin.maintenance', 'admin-lang'),
			self::AUTH => array(),
			self::DISPLAY_DURATION => true,
			self::DISPLAY_DURATION_FOR_ADMIN => true
		);
	}

	/**
	 * Returns the configuration.
	 * @return MaintenanceConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'maintenance');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'maintenance');
	}
}
?>
