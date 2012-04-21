<?php
/*##################################################
 *                              BugtrackerConfig.class.php
 *                            -------------------
 *   begin                : April 16, 2012
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

class BugtrackerConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const SEVERITY_MINOR_COLOR = 'severity_minor_color';
	const SEVERITY_MAJOR_COLOR = 'severity_major_color';
	const SEVERITY_CRITICAL_COLOR = 'severity_critical_color';
	const CLOSED_BUG_COLOR = 'closed_bug_color';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const VERSIONS = 'versions';
	const TYPES = 'types';
	const CATEGORIES = 'categories';
	const AUTHORIZATIONS = 'authorizations';

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}
	
	public function set_items_per_page($value) 
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_severity_minor_color()
	{
		return $this->get_property(self::SEVERITY_MINOR_COLOR);
	}
	
	public function set_severity_minor_color($value) 
	{
		$this->set_property(self::SEVERITY_MINOR_COLOR, $value);
	}

	public function get_severity_major_color()
	{
		return $this->get_property(self::SEVERITY_MAJOR_COLOR);
	}
	
	public function set_severity_major_color($value) 
	{
		$this->set_property(self::SEVERITY_MAJOR_COLOR, $value);
	}

	public function get_severity_critical_color()
	{
		return $this->get_property(self::SEVERITY_CRITICAL_COLOR);
	}
	
	public function set_severity_critical_color($value) 
	{
		$this->set_property(self::SEVERITY_CRITICAL_COLOR, $value);
	}

	public function get_closed_bug_color()
	{
		return $this->get_property(self::CLOSED_BUG_COLOR);
	}
	
	public function set_closed_bug_color($value) 
	{
		$this->set_property(self::CLOSED_BUG_COLOR, $value);
	}
	
	public function get_comments_activated()
	{
		return $this->get_property(self::COMMENTS_ACTIVATED);
	}
	
	public function set_comments_activated($value) 
	{
		$this->set_property(self::COMMENTS_ACTIVATED, $value);
	}
	
	public function get_versions()
	{
		return $this->get_property(self::VERSIONS);
	}
	
	public function set_versions(Array $array)
	{
		$this->set_property(self::VERSIONS, $array);
	}
	
	public function get_types()
	{
		return $this->get_property(self::TYPES);
	}
	
	public function set_types(Array $array)
	{
		$this->set_property(self::TYPES, $array);
	}
	
	public function get_categories()
	{
		return $this->get_property(self::CATEGORIES);
	}
	
	public function set_categories(Array $array)
	{
		$this->set_property(self::CATEGORIES, $array);
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
			self::ITEMS_PER_PAGE => 20,
			self::SEVERITY_MINOR_COLOR => '99CC00',
			self::SEVERITY_MAJOR_COLOR => 'ffa500',
			self::SEVERITY_CRITICAL_COLOR => 'F04343',
			self::CLOSED_BUG_COLOR => 'ACA899',
			self::COMMENTS_ACTIVATED => true,
			self::VERSIONS => array(0 => '3.0.10'),
			self::TYPES => array(0 => LangLoader::get_message('bugtracker.config.types.anomaly', 'bugtracker_config', 'bugtracker'), 1 => LangLoader::get_message('bugtracker.config.types.evolution_demand', 'bugtracker_config', 'bugtracker')),
			self::CATEGORIES => array(0 => LangLoader::get_message('bugtracker.config.categories.kernel', 'bugtracker_config', 'bugtracker'), 1 => LangLoader::get_message('bugtracker.config.categories.module', 'bugtracker_config', 'bugtracker'), 2 => LangLoader::get_message('bugtracker.config.categories.graphism', 'bugtracker_config', 'bugtracker'), 3 => LangLoader::get_message('bugtracker.config.categories.installation', 'bugtracker_config', 'bugtracker')),
			self::AUTHORIZATIONS => array('r0' => 3, 'r1' => 7)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return BugtrackerConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'bugtracker-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'bugtracker-config');
	}
}
?>