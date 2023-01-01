<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 6.0 - 2021 11 23
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
	
	public static function display_themes_graph()
	{
		$Stats = new ImagesStats();
		
		$stats_array = array();
		foreach (ThemesManager::get_activated_themes_map() as $id => $theme)
		{
			$stats_array[$theme->get_configuration()->get_name()] = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE theme = '" . $id . "'");
		}

		$Stats->load_data($stats_array, 'ellipse', 5);
		$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/theme.png');
	}
	
	public static function display_sex_graph()
	{
		$Stats = new ImagesStats();
		
		$lang = LangLoader::get_all_langs('stats');
		$array_stats = array();
		$result = PersistenceContext::get_querier()->select("SELECT count(ext_field.user_sex) as compt, ext_field.user_sex
		FROM " . PREFIX . "member member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = member.user_id
		GROUP BY ext_field.user_sex
		ORDER BY compt");
		while ($row = $result->fetch())
		{
			switch ($row['user_sex'])
			{
				case 0:
					$name = $lang['common.unknown'];
					break;
				case 1:
					$name = $lang['user.male'];
					break;
				case 2:
					$name = $lang['user.female'];
					break;
			}
			$array_stats[$name] = $row['compt'];
		}
		$result->dispose();

		$Stats->load_data($array_stats, 'ellipse', 5);
		$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/sex.png');
	}
	
	public static function display_visits_year_graph($year = '')
	{
		$Stats = new ImagesStats();
		
		$lang = LangLoader::get_all_langs('stats');
		$now = new Date();
		
		$year = NumberHelper::numeric($year ? $year : $now->get_year());

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
		$Stats->draw_histogram(440, 250, '', array($lang['date.month'], $lang['user.guests']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
	}
	
	public static function display_visits_month_graph($year = '', $month = '')
	{
		$Stats = new ImagesStats();
		
		$lang = LangLoader::get_all_langs('stats');
		$now = new Date();
		
		$year = NumberHelper::numeric($year ? $year : $now->get_year());
		$month = NumberHelper::numeric($month ? $month : $now->get_month());

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
		$Stats->draw_histogram(440, 250, '', array($lang['date.days'], $lang['user.guests']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
	}
	
	public static function display_pages_year_graph($year = '')
	{
		$Stats = new ImagesStats();
		
		$lang = LangLoader::get_all_langs('stats');
		$now = new Date();
		
		$year = NumberHelper::numeric($year ? $year : $now->get_year());

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
		$Stats->draw_histogram(440, 250, '', array($lang['date.month'], $lang['common.pages']), NO_DRAW_LEGEND, DRAW_VALUES, 8);
	}
	
	public static function display_pages_month_graph($year = '', $month = '')
	{
		$Stats = new ImagesStats();
		
		$lang = LangLoader::get_all_langs('stats');
		$now = new Date();

		$year = NumberHelper::numeric($year ? $year : $now->get_year());
		$month = NumberHelper::numeric($month ? $month : $now->get_month());

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
		$Stats->draw_histogram(440, 250, '', array($lang['date.days'], $lang['common.pages']), NO_DRAW_LEGEND, NO_DRAW_VALUES, 8);
	}
	
	public static function display_browsers_graph()
	{
		$Stats = new ImagesStats();
		
		$stats_array_browsers = LangLoader::get('browsers', 'stats');
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
	
	public static function display_os_graph()
	{
		$Stats = new ImagesStats();
		
		$stats_array_os = LangLoader::get('os', 'stats');
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
	
	public static function display_langs_graph()
	{
		$Stats = new ImagesStats();
		
		$stats_array_lang = LangLoader::get('lang', 'stats');
		$array_stats = array();
		$percent_other = 0;
		foreach (StatsSaver::retrieve_stats('lang') as $name => $value)
		{
			foreach ($stats_array_lang as $regex => $array_country)
			{
				if (preg_match('`' . $regex . '`u', $name))
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
	
	public static function display_bots_graph()
	{
		$Stats = new ImagesStats();
		
		$lang = LangLoader::get_all_langs('stats');
		$array_robot = StatsSaver::retrieve_stats('robots');
		if (isset($array_robot['unknow_bot']))
		{
			$array_robot[$lang['common.unknown']] = $array_robot['unknow_bot'];
			unset($array_robot['unknow_bot']);
		}
		$robots_visits = array();
		foreach ($array_robot as $key => $value)
		{
			$robots_visits[$key] = is_array($value) ? $value['visits_number'] : $value;
		}

		$Stats->load_data($robots_visits, 'ellipse', 5);
		$Stats->draw_ellipse(210, 100, PATH_TO_ROOT . '/stats/cache/bot.png');
	}
}
?>
