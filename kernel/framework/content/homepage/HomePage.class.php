<?php
/**
 * @package     Content
 * @subpackage  Homepage
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 08
*/
interface HomePage
{
	/**
	 * @return The title of the home page
	 */
	function get_title();

	/**
	 * @return View The content of the home page
	 */
	function get_view();
}
?>
