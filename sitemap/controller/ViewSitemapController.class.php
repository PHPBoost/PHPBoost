<?php
/*##################################################
 *                        ViewSitemapController.class.php
 *                            -------------------
 *   begin                : December 09 2009
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class ViewSitemapController extends ModuleController
{
	private $lang = array();
	
	public function __construct()
	{
		$this->lang = LangLoader::get('common', 'sitemap');
	}
	
	public function execute(HTTPRequestCustom $request)
	{
		return $this->build_response(SitemapModuleHomePage::get_view());
	}
	
	private function build_response(View $view)
	{
			$response = new SiteDisplayResponse($view);
			$response->get_graphical_environment()->set_page_title(LangLoader::get_message('sitemap', 'common', 'sitemap'));
			return $response;
	}
}
?>