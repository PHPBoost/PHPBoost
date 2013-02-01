<?php
/*##################################################
 *                      GuestbookController.class.php
 *                            -------------------
 *   begin                : December 12, 2012
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

/**
 * @author Julien BRISWALTER <julien.briswalter@gmail.com>
 * @desc Module controller of the guestbook module
 */
class GuestbookController extends ModuleController
{
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get('guestbook_common', 'guestbook');
		
		return $this->build_response(GuestbookModuleHomePage::get_view());
	}
	
	private function build_response(View $view)
	{
		$response = new SiteDisplayResponse($view);
		$breadcrumb = $response->get_graphical_environment()->get_breadcrumb();
		$breadcrumb->add($this->lang['guestbook.module_title'], GuestbookUrlBuilder::home()->absolute());
		$response->get_graphical_environment()->set_page_title($this->lang['guestbook.module_title']);
		return $response;
	}
}
?>