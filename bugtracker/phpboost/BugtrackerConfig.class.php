<?php
/*##################################################
 *		                  BugtrackerConfig.class.php
 *                            -------------------
 *   begin                : September 06, 2012
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
	const REJECTED_BUG_COLOR = 'rejected_bug_color';
	const CLOSED_BUG_COLOR = 'closed_bug_color';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const VERSIONS = 'versions';
	const TYPES = 'types';
	const CATEGORIES = 'categories';
	const CONTENTS_VALUE = 'contents_value';
	const SEVERITIES = 'severities';
	const PRIORITIES = 'priorities';
	const STATUS_LIST = 'status_list';
	const AUTHORIZATIONS = 'authorizations';
	
	// Authorizations
	const BUG_READ_AUTH_BIT = 1; //Authorization bit to display de bugs list
	const BUG_CREATE_AUTH_BIT = 2; //Authorization bit to create a bug
	const BUG_CREATE_ADVANCED_AUTH_BIT = 4; //Advanced authorization bit to create a bug (more options are activated)
	const BUG_MODERATE_AUTH_BIT = 8; //Authorization bit to moderate the bugtracker
	
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
	
	public function get_rejected_bug_color()
	{
		return $this->get_property(self::REJECTED_BUG_COLOR);
	}
	
	public function set_rejected_bug_color($value) 
	{
		$this->set_property(self::REJECTED_BUG_COLOR, $value);
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
	
	public function get_contents_value()
	{
		return $this->get_property(self::CONTENTS_VALUE);
	}
	
	public function set_contents_value($value) 
	{
		$this->set_property(self::CONTENTS_VALUE, $value);
	}
	
	public function get_severities()
	{
		return $this->get_property(self::SEVERITIES);
	}
	
	public function set_severities(Array $array)
	{
		$this->set_property(self::SEVERITIES, $array);
	}
	
	public function get_priorities()
	{
		return $this->get_property(self::PRIORITIES);
	}
	
	public function set_priorities(Array $array)
	{
		$this->set_property(self::PRIORITIES, $array);
	}
	
	public function get_status_list()
	{
		return $this->get_property(self::STATUS_LIST);
	}
	
	public function set_status_list(Array $array)
	{
		$this->set_property(self::STATUS_LIST, $array);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_severity_color($value)
	{
		switch ($value)
		{
			case 'minor' :
				$color = self::SEVERITY_MINOR_COLOR;
				break;
			case 'major' :
				$color = self::SEVERITY_MAJOR_COLOR;
				break;
			case 'critical' :
				$color = self::SEVERITY_CRITICAL_COLOR;
				break;
			default :
				$color = self::SEVERITY_MINOR_COLOR;
		}
		return $this->get_property($color);
	}
	
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 20,
			self::SEVERITY_MINOR_COLOR => 'e8ffa2',
			self::SEVERITY_MAJOR_COLOR => 'feebbc',
			self::SEVERITY_CRITICAL_COLOR => 'ffa2a2',
			self::REJECTED_BUG_COLOR => 'f8465e',
			self::CLOSED_BUG_COLOR => 'afffa2',
			self::COMMENTS_ACTIVATED => true,
			self::VERSIONS => array(),
			self::TYPES => array(LangLoader::get_message('bugtracker.config.types.anomaly', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.types.evolution_demand', 'bugtracker_config', 'bugtracker')),
			self::CATEGORIES => array(LangLoader::get_message('bugtracker.config.categories.kernel', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.module', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.graphism', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.installation', 'bugtracker_config', 'bugtracker')),
			self::CONTENTS_VALUE => LangLoader::get_message('bugtracker.config.contents_value', 'bugtracker_config', 'bugtracker'),
			self::SEVERITIES => array('minor', 'major', 'critical'),
			self::PRIORITIES => array('none', 'low', 'normal', 'high', 'urgent', 'immediate'),
			self::STATUS_LIST => array('new', 'assigned', 'fixed', 'closed', 'reopen', 'rejected'),
			self::AUTHORIZATIONS => array('r0' => 3, 'r1' => 15)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return BugtrackerConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'bugtracker', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('bugtracker', self::load(), 'config');
	}
}
?>