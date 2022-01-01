<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2010 09 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class InstallController extends AbstractController
{
	protected $lang = array();

	protected function load_lang(HTTPRequestCustom $request)
	{
		$locale = TextHelper::htmlspecialchars($request->get_string('lang', ''));
		$locale = in_array($locale, InstallationServices::get_available_langs()) ? $locale : InstallationServices::get_default_lang();
		LangLoader::set_locale($locale);
		InstallUrlBuilder::set_locale($locale);
		$this->lang = LangLoader::get_all_langs('install');
	}
}
?>
