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
	public function execute(HTTPRequestCustom $request)
	{
		$tpl = new FileTemplate('calendar/CalendarModuleMiniMenu.tpl');
		
		$lang = LangLoader::get('calendar_common', 'calendar');
		$tpl->add_lang($lang);
		
		$day = $request->get_value('day', 0);
		$month = $request->get_value('month', 0);
		$year = $request->get_value('year', 0);
		
		$date_lang = LangLoader::get('date-common');
		$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		$month_day = $array_month[$month - 1];
		
		try {
			$result = PersistenceContext::get_querier()->select("SELECT start_date, end_date
				FROM " . CalendarSetup::$calendar_table . "
				WHERE start_date BETWEEN '" . mktime(0, 0, 0, $month, 1, $year) . "' AND '" . mktime(23, 59, 59, $month, $month_day, $year) . "'
				ORDER BY start_date
				LIMIT ". ($array_month[$month - 1] - 1) ." OFFSET :start_limit",
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
					$array_action[$day_action] = true;
					$day_action++;
				}
			}
			
			//Génération des jours du calendrier.
			$array_l_days =  array("",$date_lang['monday_mini'], $date_lang['tuesday_mini'], $date_lang['wednesday_mini'], $date_lang['thursday_mini'], $date_lang['friday_mini'], $date_lang['saturday_mini'],
			$date_lang['sunday_mini']);
			foreach ($array_l_days as $l_day)
			{
				$tpl->assign_block_vars('day', array(
					'L_DAY' => '<td><span class="text_month">' . $l_day . '</span></td>'
				));
			}
			
			//Premier jour du mois.
			$first_day = @gmdate_format('w', @mktime(1, 0, 0, $month, 1, $year)); 
			if ($first_day == 0)
				$first_day = 7;
				
			//Génération du calendrier.
			$j = 1;
			$last_day = ($month_day + $first_day);
			for ($i = 1; $i <= 56; $i++)
			{
				if ( (($i % 8) == 1) && $i < $last_day)
				{
					$contents = '<td class="c_row calendar_week">'.(date('W', mktime(0, 0, 0, $month, $j, $year)) * 1).'</td>';
					$last_day++;
				}
				else
				{
					if (($i >= $first_day +1) && $i < $last_day)
					{
						$action = $j;
						if ( !empty($array_action[$j]) )
						{
							$class = 'calendar_event';
						}
						elseif (($j == Date("j")) && ($month==Date("m")) && ($year==Date("Y")) )
							$class = 'calendar_today';
						else
							if ( (($i % 8) == 7) || (($i % 8) == 0))
								$class = 'calendar_weekend';
							else
								$class = 'calendar_other';
								
						$action = '<a href="'. CalendarUrlBuilder::home($j . '/' . $month . '/' . $year . '#act')->absolute() . '">' . $j . '</a>';
						$contents = '<td class="c_row ' . $class . '">' . $action . '</td>';
						$j++;
					}
					else
					{
						if ( (($i % 8) == 7) || (($i % 8) == 0))
							$contents = '<td class="c_row calendar_weekend">&nbsp;</td>';
						else
							$contents = '<td class="c_row calendar_none">&nbsp;</td>';
					}
				}
				if (($j > $month_day) && ($i % 8) == 0)
				{
					$i = 56;
				}
				
				$tpl->assign_block_vars('calendar', array(
					'DAY' => $contents,
					'TR' => (($i % 8) == 0 && $i != 56) ? '</tr><tr class="tr_row">' : ''
				));
			}
		} catch (Exception $e) {
		}

		return new SiteNodisplayResponse($tpl);
	}
}
?>