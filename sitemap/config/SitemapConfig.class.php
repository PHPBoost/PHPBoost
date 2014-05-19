<?php
/*##################################################
 *		                     SitemapConfig.class.php
 *                            -------------------
 *   begin                : December 22, 2009
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
 * @desc This class represents the sitemap module's configuration.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class SitemapConfig extends AbstractConfigData
{
	/**
	 * Returns the last generation date
	 * @return Date the last generation date
	 */
	public function get_last_generation_date()
	{
		return $this->get_property('last_generation_date');
	}

	/**
	 * Sets the last generation date to a date
	 * @param Date $date The date
	 */
	public function set_last_generation_date(Date $date)
	{
		$this->set_property('last_generation_date', $date);
	}
	
	/**
	 * Returns the sitemap.xml file time life. We consider it as out of date after 
	 * this duration (in number of days).
	 * @return int The number of days of validity.
	 */
	public function get_sitemap_xml_life_time()
	{
		return $this->get_property('sitemap_xml_life_time');
	}
	
	/**
	 * Sets the sitemap.xml file time life.
	 * @param int $num_days The number of days of validity.
	 */
	public function set_sitemap_xml_life_time($num_days)
	{
		$this->set_property('sitemap_xml_life_time', $num_days);
	}
	
	/**
	 * Tells whether the sitemap.xml file generation is enabled.
	 * @return bool true if it is, false otherwise.
	 */
	public function is_sitemap_xml_generation_enabled()
	{
		return $this->get_property('enable_sitemap_xml');
	}

	/**
	 * Enables the sitemap.xml file generation.
	 */
	public function enable_sitemap_xml_generation()
	{
		$this->set_property('enable_sitemap_xml', true);
	}

	/**
	 * Disables the sitemap.xml file generation.
	 */
	public function disable_sitemap_xml_generation()
	{
		$this->set_property('enable_sitemap_xml', false);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			'last_generation_date' => new Date(),
			'sitemap_xml_life_time' => 3,
			'enable_sitemap_xml' => false
		);
	}

	/**
	 * Returns the configuration.
	 * @return SitemapConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'sitemap', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 * @param SitemapConfig $config The configuration to push in the database.
	 */
	public static function save(SitemapConfig $config)
	{
		ConfigManager::save('sitemap', $config, 'config');
	}
}
?>