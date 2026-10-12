<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 12
 * @since       PHPBoost 4.0 - 2014 01 21
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SiteDisplayFrameResponse extends AbstractResponse
{
	public function __construct($view)
	{
		parent::__construct(new SiteDisplayFrameGraphicalEnvironment(), $view);
	}

	public function get_graphical_environment(): SiteDisplayFrameGraphicalEnvironment
	{
		return parent::get_graphical_environment();
	}
}
?>
