<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 14
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminErrors404DeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request): Response
	{
		AppContext::get_session()->csrf_get_protect();
		AdminError404Service::delete_404_error($request->get_getint('id'));
		AppContext::get_response()->redirect(AdminErrorsUrlBuilder::list_404_errors());
	}
}
?>
