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

/**
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc Configuration of the bugtracker module
 */
class BugtrackerConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const REJECTED_BUG_COLOR = 'rejected_bug_color';
	const FIXED_BUG_COLOR = 'fixed_bug_color';
	const DATE_FORM = 'date_form';
	const COMMENTS_ACTIVATED = 'comments_activated';
	const CAT_IN_TITLE_ACTIVATED = 'cat_in_title_activated';
	const ROADMAP_ACTIVATED = 'roadmap_activated';
	const STATS_ACTIVATED = 'stats_activated';
	const STATS_TOP_POSTERS_ACTIVATED = 'stats_top_posters_activated';
	const STATS_TOP_POSTERS_NUMBER = 'stats_top_posters_number';
	const PROGRESS_BAR_ACTIVATED = 'progress_bar_activated';
	const ADMIN_ALERTS_ACTIVATED = 'admin_alerts_activated';
	const ADMIN_ALERTS_FIX_ACTION = 'admin_alerts_fix_action';
	const PM_ACTIVATED = 'pm_activated';
	const PM_COMMENT_ACTIVATED = 'pm_comment_activated';
	const PM_ASSIGN_ACTIVATED = 'pm_assign_activated';
	const PM_EDIT_ACTIVATED = 'pm_edit_activated';
	const PM_REJECT_ACTIVATED = 'pm_reject_activated';
	const PM_REOPEN_ACTIVATED = 'pm_reopen_activated';
	const PM_DELETE_ACTIVATED = 'pm_delete_activated';
	const CONTENTS_VALUE = 'contents_value';
	const TYPES = 'types';
	const CATEGORIES = 'categories';
	const SEVERITIES = 'severities';
	const PRIORITIES = 'priorities';
	const VERSIONS = 'versions';
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
	const STATUS_LIST = 'status_list';
	
	const BUGTRACKER_MAX_SEARCH_RESULTS = 50;
	
	//Date format
	const DATE_FORMAT = 'date_format';
	const DATE_FORMAT_SHORT = 'date_format_short';
	
	//Admin alerts fix type
	const FIX = 'fix';
	const DELETE = 'delete';
	
	 /**
	 * @method Get items per page
	 */
	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}
	
	 /**
	 * @method Set items per page
	 * @params int $value Number of items to display per page
	 */
	public function set_items_per_page($value) 
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}
	
	 /**
	 * @method Get the color of a rejected bug
	 */
	public function get_rejected_bug_color()
	{
		return $this->get_property(self::REJECTED_BUG_COLOR);
	}
	
	 /**
	 * @method Set the color of a rejected bug
	 * @params string $value Rejected bug color
	 */
	public function set_rejected_bug_color($value) 
	{
		$this->set_property(self::REJECTED_BUG_COLOR, $value);
	}
	
	 /**
	 * @method Get the color of a fixed bug
	 */
	public function get_fixed_bug_color()
	{
		return $this->get_property(self::FIXED_BUG_COLOR);
	}
	
	 /**
	 * @method Set the color of a fixed bug
	 * @params string $value Fixed bug color
	 */
	public function set_fixed_bug_color($value) 
	{
		$this->set_property(self::FIXED_BUG_COLOR, $value);
	}
	
	 /**
	 * @method Get the date format to display
	 */
	public function get_date_form()
	{
		return $this->get_property(self::DATE_FORM);
	}
	
	 /**
	 * @method Set the date format to display
	 * @params string $value Date format
	 */
	public function set_date_form($value) 
	{
		$this->set_property(self::DATE_FORM, $value);
	}
	
	 /**
	 * @method Check if the comments are activated
	 */
	public function get_comments_activated()
	{
		return $this->get_property(self::COMMENTS_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the comments
	 * @params boolean $value true/false
	 */
	public function set_comments_activated($value) 
	{
		$this->set_property(self::COMMENTS_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the display of the category in the title of the bug is activated
	 */
	public function get_cat_in_title_activated()
	{
		return $this->get_property(self::CAT_IN_TITLE_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the category in the title of the bug
	 * @params boolean $value true/false
	 */
	public function set_cat_in_title_activated($value) 
	{
		$this->set_property(self::CAT_IN_TITLE_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the roadmap is activated
	 */
	public function get_roadmap_activated()
	{
		return $this->get_property(self::ROADMAP_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the roadmap
	 * @params boolean $value true/false
	 */
	public function set_roadmap_activated($value) 
	{
		$this->set_property(self::ROADMAP_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the stats are activated
	 */
	public function get_stats_activated()
	{
		return $this->get_property(self::STATS_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the stats
	 * @params boolean $value true/false
	 */
	public function set_stats_activated($value) 
	{
		$this->set_property(self::STATS_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the stats top posters are activated
	 */
	public function get_stats_top_posters_activated()
	{
		return $this->get_property(self::STATS_TOP_POSTERS_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the stats top posters
	 * @params boolean $value true/false
	 */
	public function set_stats_top_posters_activated($value) 
	{
		$this->set_property(self::STATS_TOP_POSTERS_ACTIVATED, $value);
	}
	
	 /**
	 * @method Get number of posters displayed in the stats list
	 */
	public function get_stats_top_posters_number()
	{
		return $this->get_property(self::STATS_TOP_POSTERS_NUMBER);
	}
	
	 /**
	 * @method Set number of posters displayed in the stats list
	 * @params int $value Number of posters
	 */
	public function set_stats_top_posters_number($value) 
	{
		$this->set_property(self::STATS_TOP_POSTERS_NUMBER, $value);
	}
	
	 /**
	 * @method Check if the progress bar is activated
	 */
	public function get_progress_bar_activated()
	{
		return $this->get_property(self::PROGRESS_BAR_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the progress bar
	 * @params boolean $value true/false
	 */
	public function set_progress_bar_activated($value) 
	{
		$this->set_property(self::PROGRESS_BAR_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the admin alerts are activated
	 */
	public function get_admin_alerts_activated()
	{
		return $this->get_property(self::ADMIN_ALERTS_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the admin alerts
	 * @params boolean $value true/false
	 */
	public function set_admin_alerts_activated($value) 
	{
		$this->set_property(self::ADMIN_ALERTS_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check the action when closing the admin alert (delete or fix)
	 */
	public function get_admin_alerts_fix_action()
	{
		return $this->get_property(self::ADMIN_ALERTS_FIX_ACTION);
	}
	
	 /**
	 * @method Set the action when closing the admin alert
	 * @params string $value Admin alerts fix action
	 */
	public function set_admin_alerts_fix_action($value) 
	{
		$this->set_property(self::ADMIN_ALERTS_FIX_ACTION, $value);
	}
	
	 /**
	 * @method Check if the action when closing the admin alert is fix
	 */
	public function is_admin_alerts_fix_action_fix()
	{
		return $this->get_property(self::ADMIN_ALERTS_FIX_ACTION) == self::FIX ? true : false;
	}
	
	 /**
	 * @method Check if the PM are activated
	 */
	public function get_pm_activated()
	{
		return $this->get_property(self::PM_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the PM
	 * @params boolean $value true/false
	 */
	public function set_pm_activated($value) 
	{
		$this->set_property(self::PM_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the comment PM are activated
	 */
	public function get_pm_comment_activated()
	{
		return $this->get_property(self::PM_COMMENT_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the comment PM
	 * @params boolean $value true/false
	 */
	public function set_pm_comment_activated($value) 
	{
		$this->set_property(self::PM_COMMENT_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the assign PM are activated
	 */
	public function get_pm_assign_activated()
	{
		return $this->get_property(self::PM_ASSIGN_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the assign PM
	 * @params boolean $value true/false
	 */
	public function set_pm_assign_activated($value) 
	{
		$this->set_property(self::PM_ASSIGN_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the edit PM are activated
	 */
	public function get_pm_edit_activated()
	{
		return $this->get_property(self::PM_EDIT_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the edit PM
	 * @params boolean $value true/false
	 */
	public function set_pm_edit_activated($value) 
	{
		$this->set_property(self::PM_EDIT_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the reject PM are activated
	 */
	public function get_pm_reject_activated()
	{
		return $this->get_property(self::PM_REJECT_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the reject PM
	 * @params boolean $value true/false
	 */
	public function set_pm_reject_activated($value) 
	{
		$this->set_property(self::PM_REJECT_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the reopen PM are activated
	 */
	public function get_pm_reopen_activated()
	{
		return $this->get_property(self::PM_REOPEN_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the reopen PM
	 * @params boolean $value true/false
	 */
	public function set_pm_reopen_activated($value) 
	{
		$this->set_property(self::PM_REOPEN_ACTIVATED, $value);
	}
	
	 /**
	 * @method Check if the delete PM are activated
	 */
	public function get_pm_delete_activated()
	{
		return $this->get_property(self::PM_DELETE_ACTIVATED);
	}
	
	 /**
	 * @method Set the activation of the delete PM
	 * @params boolean $value true/false
	 */
	public function set_pm_delete_activated($value) 
	{
		$this->set_property(self::PM_DELETE_ACTIVATED, $value);
	}
	
	 /**
	 * @method Get the default content of a bug
	 */
	public function get_contents_value()
	{
		return $this->get_property(self::CONTENTS_VALUE);
	}
	
	 /**
	 * @method Set the default content of a bug
	 * @params string $value Default content
	 */
	public function set_contents_value($value) 
	{
		$this->set_property(self::CONTENTS_VALUE, $value);
	}
	
	 /**
	 * @method Get types
	 */
	public function get_types()
	{
		return $this->get_property(self::TYPES);
	}
	
	 /**
	 * @method Set types
	 * @params string[] $array Array of types
	 */
	public function set_types(Array $array)
	{
		$this->set_property(self::TYPES, $array);
	}
	
	 /**
	 * @method Get categories
	 */
	public function get_categories()
	{
		return $this->get_property(self::CATEGORIES);
	}
	
	 /**
	 * @method Set categories
	 * @params string[] $array Array of categories
	 */
	public function set_categories(Array $array)
	{
		$this->set_property(self::CATEGORIES, $array);
	}
	
	 /**
	 * @method Get severities
	 */
	public function get_severities()
	{
		return $this->get_property(self::SEVERITIES);
	}
	
	 /**
	 * @method Set severities
	 * @params string[] $array Array of severities
	 */
	public function set_severities(Array $array)
	{
		$this->set_property(self::SEVERITIES, $array);
	}
	
	 /**
	 * @method Get priorities
	 */
	public function get_priorities()
	{
		return $this->get_property(self::PRIORITIES);
	}
	
	 /**
	 * @method Set priorities
	 * @params string[] $array Array of priorities
	 */
	public function set_priorities(Array $array)
	{
		$this->set_property(self::PRIORITIES, $array);
	}
	
	 /**
	 * @method Get detected versions
	 */
	public function get_versions_detected()
	{
		$versions = $this->get_property(self::VERSIONS);
		$versions_detected_in = array();
		foreach ($versions as $key => $version)
		{
			if ($version['detected_in'] == true)
				$versions_detected_in[$key] = $version;
		}
		return $versions_detected_in;
	}
	
	 /**
	 * @method Get versions
	 */
	public function get_versions()
	{
		return $this->get_property(self::VERSIONS);
	}
	
	 /**
	 * @method Set versions
	 * @params string[] $array Array of versions
	 */
	public function set_versions(Array $array)
	{
		$this->set_property(self::VERSIONS, $array);
	}
	
	 /**
	 * @method Get default type
	 */
	public function get_default_type()
	{
		return $this->get_property(self::DEFAULT_TYPE);
	}
	
	 /**
	 * @method Set default type
	 * @params int $value Default type id
	 */
	public function set_default_type($value) 
	{
		$this->set_property(self::DEFAULT_TYPE, $value);
	}
	
	 /**
	 * @method Get default category
	 */
	public function get_default_category()
	{
		return $this->get_property(self::DEFAULT_CATEGORY);
	}
	
	 /**
	 * @method Set default category
	 * @params int $value Default category id
	 */
	public function set_default_category($value) 
	{
		$this->set_property(self::DEFAULT_CATEGORY, $value);
	}
	
	 /**
	 * @method Get default severity
	 */
	public function get_default_severity()
	{
		return $this->get_property(self::DEFAULT_SEVERITY);
	}
	
	 /**
	 * @method Set default severity
	 * @params int $value Default severity id
	 */
	public function set_default_severity($value) 
	{
		$this->set_property(self::DEFAULT_SEVERITY, $value);
	}
	
	 /**
	 * @method Get default priority
	 */
	public function get_default_priority()
	{
		return $this->get_property(self::DEFAULT_PRIORITY);
	}
	
	 /**
	 * @method Set default priority
	 * @params int $value Default priority id
	 */
	public function set_default_priority($value) 
	{
		$this->set_property(self::DEFAULT_PRIORITY, $value);
	}
	
	 /**
	 * @method Get default version
	 */
	public function get_default_version()
	{
		return $this->get_property(self::DEFAULT_VERSION);
	}
	
	 /**
	 * @method Set default version
	 * @params int $value Default version id
	 */
	public function set_default_version($value) 
	{
		$this->set_property(self::DEFAULT_VERSION, $value);
	}
	
	 /**
	 * @method Check if the type field is mandatory
	 */
	public function get_type_mandatory()
	{
		return $this->get_property(self::TYPE_MANDATORY);
	}
	
	 /**
	 * @method Set the mandatory of type field
	 * @params boolean $value true/false
	 */
	public function set_type_mandatory($value) 
	{
		$this->set_property(self::TYPE_MANDATORY, $value);
	}
	
	 /**
	 * @method Check if the category field is mandatory
	 */
	public function get_category_mandatory()
	{
		return $this->get_property(self::CATEGORY_MANDATORY);
	}
	
	 /**
	 * @method Set the mandatory of category field
	 * @params boolean $value true/false
	 */
	public function set_category_mandatory($value) 
	{
		$this->set_property(self::CATEGORY_MANDATORY, $value);
	}
	
	 /**
	 * @method Check if the severity field is mandatory
	 */
	public function get_severity_mandatory()
	{
		return $this->get_property(self::SEVERITY_MANDATORY);
	}
	
	 /**
	 * @method Set the mandatory of severity field
	 * @params boolean $value true/false
	 */
	public function set_severity_mandatory($value) 
	{
		$this->set_property(self::SEVERITY_MANDATORY, $value);
	}
	
	 /**
	 * @method Check if the priority field is mandatory
	 */
	public function get_priority_mandatory()
	{
		return $this->get_property(self::PRIORITY_MANDATORY);
	}
	
	 /**
	 * @method Set the mandatory of priority field
	 * @params boolean $value true/false
	 */
	public function set_priority_mandatory($value) 
	{
		$this->set_property(self::PRIORITY_MANDATORY, $value);
	}
	
	 /**
	 * @method Check if the detected in field is mandatory
	 */
	public function get_detected_in_mandatory()
	{
		return $this->get_property(self::DETECTED_IN_MANDATORY);
	}
	
	 /**
	 * @method Set the mandatory of detected in field
	 * @params boolean $value true/false
	 */
	public function set_detected_in_mandatory($value) 
	{
		$this->set_property(self::DETECTED_IN_MANDATORY, $value);
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
	 * @method Get the status list
	 */
	public function get_status_list()
	{
		return $this->get_property(self::STATUS_LIST);
	}
	
	 /**
	 * @method Set the status list
	 * @params string[] $array Array of status
	 */
	public function set_status_list(Array $array)
	{
		$this->set_property(self::STATUS_LIST, $array);
	}
	
	/**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 20,
			self::REJECTED_BUG_COLOR => '#f8465e',
			self::FIXED_BUG_COLOR => '#afffa2',
			self::DATE_FORM => self::DATE_FORMAT,
			self::COMMENTS_ACTIVATED => true,
			self::CAT_IN_TITLE_ACTIVATED => false,
			self::ROADMAP_ACTIVATED => false,
			self::STATS_ACTIVATED => true,
			self::STATS_TOP_POSTERS_ACTIVATED => true,
			self::STATS_TOP_POSTERS_NUMBER => 10,
			self::PROGRESS_BAR_ACTIVATED => true,
			self::ADMIN_ALERTS_ACTIVATED => true,
			self::ADMIN_ALERTS_FIX_ACTION => self::FIX,
			self::PM_ACTIVATED => true,
			self::PM_COMMENT_ACTIVATED => true,
			self::PM_ASSIGN_ACTIVATED => true,
			self::PM_EDIT_ACTIVATED => true,
			self::PM_REJECT_ACTIVATED => true,
			self::PM_REOPEN_ACTIVATED => true,
			self::PM_DELETE_ACTIVATED => true,
			self::CONTENTS_VALUE => LangLoader::get_message('bugtracker.config.contents_value', 'bugtracker_config', 'bugtracker'),
			self::TYPES => array(1 => LangLoader::get_message('bugtracker.config.types.anomaly', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.types.evolution_demand', 'bugtracker_config', 'bugtracker')),
			self::CATEGORIES => array(1 => LangLoader::get_message('bugtracker.config.categories.kernel', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.module', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.graphism', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.categories.installation', 'bugtracker_config', 'bugtracker')),
			self::SEVERITIES => array(1 => array('name' => LangLoader::get_message('bugtracker.config.severities.minor', 'bugtracker_config', 'bugtracker'), 'color' => '#e8ffa2'), array('name' => LangLoader::get_message('bugtracker.config.severities.major', 'bugtracker_config', 'bugtracker'), 'color' => '#feebbc'), array('name' => LangLoader::get_message('bugtracker.config.severities.critical', 'bugtracker_config', 'bugtracker'), 'color' => '#fdbbbb')),
			self::PRIORITIES => array(1 => LangLoader::get_message('bugtracker.config.priorities.very_low', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.low', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.normal', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.high', 'bugtracker_config', 'bugtracker'), LangLoader::get_message('bugtracker.config.priorities.urgent', 'bugtracker_config', 'bugtracker')),
			self::VERSIONS => array(),
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
			self::AUTHORIZATIONS => array('r0' => 3, 'r1' => 15),
			self::STATUS_LIST => array(Bug::NEW_BUG => 10, Bug::ASSIGNED => 50, Bug::IN_PROGRESS => 50, Bug::REJECTED => 0, Bug::REOPEN => 30, Bug::FIXED => 100)
		);
	}
	
	/**
	 * @method Load the bugtracker configuration.
	 * @return BugtrackerConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'bugtracker', 'config');
	}
	
	/**
	 * @method Saves the bugtracker configuration in the database. It becomes persistent.
	 */
	public static function save()
	{
		ConfigManager::save('bugtracker', self::load(), 'config');
	}
}
?>