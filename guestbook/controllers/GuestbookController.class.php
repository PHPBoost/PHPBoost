<?php
/*##################################################
 *                      GuestbookController.class.php
 *                            -------------------
 *   begin                : December 12, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class GuestbookController extends ModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		return $this->build_response(GuestbookModuleHomePage::get_view());
	}
	
	private function build_response(View $view)
	{
		$lang = LangLoader::get('common', 'guestbook');
		
		$response = new GuestbookDisplayResponse();
		$response->add_breadcrumb_link($lang['module_title'], GuestbookUrlBuilder::home(AppContext::get_request()->get_getint('page', 1)));
		$response->set_page_title($lang['module_title']);
		return $response->display($view);
	}
}
?>
