<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 04 13
 * @since       PHPBoost 5.0 - 2017 03 26
*/

class GoogleMapsConfig extends AbstractConfigData
{
	const API_KEY = 'api_key';
	const DEFAULT_MARKER_ADDRESS = 'default_marker_address';
	const DEFAULT_MARKER_LATITUDE = 'default_marker_latitude';
	const DEFAULT_MARKER_LONGITUDE = 'default_marker_longitude';
	const DEFAULT_ZOOM = 'default_zoom';

	public function get_api_key()
	{
		return $this->get_property(self::API_KEY);
	}

	public function set_api_key($api_key)
	{
		$this->set_property(self::API_KEY, $api_key);
	}

	public function get_default_marker_address()
	{
		return $this->get_property(self::DEFAULT_MARKER_ADDRESS);
	}

	public function set_default_marker_address($default_marker_address)
	{
		$this->set_property(self::DEFAULT_MARKER_ADDRESS, $default_marker_address);
	}

	public function get_default_marker_latitude()
	{
		return $this->get_property(self::DEFAULT_MARKER_LATITUDE);
	}

	public function set_default_marker_latitude($default_marker_latitude)
	{
		$this->set_property(self::DEFAULT_MARKER_LATITUDE, $default_marker_latitude);
	}

	public function get_default_marker_longitude()
	{
		return $this->get_property(self::DEFAULT_MARKER_LONGITUDE);
	}

	public function set_default_marker_longitude($default_marker_longitude)
	{
		$this->set_property(self::DEFAULT_MARKER_LONGITUDE, $default_marker_longitude);
	}

	public function get_default_zoom()
	{
		return $this->get_property(self::DEFAULT_ZOOM);
	}

	public function set_default_zoom($default_zoom)
	{
		$this->set_property(self::DEFAULT_ZOOM, $default_zoom);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::API_KEY => '',
			self::DEFAULT_MARKER_ADDRESS => '',
			self::DEFAULT_MARKER_LATITUDE => '48.85339964950244',
			self::DEFAULT_MARKER_LONGITUDE => '2.3487655397918843',
			self::DEFAULT_ZOOM => 14
		);
	}

	/**
	 * Returns the configuration.
	 * @return GoogleMapsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'google-maps', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('google-maps', self::load(), 'config');
	}
}
?>
