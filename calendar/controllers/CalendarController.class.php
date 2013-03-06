<?php
/*##################################################
 *                      CalendarController.class.php
 *                            -------------------
 *   begin                : November 21, 2012
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

class CalendarController extends ModuleController
{
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get('calendar_common', 'calendar');
		
		return $this->build_response(CalendarModuleHomePage::get_view());
	}
	
	private function build_response(View $view)
	{
		$request = AppContext::get_request();
		$error = $request->get_value('error', '');
		
		//Gestion des messages
		switch ($error)
		{
			case 'invalid_date':
				$errstr = $this->lang['calendar.error.e_invalid_date'];
				break;
			default:
				$errstr = '';
		}
		if (!empty($errstr))
			$view->put('MSG', MessageHelper::display($errstr, E_USER_ERROR));
		
		$response = new CalendarDisplayResponse();
		$response->add_breadcrumb_link($this->lang['calendar.module_title'], CalendarUrlBuilder::home());
		$response->set_page_title($this->lang['calendar.module_title']);
		
		return $response->display($view);
	}
}
?>