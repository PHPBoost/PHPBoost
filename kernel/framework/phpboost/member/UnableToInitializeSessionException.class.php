<?php
/*##################################################
 *                  UnableToInitializeSessionException.class.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class UnableToInitializeSessionException extends Exception
{
	/**
	 * @var Controller
	 */
	private $error_controller;
	
	public function __construct(Controller $error_controller)
	{
		$this->error_controller = $error_controller;
	}
	
	/**
	 * @return Controller
	 */
	public function get_error_controller()
	{
		return $this->error_controller;
	}
}