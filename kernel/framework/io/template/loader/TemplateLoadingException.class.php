<?php
/**
 * This exception is thrown when the template cannot been loaded.
 * @package     IO
 * @subpackage  Template\loader
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2009 06 18
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TemplateLoadingException extends Exception
{
	/**
	 * Constructs an exception
	 * @param string $message The error message
	 */
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}
?>
