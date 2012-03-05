<?php
/*##################################################
 *		                  PagesConfig.class.php
 *                            -------------------
 *   begin                : March 2, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class PagesConfig extends AbstractConfigData
{
	const AUTHORIZATION = 'authorization';
	const COUNT_HITS = 'count_hits';
	const ACTIV_COM = 'activ_com';

	public function get_count_hits()
	{
		return $this->get_property(self::COUNT_HITS);
	}
	
	public function set_count_hits($nbr) 
	{
		$this->set_property(self::COUNT_HITS, $nbr);
	}
	
	public function get_activ_com()
	{
		return $this->get_property(self::ACTIV_COM);
	}
	
	public function set_activ_com($active) 
	{
		$this->set_property(self::ACTIV_COM, $active);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATION => array('r-1' => 5, 'r0' => 7, 'r1' => 7, 'r2' => 7),
			self::COUNT_HITS => 1,
			self::ACTIV_COM => 1
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return PagesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'pages-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'pages-config');
	}
}
?>