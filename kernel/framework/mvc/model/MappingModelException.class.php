<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 6.1 - 2026 11 03
*/

class MappingModelException extends Exception
{
	public function __construct(string $classname, string $message)
	{
		parent::__construct('Mapping model of class "' . $classname . '" is invalid: ' . $message);
	}
}
?>
