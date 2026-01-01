<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 24
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class InstallExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('install');
	}

	public function commands()
	{
		return new CLICommandsList(['install' => 'CLIInstallCommand']);
	}
}
?>
