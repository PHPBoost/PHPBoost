<?php
/**
 * This class represents the authorizations for an action. It's associated to a label,
 * a description, the bit in which flags are saved, and obviously the authorization array which is
 * encapsulated in the RolesAuthorizations class.
 * The bit which is used to store the authorization is 2^n where n is the number of the place you want
 * to use. It's recommanded to begin with 1 (2^0 = 1) then 2 (2^1 = 2) then 4 (2^2 = 4) etc...
 * In this class the select of Visitor level is not possible.
 * @package     PHPBoost
 * @subpackage  Member\authorization
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 05
 * @since       PHPBoost 5.1 - 2018 11 04
*/

class VisitorDisabledActionAuthorization extends ActionAuthorization
{
	/**
	 * Builds an ActionAuthorization from its properties
	 * @param string $label The label
	 * @param int $bit The bit used to store authorizations (2^number)
	 * @param string $description The description to use
	 * @param RolesAuthorizations $roles The authorization roles
	 */
	public function __construct($label, $bit, $description = '', RolesAuthorizations $roles = null)
	{
		parent::__construct($label, $bit, $description, $roles, array(User::VISITOR_LEVEL));
	}
}
?>
