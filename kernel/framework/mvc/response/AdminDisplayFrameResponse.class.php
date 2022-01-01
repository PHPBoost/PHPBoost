<?php
/**
 * @package     MVC
 * @subpackage  Response
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 4.0 - 2014 01 21
*/

class AdminDisplayFrameResponse extends AbstractResponse
{
	public function __construct($view)
	{
		parent::__construct(new AdminDisplayFrameGraphicalEnvironment(), $view);
	}
}
?>
