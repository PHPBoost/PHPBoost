<?php
/*##################################################
 *                      OnlineHomeController.class.php
 *                            -------------------
 *   begin                : January 29, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class OnlineHomeController extends ModuleController
{
	public function execute(HTTPRequest $request)
	{
		return $this->build_response(OnlineModuleHomePage::get_view());
	}
	
	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add(LangLoader::get_message('online', 'online_common', 'online'), OnlineUrlBuilder::home()->absolute());
		$response->get_graphical_environment()->set_page_title(LangLoader::get_message('online', 'online_common', 'online'));
		return $response;
	}
}
?>