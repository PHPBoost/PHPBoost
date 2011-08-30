<?php
/*##################################################
 *                       MembersActivities.class.php
 *                            -------------------
 *   begin                : July 31, 2011
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

class MembersActivities
{
	private $module_name = '';
	private $module_picture = '';
	private $module_feed_url = '';
	private $activities = array();

	public function set_module_name($module_name)
	{
		$this->module_name = $module_name;
	}
	
	public function get_module_name()
	{
		return $this->module_name;
	}
	
	public function set_module_picture($module_picture)
	{
		$this->module_picture = $module_picture;
	}
	
	public function get_module_picture()
	{
		return $this->module_picture;
	}
	
	public function set_module_feed_url(Url $module_feed_url)
	{
		$this->module_feed_url = $module_feed_url;
	}
	
	public function get_module_feed_url()
	{
		return $this->module_feed_url;
	}
	
	public function set_activities(Array $activities)
	{
		$this->activities = $activities;
	}
	
	public function get_activities()
	{
		return $this->activities;
	}
}
?>