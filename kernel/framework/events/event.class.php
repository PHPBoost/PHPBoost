<?php
/*##################################################
 *                              event.class.php
 *                            -------------------
 *   begin                : July 21, 2008
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

import('util/date');

##Constants##
//Those are the different status of events
//Unread event
define('EVENT_STATUS_UNREAD', 0);
//Read event and beeing processed, somebody is focusing on it, but it's not processed
define('EVENT_STATUS_BEING_PROCESSED', 1);
//Read and processed, it is normally not useful anymore 
define('EVENT_STATUS_PROCESSED', 2);

//Table name in database
define('EVENTS_TABLE_NAME', 'events');

/* This class is abstract, it mustn't be instantiated and there is no matching service
It's the common part between two types of event existing now in PHPBoost :
	- User contribution managed into the contribution panel
	- Administrator alert, triggered for example when a new update is available or when a new member account is to approbate
*/

/*abstract*/ class Event
{
	## Public ##
	function Event()
	{
		$this->current_status = EVENT_STATUS_UNREAD;
		$this->creation_date = new Date();
	}
	
	//Id setter
	function set_id($id)
	{
		if( is_int($id) && $id > 0 )
			$this->id = $id;
	}
	
	//Entitled setter
	function set_entitled($entitled)
	{
		$this->entitled = $entitled;
	}
	
	//Sets a relative URL (from the root of the site)
	function set_fixing_url($fixing_url)
	{
		$this->fixing_url = $fixing_url;
	}

	// current_status setter
	function set_status($new_current_status)
	{
		if( in_array($new_current_status, array(EVENT_STATUS_UNREAD, EVENT_STATUS_BEING_PROCESSED, EVENT_STATUS_PROCESSED)) )
		{
			$this->current_status = $new_current_status;
		}
		//Default
		else
			$this->current_status = EVENT_STATUS_UNREAD;
		
		$this->must_regenerate_cache = true;
	}
		
	//Creation date setter
	function set_creation_date($date)
	{
		if( is_object($date) && strtolower(get_class($date)) == 'date' )
			$this->creation_date = $date;
	}	
	
	//Id in module setter
	function set_id_in_module($id)
	{
		$this->id_in_module = $id;
	}
	
	//Identifier setter
	function set_identifier($identifier)
	{
		$this->identifier = $identifier;
	}
	
	//Type setter
	function set_type($type)
	{
		$this->type = $type;
	}
	
	//Must we regenerate cache (do the changes we have done affect cache files?)
	function set_must_regenerate_cache($must)
	{
		if( is_bool($must) )
			$this->must_regenerate_cache = $must;
	}
	
	// Getters
	function get_id() { return $this->id; }
	function get_entitled() { return $this->entitled; }
	function get_fixing_url() { return $this->fixing_url; }
	function get_status() { return $this->current_status; }
	function get_creation_date() { return $this->creation_date; }
	function get_id_in_module() { return $this->id_in_module; }
	function get_identifier() { return $this->identifier; }
	function get_type() { return $this->type; }
	function get_must_regenerate_cache() { return $this->must_regenerate_cache; }
	
	function get_status_name()
	{
		global $LANG;
		
		switch($this->current_status)
		{
			case CONTRIBUTION_STATUS_UNREAD:
				return $LANG['contribution_status_unread'];
			case CONTRIBUTION_STATUS_BEING_PROCESSED:
				return $LANG['contribution_status_being_processed'];
			case CONTRIBUTION_STATUS_PROCESSED:
				return $LANG['contribution_status_processed'];
		}
	}
	
	//Constructor
	function build($id, $entitled, $fixing_url, $current_status, $creation_date, $id_in_module, $identifier, $type)
	{
		$this->id = $id;
		$this->entitled = $entitled;
		$this->fixing_url = $fixing_url;
		$this->current_status = $current_status;
		$this->creation_date = $creation_date;
		$this->id_in_module = $id_in_module;
		$this->identifier = $identifier;
		$this->type = $type;
		$this->must_regenerate_cache = false;
	}
	
	## Protected ##
	//Numerical identifier of the event (in DB)
	var $id = 0;
	//Entitled (title or name) of the event
	var $entitled = '';
	//URL where you can process the event
	var $fixing_url = '';
	//Status
	var $current_status = EVENT_STATUS_UNREAD;
	//Creation date
	var $creation_date;
	
	//The following attributes are used by the module developper to recognize his contributions
	//Id corresponding to the alert in the module (optionnal)
	var $id_in_module = 0;
	//Identifier to recognize the entry (optionnal)
	var $identifier = '';
	//Event type (optionnal)
	var $type = '';
	
	//To know if the modifications implies to regenerate the cache (for instance whether the status has been changed)
	var $must_regenerate_cache = true;
}

?>