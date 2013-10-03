<?php
/*##################################################
 *                          Notification.class.php
 *                            -------------------
 *   begin                : August 30, 2013
 *   copyright            : (C) 2013 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @package {@package}
 * @author Kévin MASSY <kevin.massy@phpboost.com>
 * @desc 
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
    private $priority = self::ADMIN_ALERT_MEDIUM_PRIORITY;

	/**
	 * @var string String containing the identifier of the module corresponding to the notification (ex: forum).
	 */
	private $module_id = '';

	/**
	 * @var array Authorization array containing the people who can read the contribution.
	 */
	private $auth = array();

	/**
	 * @desc Builds a Contribution object.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->module_id = Environment::get_running_module_name();
	}

	/**
	 * @desc Sets the module id from which the notification.
	 * @param string $module_id Module identifier (for example the name of the module folder).
	 */
	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}

	/**
	 * @desc Sets the fixing date.
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
	 * @desc Sets the authorization of the notification. It will determines who can read notifications.
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
	 * @desc Gets the module from which the notification.
	 * @return string The module identifier (for example the name of its folder).
	 */
	public function get_module_id()
	{
		return $this->module_id;
	}

	/**
	 * @desc Gets permission to read this notification
	 * @return mixed[] The authorization array.
	 */
	public function get_auth()
	{
		return $this->auth;
	}

	/**
	 * @desc Gets the name of the module from which the notification.
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