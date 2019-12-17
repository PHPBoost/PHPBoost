<?php
/**
 * This class contains the date of the last day where PHPBoost was used.
 * It's useful to know when to launch daily tasks.
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 02 08
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Kevin MASSY <reidlos@phpboost.com>
*/

class LastUseDateConfig extends AbstractConfigData
{
	/**
	 * Sets the date of the last time PHPBoost executed the daily tasks.
	 * @param Date $date The date
	 */
	public function set_last_use_date(Date $date)
	{
		$this->set_property('year', $date->get_year());
		$this->set_property('month', $date->get_month());
		$this->set_property('day', $date->get_day());
	}

	/**
	 * Returns the date of the last time PHPBoost executed the daily tasks.
	 * @return Date
	 */
	public function get_last_use_date()
	{
		try
		{
			$date = new Date(Date::DATE_NOW, Timezone::SITE_TIMEZONE);
			$date->set_year($this->get_property('year'));
			$date->set_month($this->get_property('month'));
			$date->set_day($this->get_property('day'));
			return $date;
		}
		catch(Exception $ex)
		{
			return $this->get_date_far_in_the_past();
		}
	}

	private function get_date_far_in_the_past()
	{
		$date = new Date();
		$date->set_year($date->get_year() - 1);
		return $date;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array();
	}

	/**
	 * Returns the configuration.
	 * @return LastUseDateConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'last-use-date');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'last-use-date');
	}
}
?>
