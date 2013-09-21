<?php
/*##################################################
 *                      CalendarDisplayCategoryController.class.php
 *                            -------------------
 *   begin                : August 21, 2013
 *   copyright            : (C) 2013 Julien BRISWALTER
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

class CalendarDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
		$this->check_authorizations();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->tpl = new FileTemplate('calendar/CalendarDisplayCategoryController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = CalendarService::get_authorized_categories($this->get_category()->get_id());
		$config = CalendarConfig::load();
		
		$year = $request->get_getint('year', date('Y'));
		$month = $request->get_getint('month', date('n'));
		$day = $request->get_getint('day', date('j'));
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$main_lang = LangLoader::get('main');
		$date_lang = LangLoader::get('date-common');
		
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
		$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
		$month_day = $array_month[$month - 1];
		
		$this->tpl->put_all(array(
			'C_COMMENTS_ENABLED' => $config->are_comments_enabled(),
			'C_ADD' => CalendarAuthorizationsService::check_authorizations($this->get_category()->get_id())->write() || CalendarAuthorizationsService::check_authorizations($this->get_category()->get_id())->contribution(),
			'CALENDAR' => CalendarAjaxCalendarController::get_view(),
			'DATE' => $day . ' ' . $array_l_month[$month - 1] . ' ' . $year,
			'LINK_HOME' => CalendarUrlBuilder::home()->absolute(),
			'LINK_PREVIOUS' => ($month == 1) ? CalendarUrlBuilder::home(($year - 1) . '/12/1')->absolute() : CalendarUrlBuilder::home($year . '/' . ($month - 1) . '/1')->absolute(),
			'LINK_NEXT' => ($month == 12) ? CalendarUrlBuilder::home(($year + 1) . '/1/1')->absolute() : CalendarUrlBuilder::home($year . '/' . ($month + 1) . '/1')->absolute(),
			'L_EDIT' => $main_lang['update'],
			'L_DELETE' => $main_lang['delete'],
			'L_GUEST' => $main_lang['guest'],
			'L_ON' => $main_lang['on'],
			'U_ADD' => CalendarUrlBuilder::add_event($year . '/' . $month . '/' . $day)->rel()
		));
		
		$result = PersistenceContext::get_querier()->select("SELECT calendar.*, member.*, com.number_comments
		FROM " . CalendarSetup::$calendar_table . " calendar
		LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id=calendar.author_id
		LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = calendar.id AND com.module_id = 'calendar'
		WHERE (calendar.start_date BETWEEN :first_day_hour AND :last_day_hour)
		OR (calendar.end_date BETWEEN :first_day_hour AND :last_day_hour)
		OR (:first_day_hour BETWEEN calendar.start_date AND calendar.end_date) AND calendar.id_category IN :authorized_categories
		ORDER BY start_date ASC", array(
			'first_day_hour' => mktime(0, 0, 0, $month, $day, $year),
			'last_day_hour' => mktime(23, 59, 59, $month, $day, $year),
			'authorized_categories' => $authorized_categories
		));
		
		$events = false;
		
		while ($row = $result->fetch())
		{
			$event = new CalendarEvent();
			$event->set_properties($row);
			
			$this->tpl->assign_block_vars('event', $event->get_array_tpl_vars());
			
			$events = true;
		}
		
		$this->tpl->put('C_EVENT', $events);
		
		return $this->tpl;
	}
	
	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!CalendarAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CalendarService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}
	
	private function generate_response()
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
			$this->tpl->put('MSG', MessageHelper::display($errstr, E_USER_ERROR));
		
		$response = new CalendarDisplayResponse();
		$response->set_page_title($this->get_category()->get_name());
		$response->set_page_description($this->get_category()->get_description());
		
		$response->add_breadcrumb_link($this->lang['module_title'], CalendarUrlBuilder::home());
		
		$categories = array_reverse(CalendarService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($id != Category::ROOT_CATEGORY)
				$response->add_breadcrumb_link($category->get_name(), CalendarUrlBuilder::display_category($id, $category->get_rewrited_name()));
		}
		
		return $response->display($this->tpl);
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>
