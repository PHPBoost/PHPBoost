<?php
/**
 * @package     Content
 * @subpackage  Category
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 05
 * @since       PHPBoost 4.0 - 2013 01 31
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CategoryNotFoundException extends Exception
{
	public function __construct(int $id)
	{
		parent::__construct('The category #'. $id .' doesn\'t exist');
	}
}
?>
