<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 05
 * @since       PHPBoost 3.0 - 2012 09 06
*/

class BugtrackerConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const REJECTED_BUG_COLOR = 'rejected_bug_color';
	const FIXED_BUG_COLOR = 'fixed_bug_color';
	const ROADMAP_ENABLED = 'roadmap_enabled';
	const STATS_ENABLED = 'stats_enabled';
	const STATS_TOP_POSTERS_ENABLED = 'stats_top_posters_enabled';
	const STATS_TOP_POSTERS_NUMBER = 'stats_top_posters_number';
	const PROGRESS_BAR_ENABLED = 'progress_bar_enabled';
	const RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED = 'restrict_display_to_own_elements_enabled';
	const ADMIN_ALERTS_ENABLED = 'admin_alerts_enabled';
	const ADMIN_ALERTS_LEVELS = 'admin_alerts_levels';
	const ADMIN_ALERTS_FIX_ACTION = 'admin_alerts_fix_action';
	const PM_ENABLED = 'pm_enabled';
	const PM_COMMENT_ENABLED = 'pm_comment_enabled';
	const PM_IN_PROGRESS_ENABLED = 'pm_in_progress_enabled';
	const PM_FIX_ENABLED = 'pm_fix_enabled';
	const PM_PENDING_ENABLED = 'pm_pending_enabled';
	const PM_ASSIGN_ENABLED = 'pm_assign_enabled';
	const PM_EDIT_ENABLED = 'pm_edit_enabled';
	const PM_REJECT_ENABLED = 'pm_reject_enabled';
	const PM_REOPEN_ENABLED = 'pm_reopen_enabled';
	const PM_DELETE_ENABLED = 'pm_delete_enabled';
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
	const DETECTED_IN_VERSION_MANDATORY = 'detected_in_version_mandatory';
	const DISPLAY_TYPE_COLUMN = 'display_type_column';
	const DISPLAY_CATEGORY_COLUMN = 'display_category_column';
	const DISPLAY_PRIORITY_COLUMN = 'display_priority_column';
	const DISPLAY_DETECTED_IN_COLUMN = 'display_detected_in_column';
	const AUTHORIZATIONS = 'authorizations';
	const STATUS_LIST = 'status_list';

	const NUMBER_CARACTERS_BEFORE_CUT = 150;

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

	public function enable_roadmap()
	{
		$this->set_property(self::ROADMAP_ENABLED, true);
	}

	public function disable_roadmap()
	{
		$this->set_property(self::ROADMAP_ENABLED, false);
	}

	public function is_roadmap_enabled()
	{
		return $this->get_property(self::ROADMAP_ENABLED);
	}

	public function enable_restrict_display_to_own_elements()
	{
		$this->set_property(self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED, true);
	}

	public function disable_restrict_display_to_own_elements()
	{
		$this->set_property(self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED, false);
	}

	public function is_restrict_display_to_own_elements_enabled()
	{
		return $this->get_property(self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED);
	}

	public function is_roadmap_displayed()
	{
		return $this->get_property(self::ROADMAP_ENABLED) && $this->get_property(self::VERSIONS);
	}

	public function enable_stats()
	{
		$this->set_property(self::STATS_ENABLED, true);
	}

	public function disable_stats()
	{
		$this->set_property(self::STATS_ENABLED, false);
	}

	public function are_stats_enabled()
	{
		return $this->get_property(self::STATS_ENABLED);
	}

	public function enable_stats_top_posters()
	{
		$this->set_property(self::STATS_TOP_POSTERS_ENABLED, true);
	}

	public function disable_stats_top_posters()
	{
		$this->set_property(self::STATS_TOP_POSTERS_ENABLED, false);
	}

	public function are_stats_top_posters_enabled()
	{
		return $this->get_property(self::STATS_TOP_POSTERS_ENABLED);
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

	public function display_progress_bar()
	{
		$this->set_property(self::PROGRESS_BAR_ENABLED, true);
	}

	public function hide_progress_bar()
	{
		$this->set_property(self::PROGRESS_BAR_ENABLED, false);
	}

	public function is_progress_bar_displayed()
	{
		return $this->get_property(self::PROGRESS_BAR_ENABLED);
	}

	public function enable_admin_alerts()
	{
		$this->set_property(self::ADMIN_ALERTS_ENABLED, true);
	}

	public function disable_admin_alerts()
	{
		$this->set_property(self::ADMIN_ALERTS_ENABLED, false);
	}

	public function are_admin_alerts_enabled()
	{
		return $this->get_property(self::ADMIN_ALERTS_ENABLED);
	}

	public function get_admin_alerts_levels()
	{
		return $this->get_property(self::ADMIN_ALERTS_LEVELS);
	}

	public function set_admin_alerts_levels(Array $array)
	{
		$this->set_property(self::ADMIN_ALERTS_LEVELS, $array);
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
		return $this->get_property(self::ADMIN_ALERTS_FIX_ACTION) == self::FIX;
	}

	public function enable_pm()
	{
		$this->set_property(self::PM_ENABLED, true);
	}

	public function disable_pm()
	{
		$this->set_property(self::PM_ENABLED, false);
	}

	public function are_pm_enabled()
	{
		return $this->get_property(self::PM_ENABLED);
	}

	public function enable_pm_comment()
	{
		$this->set_property(self::PM_COMMENT_ENABLED, true);
	}

	public function disable_pm_comment()
	{
		$this->set_property(self::PM_COMMENT_ENABLED, false);
	}

	public function are_pm_comment_enabled()
	{
		return $this->get_property(self::PM_COMMENT_ENABLED);
	}

	public function enable_pm_fix()
	{
		$this->set_property(self::PM_FIX_ENABLED, true);
	}

	public function disable_pm_fix()
	{
		$this->set_property(self::PM_FIX_ENABLED, false);
	}

	public function are_pm_fix_enabled()
	{
		return $this->get_property(self::PM_FIX_ENABLED);
	}

	public function enable_pm_in_progress()
	{
		$this->set_property(self::PM_IN_PROGRESS_ENABLED, true);
	}

	public function disable_pm_in_progress()
	{
		$this->set_property(self::PM_IN_PROGRESS_ENABLED, false);
	}

	public function are_pm_in_progress_enabled()
	{
		return $this->get_property(self::PM_IN_PROGRESS_ENABLED);
	}

	public function enable_pm_pending()
	{
		$this->set_property(self::PM_PENDING_ENABLED, true);
	}

	public function disable_pm_pending()
	{
		$this->set_property(self::PM_PENDING_ENABLED, false);
	}

	public function are_pm_pending_enabled()
	{
		return $this->get_property(self::PM_PENDING_ENABLED);
	}

	public function enable_pm_assign()
	{
		$this->set_property(self::PM_ASSIGN_ENABLED, true);
	}

	public function disable_pm_assign()
	{
		$this->set_property(self::PM_ASSIGN_ENABLED, false);
	}

	public function are_pm_assign_enabled()
	{
		return $this->get_property(self::PM_ASSIGN_ENABLED);
	}

	public function enable_pm_edit()
	{
		$this->set_property(self::PM_EDIT_ENABLED, true);
	}

	public function disable_pm_edit()
	{
		$this->set_property(self::PM_EDIT_ENABLED, false);
	}

	public function are_pm_edit_enabled()
	{
		return $this->get_property(self::PM_EDIT_ENABLED);
	}

	public function enable_pm_reject()
	{
		$this->set_property(self::PM_REJECT_ENABLED, true);
	}

	public function disable_pm_reject()
	{
		$this->set_property(self::PM_REJECT_ENABLED, false);
	}

	public function are_pm_reject_enabled()
	{
		return $this->get_property(self::PM_REJECT_ENABLED);
	}

	public function enable_pm_reopen()
	{
		$this->set_property(self::PM_REOPEN_ENABLED, true);
	}

	public function disable_pm_reopen()
	{
		$this->set_property(self::PM_REOPEN_ENABLED, false);
	}

	public function are_pm_reopen_enabled()
	{
		return $this->get_property(self::PM_REOPEN_ENABLED);
	}

	public function enable_pm_delete()
	{
		$this->set_property(self::PM_DELETE_ENABLED, true);
	}

	public function disable_pm_delete()
	{
		$this->set_property(self::PM_DELETE_ENABLED, false);
	}

	public function are_pm_delete_enabled()
	{
		return $this->get_property(self::PM_DELETE_ENABLED);
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
	 * @method Get fix versions
	 */
	public function get_versions_fix()
	{
		$now = new Date();
		$versions = $this->get_property(self::VERSIONS);
		$versions_fix = array();
		foreach ($versions as $key => $version)
		{
			$release_date = !empty($version['release_date']) && is_numeric($version['release_date']) ? new Date($version['release_date'], Timezone::SERVER_TIMEZONE) : null;

			if (empty($release_date) || $release_date->get_timestamp() >= $now->get_timestamp())
				$versions_fix[$key] = $version;
		}
		return $versions_fix;
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

	public function type_mandatory()
	{
		$this->set_property(self::TYPE_MANDATORY, true);
	}

	public function type_not_mandatory()
	{
		$this->set_property(self::TYPE_MANDATORY, false);
	}

	public function is_type_mandatory()
	{
		return $this->get_property(self::TYPE_MANDATORY);
	}

	public function category_mandatory()
	{
		$this->set_property(self::CATEGORY_MANDATORY, true);
	}

	public function category_not_mandatory()
	{
		$this->set_property(self::CATEGORY_MANDATORY, false);
	}

	public function is_category_mandatory()
	{
		return $this->get_property(self::CATEGORY_MANDATORY);
	}

	public function severity_mandatory()
	{
		$this->set_property(self::SEVERITY_MANDATORY, true);
	}

	public function severity_not_mandatory()
	{
		$this->set_property(self::SEVERITY_MANDATORY, false);
	}

	public function is_severity_mandatory()
	{
		return $this->get_property(self::SEVERITY_MANDATORY);
	}

	public function priority_mandatory()
	{
		$this->set_property(self::PRIORITY_MANDATORY, true);
	}

	public function priority_not_mandatory()
	{
		$this->set_property(self::PRIORITY_MANDATORY, false);
	}

	public function is_priority_mandatory()
	{
		return $this->get_property(self::PRIORITY_MANDATORY);
	}

	public function detected_in_version_mandatory()
	{
		$this->set_property(self::DETECTED_IN_VERSION_MANDATORY, true);
	}

	public function detected_in_version_not_mandatory()
	{
		$this->set_property(self::DETECTED_IN_VERSION_MANDATORY, false);
	}

	public function is_detected_in_version_mandatory()
	{
		return $this->get_property(self::DETECTED_IN_VERSION_MANDATORY);
	}

	public function display_type_column()
	{
		$this->set_property(self::DISPLAY_TYPE_COLUMN, true);
	}

	public function hide_type_column()
	{
		$this->set_property(self::DISPLAY_TYPE_COLUMN, false);
	}

	public function is_type_column_displayed()
	{
		return $this->get_property(self::DISPLAY_TYPE_COLUMN);
	}

	public function display_category_column()
	{
		$this->set_property(self::DISPLAY_CATEGORY_COLUMN, true);
	}

	public function hide_category_column()
	{
		$this->set_property(self::DISPLAY_CATEGORY_COLUMN, false);
	}

	public function is_category_column_displayed()
	{
		return $this->get_property(self::DISPLAY_CATEGORY_COLUMN);
	}

	public function display_priority_column()
	{
		$this->set_property(self::DISPLAY_PRIORITY_COLUMN, true);
	}

	public function hide_priority_column()
	{
		$this->set_property(self::DISPLAY_PRIORITY_COLUMN, false);
	}

	public function is_priority_column_displayed()
	{
		return $this->get_property(self::DISPLAY_PRIORITY_COLUMN);
	}

	public function display_detected_in_column()
	{
		$this->set_property(self::DISPLAY_DETECTED_IN_COLUMN, true);
	}

	public function hide_detected_in_column()
	{
		$this->set_property(self::DISPLAY_DETECTED_IN_COLUMN, false);
	}

	public function is_detected_in_column_displayed()
	{
		return $this->get_property(self::DISPLAY_DETECTED_IN_COLUMN);
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
		$lang = LangLoader::get('config', 'bugtracker');

		return array(
			self::ITEMS_PER_PAGE => 20,
			self::REJECTED_BUG_COLOR => '#f8465e',
			self::FIXED_BUG_COLOR => '#afffa2',
			self::ROADMAP_ENABLED => false,
			self::STATS_ENABLED => true,
			self::STATS_TOP_POSTERS_ENABLED => true,
			self::STATS_TOP_POSTERS_NUMBER => 10,
			self::PROGRESS_BAR_ENABLED => true,
			self::RESTRICT_DISPLAY_TO_OWN_ELEMENTS_ENABLED => false,
			self::ADMIN_ALERTS_ENABLED => true,
			self::ADMIN_ALERTS_LEVELS => array('2', '3'),
			self::ADMIN_ALERTS_FIX_ACTION => self::FIX,
			self::PM_ENABLED => true,
			self::PM_COMMENT_ENABLED => true,
			self::PM_IN_PROGRESS_ENABLED => true,
			self::PM_FIX_ENABLED => true,
			self::PM_PENDING_ENABLED => true,
			self::PM_ASSIGN_ENABLED => true,
			self::PM_EDIT_ENABLED => true,
			self::PM_REJECT_ENABLED => true,
			self::PM_REOPEN_ENABLED => true,
			self::PM_DELETE_ENABLED => true,
			self::CONTENTS_VALUE => '',
			self::TYPES => array(1 => $lang['types.anomaly'], $lang['types.evolution']),
			self::CATEGORIES => array(1 => $lang['categories.kernel'], $lang['categories.module'], $lang['categories.graphism'], $lang['categories.installation']),
			self::SEVERITIES => array(1 => array('name' => $lang['severities.minor'], 'color' => '#e8ffa2'), array('name' => $lang['severities.major'], 'color' => '#feebbc'), array('name' => $lang['severities.critical'], 'color' => '#fdbbbb')),
			self::PRIORITIES => array(1 => $lang['priorities.very_low'], $lang['priorities.low'], $lang['priorities.normal'], $lang['priorities.high'], $lang['priorities.urgent']),
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
			self::DETECTED_IN_VERSION_MANDATORY => false,
			self::DISPLAY_TYPE_COLUMN => false,
			self::DISPLAY_CATEGORY_COLUMN => false,
			self::DISPLAY_PRIORITY_COLUMN => false,
			self::DISPLAY_DETECTED_IN_COLUMN => false,
			self::AUTHORIZATIONS => array('r0' => 3, 'r1' => 15),
			self::STATUS_LIST => array(Bug::NEW_BUG => 0, Bug::PENDING => 0, Bug::ASSIGNED => 20, Bug::IN_PROGRESS => 50, Bug::REJECTED => 0, Bug::REOPEN => 30, Bug::FIXED => 100)
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
