<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2013 06 26
*/

class StatsConfig extends AbstractConfigData
{
	const AUTHORIZATIONS = 'authorizations';
	const ELEMENTS_NUMBER_PER_PAGE = 'elements_number_per_page';

	public function get_elements_number_per_page()
	{
		return $this->get_property(self::ELEMENTS_NUMBER_PER_PAGE);
	}

	public function set_elements_number_per_page($number)
	{
		$this->set_property(self::ELEMENTS_NUMBER_PER_PAGE, $number);
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
			self::ELEMENTS_NUMBER_PER_PAGE => 15,
			self::AUTHORIZATIONS => array('r0' => 1, 'r1' => 1)
		);
	}

	/**
	 * Returns the configuration.
	 * @return StatsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'stats', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('stats', self::load(), 'config');
	}
}
?>
