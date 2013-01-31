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
		return array_merge(parent::get_properties(), array('description' => $this->get_description()));
	}
	
	public function set_properties(array $properties)
	{
		parent::set_properties($properties);
		$this->set_description($properties['description']);
	}
}
?>