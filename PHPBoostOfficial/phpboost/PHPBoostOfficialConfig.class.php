<?php
/*##################################################
 *		             PHPBoostOfficialConfig.class.php
 *                            -------------------
 *   begin                : December 5, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class PHPBoostOfficialConfig extends AbstractConfigData
{
	const VERSIONS = 'versions';
	
	public function get_versions()
	{
		return $this->get_property(self::VERSIONS);
	}
	
	public function set_versions(Array $array)
	{
		$this->set_property(self::VERSIONS, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::VERSIONS => array()
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return PHPBoostOfficialConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'phpboostofficial', 'config');
	}
	
	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('phpboostofficial', self::load(), 'config');
	}
}
?>
