<?php
/**
 * This class manages authorizations settings which deals with all the actions for which you want
 * to restrict access. You can choose who can access to between the different roles existing in PHPBoost:
 * <ul>
 * 	<li>ranks (guest, member, moderator, administrator)</li>
 * 	<li>groups (members can belong to one or more groups)</li>
 * 	<li>members (you can tell that only a particular user can access)</li>
 * </ul>
 * This class contains a list of {@link ActionAuthorization} that correspond to each action with the
 * associated authorizations.
 * @package     PHPBoost
 * @subpackage  Member\authorization
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 03 01
*/

class AuthorizationsSettings
{
	private $actions;

	/**
	 * Constructs from a list of {@link ActionAuthorization}
	 * @param ActionAuthorization[] $actions Actions
	 */
	public function __construct(array $actions = array())
	{
		$this->actions = $actions;
	}

	/**
	 * Returns the list of the actions
	 * @return ActionAuthorization[]
	 */
	public function get_actions()
	{
		return $this->actions;
	}

	/**
	 * Adds an action
	 * @param ActionAuthorization $action The action to add
	 */
	public function add_action(ActionAuthorization $action)
	{
		$this->actions[] = $action;
	}

	/**
	 * Builds and returns the authorization array which is formatted at the legacy format to
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
	 * Sets the authorizations from an authorization array formatted at the legacy format.
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
