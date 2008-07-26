<?php
/*##################################################
 *                             contribution.class.php
 *                            -------------------
 *   begin                : July 21, 2008
 *   copyright          : (C) 2008 Benoît Sautel
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

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/contribution_panel.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

define('CONTRIBUTION_STATUS_UNREAD', 0);
define('CONTRIBUTION_STATUS_BEING_PROCESSING', 1);
define('CONTRIBUTION_STATUS_PROCESSED', 2);


//Fonction d'importation/exportation de base de donnée.
class Contribution
{
	## Public ##
	function Contribution()
	{
		$this->current_status = CONTRIBUTION_STATUS_UNREAD;
		$this->creation_date = new Date();
		$this->fixing_date = new Date();
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
	function set_fixing_url($fixing_url)
	{
		$this->fixing_url = $fixing_url;
	}
	
	//Module setter
	function set_module($module)
	{
		$this->module = $module;
	}

	// current_status setter
	function set_current_status($new_current_status)
	{
		if( in_array($new_current_status, array(CONTRIBUTION_current_status_NOT_READ, CONTRIBUTION_current_status_PROCESSING, CONTRIBUTION_current_status_FIXED)) )
		{
			$this->current_status = $new_current_status;
			//If we just come to fix it, we assign the fixing date
			if( $new_current_status == CONTRIBUTION_current_status_FIXED )
				$this->fixing_date = new Date();
		}
		//Default
		else
			$this->current_status = CONTRIBUTION_current_status_NOT_READ;
	}
		
	//Creation date setter
	function set_creation_date($date)
	{
		if( is_object($date) && strtolower(get_class($date)) == 'date' )
			$this->creation_date = $date;
		else
			die('<strong>Contribution::set_creation_date error</strong> : parameter 1 expected to be Date and ' . gettype($date) . ' given');
	}
	
	//Fixing date setter
	function set_fixing_date($date)
	{
		if( is_object($date) && strtolower(get_class($date)) == 'date' )
			$this->fixing_date = $date;
		else
			die('<strong>Contribution::set_fixing_date error</strong> : parameter 1 expected to be Date and ' . gettype($date) . ' given');
	}
	
	//Auth setter
	function set_auth($auth)
	{
		if( is_array($auth) )
			$this->auth = $auth;
		else
			die('<strong>Contribution::set_auth error</strong> : parameter 1 expected to be array and ' . gettype($date) . ' given');
	}
	
	//Poster_id setter
	function set_poster_id($poster_id)
	{
		$this->poster_id = $poster_id;
	}

	//Fixer id setter
	function set_fixer_id($fixer_id)
	{
		$this->fixer_id = $fixer_id;
	}
	
	// Getters
	function get_id() { return $this->id; }
	function get_entitled() { return $this->entitled; }
	function get_description() { return $this->description; }
	function get_fixing_url() { return $this->fixing_url; }
	function get_module() { return $this->module; }
	function get_current_status() { return $this->current_status; }
	function get_creation_date() { return $this->creation_date; }
	function get_fixing_date() { return $this->fixing_date; }
	function get_auth() { return $this->auth; }
	function get_poster_id() { return $this->poster_id; }
	function get_fixer_id() { return $this->fixer_id; }
	
	function get_status_name()
	{
		global $LANG;
		
		switch($this->current_status)
		{
			case CONTRIBUTION_STATUS_UNREAD:
				return $LANG['contribution_status_unread'];
			case CONTRIBUTION_STATUS_BEING_PROCESSING:
				return $LANG['contribution_status_being_processed'];
			case CONTRIBUTION_STATUS_PROCESSED:
				return $LANG['contribution_status_processed'];
		}
	}
	
	function get_module_name()
	{
		global $CONFIG;
		$module_ini = load_ini_file(PATH_TO_ROOT . '/' . $this->module . '/lang/', $CONFIG['lang']);
		return $module_ini['name'];
	}
	
	//Db creation
	function create_in_db()
	{
		global $Sql;
		$this->creation_date = new Date();
		$Sql->Query_inject("INSERT INTO ".PREFIX."contributions (entitled, description, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id) VALUES ('" . $this->entitled . "', '" . $this->description . "', '" . $this->fixing_url . "', '" . $this->module . "', '" . $this->current_status . "', '" . $this->creation_date->get_timestamp() . "', 0, '" . (!empty($this->auth) ? addslashes(serialize($this->auth)) : '') . "', '" . $this->poster_id . "', '" . $this->fixer_id . "')", __LINE__, __FILE__);
		$this->id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."contributions");
	}
	
	function update_in_db()
	{
		global $Sql;
		// If it exists already in the data base
		if( $this->id > 0 )
			$Sql->Query_inject("UPDATE ".PREFIX."contributions SET entitled = '" . $this->entitled . "', description = '" . $this->description . "', fixing_url = '" . $this->fixing_url . "', module = '" . $this->module . "', current_status = '" . $this->current_status . "', creation_date = '" . $this->creation_date->to_timestamp() . "', fixing_date = '" . $this->fixing_date->get_timestamp() . "', auth = '" . addslashes(serialize($this->auth)) . "', poster_id = '" . $this->poster_id . "', fixer_id = '" . $this->fixer_id . "' WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
	}
	
	function delete_in_db()
	{
		global $Sql;
		$Sql->Query_inject("DELETE FROM ".PREFIX."contributions WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
		//We reset the id
		$this->id = 0;
	}
	
	function load_from_db($id_contrib)
	{
		global $Sql;
		
		$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, description
		FROM ".PREFIX."contributions c
		LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
		WHERE id = '" . $id_contrib . "'
		ORDER BY creation_date DESC", __LINE__, __FILE__);
		
		$properties = $Sql->sql_fetch_assoc($result);
		
		$this->build_from_db($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['module'], $properties['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $properties['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $properties['fixing_date']), $properties['auth'], $properties['poster_id'], $properties['fixer_id']);
	}
	
	//Construction of a contribution from database
	function build_from_db($id, $entitled, $description, $fixing_url, $module, $current_status, $creation_date, $fixing_date, $auth, $poster_id, $fixer_id)
	{
		$this->id = $id;
		$this->entitled = $entitled;
		$this->description = $description;
		$this->fixing_url = $fixing_url;
		$this->module = $module;
		$this->current_status = $current_status;
		$this->creation_date = $creation_date;
		$this->fixing_date = $fixing_date;
		$this->auth = $auth;
		$this->poster_id = $poster_id;
		$this->fixer_id = $fixer_id;
	}
	
	## Private ##
	var $id;
	var $entitled;
	var $description;
	var $fixing_url;
	var $module;
	var $current_status = CONTRIBUTION_STATUS_UNREAD;
	var $creation_date;
	var $fixing_date;
	var $auth;
	var $poster_id;
	var $fixer_id;
}

?>