<?php
/**
 * @package     Content
 * @subpackage  Homepage
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 08
*/

interface HomePageExtensionPoint
{
	const EXTENSION_POINT = 'home_page';

	/**
	 * @return HomePage
	 */
	function get_home_page();
}
?>
