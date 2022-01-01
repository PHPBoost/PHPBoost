<?php
/**
 * This class stores different roles which are authorized for a given action.
 * @package     PHPBoost
 * @subpackage  Member\authorization
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 19
 * @since       PHPBoost 3.0 - 2010 03 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class RolesAuthorizations
{
	private $moderators = false;
	private $members = false;
	private $guests = false;
	private $groups = array();
	private $users = array();

	public function __construct(array $auth_array = array())
	{
		$this->build_from_auth_array($auth_array);
	}

	/**
	 * Returns the array authorization formatted at the legacy format in which all authorizations
	 * are on the first bit.
	 * @return mixed[]
	 */
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
		foreach ($this->groups as $group_id)
		{
			$auth_array[$group_id] = 1;
		}
	}

	private function fill_users_auths(array & $auth_array)
	{
		foreach ($this->users as $user_id)
		{
			$auth_array['m' . $user_id] = 1;
		}
	}

	private function init()
	{
		$this->moderators = false;
		$this->members = false;
		$this->guests = false;
		$this->groups = array();
		$this->users = array();
	}

	/**
	 * Sets the authorizations from the legacy style formatted array.
	 * @param array $auth_array The array
	 */
	public function build_from_auth_array(array $auth_array)
	{
		$this->init();
		$this->read_levels_auths($auth_array);
		$this->read_groups_auths($auth_array);
		$this->read_users_auths($auth_array);
	}

	private function read_levels_auths(array $auth_array)
	{
		if (!empty($auth_array['r1']))
		{
			$this->moderators = true;
			if (!empty($auth_array['r0']))
			{
				$this->members = true;
				if (!empty($auth_array['r-1']))
				{
					$this->guests = true;
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
				if (is_numeric($role))
				{
					$this->groups[] = $role;
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
				if (TextHelper::substr($role, 0, 1) == 'm')
				{
					$this->users[] = (int)TextHelper::substr($role, 1);
				}
			}
		}
	}
}
?>
