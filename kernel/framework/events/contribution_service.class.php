<?php
/*##################################################
 *                         contribution_panel.class.php
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

import('events/contribution');

//Flag which distinguishes a contribution from an alert
define('CONTRIBUTION_TYPE', 0);

/**
 * @package events
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This service allows developers to manage their contributions.
 */
class ContributionService
{
	/**
     * @desc Finds a contribution with its identifier.
     * @param int $id_contrib Id of the contribution.
     * @return Contribution The contribution you wanted. If it doesn't exist, it will return null.
	 */
    public static function find_by_id($id_contrib)
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, type, poster_member.login poster_login, fixer_member.login fixer_login, description
		FROM " . DB_TABLE_EVENTS  . " c
		LEFT JOIN " . DB_TABLE_MEMBER . " poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN " . DB_TABLE_MEMBER . " fixer_member ON fixer_member.user_id = c.poster_id
		WHERE id = '" . $id_contrib . "' AND contribution_type = '" . CONTRIBUTION_TYPE . "'
		ORDER BY creation_date DESC", __LINE__, __FILE__);
		
		$properties = $Sql->fetch_assoc($result);
		
		if ((int)$properties['id'] > 0)
		{
			$contribution = new Contribution();
			$contribution->build($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['module'], $properties['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['fixing_date']), unserialize($properties['auth']), $properties['poster_id'], $properties['fixer_id'], $properties['id_in_module'], $properties['identifier'], $properties['type'], $properties['poster_login'], $properties['fixer_login']);
			return $contribution;
		}
		else
			return null;
	}
	
	/**
	 * @desc Gets all the contributions of the table. You can sort the list.
	 * @param string $criteria Criteria according to which they are ordered. 
	 * It can be id, entitled, fixing_url, auth, current_status, module, creation_date, fixing_date, poster_id, fixer_id, 
	 * poster_member.login poster_login, fixer_member.login fixer_login, identifier, id_in_module, type, description.
	 * @param string $order desc or asc.
	 * @return Contribution[] The list of the contributions.
	 */
	public static function get_all_contributions($criteria = 'creation_date', $order = 'desc')
	{
		global $Sql;
		
		$array_result = array();
		
		//On liste les contributions
		$result = $Sql->query_while("SELECT id, entitled, fixing_url, auth, current_status, module, creation_date, fixing_date, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, identifier, id_in_module, type, description
		FROM " . DB_TABLE_EVENTS  . " c
		LEFT JOIN " . DB_TABLE_MEMBER . " poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN " . DB_TABLE_MEMBER . " fixer_member ON fixer_member.user_id = c.fixer_id
		WHERE contribution_type = " . CONTRIBUTION_TYPE . "
		ORDER BY " . $criteria . " " . strtoupper($order), __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$contri = new Contribution();
			
			$contri->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['fixing_date']), unserialize($row['auth']), $row['poster_id'], $row['fixer_id'], $row['id_in_module'], $row['identifier'], $row['type'], $row['poster_login'], $row['fixer_login']);
			$array_result[] = $contri;
		}
		
		$Sql->query_close($result);
		
		return $array_result;
	}
	
	/**
	 * @desc Builds a list of the contributions matching the required criteria(s). All the parameters represent the criterias you can use.
	 * If you don't want to use a criteria, let the null value. The returned contribution match all the criterias (it's a AND condition).
	 * @param string $module The module identifier.
	 * @param int $id_in_module The id in module field.
	 * @param string $type The contribution type.
	 * @param string $identifier The contribution identifier.
	 * @param int $poster_id The poster.
	 * @param int $fixer_id The fixer.
	 * @return Contribution[] The list of the contributions matching all the criterias.
	 */
	public static function find_by_criteria($module, $id_in_module = null, $type = null, $identifier = null, $poster_id = null, $fixer_id = null)
	{
		global $Sql;
		$criterias = array();
		
		//The module parameter must be specified and of string type, otherwise we can't continue
		if (empty($module) || !is_string($module))
			return array();
		
		$criterias[] = "module = '" . strprotect($module) . "'";
		
		if ($id_in_module != null)
			$criterias[] = "id_in_module = '" . intval($id_in_module) . "'";
		
		if ($type != null)
			$criterias[] = "type = '" . strprotect($type) . "'";
			
		if ($identifier != null)
			$criterias[] = "identifier = '" . strprotect($identifier). "'";
			
		if ($poster_id != null)
			$criterias[] = "poster_id = '" . intval($poster_id) . "'";
			
		if ($fixer_id != null)
			$criterias[] = "fixer_id = '" . intval($fixer_id) . "'";
		
		$array_result = array();
		$where_clause = "contribution_type = '" . CONTRIBUTION_TYPE . "' AND " . implode($criterias, " AND ");
		
		$result = $Sql->query_while("SELECT id, entitled, fixing_url, auth, current_status, module, creation_date, fixing_date, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, identifier, id_in_module, type, description
		FROM " . DB_TABLE_EVENTS  . " c
		LEFT JOIN " . DB_TABLE_MEMBER . " poster_member ON poster_member.user_id = c.poster_id
		LEFT JOIN " . DB_TABLE_MEMBER . " fixer_member ON fixer_member.user_id = c.fixer_id
		WHERE " . $where_clause, __LINE__, __FILE__);
		
		while ($row = $Sql->fetch_assoc($result))
		{
			$contri = new Contribution();
			$contri->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['fixing_date']), unserialize($row['auth']), $row['poster_id'], $row['fixer_id'], $row['id_in_module'], $row['identifier'], $row['type'], $row['poster_login'], $row['fixer_login']);
			$array_result[] = $contri;
		}
		
		return $array_result;
	}
	
	/**
     * @desc Create or update a contribution in the database.
     * @param Contribution $contribution The contribution to synchronize with the data base.
	 */
	public static function save_contribution(&$contribution)
	{
		global $Sql, $Cache;
		
		// If it exists already in the data base
		if ($contribution->get_id() > 0)
		{
			//We write it for PHP 4 which doesn't understand (object->get_object()->method())
			$creation_date = $contribution->get_creation_date();
			$fixing_date = $contribution->get_fixing_date();
			
			$Sql->query_inject("UPDATE " . DB_TABLE_EVENTS  . " SET entitled = '" . addslashes($contribution->get_entitled()) . "', description = '" . addslashes($contribution->get_description()) . "', fixing_url = '" . addslashes($contribution->get_fixing_url()) . "', module = '" . addslashes($contribution->get_module()) . "', current_status = '" . $contribution->get_status() . "', creation_date = '" . $creation_date->get_timestamp() . "', fixing_date = '" . $fixing_date->get_timestamp() . "', auth = '" . addslashes(serialize($contribution->get_auth())) . "', poster_id = '" . $contribution->get_poster_id() . "', fixer_id = '" . $contribution->get_fixer_id() . "', id_in_module = '" . $contribution->get_id_in_module() . "', identifier = '" . addslashes($contribution->get_identifier()) . "', type = '" . addslashes($contribution->get_type()) . "' WHERE id = '" . $contribution->get_id() . "'", __LINE__, __FILE__);
		}
		else //We create it
		{
			$contribution_auth = $contribution->get_auth();
			$creation_date = $contribution->get_creation_date();
			$Sql->query_inject("INSERT INTO " . DB_TABLE_EVENTS  . " (entitled, description, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, type, contribution_type, nbr_com, lock_com) VALUES ('" . addslashes($contribution->get_entitled()) . "', '" . addslashes($contribution->get_description()) . "', '" . addslashes($contribution->get_fixing_url()) . "', '" . addslashes($contribution->get_module()) . "', '" . $contribution->get_status() . "', '" . $creation_date->get_timestamp() . "', 0, '" . (!empty($contribution_auth) ? addslashes(serialize($contribution_auth)) : '') . "', '" . $contribution->get_poster_id() . "', '" . $contribution->get_fixer_id() . "', '" . $contribution->get_id_in_module() . "', '" . addslashes($contribution->get_identifier()) . "', '" . addslashes($contribution->get_type()) . "', '" . CONTRIBUTION_TYPE . "', '0', '0')", __LINE__, __FILE__);
			$contribution->set_id($Sql->insert_id("SELECT MAX(id) FROM " . DB_TABLE_EVENTS));	
		}
		
		//Regeneration of the member cache file
		if ($contribution->get_must_regenerate_cache())
		{
			$Cache->generate_file('member');
			$contribution->set_must_regenerate_cache(false);
		}
	}
	
	/**
     * @desc Deletes a contribution in the database.
     * @param Contribution $contribution The contribution to delete in the data base.
	 */
	public static function delete_contribution(&$contribution)
	{
		global $Sql, $Cache;
		
		//If it exists in database
		if ($contribution->get_id() > 0)
		{
			$Sql->query_inject("DELETE FROM " . DB_TABLE_EVENTS  . " WHERE id = '" . $contribution->get_id() . "'", __LINE__, __FILE__);
			//We reset the id
			$contribution->set_id(0);
			
			//Regeneration of the member cache file
			$Cache->generate_file('member');
		}
	}
	
	/**
	 * @desc Generates the contribution cache file.
	 */
	public static function generate_cache()
	{
		global $Cache;
		$Cache->generate_file('member');
	}
	
	/**
	 * @desc Computes the number of contributions available for each profile.
	 * It will count the contributions for the administrator, the moderators, the members, for each group and for each member who can have some special authorizations.
	 * @return int[] A map containing the values for each profile:
	 * <ul>
	 * 	<li>r2 => for the administrator</li>
	 * 	<li>r1 => for the moderators</li>
	 * 	<li>r0 => for the members</li>
	 * 	<li>gi => for the group whose id is i</li>
	 * 	<li>mi => for the member whose id is i</li>
	 * </ul>
	 */
	public static function compute_number_contrib_for_each_profile()
	{
		global $Sql;
		
		$array_result = array('r2' => 0, 'r1' => 0, 'r0' => 0);
		
		$result = $Sql->query_while ("SELECT auth FROM " . DB_TABLE_EVENTS  . " WHERE current_status = '" . EVENT_STATUS_UNREAD . "' AND contribution_type = '" . CONTRIBUTION_TYPE . "'", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (!($this_auth = @unserialize($row['auth'])))
				$this_auth = array();
			
			//We can count only for ranks. For groups and users we can't generalize because there can be intersection problems. Yet, we know the maximum number of contributions they can see, and we can be sure if they have at least 1.
			
			//Administrators can see everything
			$array_result['r2']++;
			
			//For moderators ?
			if (Authorizations::check_auth(RANK_TYPE, MODERATOR_LEVEL, $this_auth, CONTRIBUTION_AUTH_BIT))
				$array_result['r1']++;
			
			//For members ?
			if (Authorizations::check_auth(RANK_TYPE, MEMBER_LEVEL, $this_auth, CONTRIBUTION_AUTH_BIT))
				$array_result['r0']++;
				
			foreach ($this_auth as $profile => $auth_profile)
			{
				//Groups
				if (is_numeric($profile))
				{
					//If this member has not already an entry and he can see that contribution
					if (empty($array_result[$profile]) && Authorizations::check_auth(GROUP_TYPE, (int)$profile, $this_auth, CONTRIBUTION_AUTH_BIT))
						$array_result['g' . $profile] = 1;
				}
				//Members
				elseif (substr($profile, 0, 1) == 'm')
				{
					//If this member has not already an entry and he can see that contribution
					if (empty($array_result[$profile]) && Authorizations::check_auth(USER_TYPE, (int)substr($profile, 1), $this_auth, CONTRIBUTION_AUTH_BIT))
						$array_result[$profile] = 1;
				}
			}
		}
		
		return $array_result;
	}
}

?>