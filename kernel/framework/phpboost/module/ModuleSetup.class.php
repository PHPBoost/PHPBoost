<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
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
	 * @return version upgrading
	 */
	function upgrade($installed_version);
}
?>
