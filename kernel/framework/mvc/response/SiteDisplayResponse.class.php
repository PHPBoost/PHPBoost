<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 02 06
 * @since       PHPBoost 3.0 - 2009 10 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SiteDisplayResponse extends AbstractResponse
{
	public function __construct($view, $location_id = '')
	{
		parent::__construct(new SiteDisplayGraphicalEnvironment(), $view, $location_id);
	}

	public function get_graphical_environment(): SiteDisplayGraphicalEnvironment
	{
		return parent::get_graphical_environment();
	}
}
?>
