<?php
/*##################################################
 *                            display_stats.php
 *                            -------------------
 *   begin                : August 26, 2007
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

define('PATH_TO_ROOT', '..');

include_once(PATH_TO_ROOT . '/kernel/init.php');

$get_brw = retrieve(GET, 'browsers', false);
$get_os = retrieve(GET, 'os', false);
$get_lang = retrieve(GET, 'lang', false);
$get_bot = retrieve(GET, 'bot', false);
$get_theme = retrieve(GET, 'theme', false);
$get_sex = retrieve(GET, 'sex', false);
$get_visit_month = retrieve(GET, 'visit_month', false);
$get_visit_year = retrieve(GET, 'visit_year', false);
$get_pages_day = retrieve(GET, 'pages_day', false);
$get_pages_month = retrieve(GET, 'pages_month', false);
$get_pages_year = retrieve(GET, 'pages_year', false);

include_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location(); //Ne réactualise pas l'emplacement du visiteur/membre
load_module_lang('stats'); //Chargement de la langue du module.

$Stats = new ImagesStats();

$array_stats = array('other' => 0);
if ($get_visit_month)
{
	$year = NumberHelper::numeric(retrieve(GET, 'year', date('Y')));
	$month = NumberHelper::numeric(retrieve(GET, 'month', 1));

	$array_stats = array();
	$result = PersistenceContext::get_querier()->select("SELECT nbr, stats_day
	FROM " . StatsSetup::$stats_table . "
	WHERE stats_year = :year AND stats_month = :month
	ORDER BY stats_day", array(
		'year' => $year,
		'month' => $month
	));
	while ($row = $result->fetch())
	{
		$array_stats[$row['stats_day']] = $row['nbr'];
	}
	$result->dispose();

	//Nombre de jours pour chaque mois (gestion des années bissextiles)
	$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
	//Complément des jours manquant.
	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	for ($i = 1; $i <= $array_month[$month - 1]; $i++)
	{
		if (!isset($array_stats[$i]))
		{
			$array_stats[$i] = 0;
		}
	}
	$Stats->load_data($array_stats, 'histogram', 5);
	//Tracé de l'histogramme.
	$Stats->draw_histogram(440, 250, '', array(LangLoader::get_message('days', 'date-common'), $LANG['guest_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_visit_year)
{
	$year = NumberHelper::numeric(retrieve(GET, 'year', ''));

	$array_stats = array();
	$result = PersistenceContext::get_querier()->select("SELECT SUM(nbr) as total, stats_month
	FROM " . StatsSetup::$stats_table . "
	WHERE stats_year = :year
	GROUP BY stats_month
	ORDER BY stats_month", array(
		'year' => $year
	));
	while ($row = $result->fetch())
	{
		$array_stats[$row['stats_month']] = $row['total'];
	}
	$result->dispose();

	//Complément des mois manquant
	for ($i = 1; $i <= 12; $i++)
	{
		if (!isset($array_stats[$i]))
		{
			$array_stats[$i] = 0;
		}
	}
	$Stats->load_data($array_stats, 'histogram', 5);
	//Tracé de l'histogramme.
	$Stats->draw_histogram(440, 250, '', array(LangLoader::get_message('month', 'date-common'), $LANG['guest_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_pages_day)
{
	$year = NumberHelper::numeric(retrieve(GET, 'year', ''));
	$month = NumberHelper::numeric(retrieve(GET, 'month', 1));
	$day = NumberHelper::numeric(retrieve(GET, 'day', 1));

	$array_stats = $pages_details = array();
	try {
		$pages_details = unserialize((string)PersistenceContext::get_querier()->get_column_value(StatsSetup::$stats_table, 'pages_detail', 'WHERE stats_year = :year AND stats_month = :month AND stats_day = :day', array('year' => $year, 'month' => $month, 'day' => $day)));
	} catch (RowNotFoundException $e) {}
	
	if (is_array($pages_details))
	{
		foreach ($pages_details as $hour => $pages)
		{
			$array_stats[$hour] = $pages;
		}
	}

	//Complément des heures manquantes.
	for ($i = 0; $i <= 23; $i++)
	{
		if (!isset($array_stats[$i]))
		{
			$array_stats[$i] = 0;
		}
	}
	$Stats->load_data($array_stats, 'histogram', 5);
	//Tracé de l'histogramme.
	$Stats->draw_histogram(440, 250, '', array(LangLoader::get_message('hours', 'date-common'), $LANG['page_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_pages_month)
{
	$year = NumberHelper::numeric(retrieve(GET, 'year', date('Y')));
	$month = NumberHelper::numeric(retrieve(GET, 'month', 1));

	$array_stats = array();
	$result = PersistenceContext::get_querier()->select("SELECT pages, stats_day
	FROM " . StatsSetup::$stats_table . "
	WHERE stats_year = :year AND stats_month = :month
	ORDER BY stats_day", array(
		'year' => $year,
		'month' => $month
	));
	while ($row = $result->fetch())
	{
		$array_stats[$row['stats_day']] = $row['pages'];
	}
	$result->dispose();

	//Nombre de jours pour chaque mois (gestion des années bissextiles)
	$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
	//Complément des jours manquant.
	$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
	for ($i = 1; $i <= $array_month[$month - 1]; $i++)
	{
		if (!isset($array_stats[$i]))
		{
			$array_stats[$i] = 0;
		}
	}
	$Stats->load_data($array_stats, 'histogram', 5);
	//Tracé de l'histogramme.
	$Stats->draw_histogram(440, 250, '', array(LangLoader::get_message('days', 'date-common'), $LANG['page_s']), NO_DRAW_LEGEND, NO_DRAW_VALUES, 8);
}
elseif ($get_pages_year)
{
	$year = NumberHelper::numeric(retrieve(GET, 'year', ''));

	$array_stats = array();
	$result = PersistenceContext::get_querier()->select("SELECT SUM(pages) as total, stats_month
	FROM " . StatsSetup::$stats_table . "
	WHERE stats_year = :year
	GROUP BY stats_month
	ORDER BY stats_month", array(
		'year' => $year
	));
	while ($row = $result->fetch())
	{
		$array_stats[$row['stats_month']] = $row['total'];
	}
	$result->dispose();

	//Complément des mois manquant
	for ($i = 1; $i <= 12; $i++)
	{
		if (!isset($array_stats[$i]))
		{
			$array_stats[$i] = 0;
		}
	}
	$Stats->load_data($array_stats, 'histogram', 5);
	//Tracé de l'histogramme.
	$Stats->draw_histogram(440, 250, '', array(LangLoader::get_message('month', 'date-common'), $LANG['page_s']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
}
elseif ($get_brw) //Navigateurs.
{
	$array_stats = array();
	$percent_other = 0;
	foreach (StatsSaver::retrieve_stats('browsers') as $name => $value)
	{
		if (isset($stats_array_browsers[$name]) && $name != 'other')
		{
			$array_stats[$stats_array_browsers[$name][0]] = $value;
		}
		else
		{
			$percent_other += $value;
		}
	}
	if ($percent_other > 0)
	{
		$array_stats[$stats_array_browsers['other'][0]] = $percent_other;
	}

	$Stats->load_data($array_stats, 'ellipse', 5);
	$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/browsers.png');
}
elseif ($get_os)
{
	$array_stats = array();
	$percent_other = 0;
	foreach (StatsSaver::retrieve_stats('os') as $name => $value)
	{
		if (isset($stats_array_os[$name]) && $name != 'other')
		{
			$array_stats[$stats_array_os[$name][0]] = $value;
		}
		else
		{
			$percent_other += $value;
		}
	}
	if ($percent_other > 0)
	{
		$array_stats[$stats_array_os['other'][0]] = $percent_other;
	}

	$Stats->load_data($array_stats, 'ellipse', 5);
	$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/os.png');
}
elseif ($get_lang)
{
	$array_stats = array();
	$percent_other = 0;
	foreach (StatsSaver::retrieve_stats('lang') as $name => $value)
	{
		foreach ($stats_array_lang as $regex => $array_country)
		{
			if (preg_match('`' . $regex . '`', $name))
			{
				if ($name != 'other')
				{
					$array_stats[$array_country[0]] = $value;
				}
				else
				{
					$percent_other += $value;
				}
				break;
			}
		}
	}
	if ($percent_other > 0)
	{
		$array_stats[$stats_array_lang['other'][0]] = $percent_other;
	}

	$Stats->load_data($array_stats, 'ellipse', 5);
	$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/lang.png');
}
elseif ($get_theme)
{
	include_once(PATH_TO_ROOT . '/kernel/begin.php');
	define('TITLE', '');
	include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

	$stats_array = array();
	foreach (ThemesManager::get_activated_themes_map() as $id => $theme)
	{
		$stats_array[$id] = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE theme = '" . $id . "'");
	}

	$Stats->load_data($stats_array, 'ellipse', 5);
	$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/theme.png');
}
elseif ($get_sex)
{
	include_once(PATH_TO_ROOT . '/kernel/begin.php');
	define('TITLE', '');
	include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

	$array_stats = array();
	$result = PersistenceContext::get_querier()->select("SELECT member.user_id, count(ext_field.user_sex) as compt, ext_field.user_sex
	FROM " . PREFIX . "member member
	LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = member.user_id
	GROUP BY ext_field.user_sex
	ORDER BY compt");
	while ($row = $result->fetch())
	{
		switch ($row['user_sex'])
		{
			case 0:
				$name = $LANG['unknown'];
				break;
			case 1:
				$name = $LANG['male'];
				break;
			case 2:
				$name = $LANG['female'];
				break;
		}
		$array_stats[$name] = $row['compt'];
	}
	$result->dispose();

	$Stats->load_data($array_stats, 'ellipse', 5);
	$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/sex.png');
}
elseif ($get_bot)
{
	$array_robot = StatsSaver::retrieve_stats('robots');
	$stats_array = array();
	if (is_array($array_robot))
	{
		foreach ($array_robot as $key => $value)
		{
			if ($key == 'unknow_bot')
				$key = addslashes($LANG['unknown_bot']);
			
			$array_info = explode('/', $value);
			if (isset($array_info[0]) && isset($array_info[1]))
			{
				$name = ucwords($array_info[0]);
				if (array_key_exists($name, $stats_array))
				{
					$stats_array[$name] = ($stats_array[$name] + $array_info[1]);
				}
				else
				{
					$stats_array[$name] = $array_info[1];
				}
			}
			else if (isset($array_info[0]))
			{
				$name = ucwords($key);
				if (array_key_exists($name, $stats_array))
				{
					$stats_array[$name] = ($stats_array[$name] + $array_info[0]);
				}
				else
				{
					$stats_array[$name] = $array_info[0];
				}
			}
		}
	}
	$Stats->load_data($stats_array, 'ellipse', 5);
	$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/bot.png');
}

?>