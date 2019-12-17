<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 11 09
 * @since       PHPBoost 5.1 - 2017 09 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SandboxConfig extends AbstractConfigData
{
	const SUPERADMIN_ENABLED = 'superadmin_enabled';
	const SUPERADMIN_NAME      = 'superadmin_name';
	const OPEN_MENU    		 = 'open_menu';

	const LEFT_MENU    		 = 'left_menu';
	const RIGHT_MENU     	 = 'right_menu';

	const AUTHORIZATIONS = 'authorizations';

	public function get_superadmin_enabled()
	{
		return $this->get_property(self::SUPERADMIN_ENABLED);
	}

	public function set_superadmin_enabled($superadmin_enabled)
	{
		$this->set_property(self::SUPERADMIN_ENABLED, $superadmin_enabled);
	}

	public function get_superadmin_name()
	{
		return $this->get_property(self::SUPERADMIN_NAME);
	}

	public function set_superadmin_name($superadmin_name)
	{
		$this->set_property(self::SUPERADMIN_NAME, $superadmin_name);
	}

	public function get_open_menu()
	{
		return $this->get_property(self::OPEN_MENU);
	}

	public function set_open_menu($open_menu)
	{
		$this->set_property(self::OPEN_MENU, $open_menu);
	}

	 /**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::SUPERADMIN_ENABLED => false,
			self::SUPERADMIN_NAME => '',
			self::OPEN_MENU => self::LEFT_MENU,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
		);
	}

	/**
	 * Returns the configuration.
	 * @return GoogleMapsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'sandbox', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('sandbox', self::load(), 'config');
	}
}
?>
