<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 1.6 - 2007 01 25
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('PATH_TO_ROOT', '../../..');

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Ne réactualise pas l'emplacement du visiteur/membre
define('TITLE', '');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$request = AppContext::get_request();

$date = $request->get_getvalue('date', '');
$field = $request->get_getvalue('field', '');
$input_field = $request->get_getvalue('input_field', '');
$input_date = $request->get_getvalue('input_date', '');
$calendar_number = $request->get_getvalue('calendar_number', '');

//Vide par défaut => Type date.
$calendar_type = !empty($date) ? 'timestamp' : 'date';
$field = !empty($field) ? trim($field) : 'calendar';

$view = new FileTemplate('framework/util/mini_calendar_response.tpl');
$lang = LangLoader::get_all_langs();
$view->add_lang($lang);;

//Type date.
if ($calendar_type == 'date')
{
	$now = new Date();
	$year = $request->get_getint('y', $now->get_year());
	$month = $request->get_getint('m', $now->get_month());
	$day = $request->get_getint('d', $now->get_day());
	$input_date = !empty($input_date) ? trim($input_date) : $day . '/' . $month . '/' . $year;

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
	$array_l_month = array($lang['date.january'], $lang['date.february'], $lang['date.march'], $lang['date.april'], $lang['date.may'], $lang['date.june'],
	$lang['date.july'], $lang['date.august'], $lang['date.september'], $lang['date.october'], $lang['date.november'], $lang['date.december']);
	$month_day = $array_month[$month - 1];

	$view->put_all(array(
		'FIELD'           => $field,
		'INPUT_FIELD'     => $input_field,
		'CALENDAR_NUMBER' => $calendar_number,
		'MONTH'           => $month,
		'YEAR'            => $year,
		'PREVIOUS_YEAR'   => ($month == 1) ? ($year - 1) : $year,
		'PREVIOUS_MONTH'  => ($month == 1) ? 12 : ($month - 1),
		'NEXT_YEAR'       => ($month == 12) ? ($year + 1) : $year,
		'NEXT_MONTH'      => ($month == 12) ? 1 : ($month + 1)
	));

	//Génération des select.
	for ($i = 1; $i <= 12; $i++)
	{
		$selected = ($month == $i) ? 'selected="selected"' : '';
		$view->assign_block_vars('month', array(
			'MONTH' => '<option value="' . $i . '" ' . $selected . '>' . TextHelper::htmlspecialchars($array_l_month[$i - 1]) . '</option>'
		));
	}
	for ($i = 1900; $i <= 2521; $i++)
	{
		$selected = ($year == $i) ? 'selected="selected"' : '';
		$view->assign_block_vars('year', array(
			'YEAR' => '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>'
		));
	}

	//Premier jour du mois.
	$first_day = date('w', @mktime(1, 0, 0, $month, 1, $year));
	if ($first_day == 0)
	{
		$first_day = 7;
	}

	//Génération du calendrier.

	$month = ($month < 10 && TextHelper::substr($month, 0, 1) != 0) ? '0' . $month : $month;
	$j = 1;
	$last_day = ($month_day + $first_day);
	for ($i = 1; $i <= 42; $i++)
	{
		if ($i >= $first_day && $i < $last_day)
		{
			$date = StringVars::replace_vars(':year-:month-:day', array('year' => $year, 'month' => $month, 'day' => ($j < 10 && TextHelper::substr($j, 0, 1) != 0) ? '0' . $j : $j));
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

		$view->assign_block_vars('day', array(
			'DAY'         => $contents,
			'CLASS'       => $class,
			'CHANGE_LINE' => (($i % 7) == 0 && $i != 42),
			'INPUT_FIELD' => $input_field,
			'DATE'        => $date,
		));
	}
}
else
{
	//Non supporté
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view->display();

include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
