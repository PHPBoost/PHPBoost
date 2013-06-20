<?php
/*##################################################
 *                          AjaxMiniCalendarController.class.php
 *                            -------------------
 *   begin                : November 24, 2012
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

class AjaxMiniCalendarController extends AbstractController
{
	private $view;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_view($request);
		return new SiteNodisplayResponse($this->view);
	}
	
	private function build_view($request)
	{
		$date_lang = LangLoader::get('date-common');
		
		$year = $request->get_int('year', date('Y'));
		$month = $request->get_int('month', date('m'));
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'], $date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
		
		$month_day = $array_month[$month - 1];
		
		$previous_month = ($month == 1) ? 12 : ($month - 1);
		$previous_year = ($month == 1) ? ($year - 1) : $year;
		$next_month = ($month == 12) ? 1 : ($month + 1);
		$next_year = ($month == 12) ? ($year + 1) : $year;
		
		$this->view->put_all(array(
			'L_MONDAY' => $date_lang['monday_mini'],
			'L_TUESDAY' => $date_lang['tuesday_mini'],
			'L_WEDNESDAY' => $date_lang['wednesday_mini'],
			'L_THURSDAY' => $date_lang['thursday_mini'],
			'L_FRIDAY' => $date_lang['friday_mini'],
			'L_SATURDAY' => $date_lang['saturday_mini'],
			'L_SUNDAY' => $date_lang['sunday_mini'],
			'DATE' => $array_l_month[$month - 1] . ' ' . $year,
			'PREVIOUS_MONTH_TITLE' => ($month == 1) ? $array_l_month[11] . ' ' . ($year - 1) : $array_l_month[$month - 2] . ' ' . $year,
			'NEXT_MONTH_TITLE' => ($month == 12) ? $array_l_month[0] . ' ' . ($year + 1) : $array_l_month[$month] . ' ' . $year,
			'LINK_PREVIOUS_MONTH' => CalendarUrlBuilder::mini($previous_year . '/' . $previous_month)->absolute(),
			'LINK_NEXT_MONTH' => CalendarUrlBuilder::mini($next_year . '/' . $next_month)->absolute()
		));
	
		//Retrieve all the events of the selected month
		$result = PersistenceContext::get_querier()->select("SELECT start_date, end_date, title
		FROM " . CalendarSetup::$calendar_table . "
		WHERE start_date BETWEEN '" . mktime(0, 0, 0, $month, 1, $year) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year) . "'
		ORDER BY start_date
		LIMIT " . ($array_month[$month - 1] - 1) . " OFFSET :start_limit",
			array(
				'start_limit' => 0
			), SelectQueryResult::FETCH_ASSOC
		);
	
		while ($row = $result->fetch())
		{
			$day_action = gmdate_format('j', $row['start_date']);
			$end_day_action = gmdate_format('j', $row['end_date']);
			
			while ($day_action <= $end_day_action)
			{
				$array_action[$day_action] = $row['title'];
				$day_action++;
			}
		}
		
		//First day of the month
		$first_day = @gmdate_format('w', @mktime(1, 0, 0, $month, 1, $year)); 
		if ($first_day == 0)
			$first_day = 7;
			
		//Calendar generation
		$day = 1;
		$last_day = ($month_day + $first_day);
		for ($i = 1; $i <= 56; $i++)
		{
			$calendar_day = ' ';
			
			if ( (($i % 8) == 1) && $i < $last_day)
			{
				$calendar_day = (date('W', mktime(0, 0, 0, $month, $day, $year)) * 1);
				$class = 'calendar_week';
				$last_day++;
			}
			else
			{
				if (($i >= $first_day + 1) && $i < $last_day)
				{
					if ( !empty($array_action[$day]) )
						$class = 'calendar_event';
					elseif (($day == Date("j")) && ($month == Date("m")) && ($year == Date("Y")) )
						$class = 'calendar_today';
					else
						if ( (($i % 8) == 7) || (($i % 8) == 0))
							$class = 'calendar_weekend';
						else
							$class = 'calendar_other';
							
					$calendar_day = '<a ' . (!empty($array_action[$day]) ? 'title="' . $array_action[$day] . '" ' : '') . 'href="'. CalendarUrlBuilder::home($year . '/' . $month . '/' . $day . '#events')->absolute() . '">' . $day . '</a>';
					$day++;
				}
				else
				{
					if ( (($i % 8) == 7) || (($i % 8) == 0))
						$class = 'calendar_weekend';
					else
						$class = 'calendar_none';
				}
			}
			if (($day > $month_day) && ($i % 8) == 0)
				$i = 56;
			
			$this->view->assign_block_vars('day', array(
				'CONTENT' => $calendar_day,
				'CLASS' => $class,
				'CHANGE_LINE' => (($i % 8) == 0 && $i != 56) ? true : false
			));
		}
	}
	
	private function init()
	{
		$lang = LangLoader::get('calendar_common', 'calendar');
		$this->view = new FileTemplate('calendar/AjaxCalendarModuleMiniMenu.tpl');
		$this->view->add_lang($lang);
	}
	
	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>