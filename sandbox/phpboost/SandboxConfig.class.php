<?php
/*##################################################
 *		                   SandboxConfig.class.php
 *                            -------------------
 *   begin                : September 28, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
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
