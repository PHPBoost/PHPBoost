<?php
/*##################################################
 *                           Plugin.class.php
 *                            -------------------
 *   begin                : February 21, 2012
 *   copyright            : (C) 2012 Kévin MASSY
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

abstract class Plugin
{
	private $title;
	private $view;
	
	public function __construct($title, $view)
	{
		$this->title = $title;
		$this->view = $view;
	}
	
	public function get_id()
	{
		return get_class($this);
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function get_view()
	{
		return $this->view;
	}
	
	public function get_preview()
	{
		return $this->view;
	}
}
?>