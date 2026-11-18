<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 15
 * @since       PHPBoost 2.0 - 2009 01 14
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface ModuleSetup
{
	/**
	 * Returns a validation result containing environment errors
	 * @return ValidationResult a validation result containing environment errors
	 */
	function check_environment(): mixed;

	/**
	 * Install the module
	 */
	function install(): void;

	/**
	 * Uninstall the module
	 */
	function uninstall(): void;

	/**
	 * Upgrade the module
	 * @return string $version upgrading
	 */
	function upgrade(string $installed_version): mixed;
}
?>
