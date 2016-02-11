<?php
/*##################################################
 *		                  PagesConfig.class.php
 *                            -------------------
 *   begin                : March 2, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
	const COUNT_HITS_ACTIVATED = 'count_hits_activated';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const AUTHORIZATIONS = 'authorizations';

	public function get_count_hits_activated()
	{
		return $this->get_property(self::COUNT_HITS_ACTIVATED);
	}
	
	public function set_count_hits_activated($value) 
	{
		$this->set_property(self::COUNT_HITS_ACTIVATED, $value);
	}
	
	public function get_comments_activated()
	{
		return $this->get_property(self::COMMENTS_ACTIVATED);
	}
	
	public function set_comments_activated($value) 
	{
		$this->set_property(self::COMMENTS_ACTIVATED, $value);
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
			self::COUNT_HITS_ACTIVATED => true,
			self::COMMENTS_ACTIVATED => true,
			self::AUTHORIZATIONS => array('r-1' => 5, 'r0' => 7, 'r1' => 7, 'r2' => 7),
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return PagesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'pages', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('pages', self::load(), 'config');
	}
}
?>