<?php
/*##################################################
 *                       MemberActivity.class.php
 *                            -------------------
 *   begin                : July 27, 2011
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

class MemberActivity
{
	private $module_name;
	private $module_picture;
	private $user_id;
	private $title;
	private $description;
	private $url;
	private $date;

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
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_title($title)
	{
		$this->title = $title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_description()
	{
		return $this->description;
	}
	
	public function set_url($url)
	{
		$this->url = $url;
	}
	
	public function get_url()
	{
		return $this->url;
	}
	
	public function set_date(Date $date)
	{
		$this->date = $date;
	}
	
	public function get_date()
	{
		return $this->date;
	}
	
	public function export()
	{
		return array(
			'module_name' => $this->module_name,
			'module_picture' => $this->module_picture,
			'user_id' => $this->user_id,
			'title' => $this->title,
			'description' => $this->description,
			'url' => $this->url,
			'date' => $this->date,
		);
	}
}
?>