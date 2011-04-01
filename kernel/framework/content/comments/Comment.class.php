<?php
/*##################################################
 *                              Comment.class.php
 *                            -------------------
 *   begin                : March 31, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class Comment
{
	private $id = 0;
	private $user_id = 0;
	private $name_visitor = '';
	private $ip_visitor = '';
	private $note = 0;
	private $is_locked = false;
	private $visibility = true;
	private $message = '';
	private $module_name;
	private $module_id;
	
	public function set_id($id)
	{
		$this->id = $id;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_name_visitor($name_visitor)
	{
		$this->name_visitor = $name_visitor;
	}
	
	public function get_name_visitor()
	{
		return $this->name_visitor;
	}
	
	public function set_ip_visitor($ip_visitor)
	{
		$this->ip_visitor = $ip_visitor;
	}
	
	public function get_ip_visitor()
	{
		return $this->ip_visitor;
	}
	
	public function set_note($note)
	{
		$this->note = $note;
	}
	
	public function get_note()
	{
		return $this->note;
	}
	
	public function set_is_locked($is_locked)
	{
		$this->is_locked = $is_locked;
	}
	
	public function get_is_locked()
	{
		return $this->is_locked;
	}
	
	public function set_visibility($visibility)
	{
		$this->visibility = $visibility;
	}
	
	public function get_visibility()
	{
		return $this->visibility;
	}
	
	public function set_message($message)
	{
		$this->message = $message;
	}
	
	public function get_message()
	{
		return $this->message;
	}
	
	public function set_module_name($module)
	{
		$this->module_name = $module;
	}
	
	public function get_module_name()
	{
		return $this->module_name;
	}
	
	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}
	
	public function get_module_id()
	{
		return $this->module_id;
	}
}
?>