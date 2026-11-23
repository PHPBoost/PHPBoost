<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\extension-point
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 22
 * @since       PHPBoost 3.0 - 2011 10 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface MenusExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'menus';

	/**
	 * @return array instance Menu class
	 */
	function get_menus(): array;
}
?>
