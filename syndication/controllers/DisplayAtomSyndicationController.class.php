<?php
/*##################################################
 *                       DisplayAtomSyndicationController.class.php
 *                            -------------------
 *   begin                : July 16, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class DisplayAtomSyndicationController extends AbstractController
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
		
		$feed = new ATOM($module_id, $feed_name, $module_category_id);
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