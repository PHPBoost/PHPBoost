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
	const REJECTED_BUG_COLOR = 'rejected_bug_color';
	const FIXED_BUG_COLOR = 'fixed_bug_color';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const VERSIONS = 'versions';
	const TYPES = 'types';
	const CATEGORIES = 'categories';
	const CONTENTS_VALUE = 'contents_value';
	const SEVERITIES = 'severities';
	const PRIORITIES = 'priorities';
	const STATUS_LIST = 'status_list';
	const ROADMAP_ACTIVATED = 'roadmap_activated';
	const CAT_IN_TITLE_ACTIVATED = 'cat_in_title_activated';
	const PM_ACTIVATED = 'pm_activated';
	const DEFAULT_TYPE = 'default_type';
	const DEFAULT_CATEGORY = 'default_category';
	const DEFAULT_PRIORITY = 'default_priority';
	const DEFAULT_SEVERITY = 'default_severity';
	const DEFAULT_VERSION = 'default_version';
	const TYPE_MANDATORY = 'type_mandatory';
	const CATEGORY_MANDATORY = 'category_mandatory';
	const PRIORITY_MANDATORY = 'priority_mandatory';
	const SEVERITY_MANDATORY = 'severity_mandatory';
	const DETECTED_IN_MANDATORY = 'detected_in_mandatory';
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
	
	public function get_rejected_bug_color()
	{
		return $this->get_property(self::REJECTED_BUG_COLOR);
	}
	
	public function set_rejected_bug_color($value) 
	{
		$this->set_property(self::REJECTED_BUG_COLOR, $value);
	}
	
	public function get_fixed_bug_color()
	{
		return $this->get_property(self::FIXED_BUG_COLOR);
	}
	
	public function set_fixed_bug_color($value) 
	{
		$this->set_property(self::FIXED_BUG_COLOR, $value);
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
	
	public function get_roadmap_activated()
	{
		return $this->get_property(self::ROADMAP_ACTIVATED);
	}
	
	public function set_roadmap_activated($value) 
	{
		$this->set_property(self::ROADMAP_ACTIVATED, $value);
	}
	
	public function get_cat_in_title_activated()
	{
		return $this->get_property(self::CAT_IN_TITLE_ACTIVATED);
	}
	
	public function set_cat_in_title_activated($value) 
	{
		$this->set_property(self::CAT_IN_TITLE_ACTIVATED, $value);
	}
	
	public function get_pm_activated()
	{
		return $this->get_property(self::PM_ACTIVATED);
	}
	
	public function set_pm_activated($value) 
	{
		$this->set_property(self::PM_ACTIVATED, $value);
	}
	
	public function get_default_type()
	{
		return $this->get_property(self::DEFAULT_TYPE);
	}
	
	public function set_default_type($value) 
	{
		$this->set_property(self::DEFAULT_TYPE, $value);
	}
	
	public function get_default_category()
	{
		return $this->get_property(self::DEFAULT_CATEGORY);
	}
	
	public function set_default_category($value) 
	{
		$this->set_property(self::DEFAULT_CATEGORY, $value);
	}
	
	public function get_default_priority()
	{
		return $this->get_property(self::DEFAULT_PRIORITY);
	}
	
	public function set_default_priority($value) 
	{
		$this->set_property(self::DEFAULT_PRIORITY, $value);
	}
	
	public function get_default_severity()
	{
		return $this->get_property(self::DEFAULT_SEVERITY);
	}
	
	public function set_default_severity($value) 
	{
		$this->set_property(self::DEFAULT_SEVERITY, $value);
	}
	
	public function get_default_version()
	{
		return $this->get_property(self::DEFAULT_VERSION);
	}
	
	public function set_default_version($value) 
	{
		$this->set_property(self::DEFAULT_VERSION, $value);
	}

	public function get_type_mandatory()
	{
		return $this->get_property(self::TYPE_MANDATORY);
	}
	
	public function set_type_mandatory($value) 
	{
		$this->set_property(self::TYPE_MANDATORY, $value);
	}
	
	public function get_category_mandatory()
	{
		return $this->get_property(self::CATEGORY_MANDATORY);
	}
	
	public function set_category_mandatory($value) 
	{
		$this->set_property(self::CATEGORY_MANDATORY, $value);
	}
	
	public function get_priority_mandatory()
	{
		return $this->get_property(self::PRIORITY_MANDATORY);
	}
	
	public function set_priority_mandatory($value) 
	{
		$this->set_property(self::PRIORITY_MANDATORY, $value);
	}
	
	public function get_severity_mandatory()
	{
		return $this->get_property(self::SEVERITY_MANDATORY);
	}
	
	public function set_severity_mandatory($value) 
	{
		$this->set_property(self::SEVERITY_MANDATORY, $value);
	}
	
	public function get_detected_in_mandatory()
	{
		return $this->get_property(self::DETECTED_IN_MANDATORY);
	}
	
	public function set_detected_in_mandatory($value) 
	{
		$this->set_property(self::DETECTED_IN_MANDATORY, $value);
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
			self::REJECTED_BUG_COLOR => 'f8465e',
			self::FIXED_BUG_COLOR => 'afffa2',
			self::COMMENTS_ACTIVATED => true,
			self::ROADMAP_ACTIVATED => false,
			self::CAT_IN_TITLE_ACTIVATED => false,
			self::PM_ACTIVATED => true,
			self::STATUS_LIST => array('new', 'assigned', 'fixed', 'reopen', 'rejected'),
			self::VERSIONS => array(),
			self::TYPES => array(1 => LangLoader::get_message('bugtracker.config.types.anomaly', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.types.evolution_demand', 'bugtracker_config', 'bugtracker')),
			self::CATEGORIES => array(1 => LangLoader::get_message('bugtracker.config.categories.kernel', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.module', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.graphism', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.installation', 'bugtracker_config', 'bugtracker')),
			self::CONTENTS_VALUE => LangLoader::get_message('bugtracker.config.contents_value', 'bugtracker_config', 'bugtracker'),
			self::SEVERITIES => array(1 => array('name' => LangLoader::get_message('bugtracker.config.severities.minor', 'bugtracker_config', 'bugtracker'), 'color' => 'e8ffa2'), array('name' => LangLoader::get_message('bugtracker.config.severities.major', 'bugtracker_config', 'bugtracker'), 'color' => 'feebbc'), array('name' => LangLoader::get_message('bugtracker.config.severities.critical', 'bugtracker_config', 'bugtracker'), 'color' => 'ffa2a2')),
			self::PRIORITIES => array(1 => LangLoader::get_message('bugtracker.config.priorities.none', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.low', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.normal', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.high', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.urgent', 'bugtracker_config', 'bugtracker')),
			self::DEFAULT_TYPE => 1,
			self::DEFAULT_CATEGORY => 0,
			self::DEFAULT_SEVERITY => 1,
			self::DEFAULT_PRIORITY => 3,
			self::DEFAULT_VERSION => 0,
			self::TYPE_MANDATORY => false,
			self::CATEGORY_MANDATORY => true,
			self::SEVERITY_MANDATORY => false,
			self::PRIORITY_MANDATORY => true,
			self::DETECTED_IN_MANDATORY => false,
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