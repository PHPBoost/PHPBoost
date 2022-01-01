<?php
/**
 * This exception is raised when a configuration entry is load whereas it doesn't exists in the
 * database.
 * @package     IO
 * @subpackage  Data\config
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 09 16
*/

class ConfigNotFoundException extends Exception
{
	public function __construct($config_name)
	{
		parent::__construct('The configuration "' . $config_name . '" was not found in the database');
	}
}
?>
