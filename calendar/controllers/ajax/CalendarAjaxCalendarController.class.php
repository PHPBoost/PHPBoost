<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 13
 * @since       PHPBoost 3.0 - 2012 11 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarAjaxCalendarController extends AbstractController
{
	private $lang;
	private $view;
	private $mini_calendar = false;
	private $year;
	private $month;
	private $id_category;

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

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}

	private function init(HTTPRequestCustom $request)
	{
		$this->lang = LangLoader::get_all_langs('calendar');
		$this->view = new FileTemplate('calendar/CalendarAjaxCalendarController.tpl');
		$this->view->add_lang($this->lang);

		if ($request->has_getparameter('calendar_mini') && $request->get_getvalue('calendar_mini') == 1)
			$this->set_mini_calendar();

		if ($request->has_getparameter('id_category'))
			$this->set_id_category($request->get_getvalue('id_category'));
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$config = CalendarConfig::load();
		$categories = CategoriesService::get_categories_manager('calendar')->get_categories_cache()->get_categories();

		$year = $this->year ? $this->year : min($request->get_int('calendar_ajax_year', date('Y')), 2521);
		$month = $this->month ? $this->month : min($request->get_int('calendar_ajax_month', date('n')), 12);
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;

		$array_month = array(31, $bissextile, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$array_l_month = array($this->lang['date.january'], $this->lang['date.february'], $this->lang['date.march'], $this->lang['date.april'], $this->lang['date.may'], $this->lang['date.june'], $this->lang['date.july'], $this->lang['date.august'], $this->lang['date.september'], $this->lang['date.october'], $this->lang['date.november'], $this->lang['date.december']);

		$month_days = $array_month[$month - 1];

		$previous_month = ($month == 1) ? 12 : ($month - 1);
		$previous_year = ($month == 1) ? ($year - 1) : $year;
		$next_month = ($month == 12) ? 1 : ($month + 1);
		$next_year = ($month == 12) ? ($year + 1) : $year;

		// Months
		for ($i = 1; $i <= 12; $i++)
		{
			$this->view->assign_block_vars('months', array(
				'VALUE'    => $i,
				'NAME'     => $array_l_month[$i - 1],
				'SELECTED' => $month == $i,
			));
		}

		// Years
		for ($i = 1970; $i <= 2521; $i++)
		{
			$this->view->assign_block_vars('years', array(
				'VALUE'    => $i,
				'NAME'     => $i,
				'SELECTED' => $year == $i,
			));
		}

		// Retrieve all the items of the selected month
		$items = $month == date('n') && $year == date('Y') && $this->id_category == Category::ROOT_CATEGORY ? CalendarCache::load()->get_items() : CalendarService::get_all_current_month_items($month, $year, $month_days, $this->id_category);

		$items_legend_list = array();

		foreach ($items as $item)
		{
			if (CategoriesAuthorizationsService::check_authorizations($item['id_category'], 'calendar')->read())
			{
				$start_date = new Date($item['start_date'], Timezone::SERVER_TIMEZONE);
				$end_date = new Date($item['end_date'], Timezone::SERVER_TIMEZONE);

				if (($end_date->get_month() > $start_date->get_month() || $end_date->get_year() > $start_date->get_year()) && $month == $start_date->get_month())
				{
					$item_first_day = $start_date->get_day();
					$item_last_day = $array_month[$month - 1];
				}
				else if (($end_date->get_month() > $start_date->get_month() || $end_date->get_year() > $start_date->get_year()) && $month == $end_date->get_month())
				{
					$item_first_day = 1;
					$item_last_day = $end_date->get_day();
				}
				else if (($end_date->get_month() > $start_date->get_month() || $end_date->get_year() > $start_date->get_year()) && $month > $start_date->get_month() && $month < $end_date->get_month())
				{
					$item_first_day = 1;
					$item_last_day = $array_month[$month - 1];
				}
				else
				{
					$item_first_day = $start_date->get_day();
					$item_last_day = $end_date->get_day();
				}

				for ($j = $item_first_day ; $j <= $item_last_day ; $j++)
				{
					if ($item['type'] == 'EVENT' || $item['type'] == 'BIRTHDAY')
					{
						$title = isset($array_items[$j]['title']) ? $array_items[$j]['title'] : '';
						$color = isset($array_items[$j]['color']) ? $array_items[$j]['color'] : array();
						$color[] = ($item['type'] == 'BIRTHDAY' ? $config->get_birthday_color() : ($item['id_category'] != Category::ROOT_CATEGORY && isset($categories[$item['id_category']]) && $categories[$item['id_category']]->get_color() ? $categories[$item['id_category']]->get_color() : $config->get_event_color()));
						$array_items[$j] = array(
							'title'       => $title . (!empty($title) ? '<br />' : '') . ($item['type'] != 'BIRTHDAY' ? (($j == $start_date->get_day() && $month == $start_date->get_month() && $year == $start_date->get_year()) ? $start_date->get_hours() . 'h' . $start_date->get_minutes() . ' : ' : '') : $this->lang['calendar.birthday.of'] . ' ') . $item['title'],
							'type'        => $item['type'],
							'color'       => $color,
							'id_category' => $item['id_category'],
						);

						if ($item['type'] == 'BIRTHDAY')
						{
							$items_legend_list['BIRTHDAY'] = array(
								'id_category' => Category::ROOT_CATEGORY,
								'name'        => $this->lang['calendar.birthday'],
								'color'       => $config->get_birthday_color()
							);
						}
						else if ($item['type'] == 'EVENT' && $item['id_category'] == Category::ROOT_CATEGORY)
						{
							$items_legend_list[$item['id_category']] = array(
								'id_category' => $item['id_category'],
								'name'        => $this->lang['calendar.no.category'],
								'color'       => $config->get_event_color()
							);
						}
						else
						{
							if (isset($categories[$item['id_category']]) && !isset($items_legend_list[$item['id_category']]))
							{
								$items_legend_list[$item['id_category']] = array(
									'id_category'   => $item['id_category'],
									'name'          => $categories[$item['id_category']]->get_name(),
									'rewrited_name' => $categories[$item['id_category']]->get_rewrited_name(),
									'color'         => $categories[$item['id_category']]->get_color(),
									'year'          => $month == date('n') && $year == date('Y') ? '' : $year,
									'month'         => $month == date('n') && $year == date('Y') ? '' : $month
								);
							}
						}
					}
				}
			}
		}

		$this->view->put_all(array(
			'C_MINI_MODULE'        => $this->is_mini_calendar(),
			'C_DISPLAY_LEGEND'     => !empty($items_legend_list),
			'DATE'                 => $array_l_month[$month - 1] . ' ' . $year,
			'ID_CATEGORY'          => $this->id_category,
			'MINI_MODULE'          => (int)$this->is_mini_calendar(),
			'PREVIOUS_MONTH_TITLE' => ($month == 1) ? $array_l_month[11] . ' ' . ($year - 1) : $array_l_month[$month - 2] . ' ' . $year,
			'PREVIOUS_YEAR'        => $previous_year,
			'PREVIOUS_MONTH'       => $previous_month,
			'NEXT_MONTH_TITLE'     => ($month == 12) ? $array_l_month[0] . ' ' . ($year + 1) : $array_l_month[$month] . ' ' . $year,
			'NEXT_YEAR'            => $next_year,
			'NEXT_MONTH'           => $next_month,
			'LEGEND'               => self::build_legend($items_legend_list),
			'U_AJAX_CALENDAR'      => CalendarUrlBuilder::ajax_month_calendar()->rel(),
			'U_AJAX_EVENTS'        => CalendarUrlBuilder::ajax_month_events()->rel()
		));

		// First day of the month
		$first_day = date('w', @mktime(1, 0, 0, $month, 1, $year));
		if ($first_day == 0)
			$first_day = 7;

		// Calendar generation
		$day = 1;
		$last_day = ($month_days + $first_day);
		for ($i = 1; $i <= 56; $i++)
		{
			$birthday_day = false;
			$color_list = array();

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
					if (!empty($array_items[$day]))
					{
						$birthday_day = $array_items[$day]['type'] == 'BIRTHDAY';
						$color_list = $array_items[$day]['color'];
						if (($day == date("j")) && ($month == date("m")) && ($year == date("Y")))
							$class = 'calendar-today calendar-event';
						else if ( (($i % 8) == 7) || (($i % 8) == 0))
							$class = 'calendar-weekend calendar-event';
						else
							$class = 'calendar-other calendar-event';
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
				'C_MONTH_DAY'  => ($i % 8) != 1 && $class != 'calendar-none',
				'C_COLOR'      => $color_list || $birthday_day,
				'C_WEEK_LABEL' => ($i % 8) == 1,
				'DAY'          => $content,
				'C_HAS_TITLE'  => !empty($array_items[$today]),
				'TITLE'        => !empty($array_items[$today]) ? $array_items[$today]['title'] : '',
				'CLASS'        => $class,
				'CHANGE_LINE'  => (($i % 8) == 0 && $i != 56),
				'U_DAY_EVENTS' => $this->id_category != Category::ROOT_CATEGORY ? CalendarUrlBuilder::display_category($this->id_category, $categories[$this->id_category]->get_rewrited_name(), $year, $month, $today)->rel() : CalendarUrlBuilder::home($year, $month, $today, true)->rel()
			));

			foreach ($color_list as $color)
			{
				$this->view->assign_block_vars('day.colors', array(
					'COLOR' => $color
				));
			}
		}
	}

	public static function build_legend($items_legend_list)
	{
		$legend_view = new FileTemplate('calendar/CalendarLegend.tpl');

		foreach ($items_legend_list as $legend)
		{
			$legend_view->assign_block_vars('legend', array(
				'C_ROOT_CATEGORY' => $legend['id_category'] == Category::ROOT_CATEGORY,
				'COLOR'           => $legend['color'],
				'NAME'            => $legend['name'],
				'U_CATEGORY'      => $legend['id_category'] != Category::ROOT_CATEGORY ? CalendarUrlBuilder::display_category($legend['id_category'], $legend['rewrited_name'], $legend['year'], $legend['month'])->rel() : ''
			));
		}

		return $legend_view;
	}

	public static function get_view($is_mini = false, $year = 0, $month = 0, $id_category = Category::ROOT_CATEGORY)
	{
		$request = AppContext::get_request();
		$object = new self();
		$object->init($request);
		if ($is_mini)
			$object->set_mini_calendar();
		if ($year)
			$object->set_year($year);
		if ($month)
			$object->set_month($month);
		$object->set_id_category($id_category);
		$object->build_view($request);
		return $object->view;
	}
}
?>
