<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 18
*/

interface Response
{
	/**
	 * @return send the response to the browser
	 */
	function send();
}
?>
