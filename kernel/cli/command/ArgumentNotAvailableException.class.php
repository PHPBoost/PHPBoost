<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2011 10 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArgumentNotAvailableException extends Exception
{
	public function __construct(string $argument, string $possible_value)
	{
		parent::__construct($argument. ' argument is not available. For reminder, the possible value are : ' . $possible_value);
	}
}
?>
