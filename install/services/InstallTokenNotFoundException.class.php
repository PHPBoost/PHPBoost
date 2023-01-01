<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2010 02 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class InstallTokenNotFoundException extends Exception
{
	public function __construct($token_name)
	{
		parent::__construct('Token "' . $token_name . '" was not found, please restart install');
	}
}

?>
