<?php
/**
 * This exception is raised when a configuration entry is load whereas it doesn't exists in the
 * database.
 * @package     IO
 * @subpackage  Data\config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 3.0 - 2009 09 16
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ConfigNotFoundException extends Exception
{
	public function __construct(string $config_name)
	{
		parent::__construct('The configuration "' . $config_name . '" was not found in the database');
	}
}
?>
