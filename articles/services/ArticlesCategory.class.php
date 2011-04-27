<?php
/*##################################################
 *                        ArticlesCategory.class.php
 *                            -------------------
 *   begin                : April 27, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class ArticlesCategory
{
	private $id;
	private $id_parent;
	private $order;
	private $name;
	private $picture;
	private $description;
	private $authorizations;
	private $notation_system_is_disabled;
	private $comments_system_is_disabled;
	private $visibility;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_id_parent($id_parent)
	{
		$this->id_parent = $id_parent;
	}
	
	public function get_id_parent()
	{
		return $this->id_parent;
	}
	
	public function set_order($order)
	{
		$this->order = $order;
	}
	
	public function get_order()
	{
		return $this->order;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_picture($picture)
	{
		$this->picture = $picture;
	}
	
	public function get_picture()
	{
		return $this->picture;
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_authorizations($authorizations)
	{
		$this->authorizations = $authorizations;
	}
	
	public function get_authorizations()
	{
		return $this->authorizations;
	}
	
	public function set_disable_comments_system($comments_system_is_disabled)
	{
		$this->comments_is_disabled = $comments_system_is_disabled;
	}
	
	public function comments_system_is_disabled()
	{
		return $this->comments_system_is_disabled;
	}
	
	public function set_disable_notation_system($notation_system_is_disabled)
	{
		$this->notation_system_is_disabled = $notation_system_is_disabled;
	}
	
	public function notation_system_is_disabled()
	{
		return $this->notation_system_is_disabled;
	}
	
	public function set_visibility($visibility)
	{
		$this->visibility = $visibility;
	}
	
	public function get_visibility()
	{
		return $this->visibility;
	}
	
}
?>