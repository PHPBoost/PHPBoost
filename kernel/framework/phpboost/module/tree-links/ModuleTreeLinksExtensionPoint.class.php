<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 4.1 - 2013 11 15
*/

interface ModuleTreeLinksExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'tree_links';

	/**
	 * @return ModuleTreeLinks class containing the tree links different as possible actions in the module (addition, configuration, etc).
	 */
	function get_actions_tree_links();
}
?>
