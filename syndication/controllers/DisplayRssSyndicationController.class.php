<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 07 16
*/

class DisplayRssSyndicationController extends AbstractController
{
	private $tpl;

	public function execute(HTTPRequestCustom $request)
	{
		$module_id = $request->get_getstring('module_id', '');

		if (empty($module_id))
		{
			AppContext::get_response()->redirect(Environment::get_home_page());
		}

		$this->init();

		$module_category_id = $request->get_getint('module_category_id', 0);
		$feed_name = $request->get_getstring('feed_name', Feed::DEFAULT_FEED_NAME);

		$feed = new RSS($module_id, $feed_name, $module_category_id);
		if ($feed !== null && $feed->is_in_cache())
		{
			$this->tpl->put('SYNDICATION', $feed->read());
		}
		else
		{
			$eps = AppContext::get_extension_provider_service();
			if ($eps->provider_exists($module_id, FeedProvider::EXTENSION_POINT))
			{
				$provider = $eps->get_provider($module_id);
				$feeds = $provider->feeds();
				$data = $feeds->get_feed_data_struct($module_category_id, $feed_name);

				if ($data === null)
				{
					AppContext::get_response()->set_header('content-type', 'text/html');
					DispatchManager::redirect(PHPBoostErrors::unexisting_element());
				}
				else
				{
					$feed->load_data($data);
					$feed->cache();

					$this->tpl->put('SYNDICATION', $feed->export());
				}
			}
			else
			{
				DispatchManager::redirect(PHPBoostErrors::module_not_installed());
			}
		}
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->tpl = new StringTemplate('{SYNDICATION}');
	}

	private function build_response(View $view)
	{
		return new SiteNodisplayResponse($view);
	}
}
?>
