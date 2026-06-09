<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 05 03
*/

class UserPMController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$user_id = $request->get_getint('user_id', 0);
		$pm_id = $request->get_getint('pm_id', 0);

		// Redirect to pm.php with appropriate query parameters
		$url = '/user/pm.php';

		if ($pm_id)
		{
			$url .= '?id=' . $pm_id;
		}
		elseif ($user_id)
		{
			$url .= '?pm=' . $user_id;
		}

		AppContext::get_response()->redirect($url);
	}
}
?>
