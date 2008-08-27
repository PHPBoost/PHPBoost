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

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/contribution.class.php');

define('PRIORITY_VERY_LOW', 1);
define('PRIORITY_LOW', 2);
define('PRIORITY_MEDIUM', 3);
define('PRIORITY_HIGH', 4);
define('PRIORITY_VERY_HIGH', 5);


//Fonction d'importation/exportation de base de données.
class AdministratorAlert extends Contribution 
{
	## Public ##
	function AdministratorAlert()
	{
		parent::Contribution();
		$this->priority = PRIORITY_MEDIUM;
	}
	
	//Loadind an alert into the database
	function load_from_db($id_contrib)
	{
		global $Sql;
		
		$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, id_in_module, identifier, priority, creation_date, fixing_date, auth, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, description
		FROM ".PREFIX."contributions c
		LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
		WHERE id = '" . $id_contrib . "'
		ORDER BY creation_date DESC", __LINE__, __FILE__);
		
		$properties = $Sql->sql_fetch_assoc($result);
		
		if( (int)$properties['id'] > 0 )
		{
			$this->build_from_db($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['module'], $properties['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $properties['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $properties['fixing_date']), $properties['auth'], $properties['poster_id'], $properties['fixer_id'], $properties['id_in_module'], $properties['identifier'], $properties['priority']);
			return true;
		}
		else
			return false;
	}
	
	//Construction of a contribution from database
	function build_from_db($id, $entitled, $description, $fixing_url, $module, $current_status, $creation_date, $fixing_date, $auth, $poster_id, $fixer_id, $id_in_module, $identifier, $priority)
	{
		parent::build_from_db($id, $entitled, $description, $fixing_url, $module, $current_status, $creation_date, $fixing_date, $auth, $poster_id, $fixer_id, $id_in_module, $identifier);
		$this->priority = $priority;
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
			
			$Sql->Query_inject("UPDATE ".PREFIX."contributions SET entitled = '" . addslashes($this->entitled) . "', description = '" . addslashes($this->description) . "', fixing_url = '" . addslashes($this->fixing_url) . "', module = '" . addslashes($this->module) . "', current_status = '" . $this->current_status . "', creation_date = '" . $creation_date->get_timestamp() . "', fixing_date = '" . $fixing_date->get_timestamp() . "', auth = '" . addslashes(serialize($this->auth)) . "', poster_id = '" . $this->poster_id . "', fixer_id = '" . $this->fixer_id . "', id_in_module = '" . $this->id_in_module . "', identifier = '" . $this->identifier . "', priority = '" . $this->priority . "' WHERE id = '" . $this->id . "'", __LINE__, __FILE__);
		}
		else //We create it
		{
			$this->creation_date = new Date();
			$Sql->Query_inject("INSERT INTO ".PREFIX."contributions (entitled, description, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, priority) VALUES ('" . addslashes($this->entitled) . "', '" . addslashes($this->description) . "', '" . addslashes($this->fixing_url) . "', '" . addslashes($this->module) . "', '" . $this->current_status . "', '" . $this->creation_date->get_timestamp() . "', 0, '" . (!empty($this->auth) ? addslashes(serialize($this->auth)) : '') . "', '" . $this->poster_id . "', '" . $this->fixer_id . "', '" . $this->id_in_module . "', '" . $this->identifier . "', '" . $this->priority . "')", __LINE__, __FILE__);
			$this->id = $Sql->Sql_insert_id("SELECT MAX(id) FROM ".PREFIX."contributions");	
		}
		
		//Regeneration of the member cache file
		$Cache->generate_file('member');
	}
	
	## Private ##
	var $priority = PRIORITY_MEDIUM; 
}

?>