<?php
/**
 * @package     PHPBoost
 * @subpackage  Member\extended-fields
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 05 06
*/

interface ExtendedFieldExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'extended_field';

	/**
	 * Returns Array class name extended fields
	 * @return class name extended fields
	 */
	function get_extended_fields();
}
?>
