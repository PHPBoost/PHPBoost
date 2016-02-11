<?php
/*##################################################
 *                              stats_functions.php
 *                            -------------------
 *   begin                : July 30, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
