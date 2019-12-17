<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 02 29
*/

abstract class UpdateController extends AbstractController
{
	const DEFAULT_LOCALE = 'french';

	protected $lang = array();

	protected function load_lang(HTTPRequestCustom $request)
	{
		$locale = TextHelper::htmlspecialchars($request->get_string('lang', UpdateController::DEFAULT_LOCALE));
		LangLoader::set_locale($locale);
		UpdateUrlBuilder::set_locale($locale);
		$this->lang = LangLoader::get('update', 'update');
	}
}
?>
