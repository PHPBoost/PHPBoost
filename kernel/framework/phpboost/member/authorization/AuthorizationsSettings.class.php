<?php
/*##################################################
 *                        AuthorizationsSettings.class.php
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

/**
 * @package {@package}
 * @desc This class manages authorizations settings which deals with all the actions for which you want
 * to restrict access. You can choose who can access to between the different roles existing in PHPBoost:
 * <ul>
 * 	<li>ranks (guest, member, moderator, administrator)</li>
 * 	<li>groups (members can belong to one or more groups)</li>
 * 	<li>members (you can tell that only a particular user can access)</li>
 * </ul>
 * This class contains a list of {@link ActionAuthorization} that correspond to each action with the
 * associated authorizations.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
class AuthorizationsSettings
{
	private $actions;

	/**
	 * @desc Constructs from a list of {@link ActionAuthorization}
	 * @param ActionAuthorization[] $actions Actions
	 */
	public function __construct(array $actions = array())
	{
		$this->actions = $actions;
	}

	/**
	 * @desc Returns the list of the actions
	 * @return ActionAuthorization[]
	 */
	public function get_actions()
	{
		return $this->actions;
	}

	/**
	 * @desc Adds an action
	 * @param ActionAuthorization $action The action to add
	 */
	public function add_action(ActionAuthorization $action)
	{
		$this->actions[] = $action;
	}

	/**
	 * @desc Builds and returns the authorization array which is formatted at the legacy format to
	 * be compliant with legacy code. It's that format that has to be stored and that is used to
	 * check authorizations.
	 * @return mixed[] The correspondant array
	 */
	public function build_auth_array()
	{
		$auth_array = array();
		foreach ($this->actions as $action)
		{
			self::merge_auth_array($auth_array, $action);
		}
		return $auth_array;
	}

	private static function merge_auth_array(array & $global, ActionAuthorization $action)
	{
		foreach ($action->build_auth_array() as $role => $value)
		{
			if (!empty($global[$role]))
			{
				$global[$role] |= $value;
			}
			else
			{
				$global[$role] = $value;
			}
		}
	}
	
	/**
	 * @desc Sets the authorizations from an authorization array formatted at the legacy format.
	 * @param array $auth_array The array to read.
	 */
	public function build_from_auth_array(array $auth_array)
	{
		foreach ($this->actions as $action)
		{
			$action->build_from_auth_array($auth_array);
		}
	}
}
?>