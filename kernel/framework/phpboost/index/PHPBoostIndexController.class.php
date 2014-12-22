<?php
/*##################################################
 *                      PHPBoostIndexController.class.php
 *                            -------------------
 *   begin                : February 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
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

class PHPBoostIndexController extends AbstractController
{
	public function execute(HTTPRequestCustom $request)
	{
		$general_config = GeneralConfig::load();
		
		$other_home_page = $general_config->get_other_home_page();
		if (strpos($other_home_page, '/index.php') !== false)
		{
			AppContext::get_response()->redirect(UserUrlBuilder::home());
		}
		else if (!empty($other_home_page))
		{
			AppContext::get_response()->redirect($other_home_page);
		}
		else
		{
			try {
				$module = AppContext::get_extension_provider_service()->get_provider($general_config->get_module_home_page());
				if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
				{
					$home_page = $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page();
					return $this->build_response($home_page->get_view(), $home_page->get_title());
				}
				else
				{
					AppContext::get_response()->redirect(UserUrlBuilder::home());
				}
			} catch (UnexistingExtensionPointProviderException $e) {
				AppContext::get_response()->redirect(UserUrlBuilder::home());
			}
		}
	}
	
	private function build_response($view, $title)
	{
		$response = new SiteDisplayResponse($view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($title);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(new Url(Url::get_absolute_root()));
		return $response;
	}
}
?>