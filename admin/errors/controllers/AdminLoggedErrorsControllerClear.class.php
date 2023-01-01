<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 01 05
*/

class AdminLoggedErrorsControllerClear extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		AppContext::get_session()->csrf_get_protect();

		$file_path = PATH_TO_ROOT . '/cache/error.log';

		$error_log_file = new File($file_path);
		try
		{
			$error_log_file->delete();
		}
		catch (IOException $exception)
		{
			echo $exception->getMessage();
		}

		AppContext::get_response()->redirect(AdminErrorsUrlBuilder::logged_errors());
	}
}
?>
