<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 18
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AdminDisplayResponse extends AbstractResponse
{
	public function __construct($view, $location_id = '')
	{
		parent::__construct(new AdminDisplayGraphicalEnvironment(), $view, $location_id);
	}
}
?>
