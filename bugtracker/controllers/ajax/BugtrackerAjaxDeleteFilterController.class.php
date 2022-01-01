<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 05 08
*/

class BugtrackerAjaxDeleteFilterController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_int('id', 0);

		$code = -1;
		if (!empty($id))
		{
			//Delete filter
			BugtrackerService::delete_filter("WHERE id=:id", array('id' => $id));
			$code = $id;
		}

		return new JSONResponse(array('code' => $code));
	}
}
?>
