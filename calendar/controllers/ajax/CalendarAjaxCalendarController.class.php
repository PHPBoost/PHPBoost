<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 3.0 - 2012 11 24
*/

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
		$this->init($request);
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$config = CalendarConfig::load();
		$categories = CategoriesService::get_categories_manager('calendar')->get_categories_cache()->get_categories();

		$year = $this->year ? $this->year : min($request->get_int('calendar_ajax_year', date('Y')), 2037);
		$month = $this->month ? $this->month : min($request->get_int('calendar_ajax_month', date('n')), 12);
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;

		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($this->lang['january'], $this->lang['february'], $this->lang['march'], $this->lang['april'], $this->lang['may'], $this->lang['june'], $this->lang['july'], $this->lang['august'], $this->lang['september'], $this->lang['october'], $this->lang['november'], $this->lang['december']);

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

		//Retrieve all the events of the selected month
		$events = $month == date('n') && $year == date('Y') ? CalendarCurrentMonthEventsCache::load()->get_events() : CalendarService::get_all_current_month_events($month, $year, $month_days);

		$events_legends_list = array();

		foreach ($events as $event)
		{
			if (CategoriesAuthorizationsService::check_authorizations($event['id_category'], 'calendar')->read())
			{
				$start_date = new Date($event['start_date'], Timezone::SERVER_TIMEZONE);
				$end_date = new Date($event['end_date'], Timezone::SERVER_TIMEZONE);

				if (($end_date->get_month() > $start_date->get_month() || $end_date->get_year() > $start_date->get_year()) && $month == $start_date->get_month())
				{
					$first_event_day = $start_date->get_day();
					$last_event_day = $array_month[$month - 1];
				}
				else if (($end_date->get_month() > $start_date->get_month() || $end_date->get_year() > $start_date->get_year()) && $month == $end_date->get_month())
				{
					$first_event_day = 1;
					$last_event_day = $end_date->get_day();
				}
				else if (($end_date->get_month() > $start_date->get_month() || $end_date->get_year() > $start_date->get_year()) && $month > $start_date->get_month() && $month < $end_date->get_month())
				{
					$first_event_day = 1;
					$last_event_day = $array_month[$month - 1];
				}
				else
				{
					$first_event_day = $start_date->get_day();
					$last_event_day = $end_date->get_day();
				}

				for ($j = $first_event_day ; $j <= $last_event_day ; $j++)
				{
					if ($event['type'] == 'EVENT' || $event['type'] == 'BIRTHDAY')
					{
						$title = isset($array_events[$j]['title']) ? $array_events[$j]['title'] : '';
						$array_events[$j] = array(
							'title' => $title . (!empty($title) ? '
	' : '') . ($event['type'] != 'BIRTHDAY' ? (($j == $start_date->get_day() && $month == $start_date->get_month() && $year == $start_date->get_year()) ? $start_date->get_hours() . 'h' . $start_date->get_minutes() . ' : ' : '') : LangLoader::get_message('calendar.labels.birthday_title', 'common', 'calendar') . ' ') . $event['title'],
							'type' => $event['type'],
							'color' => ($event['type'] == 'BIRTHDAY' ? $config->get_birthday_color() : ($event['id_category'] != Category::ROOT_CATEGORY && isset($categories[$event['id_category']]) && $categories[$event['id_category']]->get_color() ? $categories[$event['id_category']]->get_color() : $config->get_event_color())),
							'id_category' => $event['id_category'],
						);

						if ($event['type'] == 'BIRTHDAY')
						{
							$events_legends_list[$j] = array(
								'name' => LangLoader::get_message('calendar.labels.birthday', 'common', 'calendar'),
								'color' => $config->get_birthday_color()
							);
						}
						else if ($event['type'] == 'EVENT' && $event['id_category'] == Category::ROOT_CATEGORY)
						{
							$events_legends_list[$j] = array(
								'name' => LangLoader::get_message('calendar.titles.event', 'common', 'calendar'),
								'color' => $config->get_event_color()
							);
						}
						else
						{
							if (isset($categories[$event['id_category']]) && !isset($events_legends_list[$event['id_category']]))
							{
								$events_legends_list[$j] = array(
									'name' => $categories[$event['id_category']]->get_name(),
									'color' => $categories[$event['id_category']]->get_color()
								);
							}
						}
					}
				}
			}
		}

		$this->view->put_all(array(
			'C_MINI_MODULE' => $this->is_mini_calendar(),
			'C_DISPLAY_LEGEND' => !empty($events_legends_list) && !$this->is_mini_calendar(),
			'DATE' => $array_l_month[$month - 1] . ' ' . $year,
			'MINI_MODULE' => (int)$this->is_mini_calendar(),
			'PREVIOUS_MONTH_TITLE' => ($month == 1) ? $array_l_month[11] . ' ' . ($year - 1) : $array_l_month[$month - 2] . ' ' . $year,
			'PREVIOUS_YEAR' => $previous_year,
			'PREVIOUS_MONTH' => $previous_month,
			'NEXT_MONTH_TITLE' => ($month == 12) ? $array_l_month[0] . ' ' . ($year + 1) : $array_l_month[$month] . ' ' . $year,
			'NEXT_YEAR' => $next_year,
			'NEXT_MONTH' => $next_month,
			'LEGEND' => self::build_legend($events_legends_list),
			'U_AJAX_CALENDAR' => CalendarUrlBuilder::ajax_month_calendar()->rel(),
			'U_AJAX_EVENTS' => CalendarUrlBuilder::ajax_month_events()->rel()
		));

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
					if (!empty($array_events[$day]))
					{
						$birthday_day = $array_events[$day]['type'] == 'BIRTHDAY';
						$color = $array_events[$day]['color'];
						$class = 'calendar-event';
					}
					else if (($day == date("j")) && ($month == date("m")) && ($year == date("Y")))
						$class = 'calendar-today';
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
				'COLOR' => $color,
				'CLASS' => $class,
				'CHANGE_LINE' => (($i % 8) == 0 && $i != 56),
				'U_DAY_EVENTS' => CalendarUrlBuilder::home($year, $month, $today, true)->rel()
			));
		}
	}

	public static function build_legend($events_legends_list)
	{
		$legend_view = new FileTemplate('calendar/CalendarLegend.tpl');

		$displayed_color = array();
		$number_elements = 0;
		foreach ($events_legends_list as $legend)
		{
			$number_elements++;

			if (!in_array($legend['color'], $displayed_color))
			{
				$legend_view->assign_block_vars('legend', array(
					'COLOR' => $legend['color'],
					'NAME'  => $legend['name']
				));
				$displayed_color[] = $legend['color'];
			}
		}

		return $legend_view;
	}

	private function init(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get('date-common');
		$this->view = new FileTemplate('calendar/CalendarAjaxCalendarController.tpl');
		$this->view->add_lang($this->lang);

		if ($request->has_getparameter('calendar_mini') && $request->get_getvalue('calendar_mini') == 1)
			$this->set_mini_calendar();
	}

	public static function get_view($is_mini = false, $year = 0, $month = 0)
	{
		$object = new self();
		$object->init(AppContext::get_request());
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
