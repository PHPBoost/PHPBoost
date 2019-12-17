<?php
/**
 * @package     Ajax
 * @subpackage  Controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2015 11 27
*/

class AjaxUrlValidationController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		return new JSONResponse(array('is_valid' => (int)Url::check_url_validity($request->get_value('url_to_check', ''))));
	}
}
?>
