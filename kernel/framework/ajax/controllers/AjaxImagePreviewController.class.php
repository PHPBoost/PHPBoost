<?php
/**
 * @package     Ajax
 * @subpackage  Controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 04 28
 * @since       PHPBoost 3.0 - 2012 06 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AjaxImagePreviewController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$url = '';
		$image = new Url($request->get_string('image', ''));

		if (Url::check_url_validity($image))
			$url = $image->rel();

		return new JSONResponse(array('url' => $url));
	}
}
?>
