<?php
/**
 * This class represents the sitemap module's configuration.
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 21
 * @since       PHPBoost 3.0 - 2009 12 22
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
	 */
	public static function save()
	{
		ConfigManager::save('sitemap', self::load(), 'config');
	}
}
?>
