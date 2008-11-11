<?php
/*##################################################
 *                       administrator_alert.class.php
 *                            -------------------
 *   begin                : August 27, 2008
 *   copyright            : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

import('events/event');

## Constants ##
//Priority levels
//High emergency, critical
define('ADMIN_ALERT_VERY_LOW_PRIORITY', 1);
//Emergency, important
define('ADMIN_ALERT_LOW_PRIORITY', 2);
//Medium
define('ADMIN_ALERT_MEDIUM_PRIORITY', 3);
//Low priority
define('ADMIN_ALERT_HIGH_PRIORITY', 4);
//Very low priority
define('ADMIN_ALERT_VERY_HIGH_PRIORITY', 5);

//Alert status (boolean)
//Unread alert
define('ADMIN_ALERT_STATUS_UNREAD', EVENT_STATUS_UNREAD);
//Processed alert
define('ADMIN_ALERT_STATUS_PROCESSED', EVENT_STATUS_PROCESSED);

//Class administrator alert
class AdministratorAlert extends Event 
{
	## Public ##
	function AdministratorAlert()
	{
		parent::Event();
		$this->priority = ADMIN_ALERT_MEDIUM_PRIORITY;
		$this->properties = '';
	}
	
	//Constructor of an administrator alert
	function build($id, $entitled, $properties, $fixing_url, $current_status, $creation_date, $id_in_module, $identifier, $type, $priority)
	{
		parent::build($id, $entitled, $fixing_url, $current_status, $creation_date, $id_in_module, $identifier, $type);
		$this->set_priority($priority);
		$this->set_properties($properties);
	}
	
	//Priority getter
	function get_priority()
	{
		return $this->priority;
	}
	
	//Properties getter
	function get_properties()
	{
		return $this->properties;
	}
	
	//Priority setter
	function set_priority($priority)
	{
		$priority = intval($priority);
		if( $priority >= ADMIN_ALERT_VERY_LOW_PRIORITY && $priority <= ADMIN_ALERT_VERY_HIGH_PRIORITY )
			$this->priority = $priority;
		else
			$this->priority = ADMIN_ALERT_MEDIUM_PRIORITY;
	}
	
	//Properties setter
	function set_properties($properties)
	{
		//If properties has the good type
		if( is_string($properties) )
			$this->properties = $properties;
	}
	
	//Priority name getter
	function get_priority_name()
	{
		global $LANG;
		switch($this->priority)
		{
			case ADMIN_ALERT_VERY_LOW_PRIORITY:
				return $LANG['priority_very_low'];
				break;
			case ADMIN_ALERT_LOW_PRIORITY:
				return $LANG['priority_low'];
			break;
			case ADMIN_ALERT_MEDIUM_PRIORITY:
				return $LANG['priority_medium'];
				break;
			case ADMIN_ALERT_HIGH_PRIORITY:
				return $LANG['priority_high'];
			break;
			case ADMIN_ALERT_VERY_HIGH_PRIORITY:
				return $LANG['priority_very_high'];
			break;
			default:
				return $LANG['normal'];
		}
	}
	
	## Private ##
	//Priority of the alert
	var $priority = ADMIN_ALERT_MEDIUM_PRIORITY;
	//Properties of the alert (string field of unlimited length) which can for example contain a serializes array of object
	var $properties = '';
}

?>