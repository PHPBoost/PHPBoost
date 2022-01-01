<?php
/**
 * @package     PHPBoost
 * @subpackage  Event
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 04 27
 * @since       PHPBoost 3.0 - 2013 08 30
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class Notification extends Event
{
	const NOTIFICATION_AUTH_BIT = 1;

	const NOTIFICATION_ALERT_LOW_PRIORITY = 2;
	const NOTIFICATION_ALERT_MEDIUM_PRIORITY = 3;
	const NOTIFICATION_ALERT_HIGH_PRIORITY = 4;

	/**
	 * @var int Priority of the alert
	 */
	private $priority = self::NOTIFICATION_ALERT_MEDIUM_PRIORITY;

	/**
	 * @var string String containing the identifier of the module corresponding to the notification (ex: forum).
	 */
	private $module_id = '';

	/**
	 * @var array Authorization array containing the people who can read the contribution.
	 */
	private $auth = array();

	/**
	 * Builds a Contribution object.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->module_id = Environment::get_running_module_name();
	}

	/**
	 * Sets the module id from which the notification.
	 * @param string $module_id Module identifier (for example the name of the module folder).
	 */
	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}

	/**
	 * Sets the fixing date.
	 * @param Date $date Date
	 */
	public function set_fixing_date($date)
	{
		if (is_object($date) && $date instanceof Date)
		{
			$this->fixing_date = $date;
		}
	}

	/**
	 * Sets the authorization of the notification. It will determines who can read notifications.
	 * @param mixed[] $auth Auth array.
	 */
	public function set_auth($auth)
	{
		if (is_array($auth))
		{
			$this->auth = $auth;
		}
	}

	/**
	 * Gets the module from which the notification.
	 * @return string The module identifier (for example the name of its folder).
	 */
	public function get_module_id()
	{
		return $this->module_id;
	}

	/**
	 * Gets permission to read this notification
	 * @return mixed[] The authorization array.
	 */
	public function get_auth()
	{
		return $this->auth;
	}

	/**
	 * Gets the name of the module from which the notification.
	 * @return string The module name.
	 */
	public function get_module_name()
	{
		if (!empty($this->module_id))
		{
			$module = ModulesManager::get_module($this->module_id);

			return $module ? $module->get_configuration()->get_name() : '';
		}
		else
		{
			return '';
		}
	}
}
?>
