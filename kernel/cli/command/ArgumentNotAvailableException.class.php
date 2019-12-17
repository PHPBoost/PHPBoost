<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 11
*/

class ArgumentNotAvailableException extends Exception
{
	public function __construct($argument, $possible_value)
	{
		parent::__construct($argument. ' argument is not available. For reminder, the possible value are : ' . $possible_value);
	}
}
?>
