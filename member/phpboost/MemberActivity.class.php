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
	private $user_id = 0;
	private $title_activity = '';
	private $description_activity = '';
	private $url_activity = '';
	private $date_activity = '';
	
	public function set_user_id($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function get_user_id()
	{
		return $this->user_id;
	}
	
	public function set_title_activity($title_activity)
	{
		$this->title_activity = $title_activity;
	}
	
	public function get_title_activity()
	{
		return $this->title_activity;
	}
	
	public function set_description_activity($description_activity)
	{
		$this->description_activity = $description_activity;
	}
	
	public function get_description_activity()
	{
		return $this->description_activity;
	}
	
	public function set_url_activity($url_activity)
	{
		$this->url_activity = $url_activity;
	}
	
	public function get_url_activity()
	{
		return $this->url_activity;
	}
	
	public function set_date_activity(Date $date_activity)
	{
		$this->date_activity = $date_activity;
	}
	
	public function get_date_activity()
	{
		return $this->date_activity;
	}
	
	public function export()
	{
		return array(
			'user_id' => $this->user_id,
			'title_activity' => $this->title_activity,
			'description_activity' => $this->description_activity,
			'url_activity' => $this->url_activity,
			'date_activity' => $this->date_activity,
		);
	}
}
?>