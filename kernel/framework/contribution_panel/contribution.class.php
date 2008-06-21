<?php
/*##################################################
 *                             contribution.class.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright          : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@gmail.com
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

require_once(PATH_TO_ROOT . '/kernel/framework/contribution_panel/contribution_panel.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

//Fonction d'importation/exportation de base de donnée.
class Contribution
{
	## Public ##
	function Contribution()
	{
	}
	
	//Entitled setter
	function set_entitled($entitled)
	{
		$this->entitled = $entitled;
	}
	
	//Description setter
	function set_description($description)
	{
		$this->description = $description;
	}
	
	//Sets a relative URL (from the root of the site)
	function set_url($url)
	{
		$this->url = $url;
	}
	
	//Module setter
	function set_module($entitled)
	{
		$this->module = $module;
	}

	// Status setter
	function set_status($new_status)
	{
		if( in_array($new_status, array(CONTRIBUTION_STATUS_NOT_READ, CONTRIBUTION_STATUS_PROCESSING, CONTRIBUTION_STATUS_FIXED) )
		{
			$this->status = $new_status;
			//If we just come to fix it, we assign the fixing date
			if( $new_status == CONTRIBUTION_STATUS_FIXED )
				$this->fixing_date = new Date();
		}
		//Default
		else
			$this->status = CONTRIBUTION_STATUS_NOT_READ;
	}
		
	//Creation date setter
	function set_creation_date($date)
	{
		if( is_object($date) && get_class($date) == 'Date' )
			$this->creation_date = $date;
		else
			die('<strong>Contribution::set_date error</strong> : parameter 1 expected to be Date and ' . gettype($date) . ' given');
	}
	
	//Fixing date setter
	function set_fixing_date($date)
	{
		if( is_object($date) && get_class($date) == 'Date' )
			$this->fixing_date = $date;
		else
			die('<strong>Contribution::set_date error</strong> : parameter 1 expected to be Date and ' . gettype($date) . ' given');
	}
	
	// Getters
	function get_id() { return $this->id; }
	function get_entitled() { return $this->entitled; }
	function get_description() { return $this->description; }
	function get_url() { return $this->url; }
	function get_module() { return $this->module; }
	function get_status() { return $this->status; }
	function get_creation_date() { return $this->creation_date; }
	function get_fixing_date() { return $this->fixing_date; }
	
	function create_in_db()
	{
		global $Sql;
		$this->creation_date = new Date();
		$Sql->Query_inject("INSERT INTO ".PREFIX."contributions (entitled, description, url, module, status, creation_date, fixing_date) VALUES (" . $entitled . "', '" . $description . "', '" . $url . "', '" . $module . "', '" . $status . "', '" . $this->creation_date->get_timestamp() . "', 0)", __LINE__, __FILE__);
		$this->id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."contributions");
	}
	
	function update_in_db()
	{
		global $Sql;
		// If it exists already in the data base
		if( $this->id > 0 )
			$Sql->Query_inject("UPDATE ".PREFIX."contributions SET entitled = '" . $entitled . "', description = '" . $description . "', url = '" . $url . "', module = '" . $module . "', status = '" . $status . "', creation_date = '" . $creation_date->to_timestamp() . "', fixing_date = '" . $fixing_date->get_timestamp() . "') WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
	}
	
	function delete_in_db()
	{
		global $Sql;
		$Sql->Query_inject("DELETE FROM ".PREFIX."contributions WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
		//We reset the id
		$this->id = 0;
	}
	
	## Private ##
	var $id;
	var $entitled;
	var $description;
	var $url;
	var $module;
	var $status;
	var $creation_date;
	var $fixing_date;
}

?>