<?php
/**
 * This class is done to time a process easily. You choose when to start and when to stop.
 * @package     Util
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 16
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
	 * Adds an error message
	 * @param string $error_msg the error message to add
	 */
	public function add_error($error_msg)
	{
		$this->errors[] = $error_msg;
	}

	/**
	 * Returns the list of the errors messages
	 * @return string[] errors messages
	 */
	public function get_errors_messages()
	{
		return $this->errors;
	}

	/**
	 * Returns <code>true</code> if there is errors
	 * @return boolean <code>true</code> if there is errors
	 */
	public function has_errors()
	{
		return !empty($this->errors);
	}
}
?>
