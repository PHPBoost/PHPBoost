<?php
/*##################################################
 *                          Category.class.php
 *                            -------------------
 *   begin                : May 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : benoit.sautel@phpboost.com
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
	private $id;
	private $name;
	private $order;
	private $visible;
	private $auth;
	private $tree_id;
	private $parent;
	
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
	
	public function get_tree_id()
	{
		return $this->tree_id;
	}
	
	public function set_tree_id($tree_id)
	{
		$this->tree_id = $tree_id;
	}
	
	/**
	 * @return Category
	 */
	public function get_parent()
	{
		return $this->parent;
	}
	
	public function set_parent(Category $parent)
	{
		$this->parent = $parent;
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'name' => $this->get_name(),
			'order' => $this->get_order(),
			'visible' => $this->get_visible(),
			'auth' => serialize($this->get_auth()),
			'tree_id' => $this->get_tree_id()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_order($properties['order']);
		$this->set_visible($properties['visible']);
		$this->set_auth(!empty($properties['auth']) ? unserialize($properties['auth']) : array());
		$this->set_tree_id($properties['tree_id']);
	}
}

?>