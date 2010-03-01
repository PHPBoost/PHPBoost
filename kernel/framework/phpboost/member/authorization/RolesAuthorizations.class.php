<?php
/*##################################################
 *                      RolesAuthorizations.class.php
 *                            -------------------
 *   begin                : March, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class RolesAuthorizations
{
	private $moderators = 0;
	private $members = 0;
	private $guests = 0;
	private $groups = array();
	private $users = array();

	public function __construct(array $auth_array = array())
	{
		$this->build_from_auth_array($auth_array);
	}

	public function build_auth_array()
	{
		$auth_array = array();
		$this->fill_levels_auths($auth_array);
		$this->fill_groups_auths($auth_array);
		$this->fill_users_auths($auth_array);
		return $auth_array;
	}

	private function fill_levels_auths(array & $auth_array)
	{
		if ($this->moderators)
		{
			$auth_array['r1'] = 1;
			if ($this->members)
			{
				$auth_array['r0'] = 1;
				if ($this->guests)
				{
					$auth_array['r-1'] = 1;
				}
			}
		}
	}

	private function fill_groups_auths(array & $auth_array)
	{
		foreach ($this->groups as $group_id => $auth)
		{
			if ($auth)
			{
				$auth_array['g' . $group_id] = 1;
			}
		}
	}

	private function fill_users_auths(array & $auth_array)
	{
		foreach ($this->users as $user_id => $auth)
		{
			if ($auth)
			{
				$auth_array['m' . $user_id] = 1;
			}
		}
	}

	public function build_from_auth_array(array $auth_array)
	{
		$this->read_levels_auths($auth_array);
		$this->read_groups_auths($auth_array);
		$this->read_users_auths($auth_array);
	}

	private function read_levels_auths(array $auth_array)
	{
		if (!empty($auth_array['r1']))
		{
			$this->moderators = 1;
			if (!empty($auth_array['r0']))
			{
				$this->members = 1;
				if (!empty($auth_array['r-1']))
				{
					$this->guests = 1;
				}
			}
		}
	}

	private function read_groups_auths(array $auth_array)
	{
		foreach ($auth_array as $role => $auth)
		{
			if ($auth)
			{
				if ($role[0] == 'g')
				{
					$this->groups[substr($role, 1)] = 1;
				}
				else if (is_numeric($role))
				{
					$this->groups[$role] = 1;
				}
			}
		}
	}

	private function read_users_auths(array $auth_array)
	{
		foreach ($auth_array as $role => $auth)
		{
			if ($auth)
			{
				if ($role[0] == 'm')
				{
					$this->users[substr($role, 1)] = 1;
				}
			}
		}
	}
}
?>