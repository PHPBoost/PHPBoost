<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 21
 * @since       PHPBoost 6.0 - 2021 11 23
*/

class StatsDisplayController extends DefaultModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$db_querier = PersistenceContext::get_querier();
		
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
			$robots_visits = array();
			$robots_visits_number = 0;
			foreach ($array_robot as $key => $value)
			{
				$robots_visits[$key] = is_array($value) ? $value['visits_number'] : $value;
				$robots_visits_number += $robots_visits[$key];
			}

			if ($robots_visits_number)
			{
				$Stats = new ImagesStats();
				$Stats->load_data($robots_visits, 'ellipse');
				foreach ($Stats->data_stats as $key => $angle_value)
				{
					if (!NumberHelper::round(($angle_value/3.6), 1))
						unset($array_robot[$key]);
				}
			}

			$file = @fopen(PATH_TO_ROOT . '/stats/cache/robots.txt', 'r+');
			fwrite($file, TextHelper::serialize($array_robot));
			fclose($file);
		}
		
		$section = $request->get_string('section', '');
		
		$visit   = ($section == 'visit');
		$pages   = ($section == 'pages');
		$referer = ($section == 'referer');
		$keyword = ($section == 'keyword');
		$members = ($section == 'members');
		$browser = ($section == 'browser');
		$os      = ($section == 'os');
		$all     = ($section == 'all');
		$country = ($section == 'lang');
		$bot     = ($section == 'bot');
		
		$now = new Date();
		
		$year = $request->has_postparameter('year') ? $request->get_postint('year', '') : $request->get_getint('year', '');
		$month = $request->has_postparameter('month') ? $request->get_postint('month', '') : $request->get_getint('month', '');
		$day = $request->has_postparameter('day') ? $request->get_postint('day', '') : $request->get_getint('day', '');
		
		$current_year = NumberHelper::numeric($year ? $year : $now->get_year());
		$current_month = NumberHelper::numeric($month ? $month : $now->get_month());
		$current_day = NumberHelper::numeric($day ? $day : $now->get_day());

		$this->view->put_all(array(
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
		));

		if ($members)
		{
			$stats_cache = StatsCache::load();
			$last_user_group_color = User::get_group_color($stats_cache->get_stats_properties('last_member_groups'), $stats_cache->get_stats_properties('last_member_level'));
			$user_sex_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_sex');

			$this->view->put_all(array(
				'C_STATS_USERS'           => true,
				'C_LAST_USER_GROUP_COLOR' => !empty($last_user_group_color),
				'C_DISPLAY_SEX'           => (!empty($user_sex_field) && $user_sex_field['display']),
				'LAST_USER_DISPLAY_NAME'  => $stats_cache->get_stats_properties('last_member_login'),
				'LAST_USER_LEVEL_CLASS'   => UserService::get_level_class($stats_cache->get_stats_properties('last_member_level')),
				'LAST_USER_GROUP_COLOR'   => $last_user_group_color,
				'U_LAST_USER_PROFILE'     => UserUrlBuilder::profile($stats_cache->get_stats_properties('last_member_id'))->rel(),
				'USERS_NUMBER'            => $stats_cache->get_stats_properties('nbr_members'),
				'U_GRAPH_RESULT_THEME'    => !file_exists('../cache/theme.png') ? StatsUrlBuilder::display_themes_graph()->rel() : '../cache/theme.png',
				'U_GRAPH_RESULT_SEX'      => !file_exists('../cache/sex.png') ? StatsUrlBuilder::display_sex_graph()->rel() : '../cache/sex.png'
			));

			$stats_array = array();
			foreach (ThemesManager::get_activated_themes_map() as $theme)
			{
				$stats_array[$theme->get_id()] = PersistenceContext::get_querier()->count(DB_TABLE_MEMBER, "WHERE theme = '" . $theme->get_id() . "'");
			}

			$Stats = new ImagesStats();

			$Stats->load_data($stats_array, 'ellipse');
			foreach ($Stats->data_stats as $name => $angle_value)
			{
				$array_color = $Stats->array_allocated_color[$Stats->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
				$this->view->assign_block_vars('templates', array(
					'NBR_THEME' => NumberHelper::round(($angle_value*$Stats->nbr_entry)/360, 0),
					'COLOR'     => 'rgb(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
					'THEME'     => ($name == 'Other') ? $this->lang['common.other'] : $name,
					'PERCENT'   => NumberHelper::round(($angle_value/3.6), 1)
				));
			}

			$stats_array = array();
			$result = $db_querier->select("SELECT count(ext_field.user_sex) as compt, ext_field.user_sex
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
				$stats_array[$name] = $row['compt'];
			}
			$result->dispose();

			$Stats->color_index = 0;
			$Stats->load_data($stats_array, 'ellipse');
			foreach ($Stats->data_stats as $name => $angle_value)
			{
				$array_color = $Stats->array_allocated_color[$Stats->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
				$this->view->assign_block_vars('sex', array(
					'MEMBERS_NUMBER' => NumberHelper::round(($angle_value*$Stats->nbr_entry)/360, 0),
					'COLOR'          => 'rgb(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
					'SEX'            => ($name == 'Other') ? $this->lang['common.other'] : $name,
					'PERCENT'        => NumberHelper::round(($angle_value/3.6), 1)
				));
			}

			$i = 1;
			$result = $db_querier->select("SELECT user_id, display_name, level, user_groups, posted_msg
			FROM " . DB_TABLE_MEMBER . "
			ORDER BY posted_msg DESC
			LIMIT 10 OFFSET 0");
			while ($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['user_groups'], $row['level']);

				$this->view->assign_block_vars('top_poster', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'ID'                 => $i,
					'LOGIN'              => $row['display_name'],
					'USER_LEVEL_CLASS'   => UserService::get_level_class($row['level']),
					'USER_GROUP_COLOR'   => $user_group_color,
					'USER_POST'          => $row['posted_msg'],
					'U_USER_PROFILE'     => UserUrlBuilder::profile($row['user_id'])->rel(),
				));

				$i++;
			}
			$result->dispose();
		}
		elseif ($visit) //Visites par jour classées par mois.
		{
			//On affiche les visiteurs totaux et du jour
			$visit_counter = array('nbr_ip' => 0, 'total' => 0);
			try {
				$visit_counter = $db_querier->select_single_row(DB_TABLE_VISIT_COUNTER, array('ip AS nbr_ip', 'total'), 'WHERE id = :id', array('id' => 1));
			} catch (RowNotFoundException $e) {}

			$visit_counter_total = !empty($visit_counter['nbr_ip']) ? $visit_counter['nbr_ip'] : 1;
			$visit_counter_day = !empty($visit_counter['total']) ? $visit_counter['total'] : 1;

			$year_requested = (bool)$year;
			$month_requested = (bool)$month;
			$month = $month ? $month : $current_month;
			$year = $year ? $year : $current_year;

			//Gestion des mois pour s'adapter au array défini dans lang/{locale}/date-lang.php
			$array_l_months = array($this->lang['date.january'], $this->lang['date.february'], $this->lang['date.march'], $this->lang['date.april'], $this->lang['date.may'], $this->lang['date.june'],
			$this->lang['date.july'], $this->lang['date.august'], $this->lang['date.september'], $this->lang['date.october'], $this->lang['date.november'], $this->lang['date.december']);

			if ($year_requested && !$month_requested) //Visites par mois classées par ans.
			{
				//Années précédente et suivante
				$next_year = $year + 1;
				$previous_year = $year - 1;

				//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
				$info = array('max_month' => 0, 'sum_month' => 0, 'nbr_month' => 0);
				try {
					$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(nbr) as max_month', 'SUM(nbr) as sum_month', 'COUNT(DISTINCT(stats_month)) as nbr_month'), 'WHERE stats_year=:year GROUP BY stats_year', array('year' => $year));
				} catch (RowNotFoundException $e) {}

				$this->view->put_all(array(
					'C_STATS_VISIT'   => true,
					'TYPE'            => 'visit',
					'VISIT_TOTAL'     => $visit_counter_total,
					'VISIT_DAY'       => $visit_counter_day,
					'YEAR'            => $year,
					'COLSPAN'         => 14,
					'SUM_NBR'         => $info['sum_month'],
					'MAX_NBR'         => $info['max_month'],
					'MOY_NBR'         => !empty($info['nbr_month']) ? NumberHelper::round($info['sum_month']/$info['nbr_month'], 1) : 1,
					'U_NEXT_LINK'     => StatsUrlBuilder::home('visit', $next_year)->rel(),
					'U_PREVIOUS_LINK' => StatsUrlBuilder::home('visit', $previous_year)->rel(),
					'U_YEAR'          => StatsUrlBuilder::home('visit', $year)->rel()
				));

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$this->view->assign_block_vars('year_select', array(
						'C_SELECTED' => ($i == $year),
						'LABEL'      => $i,
						'VALUE'      => $i
					));
				}
				$this->view->put_all(array(
					'C_STATS_YEAR' => true
				));

				if (@extension_loaded('gd'))
				{
					$this->view->put_all(array(
						'U_GRAPH_RESULT' => StatsUrlBuilder::display_visits_year_graph($year)->rel()
					));

					//On fait la liste des visites journalières
					$result = $db_querier->select("SELECT stats_month, SUM(nbr) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $year
					));
					while ($row = $result->fetch())
					{
						//On affiche les stats numériquement dans un tableau en dessous
						$this->view->assign_block_vars('value', array(
							'C_DETAILS_LINK' => true,
							'U_DETAILS'      => StatsUrlBuilder::home('visit', $year, $row['stats_month'])->rel(),
							'L_DETAILS'      => $array_l_months[$row['stats_month'] - 1],
							'NBR'            => $row['total']
						));
					}
					$result->dispose();
				}
				else
				{
					$max_month = 1;
					$result = $db_querier->select("SELECT SUM(nbr) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $year
					));
					while ($row = $result->fetch())
					{
						$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
					}
					$result->dispose();

					$this->view->put_all(array(
						'C_STATS_NO_GD' => true
					));

					$i = 1;
					$last_month = 1;
					$months_not_empty = array();
					$result = $db_querier->select("SELECT stats_month, SUM(nbr) AS total
					FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $year
					));
					while ($row = $result->fetch())
					{
						$diff = 0;
						if ($row['stats_month'] != $i)
						{
							$diff = $row['stats_month'] - $i;
							for ($j = 0; $j < $diff; $j++)
							{
								$this->view->assign_block_vars('values', array(
									'HEIGHT' => 0
								));
							}
						}

						$i += $diff;

						//On a des stats pour ce mois-ci, on l'enregistre
						array_push($months_not_empty, $row['stats_month']);

						//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
						$height = $row['total'] / $max_month * 200;

						$this->view->assign_block_vars('values', array(
							'HEIGHT' => ceil($height)
						));

						$this->view->assign_block_vars('values.head', array(
						));

						//On affiche les stats numériquement dans un tableau en dessous
						$this->view->assign_block_vars('value', array(
							'C_DETAILS_LINK' => true,
							'U_DETAILS'      => StatsUrlBuilder::home('visit', $year, $row['stats_month'])->rel(),
							'L_DETAILS'      => $array_l_months[$row['stats_month'] - 1],
							'NBR'            => $row['total']
						));

						$last_month = $row['stats_month'];
						$i++;
					}
					$result->dispose();

					//Génération des td manquants.
					$date_day = isset($date_day) ? $date_day : 1;
					for	($i = $last_month; $i < 12; $i++)
					{
						$this->view->assign_block_vars('end_td', array(
							'END_TD' => $i
						));
					}
					//On liste les jours en dessous du graphique
					$i = 1;
					foreach ($array_l_months as $value)
					{
						$this->view->assign_block_vars('legend', array(
							'C_LEGEND' => (in_array($i, $months_not_empty)),
							'L_LEGEND' => TextHelper::substr($value, 0, 3),
							'U_LEGEND' => StatsUrlBuilder::home('visit', $year, $i)->rel()
						));
						$i++;
					}
				}
			}
			else
			{
				//Nombre de jours pour chaque mois (gestion des années bissextiles)
				$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
				$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);

				//Mois précédent et suivant
				$next_month = ($month < 12) ? $month + 1 : 1;
				$next_year = ($month < 12) ? $year : $year + 1;
				$previous_month = ($month > 1) ? $month - 1 : 12;
				$previous_year = ($month > 1) ? $year : $year - 1;

				//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
				$info = array('max_nbr' => 0, 'min_day' => 0, 'sum_nbr' => 0, 'avg_nbr' => 0);
				try {
					$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(nbr) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(nbr) as sum_nbr', 'AVG(nbr) as avg_nbr'), 'WHERE stats_year=:year AND stats_month=:month GROUP BY stats_month', array('year' => $year, 'month' => $month));
				} catch (RowNotFoundException $e) {}

				$this->view->put_all(array(
					'C_STATS_VISIT'   => true,
					'C_YEAR'          => $year && !$month,
					'TYPE'            => 'visit',
					'VISIT_TOTAL'     => $visit_counter_total,
					'VISIT_DAY'       => $visit_counter_day,
					'COLSPAN'         => $array_month[$month-1] + 2,
					'SUM_NBR'         => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
					'MONTH'           => $array_l_months[$month - 1],
					'MAX_NBR'         => $info['max_nbr'],
					'MOY_NBR'         => NumberHelper::round($info['avg_nbr'], 1),
					'YEAR'            => $year,
					'U_NEXT_LINK'     => StatsUrlBuilder::home('visit', $next_year, $next_month)->rel(),
					'U_PREVIOUS_LINK' => StatsUrlBuilder::home('visit', $previous_year, $previous_month)->rel(),
					'U_YEAR'          => StatsUrlBuilder::home('visit', $year)->rel()
				));

				for ($i = 1; $i <= 12; $i++)
				{
					$this->view->assign_block_vars('month_select', array(
						'C_SELECTED' => ($i == $month),
						'LABEL'      => $array_l_months[$i - 1],
						'VALUE'      => $i
					));
				}

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$this->view->assign_block_vars('year_select', array(
						'C_SELECTED' => ($i == $year),
						'LABEL'      => $i,
						'VALUE'      => $i
					));
				}
				$this->view->put_all(array(
					'C_STATS_YEAR'  => true,
					'C_STATS_MONTH' => true
				));

				if (@extension_loaded('gd'))
				{
					$this->view->put_all(array(
						'U_GRAPH_RESULT' => StatsUrlBuilder::display_visits_month_graph($year, $month)->rel()
					));

					//On fait la liste des visites journalières
					$result = $db_querier->select("SELECT nbr, stats_day AS day
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :year AND stats_month = :month
						GROUP BY stats_day, nbr", array(
							'year' => $year,
							'month' => $month
					));
					while ($row = $result->fetch())
					{
						$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];

						//On affiche les stats numériquement dans un tableau en dessous
						$this->view->assign_block_vars('value', array(
							'L_DETAILS' => $date_day . '/' . sprintf('%02d', $month) . '/' . $year,
							'NBR'       => $row['nbr']
						));
					}
					$result->dispose();
				}
				else
				{
					//Mois selectionné.
					if (!empty($month) && !empty($year))
					{
						$this->view->put_all(array(
							'C_STATS_NO_GD' => true
						));

						//On rajoute un 0 devant tous les mois plus petits que 10
						$month = ($month < 10) ? '0' . $month : $month;
						unset($i);

						//On fait la liste des visites journalières
						$j = 0;
						$result = $db_querier->select("SELECT nbr, stats_day AS day
							FROM " . StatsSetup::$stats_table . "
							WHERE stats_year = :year AND stats_month = :month
							ORDER BY stats_day", array(
								'year' => $year,
								'month' => $month
						));
						while ($row = $result->fetch())
						{
							//Complétion des jours précédent le premier enregistrement du mois.
							if ($j == 0)
							{
								for ($z = 1; $z < $row['day']; $z++)
								{
									$this->view->assign_block_vars('values', array(
										'HEIGHT' => 0
									));
								}
								$j++;
							}
							//Remplissage des trous possibles entre les enregistrements.
							$i = !isset($i) ? $row['day'] : $i;
							$diff = 0;
							if ($row['day'] != $i)
							{
								$diff = $row['day'] - $i;
								for ($j = 0; $j < $diff; $j++)
								{
									$this->view->assign_block_vars('values', array(
										'HEIGHT' => 0
									));
								}
							}
							$i += $diff;

							//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
							$height = ($row['nbr'] / $info['max_nbr']) * 200;

							$this->view->assign_block_vars('values', array(
								'HEIGHT' => ceil($height)
							));

							$this->view->assign_block_vars('values.head', array(
							));

							$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];

							//On affiche les stats numériquement dans un tableau en dessous
							$this->view->assign_block_vars('value', array(
								'L_DETAILS' => $date_day . '/' . sprintf('%02d', $month) . '/' . $year,
								'NBR'       => $row['nbr']
							));

							$i++;
						}
						$result->dispose();

						//Génération des td manquants.
						$date_day = isset($date_day) ? $date_day : 1;
						for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
						{
							$this->view->assign_block_vars('end_td', array(
								'END_TD' => $i
							));
						}

						//On liste les jours en dessous du graphique
						for ($i = 1; $i <= $array_month[$month - 1]; $i++)
						{
							$this->view->assign_block_vars('legend', array(
								'L_LEGEND' => $i
							));
						}
					}
				}
			}
		}
		elseif ($pages) //Pages par jour classées par mois.
		{
			$year_requested = (bool)$year;
			$month_requested = (bool)$month;
			$month = $month ? $month : $current_month;
			$year = $year ? $year : $current_year;
			
			$condition = 'WHERE stats_year=:year' . ($month || !$year ? ' AND stats_month=:month' : '') . ' AND pages_detail <> \'\' GROUP BY stats_month';
			
			$parameters = array(
				'year'  => $year,
				'month' => $month
			);

			//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
			$info = array('max_nbr' => 0, 'min_day' => 0, 'sum_nbr' => 0, 'avg_nbr' => 0);
			try {
				$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(pages) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(pages) as sum_nbr', 'AVG(pages) as avg_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'), $condition, $parameters);
			} catch (RowNotFoundException $e) {}

			//On affiche les visiteurs totaux et du jour
			$pages_total = $db_querier->get_column_value(StatsSetup::$stats_table, 'SUM(pages)', '');
			$pages_day = array_sum(StatsSaver::retrieve_stats('pages')) + 1;
			$pages_total = $pages_total + $pages_day;
			$pages_day = !empty($pages_day) ? $pages_day : 1;

			//Gestion des mois pour s'adapter au array défini dans lang/{locale}/date-lang.php
			$array_l_months = array($this->lang['date.january'], $this->lang['date.february'], $this->lang['date.march'], $this->lang['date.april'], $this->lang['date.may'], $this->lang['date.june'],
			$this->lang['date.july'], $this->lang['date.august'], $this->lang['date.september'], $this->lang['date.october'], $this->lang['date.november'], $this->lang['date.december']);

			if ($year_requested && !$month_requested) //Visites par mois classées par ans.
			{
				//Années précédente et suivante
				$next_year = $year + 1;
				$previous_year = $year - 1;

				//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
				$info = array('max_nbr' => 0, 'sum_nbr' => 0, 'nbr_month' => 0);
				try {
					$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(pages) as max_nbr', 'SUM(pages) as sum_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'), 'WHERE stats_year = :year AND pages_detail <> \'\' GROUP BY stats_year', array('year' => $year));
				} catch (RowNotFoundException $e) {}

				$this->view->put_all(array(
					'C_STATS_VISIT'   => true,
					'C_STATS_PAGES'   => true,
					'TYPE'            => 'pages',
					'VISIT_TOTAL'     => $pages_total,
					'VISIT_DAY'       => $pages_day,
					'YEAR'            => $year,
					'COLSPAN'         => 13,
					'SUM_NBR'         => $info['sum_nbr'],
					'MAX_NBR'         => $info['max_nbr'],
					'MOY_NBR'         => !empty($info['nbr_month']) ? NumberHelper::round($info['sum_nbr']/$info['nbr_month'], 1) : 0,
					'U_NEXT_LINK'     => StatsUrlBuilder::home('pages', $next_year)->rel(),
					'U_PREVIOUS_LINK' => StatsUrlBuilder::home('pages', $previous_year)->rel()
				));

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}

				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$this->view->assign_block_vars('year_select', array(
						'C_SELECTED' => ($i == $year),
						'LABEL'      => $i,
						'VALUE'      => $i
					));
				}
				$this->view->put_all(array(
					'C_STATS_YEAR' => true
				));

				if (@extension_loaded('gd'))
				{
					$this->view->put_all(array(
						'U_GRAPH_RESULT' => StatsUrlBuilder::display_pages_year_graph($year)->rel()
					));

					//On fait la liste des visites journalières
					$result = $db_querier->select("SELECT  stats_month, SUM(pages) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :year
						GROUP BY stats_month", array(
							'year' => $year
					));
					while ($row = $result->fetch())
					{
						//On affiche les stats numériquement dans un tableau en dessous
						$this->view->assign_block_vars('value', array(
							'C_DETAILS_LINK' => true,
							'U_DETAILS'      => StatsUrlBuilder::home('pages', $year, $row['stats_month'])->rel(),
							'L_DETAILS'      => $array_l_months[$row['stats_month'] - 1],
							'NBR'            => $row['total']
						));
					}
					$result->dispose();
				}
				else
				{
					$result = $db_querier->select("SELECT SUM(nbr) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $year
					));
					$max_month = 1;
					while ($row = $result->fetch())
					{
						$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
					}

					$this->view->put_all(array(
						'C_STATS_NO_GD' => true
					));

					$i = 1;
					$last_month = 1;
					$months_not_empty = array();
					$result = $db_querier->select("SELECT stats_month, SUM(pages) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $year
					));
					while ($row = $result->fetch())
					{
						$diff = 0;
						if ($row['stats_month'] != $i)
						{
							$diff = $row['stats_month'] - $i;
							for ($j = 0; $j < $diff; $j++)
							{
								$this->view->assign_block_vars('values', array(
									'HEIGHT' => 0
								));
							}
						}

						$i += $diff;

						//On a des stats pour ce mois-ci, on l'enregistre
						array_push($months_not_empty, $row['stats_month']);

						//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
						$height = $row['total'] / $info['max_month'] * 200;

						$this->view->assign_block_vars('months', array(
							'HEIGHT' => ceil($height)
						));

						$this->view->assign_block_vars('values.head', array(
						));

						//On affiche les stats numériquement dans un tableau en dessous
						$this->view->assign_block_vars('value', array(
							'C_DETAILS_LINK' => true,
							'U_DETAILS'      => StatsUrlBuilder::home('pages', $year, $row['stats_month'])->rel(),
							'L_DETAILS'      => $array_l_months[$row['stats_month'] - 1],
							'NBR'            => $row['total']
						));

						$last_month = $row['stats_month'];
						$i++;
					}
					$result->dispose();

					//Génération des td manquants.
					$date_day = isset($date_day) ? $date_day : 1;
					for	($i = $last_month; $i < 12; $i++)
					{
						$this->view->assign_block_vars('end_td', array(
							'END_TD' => $i
						));
					}
					//On liste les jours en dessous du graphique
					$i = 1;
					foreach ($array_l_months as $value)
					{
						$this->view->assign_block_vars('legend', array(
							'C_LEGEND' => (in_array($i, $months_not_empty)),
							'L_LEGEND' => TextHelper::substr($value, 0, 3),
							'U_LEGEND' => StatsUrlBuilder::home('pages', $year, $i)->rel()
						));
						$i++;
					}
				}
			}
			else
			{
				//Nombre de jours pour chaque mois (gestion des années bissextiles)
				$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
				$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);

				//Mois précédent et suivant
				$next_month = ($month < 12) ? $month + 1 : 1;
				$next_year = ($month < 12) ? $year : $year + 1;
				$previous_month = ($month > 1) ? $month - 1 : 12;
				$previous_year = ($month > 1) ? $year : $year - 1;

				$this->view->put_all(array(
					'C_STATS_VISIT'   => true,
					'C_STATS_PAGES'   => true,
					'TYPE'            => 'pages',
					'VISIT_TOTAL'     => $pages_total,
					'VISIT_DAY'       => $pages_day,
					'COLSPAN'         => $array_month[$month - 1] + 2,
					'SUM_NBR'         => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
					'MONTH'           => $array_l_months[$month - 1],
					'MAX_NBR'         => $info['max_nbr'],
					'MOY_NBR'         => NumberHelper::round($info['avg_nbr'], 1),
					'YEAR'            => $year,
					'U_NEXT_LINK'     => StatsUrlBuilder::home('pages', $next_year, $next_month)->rel(),
					'U_PREVIOUS_LINK' => StatsUrlBuilder::home('pages', $previous_year, $previous_month)->rel(),
					'U_YEAR'          => StatsUrlBuilder::home('pages', $year)->rel()
				));

				for ($i = 1; $i <= 12; $i++)
				{
					$this->view->assign_block_vars('month_select', array(
						'C_SELECTED' => ($i == $month),
						'LABEL'      => $array_l_months[$i - 1],
						'VALUE'      => $i
					));
				}

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$this->view->assign_block_vars('year_select', array(
						'C_SELECTED' => ($i == $year),
						'LABEL'      => $i,
						'VALUE'      => $i
					));
				}
				$this->view->put_all(array(
					'C_STATS_YEAR'  => true,
					'C_STATS_MONTH' => true
				));

				if (@extension_loaded('gd'))
				{
					$this->view->put_all(array(
						'U_GRAPH_RESULT' => StatsUrlBuilder::display_pages_month_graph($year, $month)->rel()
					));

					//On fait la liste des visites journalières
					$result = $db_querier->select("SELECT pages, stats_day, stats_month, stats_year
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year AND stats_month = :stats_month
						GROUP BY stats_day, pages, stats_month, stats_year", array(
							'stats_year' => $year,
							'stats_month' => $month,
					));
					while ($row = $result->fetch())
					{
						$date_day = ($row['stats_day'] < 10) ? 0 . $row['stats_day'] : $row['stats_day'];

						//On affiche les stats numériquement dans un tableau en dessous
						$this->view->assign_block_vars('value', array(
							'U_DETAILS' => StatsUrlBuilder::home('pages', $row['stats_year'], $row['stats_month'], $row['stats_day'])->rel(),
							'L_DETAILS' => $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'],
							'NBR'       => $row['pages']
						));
					}
					$result->dispose();
				}
				else
				{
					//Mois selectionné.
					if (!empty($month) && !empty($year))
					{
						$this->view->put_all(array(
							'C_STATS_NO_GD' => true
						));

						//On rajoute un 0 devant tous les mois plus petits que 10
						$month = ($month < 10) ? '0' . $month : $month;
						unset($i);

						//On fait la liste des visites journalières
						$j = 0;
						$result = $db_querier->select("SELECT pages, stats_day AS day, stats_month, stats_year
							FROM " . StatsSetup::$stats_table . "
							WHERE stats_year = :stats_year AND stats_month = :stats_month
							GROUP BY stats_day, pages, stats_month, stats_year", array(
								'stats_year' => $year,
								'stats_month' => $month,
						));
						while ($row = $result->fetch())
						{
							//Complétion des jours précédent le premier enregistrement du mois.
							if ($j == 0)
							{
								for ($z = 1; $z < $row['day']; $z++)
								{
									$this->view->assign_block_vars('days', array(
										'HEIGHT' => 0
									));
								}
								$j++;
							}
							//Remplissage des trous possibles entre les enregistrements.
							$i = !isset($i) ? $row['day'] : $i;
							$diff = 0;
							if ($row['day'] != $i)
							{
								$diff = $row['day'] - $i;
								for ($j = 0; $j < $diff; $j++)
								{
									$this->view->assign_block_vars('days', array(
										'HEIGHT' => 0
									));
								}
							}
							$i += $diff;

							//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
							$height = ($row['pages'] / $info['max_nbr']) * 200;

							$this->view->assign_block_vars('values', array(
								'HEIGHT' => ceil($height)
							));

							$this->view->assign_block_vars('values.head', array(
							));

							$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];

							//On affiche les stats numériquement dans un tableau en dessous
							$this->view->assign_block_vars('value', array(
								'L_DETAILS' => $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'],
								'NBR'       => $row['pages']
							));

							$i++;
						}
						$result->dispose();

						//Génération des td manquants.
						$date_day = isset($date_day) ? $date_day : 1;
						for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
						{
							$this->view->assign_block_vars('end_td', array(
								'END_TD' => $i
							));
						}

						//On liste les jours en dessous du graphique
						for ($i = 1; $i <= $array_month[$month - 1]; $i++)
						{
							$this->view->assign_block_vars('legend', array(
								'L_LEGEND' => $i
							));
						}
					}
				}
			}
		}
		elseif ($referer)
		{
			$nbr_referer = $db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = :type', array('type' => 0), 'DISTINCT(url)');

			$page = $request->get_getint('page', 1);
			$pagination = new ModulePagination($page, $nbr_referer, $this->config->get_items_per_page());
			$pagination->set_url(StatsUrlBuilder::table('referer', '%d'));

			if ($pagination->current_page_is_empty() && $page > 1)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			$result = $db_querier->select("SELECT id, COUNT(*) as count, url, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
				FROM " . PREFIX . "stats_referer
				WHERE type = 0
				GROUP BY url, id, relative_url, nbr_day
				ORDER BY total_visit DESC
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
			while ($row = $result->fetch())
			{
				$trend_parameters = StatsDisplayService::get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

				$this->view->assign_block_vars('referer_list', array(
					'ID'              => $row['id'],
					'URL'             => $row['url'],
					'NBR_LINKS'       => $row['count'],
					'TOTAL_VISIT'     => $row['total_visit'],
					'AVERAGE_VISIT'   => $trend_parameters['average'],
					'LAST_UPDATE'     => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
					'C_TREND_PICTURE' => !empty($trend_parameters['picture']),
					'TREND_PICTURE'   => $trend_parameters['picture'],
					'TREND_LABEL'     => '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)'
				));
			}
			$result->dispose();

			$this->view->put_all(array(
				'C_STATS_REFERER' => true,
				'C_REFERERS'      => $nbr_referer,
				'C_PAGINATION'    => $pagination->has_several_pages(),
				'PAGINATION'      => $pagination->display(),
			));
		}
		elseif ($keyword)
		{
			$nbr_keyword = $db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = :type', array('type' => 1), 'DISTINCT(relative_url)');

			$page = $request->get_getint('page', 1);
			$pagination = new ModulePagination($page, $nbr_keyword, $this->config->get_items_per_page());
			$pagination->set_url(StatsUrlBuilder::table('keyword', '%d'));
			
			if ($pagination->current_page_is_empty() && $page > 1)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			$result = $db_querier->select("SELECT id, count(*) as count, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
				FROM " . PREFIX . "stats_referer
				WHERE type = 1
				GROUP BY relative_url, id, nbr_day
				ORDER BY total_visit DESC
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
			while ($row = $result->fetch())
			{
				$trend_parameters = StatsDisplayService::get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

				$this->view->assign_block_vars('keyword_list', array(
					'ID'              => $row['id'],
					'KEYWORD'         => $row['relative_url'],
					'NBR_LINKS'       => $row['count'],
					'TOTAL_VISIT'     => $row['total_visit'],
					'AVERAGE_VISIT'   => $trend_parameters['average'],
					'LAST_UPDATE'     => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
					'C_TREND_PICTURE' => !empty($trend_parameters['picture']),
					'TREND_PICTURE'   => $trend_parameters['picture'],
					'TREND_LABEL'     => '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)'
				));
			}
			$result->dispose();

			$this->view->put_all(array(
				'C_STATS_KEYWORD' => true,
				'C_KEYWORDS'      => $nbr_keyword,
				'C_PAGINATION'    => $pagination->has_several_pages(),
				'PAGINATION'      => $pagination->display(),
			));
		}
		elseif ($browser || $os || $country) //Graphiques camembert.
		{
			$path = '../images/stats/';
			if (!empty($browser))
			{
				$this->view->put_all(array(
					'C_STATS_BROWSERS' => true,
					'C_CACHE_FILE'     => !file_exists('../cache/browsers.png'),
					'U_GRAPH_RESULT'   => !file_exists('../cache/browsers.png') ? StatsUrlBuilder::display_browsers_graph()->rel() : '../cache/browsers.png',
				));
				$stats_menu = 'browsers';
				$array_stats_info = LangLoader::get($stats_menu, 'stats');
				$path = 'browsers/';
			}
			elseif (!empty($os))
			{
				$this->view->put_all(array(
					'C_STATS_OS'     => true,
					'C_CACHE_FILE'   => !file_exists('../cache/os.png'),
					'U_GRAPH_RESULT' => !file_exists('../cache/os.png') ? StatsUrlBuilder::display_os_graph()->rel() : '../cache/os.png',
				));
				$stats_menu = 'os';
				$array_stats_info = LangLoader::get($stats_menu, 'stats');
				$path = 'os/';
			}
			elseif (!empty($country))
			{
				$this->view->put_all(array(
					'C_STATS_LANG'   => true,
					'C_CACHE_FILE'   => !file_exists('../cache/lang.png'),
					'U_GRAPH_RESULT' => !file_exists('../cache/lang.png') ? StatsUrlBuilder::display_langs_graph()->rel() : '../cache/lang.png',
				));
				$stats_menu = 'lang';
				$array_stats_info = LangLoader::get($stats_menu, 'stats');
				$path = 'countries/';
			}

			$Stats = new ImagesStats();

			$Stats->load_data(StatsSaver::retrieve_stats($stats_menu), 'ellipse', 5);

			//Tri décroissant.
			arsort($Stats->data_stats);

			//Traitement des données.
			$array_stats_tmp = array();
			$array_order = array();
			$percent_other = 0;
			foreach ($Stats->data_stats as $value_name => $angle_value)
			{
				if (!isset($array_stats_info[$value_name]) || $value_name == 'other') //Autres, on additionne le tout.
				{
					$value_name = 'other';
					$angle_value += $percent_other;
					$percent_other += $angle_value;
					$stats_img = !empty($array_stats_info['other'][1]) ? '<img src="'. TPL_PATH_TO_ROOT . '/images/stats/' . $array_stats_info['other'][1] . '" alt="' . $this->lang['common.other'] . '" />' : '<img src="' . TPL_PATH_TO_ROOT . '/images/stats/other.png" alt="' . $this->lang['common.other'] . '" />';
					$name_stats = $this->lang['common.other'];
				}
				else
				{
					$stats_img = !empty($array_stats_info[$value_name][1]) ? '<img src="' . TPL_PATH_TO_ROOT . '/images/stats/' . $path . $array_stats_info[$value_name][1] . '" alt="' . $array_stats_info[$value_name][0] . '" />' : '-';
					$name_stats = $array_stats_info[$value_name][0];
				}

				if (!isset($array_order[$value_name]))
				{
					$array_color = $Stats->array_allocated_color[$Stats->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
					$array_stats_tmp[$value_name] = array($name_stats, $array_color, $stats_img);
					$array_order[$value_name] = $angle_value;
				}
			}

			//Affichage.
			foreach ($array_order as $value_name => $angle_value)
			{
				$this->view->assign_block_vars('list', array(
					'COLOR'   => 'rgb(' . trim(implode(', ', $array_stats_tmp[$value_name][1]), ', ') . ')',
					'IMG'     => $array_stats_tmp[$value_name][2],
					'L_NAME'  => $array_stats_tmp[$value_name][0],
					'PERCENT' => NumberHelper::round(($angle_value/3.6), 1),
				));
			}
		}
		elseif ($bot)
		{
			$array_robot = StatsSaver::retrieve_stats('robots');

			if (isset($array_robot['unknow_bot']))
			{
				$array_robot[$this->lang['common.unknown']] = $array_robot['unknow_bot'];
				unset($array_robot['unknow_bot']);
			}
			$robots_visits = array();
			$robots_visits_number = 0;
			foreach ($array_robot as $key => $value)
			{
				$robots_visits[$key] = is_array($value) ? $value['visits_number'] : $value;
				$robots_visits_number += $robots_visits[$key];
			}

			if ($robots_visits_number)
			{
				$Stats = new ImagesStats();
				$Stats->load_data($robots_visits, 'ellipse');
				foreach ($Stats->data_stats as $key => $angle_value)
				{
					$array_color = $Stats->array_allocated_color[$Stats->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
					$this->view->assign_block_vars('list', array(
						'C_BOT_DETAILS' => $key != $this->lang['common.unknown'],
						'COLOR'         => 'rgb(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
						'VISITS_NUMBER' => $robots_visits[$key],
						'LAST_SEEN'     => is_array($array_robot[$key]) && isset($array_robot[$key]['last_seen']) ? Date::to_format($array_robot[$key]['last_seen'], Date::FORMAT_DAY_MONTH_YEAR) : $this->lang['common.undetermined'],
						'PERCENT'       => NumberHelper::round(($angle_value/3.6), 1),
						'L_NAME'        => $key,
						'U_BOT_DETAILS' => 'https://udger.com/resources/ua-list/bot-detail?bot=' . urlencode($key)
					));
				}
			}

			$this->view->put_all(array(
				'C_STATS_ROBOTS' => true,
				'C_ROBOTS_DATA'  => $robots_visits_number,
				'U_GRAPH_RESULT' => !file_exists('../cache/bot.png') ? StatsUrlBuilder::display_bots_graph()->rel() : '../cache/bot.png'
			));
		}
		else
		{
			$general_config = GeneralConfig::load();
			
			$this->view->put_all(array(
				'C_STATS_SITE' => true,
				'START'        => $general_config->get_site_install_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'VERSION'      => $general_config->get_phpboost_major_version()
			));
		}
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
		$object = new self();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
