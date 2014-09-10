<?php
/*##################################################
 *                               mini_calendar_xmlhttprequest.php
 *                            -------------------
 *   begin                : January, 25 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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
* @package ajax
*
*/

define('PATH_TO_ROOT', '../../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Ne réactualise pas l'emplacement du visiteur/membre
define('TITLE', '');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

//Vide par défaut => Type date.
$calendar_type = !empty($_GET['date']) ? 'timestamp' : 'date';
$field = !empty($_GET['field']) ? trim($_GET['field']) : 'calendar';
$input_field = !empty($_GET['input_field']) ? trim($_GET['input_field']) : '';
$lyear = !empty($_GET['lyear']) ? '&amp;lyear=1' : '';

$date_lang = LangLoader::get('date-common');
$tpl = new FileTemplate('framework/util/mini_calendar_response.tpl');
$tpl->add_lang($date_lang);;

//Type date.
if ($calendar_type == 'date')
{
	$now = new Date();
	$year = !empty($_GET['y']) ? NumberHelper::numeric($_GET['y']) : $now->get_year();
	$month = !empty($_GET['m']) ? NumberHelper::numeric($_GET['m']) : $now->get_month();
	$day = !empty($_GET['d']) ? NumberHelper::numeric($_GET['d']) : $now->get_day();
	$input_date = !empty($_GET['input_date']) ? trim($_GET['input_date']) : $day . '/' . $month . '/' . $year;
	
	$selected = explode('/', $input_date);
	$selected_day = NumberHelper::numeric($selected[0]);
	$selected_month = NumberHelper::numeric($selected[1]);
	$selected_year = NumberHelper::numeric($selected[2]);
	
	if (!checkdate($month, $day, $year))
	{
		list($year, $month, $day) = array(date('Y'), date('n'), date('j'));
	}
	$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;

	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	$array_l_month = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
	$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);
	$month_day = $array_month[$month - 1];
		
	$tpl->put_all(array(
		'FIELD' => $field,
		'INPUT_FIELD' => $input_field,
		'LYEAR' => $lyear,
		'MONTH' => $month,
		'YEAR' => $year,
		'U_PREVIOUS' => ($month == 1) ? 'input_field=' . $input_field . '&amp;field=' . $field . $lyear . '&amp;input_date=' . $input_date . '&amp;d=' . $day . '&amp;m=12&amp;y=' . ($year - 1) :  'input_field=' . $input_field . '&amp;input_field=' . $input_field . '&amp;field=' . $field . $lyear . '&amp;input_date=' . $input_date . '&amp;d=1&amp;m=' . ($month - 1) . '&amp;y=' . $year,
		'U_NEXT' => ($month == 12) ? 'input_field=' . $input_field . '&amp;field=' . $field . $lyear . '&amp;input_date=' . $input_date . '&amp;d=' . $day . '&amp;m=1&amp;y=' . ($year + 1) :  'input_field=' . $input_field . '&amp;field=' . $field . $lyear . '&amp;input_date=' . $input_date . '&amp;d=1&amp;m=' . ($month + 1) . '&amp;y=' . $year
	));

	//Génération des select.
	for ($i = 1; $i <= 12; $i++)
	{
		$selected = ($month == $i) ? 'selected="selected"' : '';
		$tpl->assign_block_vars('month', array(
			'MONTH' => '<option value="' . $i . '" ' . $selected . '>' . TextHelper::htmlentities($array_l_month[$i - 1]) . '</option>'
		));
	}
	for ($i = 1900; $i <= 2037; $i++)
	{
		$selected = ($year == $i) ? 'selected="selected"' : '';
		$tpl->assign_block_vars('year', array(
			'YEAR' => '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>'
		));
	}
	
	//Premier jour du mois.
	$first_day = date('w', @mktime(1, 0, 0, $month, 1, $year));
	if ($first_day == 0)
	{
		$first_day = 7;
	}
		
	//Prise en compte et conversion des formats de dates.
	$format = '';
	$array_date = explode('/', LangLoader::get_message('date_format_day_month_year', 'date-common'));
	for ($i = 0; $i < 3; $i++)
	{
		switch ($array_date[$i])
		{
			case 'd':
				$format .= "%1\$s";
				break;
			case 'm':
				$format .= "%2\$s";
				break;
			case 'Y':
				$format .= "%3\$s";
				break;
		}
		$format .= ($i != 2) ? '/' : '';
	}

	//Génération du calendrier. 
   
	$month = ($month < 10 && substr($month, 0, 1) != 0) ? '0' . $month : $month;
	$j = 1;
	$last_day = ($month_day + $first_day);
	for ($i = 1; $i <= 42; $i++)
	{
		if ($i >= $first_day && $i < $last_day)
		{
			$date = sprintf($format, (($j < 10 && substr($j, 0, 1) != 0) ? '0' . $j : $j), $month, $year);
			
			$class ='';
			if ( (($i % 7) == 6) || (($i % 7) == 0)) 
			{ 
				$class = 'calendar-weekend';
			}

			if ($j == $selected_day && $month == $selected_month && $year == $selected_year)
			{
				$class = 'calendar-event';
			}
			
			$contents = $j;
			$j++;
		}
		else
		{
			$date = $contents = '';
			$class = 'calendar-none';
		}

		$tpl->assign_block_vars('day', array(
			'DAY' => $contents,
			'CLASS' => $class,
			'CHANGE_LINE' => (($i % 7) == 0 && $i != 42),
			'INPUT_FIELD' => $input_field,
			'DATE' => $date,
		));
	}
}
else
{
	//Non supporté
}

$tpl->display();

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>