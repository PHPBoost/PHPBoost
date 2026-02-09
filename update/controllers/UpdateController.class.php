<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 25
 * @since       PHPBoost 3.0 - 2012 02 29
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class UpdateController extends AbstractController
{
	const DEFAULT_LOCALE = 'french';

	protected $lang = array();

	protected function load_lang(HTTPRequestCustom $request)
	{
		$this->redirect_to_https_if_needed($request);
		$locale = TextHelper::htmlspecialchars($request->get_string('lang', UpdateController::DEFAULT_LOCALE));
		LangLoader::set_locale($locale);
		UpdateUrlBuilder::set_locale($locale);
		$this->lang = LangLoader::get('update', 'update');
	}

	protected function redirect_to_https_if_needed(HTTPRequestCustom $request)
	{
		if (!$request->get_is_https() && @extension_loaded('curl'))
		{
			$status = 0;
			$curl = curl_init(str_replace('http://', 'https://', $request->get_site_url()));
			curl_setopt($curl, CURLOPT_NOBODY, true);
			$result = curl_exec($curl);

			if ($result !== false)
			{
				$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			}
			if (\PHP_VERSION_ID < 80100)
                curl_close($curl);

			if ($status >= 200 && $status < 400)
			{
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . str_replace('http://', 'https://', $request->get_current_url()));
				exit;
			}
		}
	}
}
?>
