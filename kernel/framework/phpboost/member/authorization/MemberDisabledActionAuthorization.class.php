<?php
/*##################################################
 *                         MemberDisabledActionAuthorization.class.php
 *                            -------------------
 *   begin                : November 4, 2018
 *   copyright            : (C) 2018 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * In this class the select of Visitor and Member level is not possible.
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class MemberDisabledActionAuthorization extends ActionAuthorization
{
	/**
	 * @desc Builds an ActionAuthorization from its properties
	 * @param string $label The label
	 * @param int $bit The bit used to store authorizations (2^number)
	 * @param string $description The description to use
	 * @param RolesAuthorizations $roles The authorization roles
	 */
	public function __construct($label, $bit, $description = '', RolesAuthorizations $roles = null)
	{
		parent::__construct($label, $bit, $description, $roles, array(User::VISITOR_LEVEL, User::MEMBER_LEVEL));
	}
}
?>