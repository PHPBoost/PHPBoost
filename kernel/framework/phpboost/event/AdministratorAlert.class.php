<?php
/**
 * This class represents an alert which must be sent to the administrator.
 * It allows to the module developers to handle the administrator alerts.
 * The administrator alerts can be in the administration panel and can be used when you want to signal an important event to the administrator(s).
 * @package     PHPBoost
 * @subpackage  Event
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 2.0 - 2008 08 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdministratorAlert extends Event
{
	//Priority levels
	//High emergency, critical
	const ADMIN_ALERT_VERY_LOW_PRIORITY = 1;
	//Emergency, important
	const ADMIN_ALERT_LOW_PRIORITY = 2;
	//Medium
	const ADMIN_ALERT_MEDIUM_PRIORITY = 3;
	//Low priority
	const ADMIN_ALERT_HIGH_PRIORITY = 4;
	//Very low priority
	const ADMIN_ALERT_VERY_HIGH_PRIORITY = 5;

	//Alert status (boolean)
	//Unread alert
	const ADMIN_ALERT_STATUS_UNREAD = Event::EVENT_STATUS_UNREAD;
	//Processed alert
	const ADMIN_ALERT_STATUS_PROCESSED = Event::EVENT_STATUS_PROCESSED;

	/**
	 * @var int Priority of the alert
	 */
	private $priority = self::ADMIN_ALERT_MEDIUM_PRIORITY;

	/**
	 * @var string Properties of the alert (string field of unlimited length) which can for example contain a serializes array of object.
	 */
	private $alert_properties = '';

	/**
	 * Builds an AdministratorAlert object.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->priority = self::ADMIN_ALERT_MEDIUM_PRIORITY;
		$this->alert_properties = '';
	}

	/**
	 * Builds an alert from its whole parameters.
	 * @param int $id Identifier of the alert.
	 * @param string $entitled Entitled of the alert.
	 * @param string $properties Properties of the alert.
	 * @param string $fixing_url Fixing url.
	 * @param int $current_status Alert status.
	 * @param Date $creation_date Alert creation date?
	 * @param int $id_in_module Id in module field.
	 * @param string $identifier Identifier of the alert.
	 * @param string $type Type of the alert.
	 * @param int $priority Priority of the alert.
	 */
	public function build($id, $entitled, $properties, $fixing_url, $current_status, $creation_date, $id_in_module, $identifier, $type, $priority)
	{
		parent::build_event($id, $entitled, $fixing_url, $current_status, $creation_date, $id_in_module, $identifier, $type);
		$this->set_priority($priority);
		$this->set_alert_properties($properties);
	}

	/**
	 * Gets the priority of the alert.
	 * @return int One of those values:
	 * <ul>
	 *  <li>AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY Very low priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY Low priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY Medium priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY High priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY Very high priority</li>
	 * </ul>
	 */
	public function get_priority()
	{
		return $this->priority;
	}

	/**
	 * Gets the alert properties.
	 * @return string The properties.
	 */
	public function get_alert_properties()
	{
		return $this->alert_properties;
	}

	/**
	 * Sets the priority of the alert.
	 * @param int $priority The priority, it must be one of those values:
	 * <ul>
	 *  <li>AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY Very low priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY Low priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY Medium priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY High priority</li>
	 *  <li>AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY Very high priority</li>
	 * </ul>
	 */
	public function set_priority($priority)
	{
		$priority = intval($priority);
		if ($priority >= self::ADMIN_ALERT_VERY_LOW_PRIORITY && $priority <= self::ADMIN_ALERT_VERY_HIGH_PRIORITY)
		{
			$this->priority = $priority;
		}
		else
		{
			$this->priority = self::ADMIN_ALERT_MEDIUM_PRIORITY;
		}
	}

	/**
	 * Sets the properties of the alert.
	 * @param string $properties Properties.
	 */
	public function set_alert_properties($properties)
	{
		//If properties has the good type
		if (is_string($properties))
		{
			$this->alert_properties = $properties;
		}
	}

	/**
	 * Gets the priority name. It's automatically translater to the user language, ready to be displayed.
	 * @return string The priority name.
	 */
	public function get_priority_name()
	{
		$lang = LangLoader::get_all_langs();
		switch ($this->priority)
		{
			case self::ADMIN_ALERT_VERY_LOW_PRIORITY:
				return $lang['admin.priority.very.low'];
				break;
			case self::ADMIN_ALERT_LOW_PRIORITY:
				return $lang['admin.priority.low'];
				break;
			case self::ADMIN_ALERT_MEDIUM_PRIORITY:
				return $lang['admin.priority.medium'];
				break;
			case self::ADMIN_ALERT_HIGH_PRIORITY:
				return $lang['admin.priority.high'];
				break;
			case self::ADMIN_ALERT_VERY_HIGH_PRIORITY:
				return $lang['admin.priority.very.high'];
				break;
			default:
				return $lang['admin.priority.medium'];
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_properties()
	{
		return array_merge(parent::get_properties(), array(
			'priority'         => $this->get_priority(),
			'priority_name'    => $this->get_priority_name(),
			'alert_properties' => $this->get_alert_properties()
		));
	}
}
?>
