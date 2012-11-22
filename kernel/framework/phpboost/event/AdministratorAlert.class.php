<?php
/*##################################################
 *                       AdministratorAlert.class.php
 *                            -------------------
 *   begin                : August 27, 2008
 *   copyright            : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class represents an alert which must be sent to the administrator.
 * It allows to the module developers to handle the administrator alerts.
 * The administrator alerts can be in the administration panel and can be used when you want to signal an important event to the administrator(s).
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
    private $properties = '';
    
    /**
     * @desc Builds an AdministratorAlert object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->priority = self::ADMIN_ALERT_MEDIUM_PRIORITY;
        $this->properties = '';
    }

    /**
     * @desc Builds an alert from its whole parameters.
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
        $this->set_properties($properties);
    }

    /**
     * @desc Gets the priority of the alert.
     * @return int One of those values:
     * <ul>
     * 	<li>AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY Very low priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY Low priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY Medium priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY High priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY Very high priority</li>
     * </ul>
     */
    public function get_priority()
    {
        return $this->priority;
    }

    /**
     * @desc Gets the alert properties.
     * @return string The properties.
     */
    public function get_properties()
    {
        return $this->properties;
    }

    /**
     * @desc Sets the priority of the alert.
     * @param int $priority The priority, it must be one of those values:
     * <ul>
     * 	<li>AdministratorAlert::ADMIN_ALERT_VERY_LOW_PRIORITY Very low priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_LOW_PRIORITY Low priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_MEDIUM_PRIORITY Medium priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_HIGH_PRIORITY High priority</li>
     * 	<li>AdministratorAlert::ADMIN_ALERT_VERY_HIGH_PRIORITY Very high priority</li>
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
     * @desc Sets the properties of the alert. 
     * @param string $properties Properties.
     */
    public function set_properties($properties)
    {
        //If properties has the good type
        if (is_string($properties))
        {
            $this->properties = $properties;
        }
    }

    /**
     * @desc Gets the priority name. It's automatically translater to the user language, ready to be displayed. 
     * @return string The priority name.
     */
    public function get_priority_name()
    {
        global $LANG;
        
        $admin_lang = LangLoader::get('admin');
        switch ($this->priority)
        {
            case self::ADMIN_ALERT_VERY_LOW_PRIORITY:
                return $admin_lang['priority_very_low'];
                break;
            case self::ADMIN_ALERT_LOW_PRIORITY:
                return $admin_lang['priority_low'];
                break;
            case self::ADMIN_ALERT_MEDIUM_PRIORITY:
                return $admin_lang['priority_medium'];
                break;
            case self::ADMIN_ALERT_HIGH_PRIORITY:
                return $admin_lang['priority_high'];
                break;
            case self::ADMIN_ALERT_VERY_HIGH_PRIORITY:
                return $admin_lang['priority_very_high'];
                break;
            default:
				// TODO, langue existante ?
                return $LANG['normal'];
        }
    }
}
?>