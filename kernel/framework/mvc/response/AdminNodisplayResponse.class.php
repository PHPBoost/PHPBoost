<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 18
*/

class AdminNodisplayResponse extends AbstractResponse
{
	public function __construct($view)
	{
		parent::__construct(new AdminNodisplayGraphicalEnvironment(), $view);
	}
}
?>
