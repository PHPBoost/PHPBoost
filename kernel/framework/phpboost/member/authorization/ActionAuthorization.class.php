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

class ActionAuthorization
{
	private $label;
	private $description = '';
	private $bit;
	/**
	 * @var RolesAuthorizations
	 */
	private $profiles = null;

	public function __construct($label, $bit, $description = '')
	{
		$this->label = $label;
		$this->bit = $bit;
		$this->description = $description;
	}

	public function get_label()
	{
		return $this->label;
	}

	public function set_label($label)
	{
		$this->label  = $label;
	}

	public function get_bit()
	{
		return $this->bit;
	}

	public function set_bit($bit)
	{
		$this->bit = $bit;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	/**
	 * @return RolesAuthorizations
	 */
	public function get_profiles()
	{
		return $this->profiles;
	}

	public function set_profiles(RolesAuthorizations $profiles)
	{
		$this->profiles = $profiles;
	}

	public function get_auth_array()
	{
 		$auth_array = $this->profiles->build_auth_array();
		foreach ($auth_array as &$profile)
		{
			$profile *= $this->bit;
		}
		return $auth_array;
	}

	public function set_auth_array(array $auth_array)
	{
		foreach ($auth_array as &$profile)
		{
			$profile &= $this->bit;
		}
		$this->profiles = new RolesAuthorizations($auth_array);
	}
}
?>