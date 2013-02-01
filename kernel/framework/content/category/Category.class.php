<?php
/*##################################################
 *                          Category.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 K�vin MASSY
 *   email                : kevin.massy@phpboost.com
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

class Category
{
	protected $id;
	protected $name;
	protected $order;
	protected $visible;
	protected $auth;
	protected $id_parent;
	
	const ROOT_CATEGORY = '0';
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_order()
	{
		return $this->order;
	}
	
	public function set_order($order)
	{
		$this->order = $order;
	}
	
	public function incremente_order()
	{
		$this->order++;
	}
	
	public function is_visible()
	{
		return $this->visible;
	}
	
	public function set_visible($visible)
	{
		$this->visible = (boolean)$visible;
	}
	
	public function get_auth()
	{
		return $this->auth;
	}
	
	public function set_auth(array $auth)
	{
		$this->auth = $auth;
	}
	
	public function auth_is_empty()
	{
		return empty($this->auth);
	}
	
	public function get_id_parent()
	{
		return $this->id_parent;
	}
	
	public function set_id_parent($id_parent)
	{
		$this->id_parent = $id_parent;
	}
	
	public function check_auth($bit)
    {
    	return AppContext::get_current_user()->check_auth($this->auth, $bit);
    }

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'name' => $this->get_name(),
			'c_order' => $this->get_order(),
			'visible' => (int)$this->is_visible(),
			'auth' => !$this->auth_is_empty() ? serialize($this->get_auth()) : '',
			'id_parent' => $this->get_id_parent()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_order($properties['c_order']);
		$this->set_visible($properties['visible']);
		$this->set_auth(!empty($properties['auth']) ? unserialize($properties['auth']) : array());
		$this->set_id_parent($properties['id_parent']);
	}
}
?>