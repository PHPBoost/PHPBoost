<?php
/*##################################################
 *                         ActionAuthorization.class.php
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
 * @desc This class represents the authorizations for an action. It's associated to a label, 
 * a description, the bit in which flags are saved, and obviously the authorization array which is
 * encapsulated in the RolesAuthorizations class.
 * The bit which is used to store the authorization is 2^n where n is the number of the place you want 
 * to use. It's recommanded to begin with 1 (2^0 = 1) then 2 (2^1 = 2) then 4 (2^2 = 4) etc... 
 * @author Benoit Sautel <ben.popeye@phpboost.com>
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

	/**
	 * @desc Builds an ActionAuthorization from its properties
	 * @param string $label The label
	 * @param int $bit The bit used to store authorizations (2^number)
	 * @param string $description The description to use
	 * @param RolesAuthorizations $roles The authorization roles
	 */
	public function __construct($label, $bit, $description = '', RolesAuthorizations $roles = null)
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
	}

	/**
	 * @desc Returns the label
	 * @return string The label
	 */
	public function get_label()
	{
		return $this->label;
	}

	/**
	 * @desc Sets the label
	 * @param string $label The label to set
	 */
	public function set_label($label)
	{
		$this->label  = $label;
	}

	/**
	 * @desc Returns the bit which is used to store the authorization flags.
	 * @return int The bit (see the {@link #set_bit()} to know how the bit is built
	 */
	public function get_bit()
	{
		return $this->bit;
	}

	/**
	 * @desc Sets the bit corresponding to the autorization flags.
	 * @param int $bit The bit to use. It's an integer whose boolean representation is 0 everywhere but 1 where the flag is.
	 * In fact it's 2^n where n is the number of the bit to use.
	 */
	public function set_bit($bit)
	{
		$this->bit = $bit;
	}

	/**
	 * @desc Returns the action description
	 * @return string the description
	 */
	public function get_description()
	{
		return $this->description;
	}

	/**
	 * @desc Sets the description associated to the action
	 * @param string $description The description
	 */
	public function set_description($description)
	{
		$this->description = $description;
	}

	/**
	 * @desc Returns the roles authorizations associated to this action
	 * @return RolesAuthorizations
	 */
	public function get_roles_auths()
	{
		return $this->roles;
	}

	/**
	 * @desc Sets the roles authorizations
	 * @param RolesAuthorizations $roles The roles
s	 */
	public function set_roles_auths(RolesAuthorizations $roles)
	{
		$this->roles = $roles;
	}

	/**
	 * @desc Builds the array at the legacy format containing only the action's authorizations.
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
	 * @desc Sets authorizations from a array at the legacy format. 
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