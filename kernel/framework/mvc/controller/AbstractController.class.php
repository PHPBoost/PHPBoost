<?php
/**
 * This class defines the minimalist controler pattern
 * @package     MVC
 * @subpackage  Controller
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 09 15
*/

abstract class AbstractController implements Controller
{
	public function get_right_controller_regarding_authorizations()
    {
        return $this;
    }
}
?>
