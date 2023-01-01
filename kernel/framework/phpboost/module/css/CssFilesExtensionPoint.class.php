<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\css
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 06
*/

interface CssFilesExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'css_files';

	/**
	 * @return array css files containing in the templates folder module
	 */
	function get_css_files_always_displayed();

	/**
	 * @return array css files containing in the templates folder module
	 */
	function get_css_files_running_module_displayed();
}
?>
