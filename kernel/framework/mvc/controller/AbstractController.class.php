<?php
/*##################################################
 *                           abstract_controller.class.php
 *                            -------------------
 *   begin                : September 15 2009
 *   copyright         : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @desc This class defines the minimalist controler pattern
 * with no actions. This, in order to avoid to defines empty
 * init() and destroy() method for controlers that doesn't need
 * this functionality
 */
abstract class AbstractController implements Controller
{
	public function init() {}
	public function destroy() {}
	
	public function exception_handler($exception)
	{
		throw $exception;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function get_bread_crumb()
	{
		global $Bread_crumb;
		return $Bread_crumb;
	}
	
	public function is_display_enabled()
	{
		return $this->display;
	}
	
	protected function disable_display()
	{
		$this->display = false;
	}
	
	protected function enable_display()
	{
		$this->display = true;
	}
	
	protected function set_title($title)
	{
		$this->title = $title;
	}
	
	protected function check_token()
	{
		global $Session;
		$Session->csrf_get_protect();
	}
	
	var $display = true;
	var $title = 'Controller';
}
?>