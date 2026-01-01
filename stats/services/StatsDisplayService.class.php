<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 24
 * @since       PHPBoost 6.0 - 2021 11 23
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class StatsDisplayService
{
	public static function get_trend_parameters($total_visit, $nbr_day, $yesterday_visit, $today_visit)
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
}
?>
