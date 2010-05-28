<?php
/*##################################################
 *                            AdminUser.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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
 * @author Régis VIARRE <crowkait@phpboost.com>
 * @desc This class manage user, it provide you methods to get or modify user informations, moreover methods allow you to control user authorizations
 * @package members
 */
class AdminUser extends User
{
	private $is_admin = false;
	private $user_data; //Données du membres, obtenues à partir de la class de session.
	private $groups_auth; //Tableau contenant le nom des groupes disponibles.
	private $user_groups; //Groupes du membre.

	/**
	 * @desc Sets global authorizations which are given by all the user groups authorizations.
	 */
	public function __construct()
	{
//		parent::__construct();
		$this->is_admin = true;
	}

	public function is_admin()
	{
		return true;
	}

	/**
	 * @desc Accessor
	 * @param string $attribute The attribute name.
	 * @return unknown_type
	 */
	public function get_attribute($attribute)
	{
		return isset($this->user_data[$attribute]) ? $this->user_data[$attribute] : '';
	}

	/**
	 * @desc Get the user id
	 * @return int The user id.
	 */
	public function get_id()
	{
		return 1;
	}

	/**
	 * @desc Check the authorization level
	 * @param int $secure Constant of level authorization to check (MEMBER_LEVEL, MODO_LEVEL, ADMIN_LEVEL).
	 * @return boolean True if authorized, false otherwise.
	 */
	public function check_level($secure)
	{
		return true;
	}

	/**
	 * @desc Get the authorizations given by all the user groups. Then check the authorization.
	 * @param array $array_auth_groups The array passed to check the authorization.
	 * @param int $authorization_bit Value of position bit to check the authorization.
	 * This value has to be a multiple of two. You can use this simplified scripture :
	 * 0x01, 0x02, 0x04, 0x08 to set a new position bit to check.
	 * @return boolean True if authorized, false otherwise.
	 */
	public function check_auth($array_auth_groups, $authorization_bit)
	{
		return true;
	}
}

?>
