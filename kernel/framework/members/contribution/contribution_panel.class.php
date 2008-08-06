<?php
/*##################################################
 *                             contribution_panel.class.php
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

require_once(PATH_TO_ROOT . '/kernel/framework/members/contribution/contribution.class.php');

define('CONTRIBUTION_STATUS_UNREAD', 0);
define('CONTRIBUTION_STATUS_BEING_PROCESSED', 1);
define('CONTRIBUTION_STATUS_PROCESSED', 2);
define('CONTRIBUTION_AUTH_BIT', 1);

//This is a static class, it must not be instantiated.

class ContributionPanel
{
	/*static*/ function add_contribution(&$contribution)
	{
		$contribution->create_in_db();
		return $contribution->get_id();
	}
	
	/*static*/ function update_contribution(&$contribution)
	{
		$contribution->update_in_db();
	}
	
	/*static*/ function delete_contribution(&$contribution)
	{
		$contribution->delete_in_db();
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
		
		$result = $Sql->Query_while("SELECT auth FROM ".PREFIX."contributions WHERE current_status = '" . CONTRIBUTION_STATUS_UNREAD . "'", __LINE__, __FILE__);
		while($row = $Sql->sql_fetch_assoc($result) )
		{
			$this_auth = sunserialize($row['auth']);
			
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