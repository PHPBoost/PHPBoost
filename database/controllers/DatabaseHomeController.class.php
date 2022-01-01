<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 09 30
*/

class DatabaseHomeController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_response()->redirect('/database/admin_database.php');
	}
}
?>
