<?php
/*##################################################
 *                          RichCategory.class.php
 *                            -------------------
 *   begin                : January 29, 2013
 *   copyright            : (C) 2013 Kévin MASSY
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

class RichCategory extends Category
{
	protected $description;
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'name' => $this->get_name(),
			'description' => $this->description(),
			'order' => $this->get_order(),
			'visible' => $this->get_visible(),
			'auth' => serialize($this->get_auth()),
			'id_parent' => $this->get_id_parent()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_description($properties['description']);
		$this->set_order($properties['order']);
		$this->set_visible($properties['visible']);
		$this->set_auth(!empty($properties['auth']) ? unserialize($properties['auth']) : array());
		$this->set_id_parent($properties['id_parent']);
	}
}
?>