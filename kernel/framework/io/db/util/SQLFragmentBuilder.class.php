<?php
/**
 * @package     IO
 * @subpackage  DB\util
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 22
*/

interface SQLFragmentBuilder
{
	/**
	 * @return SQLFragment
	 */
	function get_sql();
}
?>
