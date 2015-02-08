<?php
/*##################################################
 *		                   LastUseDateConfig.class.php
 *                            -------------------
 *   begin                : October 18, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * This class contains the date of the last day where PHPBoost was used.
 * It's useful to know when to launch daily tasks.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
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