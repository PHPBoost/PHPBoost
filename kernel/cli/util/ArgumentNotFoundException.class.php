<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 02 06
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArgumentNotFoundException extends Exception
{
	public function __construct(string $argument, array $args)
	{
		parent::__construct('argument ' . $argument . ' was not found in arguments: ' . '("' . implode('", "', $args) . '")');
	}
}
?>
