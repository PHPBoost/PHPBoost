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

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/contribution.class.php');

//This is a static class, it must not be instantiated.

class ContributionService
{
	/*static*/ function find_by_id($id)
	{
		$contri = new AdministratorAlert();
		if( $contri->load_from_db($id) )
			return $contri;
		else
			return null;
	}
    
    /*static*/ function find_by_identifier($identifier, $type = '', $module = '')
	{
        global $Sql;
		
        $result = $Sql->Query_while(
            "SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, type, poster_member.login poster_login, fixer_member.login fixer_login, description
    		FROM ".PREFIX."contributions c
    		LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
    		LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
    		WHERE identifier = '" . addslashes($identifier) . "'" . (!empty($type) ? " AND type = '" . addslashes($type) . "'" : '') . (!empty($module) ? " AND module = '" . addslashes($module) . "'" : '') . " ORDER BY creation_date DESC " . $Sql->Sql_limit(0, 1) . ";"
            , __LINE__, __FILE__);
		if( $row = $Sql->Sql_fetch_assoc($result) )
		{
            $contri = new Contribution();
			$contri->build_from_db($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_USER, $row['fixing_date']), $row['auth'], $row['poster_id'], $row['fixer_id'], $row['id_in_module'], $row['identifier'], $row['type']);
			return $contri;
        }
        $Sql->Close($result);
        return null;
	}
	
	/*static*/ function save_contribution(&$contribution)
	{
		$contribution->save();
	}
	
	/*static*/ function delete_contribution(&$contribution)
	{
		$contribution->delete();
	}
	
	/*static*/ function generate_cache()
	{
		global $Cache;
		$Cache->generate_file('member');
	}
	
	/*static*/ function compute_number_contrib_for_each_profile()
	{
		global $Sql;
		
		$array_result = array('r2' => 0, 'r1' => 0, 'r0' => 0);
		
		$result = $Sql->Query_while("SELECT auth FROM ".PREFIX."contributions WHERE current_status = '" . CONTRIBUTION_STATUS_UNREAD . "' AND contribution_type = '" . CONTRIBUTION_TYPE . "'", __LINE__, __FILE__);
		while($row = $Sql->sql_fetch_assoc($result) )
		{
			if( !($this_auth = @unserialize($row['auth'])) )
				$this_auth = array();
			
			//We can count only for ranks. For groups and users we can't generalize because there can be intersection problems. Yet, we know the maximum number of contributions they can see, and we can be sure if they have at least 1.
			
			//Administrators can see everything
			$array_result['r2']++;
			
			//For moderators ?
			if( Authorizations::check_some_body_auth(RANK_TYPE, MODERATOR_LEVEL, $this_auth, CONTRIBUTION_AUTH_BIT) )
				$array_result['r1']++;
			
			//For members ?
			if( Authorizations::check_some_body_auth(RANK_TYPE, MEMBER_LEVEL, $this_auth, CONTRIBUTION_AUTH_BIT) )
				$array_result['r0']++;
				
			foreach($this_auth as $profile => $auth_profile)
			{
				//Groups
				if( is_numeric($profile) )
				{
					//If this member has not already an entry and he can see that contribution
					if( empty($array_result[$profile]) && Authorizations::check_some_body_auth(GROUP_TYPE, (int)$profile, $this_auth, CONTRIBUTION_AUTH_BIT) )
						$array_result['g' . $profile] = 1;
				}
				//Members
				elseif( substr($profile, 0, 1) == 'm' )
				{
					//If this member has not already an entry and he can see that contribution
					if( empty($array_result[$profile]) && Authorizations::check_some_body_auth(USER_TYPE, (int)substr($profile, 1), $this_auth, CONTRIBUTION_AUTH_BIT) )
						$array_result[$profile] = 1;
				}
			}
		}
		
		return $array_result;
	}
}

?>