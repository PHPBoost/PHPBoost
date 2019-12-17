<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 08 02
 * @since       PHPBoost 5.0 - 2016 07 31
*/

class UpdateExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('update');
	}

	public function commands()
	{
		return new CLICommandsList(array('update' => 'CLIUpdateCommand'));
	}
}
?>
