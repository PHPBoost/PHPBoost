<?php
/*##################################################
 *                        CalendarEventParticipant.class.php
 *                            -------------------
 *   begin                : November 27, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class CalendarEventParticipant
{
	private $user_id;
	private $display_name;
	private $level;
	private $user_groups;
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_display_name($display_name)
	{
		$this->display_name = $display_name;
	}
	
	public function get_display_name()
	{
		return $this->display_name;
	}
	
	public function set_level($level)
	{
		$this->level = $level;
	}
	
	public function get_level()
	{
		return $this->level;
	}
	
	public function set_user_groups(Array $user_groups)
	{
		$this->user_groups = $user_groups;
	}
	
	public function get_user_groups()
	{
		return $this->user_groups;
	}
	
	public function get_properties()
	{
		return array(
			'user_id' => $this->get_user_id(),
			'display_name' => $this->get_login(),
			'level' => $this->get_level(),
			'user_groups' => $this->get_user_groups()
		);
	}
	
	public function set_properties(array $properties)
	{
		$this->user_id = $properties['user_id'];
		$this->display_name = $properties['display_name'];
		$this->level = $properties['level'];
		$this->user_groups = explode('|', $properties['groups']);
	}
	
	public function get_array_tpl_vars()
	{
		$group_color = User::get_group_color($this->user_groups, $this->level, true);
		
		return array(
			'C_GROUP_COLOR' => !empty($group_color),
			'DISPLAY_NAME' => $this->display_name,
			'LEVEL_CLASS' => UserService::get_level_class($this->level),
			'GROUP_COLOR' => $group_color,
			'U_PROFILE' => UserUrlBuilder::profile($this->user_id)->rel()
		);
	}
}
?>
