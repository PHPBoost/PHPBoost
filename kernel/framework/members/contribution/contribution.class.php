<?php
/*##################################################
 *                          contribution.class.php
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

require_once(PATH_TO_ROOT . '/kernel/framework/util/date.class.php');

define('CONTRIBUTION_TYPE', 0);
define('CONTRIBUTION_STATUS_UNREAD', 0);
define('CONTRIBUTION_STATUS_BEING_PROCESSED', 1);
define('CONTRIBUTION_STATUS_PROCESSED', 2);
define('CONTRIBUTION_AUTH_BIT', 1);

//Fonction d'importation/exportation de base de données.
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
	function set_status($new_current_status)
	{
		if( in_array($new_current_status, array(CONTRIBUTION_STATUS_UNREAD, CONTRIBUTION_STATUS_BEING_PROCESSED, CONTRIBUTION_STATUS_PROCESSED)) )
		{
			$this->current_status = $new_current_status;
			//If we just come to fix it, we assign the fixing date
			if( $new_current_status == CONTRIBUTION_STATUS_PROCESSED )
				$this->fixing_date = new Date();
		}
		//Default
		else
			$this->current_status = CONTRIBUTION_STATUS_UNREAD;
		
		$this->must_regenerate_cache = true;
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
	
	// Getters
	function get_id() { return $this->id; }
	function get_entitled() { return $this->entitled; }
	function get_description() { return $this->description; }
	function get_fixing_url() { return $this->fixing_url; }
	function get_module() { return $this->module; }
	function get_status() { return $this->current_status; }
	function get_creation_date() { return $this->creation_date; }
	function get_fixing_date() { return $this->fixing_date; }
	function get_auth() { return $this->auth; }
	function get_poster_id() { return $this->poster_id; }
	function get_fixer_id() { return $this->fixer_id; }
	function get_id_in_module() { return $this->id_in_module; }
	function get_identifier() { return $this->identifier; }
	function get_type() { return $this->type; }
	
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
	
	function get_module_name()
	{
		global $CONFIG;
		$module_ini = load_ini_file(PATH_TO_ROOT . '/' . $this->module . '/lang/', $CONFIG['lang']);
		return $module_ini['name'];
	}
	
	//DB creation or updating
	function save()
	{
		global $Sql, $Cache;
		// If it exists already in the data base
		if( $this->id > 0 )
		{
			//Feinte PHP4
			$creation_date = $this->creation_date;
			$fixing_date = $this->fixing_date;
			
			$Sql->Query_inject("UPDATE ".PREFIX."contributions SET entitled = '" . addslashes($this->entitled) . "', description = '" . addslashes($this->description) . "', fixing_url = '" . addslashes($this->fixing_url) . "', module = '" . addslashes($this->module) . "', current_status = '" . $this->current_status . "', creation_date = '" . $creation_date->get_timestamp() . "', fixing_date = '" . $fixing_date->get_timestamp() . "', auth = '" . addslashes(serialize($this->auth)) . "', poster_id = '" . $this->poster_id . "', fixer_id = '" . $this->fixer_id . "', id_in_module = '" . $this->id_in_module . "', identifier = '" . addslashes($this->identifier) . "', type = '" . addslashes($this->type) . "' WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
		}
		else //We create it
		{
			$this->creation_date = new Date();
			$Sql->Query_inject("INSERT INTO ".PREFIX."contributions (entitled, description, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, type) VALUES ('" . addslashes($this->entitled) . "', '" . addslashes($this->description) . "', '" . addslashes($this->fixing_url) . "', '" . addslashes($this->module) . "', '" . $this->current_status . "', '" . $this->creation_date->get_timestamp() . "', 0, '" . (!empty($this->auth) ? addslashes(serialize($this->auth)) : '') . "', '" . $this->poster_id . "', '" . $this->fixer_id . "', '" . $this->id_in_module . "', '" . addslashes($this->identifier) . "', '" . addslashes($this->type) . "')", __LINE__, __FILE__);
			$this->id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."contributions");	
		}
		
		//Regeneration of the member cache file
		if( $this->must_regenerate_cache )
		{
			$Cache->generate_file('member');
			$this->must_regenerate_cache = false;
		}
	}
	
	//Deleting a contribution in the database
	function delete()
	{
		global $Sql, $Cache;
		$Sql->Query_inject("DELETE FROM ".PREFIX."contributions WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
		//We reset the id
		$this->id = 0;
		
		//Regeneration of the member cache file
		$Cache->generate_file('member');
	}
	
	//Loadind a contribution into the database
	function load_from_db($id_contrib)
	{
		global $Sql;
		
		$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, type, poster_member.login poster_login, fixer_member.login fixer_login, description
		FROM ".PREFIX."contributions c
		LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
		WHERE id = '" . $id_contrib . "'
		ORDER BY creation_date DESC", __LINE__, __FILE__);
		
		$properties = $Sql->sql_fetch_assoc($result);
		
		if( (int)$properties['id'] > 0 )
		{
			$this->build_from_db($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['module'], $properties['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $properties['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $properties['fixing_date']), $properties['auth'], $properties['poster_id'], $properties['fixer_id'], $properties['id_in_module'], $properties['identifier'], $properties['type']);
			return true;
		}
		else
			return false;
	}
	
	//Construction of a contribution from database
	function build_from_db($id, $entitled, $description, $fixing_url, $module, $current_status, $creation_date, $fixing_date, $auth, $poster_id, $fixer_id, $id_in_module, $type, $identifier)
	{
		$this->id = $id;
		$this->entitled = $entitled;
		$this->description = $description;
		$this->fixing_url = $fixing_url;
		$this->module = $module;
		$this->current_status = $current_status;
		$this->creation_date = $creation_date;
		$this->fixing_date = $fixing_date;
		$this->auth = @unserialize($auth);
		if( $this->auth === false )
			$this->auth = array();
		$this->poster_id = $poster_id;
		$this->fixer_id = $fixer_id;
		$this->id_in_module = $id_in_module;
		$this->identifier = $identifier;
		$this->type = $type;
		$this->must_regenerate_cache = false;
	}
	
	## Protected ##
	var $id = 0;
	var $entitled = '';
	var $description = '';
	var $fixing_url = '';
	var $module = '';
	var $current_status = CONTRIBUTION_STATUS_UNREAD;
	var $creation_date;
	var $fixing_date;
	var $auth = array();
	var $poster_id = 0;
	var $fixer_id = 0;
	var $id_in_module = 0;
	var $identifier = '';
	var $type = '';
	var $must_regenerate_cache = true;
}

?>