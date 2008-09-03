<?php
/*##################################################
 *                    administrator_alert_service.class.php
 *                            -------------------
 *   begin                : August 29, 2008
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

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/administrator_alert.class.php');

//This is a static class, it must not be instantiated.

class AdministratorAlertService
{
	//Function which builds an alert knowing its id. If it's not found, it returns null
	/*static*/ function find_by_id($id)
	{
		$alert = new AdministratorAlert();
		if( $alert->load_from_db($id) )
			return $alert;
		else
			return null;
	}
	
	//Function which saves an alert in the database. It creates it whether it doesn't exist or updates it if it already exists.
	/*static*/ function save_alert(&$alert)
	{
		$alert->save();
	}
	
	//Function which deletes an alert
	/*static*/ function delete_alert(&$alert)
	{
		$alert->delete();
	}
	
	//Function which returns all the alerts of the table
	/*static*/ function get_all_alerts($criteria = 'creation_date', $order = 'desc', $begin = 0, $number = 20)
	{
		global $Sql;
		
		$array_result = array();
		
		//On liste les contributions
		$result = $Sql->Query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, identifier, id_in_module, type, priority, description
		FROM ".PREFIX."contributions c
		LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.fixer_id
		WHERE contribution_type = " . ADMINISTRATOR_ALERT_TYPE . "
		ORDER BY " . $criteria . " " . strtoupper($order) . " " . 
		$Sql->Sql_limit($begin, $number), __LINE__, __FILE__);
		while( $row = $Sql->Sql_fetch_assoc($result) )
		{
			$alert = new AdministratorAlert();
			$alert->build_from_db($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['fixing_date']), $row['auth'], $row['poster_id'], $row['fixer_id'], $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
			$array_result[] = $alert;
		}
		
		$Sql->Close($result);
		
		return $array_result;
	}
	
	//Counts the number of unread contributions
	/*static*/ function compute_number_unread_alerts()
	{
		global $Sql;
		
		return array('unread' => $Sql->Query("SELECT count(*) FROM ".PREFIX."contributions WHERE current_status = '" . CONTRIBUTION_STATUS_UNREAD . "' AND contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__),
			'all' => $Sql->Query("SELECT count(*) FROM ".PREFIX."contributions WHERE contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__)
			);
	}
	
	//Returns the number of unread alerts
	/*static*/ function get_number_unread_alerts()
	{
		global $ADMINISTRATOR_ALERTS;
		return $ADMINISTRATOR_ALERTS['unread'];
	}
	
	//Returns the number of alerts
	/*static*/ function get_number_alerts()
	{
		global $ADMINISTRATOR_ALERTS;
		return $ADMINISTRATOR_ALERTS['all'];
	}
}

?>