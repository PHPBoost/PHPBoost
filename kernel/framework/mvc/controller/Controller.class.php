<?php
/**
 * This interface declares the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 09 09
*/

interface Controller
{
    /**
     * Returns the right controller to execute regarding the authorizations defined
     * for the controller
     * @return Controller the right controller
     */
    function get_right_controller_regarding_authorizations();

	/**
	 * execute the controller and returns the response
	 * @param HTTPRequestCustom $request the request received
	 * @return Response the controller response
	 */
	function execute(HTTPRequestCustom $request);
}
?>
