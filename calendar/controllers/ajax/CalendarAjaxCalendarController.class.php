<?php
/*##################################################
 *                          CalendarAjaxCalendarController.class.php
 *                            -------------------
 *   begin                : November 24, 2012
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

class CalendarAjaxCalendarController extends AbstractController
{
	private $lang;
	private $view;
	private $mini_calendar = false;
	private $year;
	private $month;
	
	public function set_mini_calendar()
	{
		$this->mini_calendar = true;
	}
	
	public function is_mini_calendar()
	{
		return $this->mini_calendar;
	}
	
	public function set_year($year)
	{
		$this->year = $year;
	}
	
	public function set_month($month)
	{
		$this->month = $month;
	}
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}
	
	private function build_view(HTTPRequestCustom $request)
	{
		$config = CalendarConfig::load();
		$categories = CalendarService::get_categories_manager()->get_categories_cache()->get_categories();
		$date_lang = LangLoader::get('date-common');
		
		$year = $this->year ? $this->year : $request->get_int('calendar_ajax_year', date('Y'));
		$month = $this->month ? $this->month : $request->get_int('calendar_ajax_month', date('n'));
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'], $date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
		
		$month_days = $array_month[$month - 1];
		
		$previous_month = ($month == 1) ? 12 : ($month - 1);
		$previous_year = ($month == 1) ? ($year - 1) : $year;
		$next_month = ($month == 12) ? 1 : ($month + 1);
		$next_year = ($month == 12) ? ($year + 1) : $year;
		
		//Months
		for ($i = 1; $i <= 12; $i++)
		{
			$this->view->assign_block_vars('months', array(
				'VALUE' => $i,
				'NAME' => $array_l_month[$i - 1],
				'SELECTED' => $month == $i,
			));
		}
		
		//Years
		for ($i = 1970; $i <= 2037; $i++)
		{
			$this->view->assign_block_vars('years', array(
				'VALUE' => $i,
				'NAME' => $i,
				'SELECTED' => $year == $i,
			));
		}
		
		$this->view->put_all(array(
			'C_MINI_MODULE' => $this->is_mini_calendar(),
			'L_MONDAY' => $date_lang['monday_mini'],
			'L_TUESDAY' => $date_lang['tuesday_mini'],
			'L_WEDNESDAY' => $date_lang['wednesday_mini'],
			'L_THURSDAY' => $date_lang['thursday_mini'],
			'L_FRIDAY' => $date_lang['friday_mini'],
			'L_SATURDAY' => $date_lang['saturday_mini'],
			'L_SUNDAY' => $date_lang['sunday_mini'],
			'DATE' => $array_l_month[$month - 1] . ' ' . $year,
			'MINI_MODULE' => (int)$this->is_mini_calendar(),
			'PREVIOUS_MONTH_TITLE' => ($month == 1) ? $array_l_month[11] . ' ' . ($year - 1) : $array_l_month[$month - 2] . ' ' . $year,
			'PREVIOUS_YEAR' => $previous_year,
			'PREVIOUS_MONTH' => $previous_month,
			'NEXT_MONTH_TITLE' => ($month == 12) ? $array_l_month[0] . ' ' . ($year + 1) : $array_l_month[$month] . ' ' . $year,
			'NEXT_YEAR' => $next_year,
			'NEXT_MONTH' => $next_month,
			'U_AJAX_CALENDAR' => CalendarUrlBuilder::ajax_month_calendar()->rel(),
			'U_AJAX_EVENTS' => CalendarUrlBuilder::ajax_month_events()->rel()
		));
		
		//Retrieve all the events of the selected month
		$events = $month == date('n') && $year == date('Y') ? CalendarCurrentMonthEventsCache::load()->get_events() : CalendarService::get_all_current_month_events($month, $year, $month_days);
		
		foreach ($events as $event)
		{
			$start_date = new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, $event['start_date']);
			$end_date = new Date(DATE_TIMESTAMP, Timezone::SERVER_TIMEZONE, $event['end_date']);
			
			for ($j = $start_date->get_day() ; $j <= $end_date->get_day() ; $j++)
			{
				if (($event['type'] == 'EVENT' && $start_date->get_month() == $month && $start_date->get_year() == $year) || ($event['type'] == 'BIRTHDAY' && $start_date->get_month() == $month))
				{
					$title = isset($array_events[$j]['title']) ? $array_events[$j]['title']: '';
					$array_events[$j] = array(
						'title' => $title . (!empty($title) ? '
' : '') . ($event['type'] != 'BIRTHDAY' ? ($j == $start_date->get_day() ? $start_date->get_hours() . 'h' . $start_date->get_minutes() . ' : ' : '') : $this->lang['calendar.labels.birthday_title'] . ' ') . $event['title'],
						'type' => $event['type'],
						'color' => !empty($event['id_category']) ? $categories[$event['id_category']]->get_color() : '',
						'id_category' => $event['id_category'],
					);
				}
			}
		}
		
		//First day of the month
		$first_day = date('w', @mktime(1, 0, 0, $month, 1, $year)); 
		if ($first_day == 0)
			$first_day = 7;
			
		//Calendar generation
		$day = 1;
		$last_day = ($month_days + $first_day);
		for ($i = 1; $i <= 56; $i++)
		{
			$birthday_day = $color = false;
			
			if ( (($i % 8) == 1) && $i < $last_day)
			{
				$content = date('W', mktime(0, 0, 0, $month, $day, $year));
				$class = 'calendar-week';
				$last_day++;
			}
			else
			{
				if (($i >= $first_day + 1) && $i < $last_day)
				{
					if (($day == Date("j")) && ($month == Date("m")) && ($year == Date("Y")))
						$class = 'calendar-today';
					else if (!empty($array_events[$day]))
					{
						$birthday_day = $array_events[$day]['type'] == 'BIRTHDAY';
						$color = $array_events[$day]['color'];
						$class = 'calendar-event';
					}
					else
					{
						if ( (($i % 8) == 7) || (($i % 8) == 0))
							$class = 'calendar-weekend';
						else
							$class = 'calendar-other';
					}
					
					$content = $day;
					$day++;
				}
				else
				{
					if ( ((($i % 8) == 7) || (($i % 8) == 0)) && ($i > $first_day) && ($day <= $month_days))
						$class = 'calendar-weekend';
					else
						$class = 'calendar-none';
				}
			}
			if (($day > $month_days) && ($i % 8) == 0)
				$i = 56;
			
			$today = $day - 1;
			$this->view->assign_block_vars('day', array(
				'C_MONTH_DAY' => ($i % 8) != 1 && $class != 'calendar-none',
				'C_COLOR' => $color || $birthday_day,
				'C_WEEK_LABEL' => ($i % 8) == 1,
				'DAY' => $content,
				'TITLE' => !empty($array_events[$today]) ? $array_events[$today]['title'] : '',
				'COLOR' => !empty($color) ? $color : $config->get_birthday_color(),
				'CLASS' => $class,
				'CHANGE_LINE' => (($i % 8) == 0 && $i != 56),
				'U_DAY_EVENTS' => CalendarUrlBuilder::home($year . '/' . $month . '/' . $today . '#calendar')->rel()
			));
		}
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->view = new FileTemplate('calendar/CalendarAjaxCalendarController.tpl');
		$this->view->add_lang($this->lang);
		
		if (in_array('calendar_mini', array_keys($_GET)) && $_GET['calendar_mini'] == 1)
			$this->set_mini_calendar();
	}
	
	public static function get_view($is_mini = false, $year = 0, $month = 0)
	{
		$object = new self();
		$object->init();
		if ($is_mini)
			$object->set_mini_calendar();
		if ($year)
			$object->set_year($year);
		if ($month)
			$object->set_month($month);
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
