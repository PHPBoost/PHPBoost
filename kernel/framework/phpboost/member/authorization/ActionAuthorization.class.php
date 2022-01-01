<?php
/**
 * This class represents the authorizations for an action. It's associated to a label,
 * a description, the bit in which flags are saved, and obviously the authorization array which is
 * encapsulated in the RolesAuthorizations class.
 * The bit which is used to store the authorization is 2^n where n is the number of the place you want
 * to use. It's recommanded to begin with 1 (2^0 = 1) then 2 (2^1 = 2) then 4 (2^2 = 4) etc...
 * @package     PHPBoost
 * @subpackage  Member\authorization
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 05
 * @since       PHPBoost 3.0 - 2010 03 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class ActionAuthorization
{
	private $label;
	private $description = '';
	private $bit;
	/**
	 * @var RolesAuthorizations
	 */
	private $roles;

	private $disabled_ranks = array();

	/**
	 * Builds an ActionAuthorization from its properties
	 * @param string $label The label
	 * @param int $bit The bit used to store authorizations (2^number)
	 * @param string $description The description to use
	 * @param RolesAuthorizations $roles The authorization roles
	 * @param mixed[] $disabled_ranks The ranks to disable in select
	 */
	public function __construct($label, $bit, $description = '', RolesAuthorizations $roles = null, $disabled_ranks = array())
	{
		$this->label = $label;
		$this->bit = $bit;
		$this->description = $description;
		if ($roles != null)
		{
			$this->roles = $roles;
		}
		else
		{
			$this->roles = new RolesAuthorizations();
		}
		$this->disabled_ranks = $disabled_ranks;
	}

	/**
	 * Returns the label
	 * @return string The label
	 */
	public function get_label()
	{
		return $this->label;
	}

	/**
	 * Sets the label
	 * @param string $label The label to set
	 */
	public function set_label($label)
	{
		$this->label  = $label;
	}

	/**
	 * Returns the bit which is used to store the authorization flags.
	 * @return int The bit (see the {@link #set_bit()} to know how the bit is built
	 */
	public function get_bit()
	{
		return $this->bit;
	}

	/**
	 * Sets the bit corresponding to the autorization flags.
	 * @param int $bit The bit to use. It's an integer whose boolean representation is 0 everywhere but 1 where the flag is.
	 * In fact it's 2^n where n is the number of the bit to use.
	 */
	public function set_bit($bit)
	{
		$this->bit = $bit;
	}

	/**
	 * Returns the action description
	 * @return string the description
	 */
	public function get_description()
	{
		return $this->description;
	}

	/**
	 * Sets the description associated to the action
	 * @param string $description The description
	 */
	public function set_description($description)
	{
		$this->description = $description;
	}

	/**
	 * Returns the roles authorizations associated to this action
	 * @return RolesAuthorizations
	 */
	public function get_roles_auths()
	{
		return $this->roles;
	}

	/**
	 * Sets the roles authorizations
	 * @param RolesAuthorizations $roles The roles
	 */
	public function set_roles_auths(RolesAuthorizations $roles)
	{
		$this->roles = $roles;
	}

	/**
	 * Returns the disabled ranks in select associated to this action
	 * @return mixed[] The disabled ranks
	 */
	public function get_disabled_ranks()
	{
		return $this->disabled_ranks;
	}

	/**
	 * Sets the disabled ranks in select
	 * @param mixed[] $disabled_ranks The ranks to disable
	 */
	public function set_disabled_ranks(Array $disabled_ranks)
	{
		$this->disabled_ranks = $disabled_ranks;
	}

	/**
	 * Builds the array at the legacy format containing only the action's authorizations.
	 * @return mixed[] The array at the legacy format.
	 */
	public function build_auth_array()
	{
		$auth_array = $this->roles->build_auth_array();
		foreach ($auth_array as &$profile)
		{
			$profile *= $this->bit;
		}
		return $auth_array;
	}

	/**
	 * Sets authorizations from a array at the legacy format.
	 * @param array $auth_array The array to read
	 */
	public function build_from_auth_array(array $auth_array)
	{
		foreach ($auth_array as &$profile)
		{
			$profile &= $this->bit;
		}
		$this->roles = new RolesAuthorizations($auth_array);
	}
}
?>
