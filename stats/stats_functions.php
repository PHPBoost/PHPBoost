<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.1 - 2014 07 30
*/

function get_trend_parameters($total_visit, $nbr_day, $yesterday_visit, $today_visit)
{
	$average = ($total_visit / $nbr_day);

	if (!$yesterday_visit && $nbr_day == 1)
	{
		$trend = 100;
	}
	elseif ($today_visit > $average)
	{
		$trend = NumberHelper::round((($today_visit * 100) / $average), 1) - 100;
	}
	elseif ($today_visit < $average)
	{
		$trend = 100 - NumberHelper::round((($today_visit * 100) / $average), 1);
	}
	else
	{
		$trend = 0;
	}

	$sign = $today_visit < $average ? '-' : '+';

	return array (
		'average' => NumberHelper::round($average, 1),
		'trend' => $trend,
		'sign' => $sign,
		'picture' => ($trend != 0 ? ($sign == '+' ? 'up' : 'down') : '')
	);
}
?>
