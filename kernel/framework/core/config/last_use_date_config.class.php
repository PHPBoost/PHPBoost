<?php
/*##################################################
 *		                 last_use_date_config.class.php
 *                            -------------------
 *   begin                : October 18, 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/config/default_config_data');
import('util/date');

/**
 * This class contains the date of the last day where PHPBoost was used.
 * It's useful to know when to launch daily tasks.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class LastUseDateConfig extends DefaultConfigData
{
	/**
	 * Sets the date of the last time PHPBoost executed the daily tasks.
	 * @param Date $date The date
	 */
	public function set_last_use_date(Date $date)
	{
		$this->set_property('year', $date->get_year(TIMEZONE_SYSTEM));
		$this->set_property('month', $date->get_month(TIMEZONE_SYSTEM));
		$this->set_property('day', $date->get_day(TIMEZONE_SYSTEM));
	}

	/**
	 * Returns the date of the last time PHPBoost executed the daily tasks.
	 * @return Date
	 */
	public function get_last_use_date()
	{
		try
		{
			$year = $this->get_property('year');
			$month = $this->get_property('month');
			$day = $this->get_property('day');
			return new Date(DATE_YEAR_MONTH_DAY, TIMEZONE_SYSTEM, $year, $month, $day);
		}
		catch(PropertyNotFoundException $ex)
		{
			return new Date();
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/config/DefaultConfigData#set_default_values()
	 */
	public function set_default_values()
	{
		$this->set_last_use_date(new Date());
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
	 * @param LastUseDateConfig $config The configuration to push in the database.
	 */
	public static function save(LastUseDateConfig $config)
	{
		ConfigManager::save('kernel', $config, 'last-use-date');
	}
}