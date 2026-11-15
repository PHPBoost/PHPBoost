<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 2.0 - 2009 01 14
*/

interface ModuleSetup
{
	/**
	 * Returns a validation result containing environment errors
	 * @return ValidationResult a validation result containing environment errors
	 */
	function check_environment();

	/**
	 * Install the module
	 */
	function install();

	/**
	 * Uninstall the module
	 * @return string error if module uninstall failled
	 */
	function uninstall();

	/**
	 * Upgrade the module
	 * @return string $version upgrading
	 */
	function upgrade($installed_version);
}
?>
