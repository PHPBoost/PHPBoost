<?php
/**
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 29
 * @since       PHPBoost 6.0 - 2021 11 23
 * @contributor Maxence CAUDERLIER <mxkoder@phpboost.com>
*/

class StatsDisplayController extends DefaultModuleController
{
	private $db_querier;
	private $year;
	private $month;
	private $current_year;
	private $current_month;
	private $year_requested;
	private $month_requested;
	private $next_year;
	private $previous_year;
	private $next_month;
	private $previous_month;
	private $array_month;
	private $bissextile;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		$this->check_erase($request);
		$this->affect_vars($request);
		$this->build_view($request);
		return $this->generate_response($request);
	}

	private function build_view(HTTPRequestCustom $request)
	{		
		$section = $request->get_string('section', '');
		$visit   = ($section == 'visit');
		$pages   = ($section == 'pages');
		$referer = ($section == 'referer');
		$keyword = ($section == 'keyword');
		$members = ($section == 'members');
		$browser = ($section == 'browser');
		$os      = ($section == 'os');
		$country = ($section == 'lang');
		$bot     = ($section == 'bot');

		$this->view->put_all([
			'U_STATS_SITE'    => StatsUrlBuilder::home('site')->rel(),
			'U_STATS_USERS'   => StatsUrlBuilder::home('members')->rel(),
			'U_STATS_VISIT'   => StatsUrlBuilder::home('visit')->rel(),
			'U_STATS_PAGES'   => StatsUrlBuilder::home('pages')->rel(),
			'U_STATS_REFERER' => StatsUrlBuilder::home('referer')->rel(),
			'U_STATS_KEYWORD' => StatsUrlBuilder::home('keyword')->rel(),
			'U_STATS_BROWSER' => StatsUrlBuilder::home('browser')->rel(),
			'U_STATS_OS'      => StatsUrlBuilder::home('os')->rel(),
			'U_STATS_LANG'    => StatsUrlBuilder::home('lang')->rel(),
			'U_STATS_ROBOTS'  => StatsUrlBuilder::home('bot')->rel()
		]);

		if ($members)
		{
			$this->build_members_view();
		}
		elseif ($visit) //Visites par jour classées par mois.
		{
			$this->build_visits_view();
		}
		elseif ($pages) //Pages par jour classées par mois.
		{
			$this->build_pages_view();
		}
		elseif ($referer)
		{
			$this->build_referers_view($request);
		}
		elseif ($keyword)
		{
			$this->build_keyword_view($request);
		}
		elseif ($browser)
		{
			$this->build_pie_stats_view('browsers');
		}
		elseif ($os)
		{
			$this->build_pie_stats_view('os');
		}
		elseif ($country)
		{
			$this->build_pie_stats_view('lang');
		}
		elseif ($bot)
		{
			$this->build_robots_view();
		}
		else
		{
			$general_config = GeneralConfig::load();
			$this->view->put_all([
				'C_STATS_SITE' => true,
				'START'        => $general_config->get_site_install_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'VERSION'      => $general_config->get_phpboost_major_version()
			]);
		}
	}

	private function check_erase(HTTPRequestCustom $request)
	{
		$erase            = $request->get_postbool('erase', false);
		$erase_occasional = $request->get_postbool('erase-occasional', false);

		if ($erase) //erase robots.txt
		{
			$file = new File(PATH_TO_ROOT . '/stats/cache/robots.txt');
			if ($file->exists())
				$file->delete();
		}

		if ($erase_occasional) //Erase occasional robots
		{
			$array_robot = StatsSaver::retrieve_stats('robots');
			$robots_visits = [];
			$robots_visits_number = 0;
			foreach ($array_robot as $key => $value)
			{
				$robots_visits[$key] = is_array($value) ? $value['visits_number'] : $value;
				$robots_visits_number += $robots_visits[$key];
			}

			if ($robots_visits_number)
			{
				foreach ($robots_visits as $key => $value)
				{
					if (NumberHelper::round(($value/$robots_visits_number*100), 1) == 0)
						unset($array_robot[$key]);
				}
			}

			$file = @fopen(PATH_TO_ROOT . '/stats/cache/robots.txt', 'r+');
			fwrite($file, TextHelper::serialize($array_robot));
			fclose($file);
		}
	}

	private function affect_vars(HTTPRequestCustom $request)
	{
		$this->db_querier = PersistenceContext::get_querier();
		$now = new Date();
		
		$this->year = $request->has_postparameter('year') ? $request->get_postint('year', '') : $request->get_getint('year', '');
		$this->month = $request->has_postparameter('month') ? $request->get_postint('month', '') : $request->get_getint('month', '');

		$this->current_year = NumberHelper::numeric($this->year ? $this->year : $now->get_year());
		$this->current_month = NumberHelper::numeric($this->month ? $this->month : $now->get_month());

		$this->year_requested = (bool)$this->year;
		$this->month_requested = (bool)$this->month;

		$this->month = $this->month ? $this->month : $this->current_month;
		$this->year = $this->year ? $this->year : $this->current_year;

		if (!$this->year_requested && $this->month_requested)
		{
			//Mois précédent et suivant
			$this->next_month = ($this->month < 12) ? $this->month + 1 : 1;
			$this->next_year = ($this->month < 12) ? $this->year : $this->year + 1;
			$this->previous_month = ($this->month > 1) ? $this->month - 1 : 12;
			$this->previous_year = ($this->month > 1) ? $this->year : $this->year - 1;
		}
		else
		{
			$this->next_year = $this->year + 1;
			$this->previous_year = $this->year - 1;
		}
		//Nombre de jours pour chaque mois (gestion des années bissextiles)
		$this->bissextile = (date("L", mktime(0, 0, 0, 1, 1, $this->year)) == 1) ? 29 : 28;
		$this->array_month = [31, $this->bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31];
	}

	private function build_members_view()
	{
		$stats_cache = StatsCache::load();
		$last_user_group_color = User::get_group_color($stats_cache->get_stats_properties('last_member_groups'), $stats_cache->get_stats_properties('last_member_level'));
		$user_sex_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_sex');
		$users_number = $stats_cache->get_stats_properties('nbr_members');

		$themes_stats_array = [];
		foreach (ThemesManager::get_activated_themes_map() as $theme)
		{
			$themes_stats_array[$theme->get_id()] = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE theme = '" . $theme->get_id() . "'");
		}
		$themes_chart = new StatsPieChart( 'members');
		$dataset = new ChartDataset($this->lang['user.members']);
		$dataset->set_datas($themes_stats_array);

		$themes_chart->add_dataset($dataset);
		foreach ($themes_stats_array as $name => $value)
		{
			$this->view->assign_block_vars('templates', [
				'NBR_THEME' => $value,
				'COLOR'     => $dataset->get_color_label($name),
				'THEME'     => ($name == 'Other') ? $this->lang['common.other'] : $name,
				'PERCENT'   => NumberHelper::round($value/$users_number*100, 1)
			]);
		}

		$gender_stats_array = [];
		$result = $this->db_querier->select("SELECT count(ext_field.user_sex) as compt, ext_field.user_sex
		FROM " . PREFIX . "member member
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = member.user_id
		GROUP BY ext_field.user_sex
		ORDER BY compt");
		while ($row = $result->fetch())
		{
			switch ($row['user_sex'])
			{
				case 0:
				$name = $this->lang['common.unknown'];
				break;
				case 1:
				$name = $this->lang['user.male'];
				break;
				case 2:
				$name = $this->lang['user.female'];
				break;
			}
			$gender_stats_array[$name] = (int)$row['compt'];
		}
		if (!isset($gender_stats_array[$this->lang['common.unknown']]))
		{
			$number_unknown = $users_number - ($gender_stats_array[$this->lang['user.male']] ?? 0) - ($gender_stats_array[$this->lang['user.female']] ?? 0);
			if ($number_unknown > 0)
				$gender_stats_array[$this->lang['common.unknown']] = $number_unknown;
		}
		$gender_chart = new StatsPieChart('gender');
		$dataset = new ChartDataset($this->lang['user.members']);
		$dataset->set_datas($gender_stats_array);

		$gender_chart->add_dataset($dataset);

		$this->view->put_all([
			'C_STATS_USERS'           => true,
			'C_LAST_USER_GROUP_COLOR' => !empty($last_user_group_color),
			'C_DISPLAY_SEX'           => (!empty($user_sex_field) && $user_sex_field['display']),
			'LAST_USER_DISPLAY_NAME'  => $stats_cache->get_stats_properties('last_member_login'),
			'LAST_USER_LEVEL_CLASS'   => UserService::get_level_class($stats_cache->get_stats_properties('last_member_level')),
			'LAST_USER_GROUP_COLOR'   => $last_user_group_color,
			'U_LAST_USER_PROFILE'     => UserUrlBuilder::profile($stats_cache->get_stats_properties('last_member_id'))->rel(),
			'USERS_NUMBER'            => $users_number,
			'MEMBERS_THEMES_CHART'    => $themes_chart->get_html(),
			'MEMBERS_GENDER_CHART' 	  => $gender_chart->get_html()
		]);
		$result->dispose();

		foreach ($gender_stats_array as $name => $value)
		{
			$this->view->assign_block_vars('sex', [
				'MEMBERS_NUMBER' => $value,
				'COLOR'          => $dataset->get_color_label($name),
				'SEX'            => ($name == 'Other') ? $this->lang['common.other'] : $name,
				'PERCENT'        => NumberHelper::round($value/$users_number*100, 1)
			]);
		}
		$i = 1;
		$result = $this->db_querier->select("SELECT user_id, display_name, level, user_groups, posted_msg 
		FROM " . DB_TABLE_MEMBER . " ORDER BY posted_msg DESC LIMIT 10 OFFSET 0");
		while ($row = $result->fetch())
		{
			$modules = AppContext::get_extension_provider_service()->get_extension_point(UserExtensionPoint::EXTENSION_POINT);
			$contributions_number = 0;
			foreach ($modules as $module)
			{
				if($module->get_publications_module_id() != 'forum')
					$contributions_number += $module->get_publications_number($row['user_id']);
			}
			$user_group_color = User::get_group_color($row['user_groups'], $row['level']);

			$this->view->assign_block_vars('top_poster', [
				'C_USER_GROUP_COLOR' => !empty($user_group_color),
				'ID'                 => $i,
				'LOGIN'              => $row['display_name'],
				'USER_LEVEL_CLASS'   => UserService::get_level_class($row['level']),
				'USER_GROUP_COLOR'   => $user_group_color,
				'USER_POST'          => $row['posted_msg'],
				'USER_PUBLICATIONS'  => $contributions_number,
				'U_USER_PROFILE'      => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_USER_PUBLICATIONS' => UserUrlBuilder::publications($row['user_id'])->rel(),
			]);
			$i++;
		}
		$result->dispose();
	}

	private function build_visits_view()
	{
		//On affiche les visiteurs totaux et du jour
		$visit_counter = ['nbr_ip' => 0, 'total' => 0];
		try {
			$visit_counter = $this->db_querier->select_single_row(DB_TABLE_VISIT_COUNTER, ['ip AS nbr_ip', 'total'], 'WHERE id = :id', ['id' => 1]);
		} catch (RowNotFoundException $e) {}

		$visit_counter_total = !empty($visit_counter['nbr_ip']) ? $visit_counter['nbr_ip'] : 1;
		$visit_counter_day = !empty($visit_counter['total']) ? $visit_counter['total'] : 1;

		$this->month = $this->month ? $this->month : $this->current_month;
		$this->year = $this->year ? $this->year : $this->current_year;

		if ($this->year_requested && !$this->month_requested) //Visites par mois classées par ans.
		{
			//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
			$info = ['max_month' => 0, 'sum_month' => 0, 'nbr_month' => 0];
			
			try {
				$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(nbr) as max_month', 'SUM(nbr) as sum_month', 'COUNT(DISTINCT(stats_month)) as nbr_month'], 'WHERE stats_year=:year GROUP BY stats_year', ['year' => $this->year]);
			} catch (RowNotFoundException $e) {}
			$average = !empty($info['nbr_month']) ? NumberHelper::round($info['sum_month']/$info['nbr_month'], 1) : 1;

			//Année maximale
			$info_year = ['max_year' => 0, 'min_year' => 0];
			try {
				$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'], '');
			} catch (RowNotFoundException $e) {}
			for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
			{
				$this->view->assign_block_vars('year_select', [
					'C_SELECTED' => ($i == $this->year),
					'LABEL'      => $i,
					'VALUE'      => $i
				]);
			}

			$array_stats = [];
			$result = PersistenceContext::get_querier()->select("SELECT SUM(nbr) as total, stats_month
			FROM " . StatsSetup::$stats_table . "
			WHERE stats_year = :year
			GROUP BY stats_month
			ORDER BY stats_month", [
				'year' => $this->year
			]);
			while ($row = $result->fetch())
			{
				$array_stats[$row['stats_month']] = $row['total'];
				//On affiche les stats numériquement dans un tableau en dessous
				$this->view->assign_block_vars('value', [
					'C_DETAILS_LINK' => true,
					'U_DETAILS'      => StatsUrlBuilder::home('visit', $this->year, $row['stats_month'])->rel(),
					'L_DETAILS'      => $this->get_translated_month($row['stats_month']),
					'NBR'            => $row['total']
				]);
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
			
			$visits_year_data = [];
			foreach ($array_stats as $key => $value)
			{
				$visits_year_data[$this->get_translated_month($key)] = $value;
			}
			$visits_chart = new StatsBarChart('visits');
			$dataset = new ChartDataset($this->lang['user.guests']);
			$dataset->set_datas($visits_year_data);
			$visits_chart->add_dataset($dataset);
			$visits_chart->add_average_dataset($dataset, $this->lang['common.average'], $average);

			$this->view->put_all([
				'C_STATS_VISIT'   => true,
				'TYPE'            => 'visit',
				'VISIT_TOTAL'     => $visit_counter_total,
				'VISIT_DAY'       => $visit_counter_day,
				'YEAR'            => $this->year,
				'COLSPAN'         => 14,
				'SUM_NBR'         => $info['sum_month'],
				'MAX_NBR'         => $info['max_month'],
				'MOY_NBR'         => $average,
				'U_NEXT_LINK'     => StatsUrlBuilder::home('visit', $this->next_year)->rel(),
				'U_PREVIOUS_LINK' => StatsUrlBuilder::home('visit', $this->previous_year)->rel(),
				'U_YEAR'          => StatsUrlBuilder::home('visit', $this->year)->rel(),
				'C_STATS_YEAR'    => true,
				'VISITS_CHART'    => $visits_chart->get_html()
			]);
		}
		else
		{
			//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
			$info = ['max_nbr' => 0, 'min_day' => 0, 'sum_nbr' => 0, 'avg_nbr' => 0];
			try {
				$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(nbr) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(nbr) as sum_nbr', 'AVG(nbr) as avg_nbr'], 'WHERE stats_year=:year AND stats_month=:month GROUP BY stats_month', ['year' => $this->year, 'month' => $this->month]);
			} catch (RowNotFoundException $e) {}
			$average = NumberHelper::round($info['avg_nbr'], 1);

			for ($i = 1; $i <= 12; $i++)
			{
				$this->view->assign_block_vars('month_select', [
					'C_SELECTED' => ($i == $this->month),
					'LABEL'      => $this->get_translated_month($i),
					'VALUE'      => $i
				]);
			}

			//Année maximale
			$info_year = ['max_year' => 0, 'min_year' => 0];
			try {
				$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'], '');
			} catch (RowNotFoundException $e) {}
			for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
			{
				$this->view->assign_block_vars('year_select', [
					'C_SELECTED' => ($i == $this->year),
					'LABEL'      => $i,
					'VALUE'      => $i
				]);
			}

			$array_stats = [];
			//On fait la liste des visites journalières
			$result = $this->db_querier->select("SELECT nbr, stats_day AS day
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :year AND stats_month = :month
				GROUP BY stats_day, nbr", [
					'year' => $this->year,
					'month' => $this->month
			]);
			while ($row = $result->fetch())
			{
				$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];
				$array_stats[$row['day']] = $row['nbr'];

				//On affiche les stats numériquement dans un tableau en dessous
				$this->view->assign_block_vars('value', [
					'L_DETAILS' => $date_day . '/' . sprintf('%02d', $this->month) . '/' . $this->year,
					'NBR'       => $row['nbr']
				]);
			}
			$result->dispose();

			for ($i = 1; $i <= $this->array_month[$this->month - 1]; $i++)
			{
				if (!isset($array_stats[$i]))
				{
					$array_stats[$i] = 0;
				}
			}
			$visits_chart = new StatsBarChart('visits');
			$dataset = new ChartDataset($this->lang['user.guests']);
			$dataset->set_datas($array_stats);
			$visits_chart->add_dataset($dataset);
			$visits_chart->add_average_dataset($dataset, $this->lang['common.average'], $average);

			$this->view->put_all([
				'C_STATS_VISIT'   => true,
				'C_YEAR'          => $this->year && !$this->month,
				'C_STATS_YEAR'    => true,
				'C_STATS_MONTH'   => true,
				'TYPE'            => 'visit',
				'VISIT_TOTAL'     => $visit_counter_total,
				'VISIT_DAY'       => $visit_counter_day,
				'COLSPAN'         => $this->array_month[$this->month-1] + 2,
				'SUM_NBR'         => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
				'MONTH'           => $this->get_translated_month($this->month),
				'MAX_NBR'         => $info['max_nbr'],
				'MOY_NBR'         => $average,
				'YEAR'            => $this->year,
				'U_NEXT_LINK'     => StatsUrlBuilder::home('visit', $this->next_year, $this->next_month)->rel(),
				'U_PREVIOUS_LINK' => StatsUrlBuilder::home('visit', $this->previous_year, $this->previous_month)->rel(),
				'U_YEAR'          => StatsUrlBuilder::home('visit', $this->year)->rel(),
				'VISITS_CHART'    => $visits_chart->get_html()
			]);
		}
	}

	private function build_pages_view()
	{
		$condition = 'WHERE stats_year=:year' . ($this->month || !$this->year ? ' AND stats_month=:month' : '') . ' AND pages_detail <> \'\' GROUP BY stats_month';
			
		$parameters = [
			'year'  => $this->year,
			'month' => $this->month
		];

		//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
		$info = ['max_nbr' => 0, 'min_day' => 0, 'sum_nbr' => 0, 'avg_nbr' => 0];
		try {
			$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(pages) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(pages) as sum_nbr', 'AVG(pages) as avg_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'], $condition, $parameters);
		} catch (RowNotFoundException $e) {}

		//On affiche les visiteurs totaux et du jour
		$pages_total = $this->db_querier->get_column_value(StatsSetup::$stats_table, 'SUM(pages)', '');
		$pages_day = array_sum(StatsSaver::retrieve_stats('pages')) + 1;
		$pages_total = $pages_total + $pages_day;
		$pages_day = !empty($pages_day) ? $pages_day : 1;

		$this->view->put_all([
			'C_STATS_VISIT'   => true,
			'C_STATS_PAGES'   => true,
			'C_STATS_YEAR'    => true,
			'TYPE'            => 'pages',
			'VISIT_TOTAL'     => $pages_total,
			'VISIT_DAY'       => $pages_day,
			'YEAR'            => $this->year
		]);

		if ($this->year_requested && !$this->month_requested) //Visites par mois classées par ans.
		{
			//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
			$info = ['max_nbr' => 0, 'sum_nbr' => 0, 'nbr_month' => 0];
			try {
				$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(pages) as max_nbr', 'SUM(pages) as sum_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'], 'WHERE stats_year = :year AND pages_detail <> \'\' GROUP BY stats_year', ['year' => $this->year]);
			} catch (RowNotFoundException $e) {}
			$average = !empty($info['nbr_month']) ? NumberHelper::round($info['sum_nbr']/$info['nbr_month'], 1) : 0;

			//Année maximale
			$info_year = ['max_year' => 0, 'min_year' => 0];
			try {
				$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'], '');
			} catch (RowNotFoundException $e) {}

			for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
			{
				$this->view->assign_block_vars('year_select', [
					'C_SELECTED' => ($i == $this->year),
					'LABEL'      => $i,
					'VALUE'      => $i
				]);
			}

			$array_stats = [];
			//On fait la liste des visites journalières
			$result = $this->db_querier->select("SELECT stats_month, SUM(pages) AS total
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :year
				GROUP BY stats_month", [
					'year' => $this->year
			]);
			while ($row = $result->fetch())
			{
				//On affiche les stats numériquement dans un tableau en dessous
				$this->view->assign_block_vars('value', [
					'C_DETAILS_LINK' => true,
					'U_DETAILS'      => StatsUrlBuilder::home('pages', $this->year, $row['stats_month'])->rel(),
					'L_DETAILS'      => $this->get_translated_month($row['stats_month']),
					'NBR'            => $row['total']
				]);
				$array_stats[$this->get_translated_month($row['stats_month'])] = $row['total'];
			}
			$pages_chart = new StatsBarChart('pages');
			$dataset = new ChartDataset($this->lang['common.pages']);
			$dataset->set_datas($array_stats);
			$pages_chart->add_dataset($dataset);
			$pages_chart->add_average_dataset($dataset, $this->lang['common.average'], $average);

			$this->view->put_all([
				'COLSPAN'         => 13,
				'SUM_NBR'         => $info['sum_nbr'],
				'MAX_NBR'         => $info['max_nbr'],
				'MOY_NBR'         => $average,
				'U_NEXT_LINK'     => StatsUrlBuilder::home('pages', $this->next_year)->rel(),
				'U_PREVIOUS_LINK' => StatsUrlBuilder::home('pages', $this->previous_year)->rel(),
				'VISITS_CHART'    => $pages_chart->get_html()
			]);
			$result->dispose();
		}
		else
		{
			$average = NumberHelper::round($info['avg_nbr'], 1);
			for ($i = 1; $i <= 12; $i++)
			{
				$this->view->assign_block_vars('month_select', [
					'C_SELECTED' => ($i == $this->month),
					'LABEL'      => $this->get_translated_month($i),
					'VALUE'      => $i
				]);
			}

			//Année maximale
			$info_year = ['max_year' => 0, 'min_year' => 0];
			try {
				$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, ['MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'], '');
			} catch (RowNotFoundException $e) {}
			for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
			{
				$this->view->assign_block_vars('year_select', [
					'C_SELECTED' => ($i == $this->year),
					'LABEL'      => $i,
					'VALUE'      => $i
				]);
			}

			//On fait la liste des visites journalières
			$result = $this->db_querier->select("SELECT pages, stats_day, stats_month, stats_year
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :stats_year AND stats_month = :stats_month
				GROUP BY stats_day, pages, stats_month, stats_year", [
					'stats_year' => $this->year,
					'stats_month' => $this->month,
			]);

			$array_stats = [];
			while ($row = $result->fetch())
			{
				$date_day = ($row['stats_day'] < 10) ? 0 . $row['stats_day'] : $row['stats_day'];
				$array_stats[$row['stats_day']] = $row['pages'];

				//On affiche les stats numériquement dans un tableau en dessous
				$this->view->assign_block_vars('value', [
					'U_DETAILS' => StatsUrlBuilder::home('pages', $row['stats_year'], $row['stats_month'], $row['stats_day'])->rel(),
					'L_DETAILS' => $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'],
					'NBR'       => $row['pages']
				]);
			}
			$pages_chart = new StatsBarChart('pages');
			$dataset = new ChartDataset($this->lang['common.pages']);
			$dataset->set_datas($array_stats);
			$pages_chart->add_dataset($dataset);
			$pages_chart->add_average_dataset($dataset, $this->lang['common.average'], $average);
			
			$this->view->put_all([
				'C_STATS_MONTH'   => true,
				'COLSPAN'         => $this->array_month[$this->month - 1] + 2,
				'SUM_NBR'         => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
				'MONTH'           => $this->get_translated_month($this->month),
				'MAX_NBR'         => $info['max_nbr'],
				'MOY_NBR'         => $average,
				'U_NEXT_LINK'     => StatsUrlBuilder::home('pages', $this->next_year, $this->next_month)->rel(),
				'U_PREVIOUS_LINK' => StatsUrlBuilder::home('pages', $this->previous_year, $this->previous_month)->rel(),
				'U_YEAR'          => StatsUrlBuilder::home('pages', $this->year)->rel(),
				'VISITS_CHART'    => $pages_chart->get_html()
			]);
			$result->dispose();
			
		}
	}

	private function build_referers_view(HTTPRequestCustom $request)
	{
		$nbr_referer = $this->db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = :type', ['type' => 0], 'DISTINCT(url)');

		$page = $request->get_getint('page', 1);
		$pagination = new ModulePagination($page, $nbr_referer, $this->config->get_items_per_page());
		$pagination->set_url(StatsUrlBuilder::table('referer', '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$result = $this->db_querier->select("SELECT id, COUNT(*) as count, url, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
			FROM " . PREFIX . "stats_referer
			WHERE type = 0
			GROUP BY url, id, relative_url, nbr_day
			ORDER BY total_visit DESC
			LIMIT :number_items_per_page OFFSET :display_from", [
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			]);
		while ($row = $result->fetch())
		{
			$trend_parameters = StatsDisplayService::get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

			$this->view->assign_block_vars('referer_list', [
				'ID'              => $row['id'],
				'URL'             => $row['url'],
				'NBR_LINKS'       => $row['count'],
				'TOTAL_VISIT'     => $row['total_visit'],
				'AVERAGE_VISIT'   => $trend_parameters['average'],
				'LAST_UPDATE'     => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
				'C_TREND_PICTURE' => !empty($trend_parameters['picture']),
				'TREND_PICTURE'   => $trend_parameters['picture'],
				'TREND_LABEL'     => '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)'
			]);
		}
		$result->dispose();

		$this->view->put_all([
			'C_STATS_REFERER' => true,
			'C_REFERERS'      => $nbr_referer,
			'C_PAGINATION'    => $pagination->has_several_pages(),
			'PAGINATION'      => $pagination->display(),
		]);
	}

	private function build_robots_view()
	{
		$array_robot = StatsSaver::retrieve_stats('robots');

		if (isset($array_robot['unknow_bot']))
		{
			$array_robot[$this->lang['common.unknown']] = $array_robot['unknow_bot'];
			unset($array_robot['unknow_bot']);
		}
		$robots_visits = [];
		$robots_visits_number = 0;
		foreach ($array_robot as $key => $value)
		{
			$robots_visits[$key] = is_array($value) ? $value['visits_number'] : $value;
			$robots_visits_number += $robots_visits[$key];
		}
		$robots_chart = new StatsPieChart('robots');
		$dataset = new ChartDataset($this->lang['stats.hits']);
		$dataset->set_datas($robots_visits);
		$robots_chart->add_dataset($dataset);

		if ($robots_visits_number)
		{
			foreach ($robots_visits as $key => $value)
			{
				$this->view->assign_block_vars('list', [
					'C_BOT_DETAILS' => $key != $this->lang['common.unknown'],
					'COLOR'         => $dataset->get_color_label($key),
					'VISITS_NUMBER' => $value,
					'LAST_SEEN'     => is_array($array_robot[$key]) && isset($array_robot[$key]['last_seen']) ? Date::to_format($array_robot[$key]['last_seen'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['common.undetermined'],
					'PERCENT'       => NumberHelper::round($value/$robots_visits_number*100, 1),
					'L_NAME'        => $key,
					'U_BOT_DETAILS' => 'https://udger.com/resources/ua-list/bot-detail?bot=' . urlencode($key)
				]);
			}
		}

		$this->view->put_all([
			'C_STATS_ROBOTS' => true,
			'C_ROBOTS_DATA'  => $robots_visits_number,
			'ROBOTS_CHART'   => $robots_chart->get_html()
		]);
	}

	private function build_keyword_view(HTTPRequestCustom $request)
	{
		$nbr_keyword = $this->db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = :type', ['type' => 1], 'DISTINCT(relative_url)');

		$page = $request->get_getint('page', 1);
		$pagination = new ModulePagination($page, $nbr_keyword, $this->config->get_items_per_page());
		$pagination->set_url(StatsUrlBuilder::table('keyword', '%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$result = $this->db_querier->select("SELECT id, count(*) as count, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
			FROM " . PREFIX . "stats_referer
			WHERE type = 1
			GROUP BY relative_url, id, nbr_day
			ORDER BY total_visit DESC
			LIMIT :number_items_per_page OFFSET :display_from", [
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
		]);

		while ($row = $result->fetch())
		{
			$trend_parameters = StatsDisplayService::get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);
			$this->view->assign_block_vars('keyword_list', [
				'ID'              => $row['id'],
				'KEYWORD'         => $row['relative_url'],
				'NBR_LINKS'       => $row['count'],
				'TOTAL_VISIT'     => $row['total_visit'],
				'AVERAGE_VISIT'   => $trend_parameters['average'],
				'LAST_UPDATE'     => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
				'C_TREND_PICTURE' => !empty($trend_parameters['picture']),
				'TREND_PICTURE'   => $trend_parameters['picture'],
				'TREND_LABEL'     => '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)'
			]);
		}
		$result->dispose();

		$this->view->put_all([
			'C_STATS_KEYWORD' => true,
			'C_KEYWORDS'      => $nbr_keyword,
			'C_PAGINATION'    => $pagination->has_several_pages(),
			'PAGINATION'      => $pagination->display(),
		]);
	}

	private function build_pie_stats_view($stats_menu)
	{
		$path = $stats_menu . '/';
		$array_stats_info = LangLoader::get($stats_menu, 'stats');
		$array_stats = StatsSaver::retrieve_stats($stats_menu);
		arsort($array_stats);

		$array_stats_displayed = [];
		$array_stats_graph = [];

		foreach ($array_stats as $browser => $value) {
			if ($browser === 'other')
			{
				$img = !empty($array_stats_info['other'][1]) ? '<img src="'. TPL_PATH_TO_ROOT . '/images/stats/' . $array_stats_info['other'][1] . '" alt="' . $this->lang['common.other'] . '" />' : '<img src="' . TPL_PATH_TO_ROOT . '/images/stats/other.png" alt="' . $this->lang['common.other'] . '" />';
				$stats_browser = $this->lang['common.other'];
			}
			else
			{
				$stats_browser = $array_stats_info[$browser][0];
				$img = !empty($array_stats_info[$browser][1]) ? '<img src="' . TPL_PATH_TO_ROOT . '/images/stats/' . $path . $array_stats_info[$browser][1] . '" alt="' . $array_stats_info[$browser][0] . '" />' : '-';
			}
			$array_stats_displayed[$browser] = [
				'img' => $img,
				'stat' => $value,
				'browser' => $stats_browser
			];
			$array_stats_graph[$stats_browser] = $value;
		}

		$chart = new StatsPieChart('pie');
		$dataset = new ChartDataset($this->lang['stats.hits']);
		$dataset->set_datas($array_stats_graph);
		$chart->add_dataset($dataset);

		$this->view->put_all([
			'CHART' => $chart->get_html(),
			'C_STATS_' . strtoupper($stats_menu) => true
		]);
		$total_stats = array_sum($array_stats_graph);

		//Affichage des données
		foreach ($array_stats_displayed as $browser)
		{
			$this->view->assign_block_vars('list', [
				'COLOR'   => $dataset->get_color_label($browser['browser']),
				'IMG'     => $browser['img'],
				'L_NAME'  => $browser['browser'],
				'PERCENT' => NumberHelper::round($browser['stat']/$total_stats * 100,1)
			]);
		}
	}

	private function get_translated_month($month_number):string {
		$months = [
			'1'  => 'january',
			'2'  => 'february',
			'3'  => 'march',
			'4'  => 'april',
			'5'  => 'may',
			'6'  => 'june',
			'7'  => 'july',
			'8'  => 'august',
			'9'  => 'september',
			'10' => 'october',
			'11' => 'november',
			'12' => 'december'
		];
		return $this->lang['date.' . $months[$month_number]];
	}

	private function check_authorizations()
	{
		if (!StatsAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		return new FileTemplate('stats/stats.tpl');
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$section = $request->get_string('section', '');
		$page    = $request->get_getint('page', 1);
		$year    = $request->get_getint('year', 0);
		$month   = $request->get_getint('month', 0);
		$day     = $request->get_getint('day', 0);
		
		$page_title = $this->lang['stats.website'];
		$page_title = ($section == 'visit') ? $this->lang['user.guests'] : $page_title;
		$page_title = ($section == 'pages') ? $this->lang['common.pages'] : $page_title;
		$page_title = ($section == 'referer') ? $this->lang['stats.referers'] : $page_title;
		$page_title = ($section == 'keyword') ? $this->lang['common.keywords'] : $page_title;
		$page_title = ($section == 'members') ? $this->lang['user.members'] : $page_title;
		$page_title = ($section == 'browser') ? $this->lang['stats.browsers'] : $page_title;
		$page_title = ($section == 'os') ? $this->lang['stats.os'] : $page_title;
		$page_title = ($section == 'lang') ? $this->lang['stats.countries'] : $page_title;
		$page_title = ($section == 'bot') ? $this->lang['stats.robots'] : $page_title;

		$response = new SiteDisplayResponse($this->view);
		
		$graphical_environment = $response->get_graphical_environment();

		$graphical_environment->set_page_title($page_title, self::get_module_configuration()->get_name(), $page);

		$graphical_environment->get_seo_meta_data()->set_canonical_url(StatsUrlBuilder::home($section, $year, $month, $day));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module_configuration()->get_name(), StatsUrlBuilder::home());
		$breadcrumb->add($page_title, StatsUrlBuilder::home($section));

		return $response;
	}

	public static function get_view()
	{
		$object = new self('stats');
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
