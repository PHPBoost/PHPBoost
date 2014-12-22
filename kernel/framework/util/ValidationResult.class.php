<?php
/*##################################################
 *                          ValidationResult.class.php
 *                            -------------------
 *   begin                : January 16, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc This class is done to time a process easily. You choose when to start and when to stop.
 * @package {@package}
 */
class ValidationResult
{
	/**
	 * @var string the validation result title
	 */
	private $title;

	/**
	 * @var string[]
	 */
	private $errors = array();

	public function __construct($title = 'Validation result')
	{
		$this->title = $title;
	}

	public function get_title()
	{
		return $this->title;
	}

	/**
	 * @desc Adds an error message
	 * @param string $error_msg the error message to add
	 */
	public function add_error($error_msg)
	{
		$this->errors[] = $error_msg;
	}

	/**
	 * @desc Returns the list of the errors messages
	 * @return string[] errors messages
	 */
	public function get_errors_messages()
	{
		return $this->errors;
	}

	/**
	 * @desc Returns <code>true</code> if there is errors
	 * @return boolean <code>true</code> if there is errors
	 */
	public function has_errors()
	{
		return !empty($this->errors);
	}
}
?>