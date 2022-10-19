<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 21
 * @since       PHPBoost 5.1 - 2017 09 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class SandboxConfig extends AbstractConfigData
{
	const SUPERADMIN_ENABLED = 'superadmin_enabled';
	const SUPERADMIN_NAME    = 'superadmin_name';

	const MENU_OPENING_TYPE = 'menu_opening_type';
	const TOP_MENU 	        = 'top';
	const RIGHT_MENU        = 'right';
	const LEFT_MENU         = 'left';
	const BOTTOM_MENU       = 'bottom';

	const EXPANSION_TYPE = 'expansion_type';
	const OVERLAP        = "overlap";
	const EXPANSION      = 'expand';
	const NO_EXPANSION   = 'none';

	const DISABLED_BODY  = 'disabled_body';
	const PUSHED_CONTENT = 'pushed_content';

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

	public function get_menu_opening_type()
	{
		return $this->get_property(self::MENU_OPENING_TYPE);
	}

	public function set_menu_opening_type($menu_opening_type)
	{
		$this->set_property(self::MENU_OPENING_TYPE, $menu_opening_type);
	}

	public function get_expansion_type()
	{
		return $this->get_property(self::EXPANSION_TYPE);
	}

	public function set_expansion_type($expansion_type)
	{
		$this->set_property(self::EXPANSION_TYPE, $expansion_type);
	}

	public function get_disabled_body()
	{
		return $this->get_property(self::DISABLED_BODY);
	}

	public function set_disabled_body($disabled_body)
	{
		$this->set_property(self::DISABLED_BODY, $disabled_body);
	}

	public function get_pushed_content()
	{
		return $this->get_property(self::PUSHED_CONTENT);
	}

	public function set_pushed_content($pushed_content)
	{
		$this->set_property(self::PUSHED_CONTENT, $pushed_content);
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
			self::MENU_OPENING_TYPE => self::LEFT_MENU,
			self::EXPANSION_TYPE => self::OVERLAP,
			self::DISABLED_BODY => true,
			self::PUSHED_CONTENT => true,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
		);
	}

	/**
	 * Returns the configuration.
	 * @return SandboxConfig
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
