<?php
/**
 * @package     Ajax
 * @subpackage  Controllers
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 06 25
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AjaxImagePreviewController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$url = '';
		$image = new Url($request->get_string('image', ''));
		$check = $image->rel();;

		if (Url::check_url_validity($check))
			$url = $image->rel();

		return new JSONResponse(['url' => $url]);
	}
}
?>
