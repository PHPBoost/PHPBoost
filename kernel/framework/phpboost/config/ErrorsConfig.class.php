<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2029 10 01
 * @since       PHPBoost 6.1 - 2029 09 30
*/

class ErrorsConfig extends AbstractConfigData
{
    const SEND_ALERTS = 'send_alerts';
    const ALERT_LEVEL = 'alert_level';
    const EMAIL_LIST  = 'email_list';
    const DELAY       = 'delay';

    public function get_send_alerts()
    {
		return $this->get_property(self::SEND_ALERTS);
    }

	public function set_send_alerts(bool $value)
	{
		$this->set_property(self::SEND_ALERTS, $value);
	}

    public function get_alert_level()
    {
		return $this->get_property(self::ALERT_LEVEL);
    }

	public function set_alert_level(string $value)
	{
		$this->set_property(self::ALERT_LEVEL, $value);
	}

    public function get_email_list()
    {
		return $this->get_property(self::EMAIL_LIST);
    }

	public function set_email_list(?string $value)
	{
		$this->set_property(self::EMAIL_LIST, $value);
	}

    public function get_delay()
    {
		return $this->get_property(self::DELAY);
    }

	public function set_delay(int $delay)
	{
		$this->set_property(self::DELAY, $delay);
	}

	protected function get_default_values()
	{
		return [
			self::SEND_ALERTS => false,
			self::ALERT_LEVEL => '',
			self::EMAIL_LIST  => '',
			self::DELAY       => 0,
		];
	}

	/**
	 * Returns the configuration.
	 * @return ErrorsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(self::class, 'kernel', 'errors-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'errors-config');
	}
}
?>
