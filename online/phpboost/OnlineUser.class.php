<?php
/*##################################################
 *		             OnlineUser.class.php
 *                            -------------------
 *   begin                : February 01, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class OnlineUser extends User
{
	protected $location;
	protected $last_update;
	
	public function set_location($location)
	{
		$this->location = $location;
	}
	
	public function get_location()
	{
		return $this->location;
	}
	
	public function set_last_update($last_update)
	{
		$this->last_update = $last_update;
	}
	
	public function get_last_update()
	{
		return $this->last_update;
	}
}
?>