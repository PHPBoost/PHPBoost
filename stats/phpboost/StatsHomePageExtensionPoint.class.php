<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 16
 * @since       PHPBoost 3.0 - 2012 02 08
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class StatsHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $db_querier;

	public function __construct()
	{
		$this->db_querier = PersistenceContext::get_querier();
	}

	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}

	private function get_title()
	{
		global $LANG;

		load_module_lang('stats');

		return $LANG['stats.module.title'];
	}

	private function get_view()
	{
		$this->check_authorizations();

		global $LANG, $auth_write, $Bread_crumb, $members, $pages, $pages_year, $referer, $keyword, $visit, $visit_year, $os, $browser, $user_lang, $stats_array_browsers, $stats_array_os, $stats_array_lang, $bot;

		require_once(PATH_TO_ROOT . '/stats/stats_begin.php');

		$tpl = new FileTemplate('stats/stats.tpl');

		$date_lang = LangLoader::get('date-common');
		$_NBR_ELEMENTS_PER_PAGE = StatsConfig::load()->get_elements_number_per_page();

		$tpl->put_all(array(
			'U_STATS_SITE'    => url('.php?site=1', '-site.php'),
			'U_STATS_USERS'   => url('.php?members=1', '-members.php'),
			'U_STATS_VISIT'   => url('.php?visit=1', '-visit.php'),
			'U_STATS_PAGES'   => url('.php?pages=1', '-pages.php'),
			'U_STATS_REFERER' => url('.php?referer=1', '-referer.php'),
			'U_STATS_KEYWORD' => url('.php?keyword=1', '-keyword.php'),
			'U_STATS_BROWSER' => url('.php?browser=1', '-browser.php'),
			'U_STATS_OS'      => url('.php?os=1', '-os.php'),
			'U_STATS_LANG'    => url('.php?lang=1', '-lang.php'),
			'U_STATS_ROBOTS'  => url('.php?bot=1', '-bot.php'),
			'L_SITE'     => $LANG['site'],
			'L_STATS'    => $LANG['stats.module.title'],
			'L_USERS'    => $LANG['member_s'],
			'L_VISITS'   => $LANG['guest_s'],
			'L_PAGES'    => $LANG['page_s'],
			'L_REFERER'  => $LANG['referer_s'],
			'L_KEYWORD'  => $LANG['keyword_s'],
			'L_BROWSERS' => $LANG['browser_s'],
			'L_ROBOTS'   => $LANG['robot_s'],
			'L_OS'       => $LANG['os'],
			'L_LANG'     => $LANG['stat_lang']
		));

		if ($members)
		{
			$stats_cache = StatsCache::load();
			$last_user_group_color = User::get_group_color($stats_cache->get_stats_properties('last_member_groups'), $stats_cache->get_stats_properties('last_member_level'));
			$user_sex_field = ExtendedFieldsCache::load()->get_extended_field_by_field_name('user_sex');

			$tpl->put_all(array(
				'C_STATS_USERS' => true,
				'C_LAST_USER_GROUP_COLOR' => !empty($last_user_group_color),
				'C_DISPLAY_SEX' => (!empty($user_sex_field) && $user_sex_field['display']),
				'LAST_USER' => $stats_cache->get_stats_properties('last_member_login'),
				'LAST_USER_LEVEL_CLASS' => UserService::get_level_class($stats_cache->get_stats_properties('last_member_level')),
				'LAST_USER_GROUP_COLOR' => $last_user_group_color,
				'U_LAST_USER_PROFILE' => UserUrlBuilder::profile($stats_cache->get_stats_properties('last_member_id'))->rel(),
				'USERS' => $stats_cache->get_stats_properties('nbr_members'),
				'GRAPH_RESULT_THEME' => !file_exists('../cache/theme.png') ? '<img src="display_stats.php?theme=1" alt="' . $LANG['theme_s'] . '" />' : '<img src="../cache/theme.png" alt="' . $LANG['theme_s'] . '" />',
				'GRAPH_RESULT_SEX' => !file_exists('../cache/sex.png') ? '<img src="display_stats.php?sex=1" alt="' . $LANG['sex'] . '" />' : '<img src="../cache/sex.png" alt="' . $LANG['sex'] . '" />',
				'L_LAST_USER' => $LANG['last_member'],
				'L_TEMPLATES' => $LANG['theme_s'],
				'L_PSEUDO' => LangLoader::get_message('display_name', 'user-common'),
				'L_MSG' => $LANG['message_s'],
				'L_TOP_TEN_POSTERS' => $LANG['top_10_posters'],
				'L_COLORS' => $LANG['colors'],
				'L_USERS' => $LANG['member_s'],
				'L_SEX' => $LANG['sex']
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
				$tpl->assign_block_vars('templates', array(
					'NBR_THEME' => NumberHelper::round(($angle_value*$Stats->nbr_entry)/360, 0),
					'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
					'THEME' => ($name == 'Other') ? $LANG['other'] : $name,
					'PERCENT' => NumberHelper::round(($angle_value/3.6), 1)
				));
			}

			$stats_array = array();
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
					$name = $LANG['unknown'];
					break;
					case 1:
					$name = $LANG['male'];
					break;
					case 2:
					$name = $LANG['female'];
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
				$tpl->assign_block_vars('sex', array(
					'NBR_MBR' => NumberHelper::round(($angle_value*$Stats->nbr_entry)/360, 0),
					'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
					'SEX' => ($name == 'Other') ? $LANG['other'] : $name,
					'PERCENT' => NumberHelper::round(($angle_value/3.6), 1)
				));
			}

			$i = 1;
			$result = $this->db_querier->select("SELECT user_id, display_name, level, groups, posted_msg
			FROM " . DB_TABLE_MEMBER . "
			ORDER BY posted_msg DESC
			LIMIT 10 OFFSET 0");
			while ($row = $result->fetch())
			{
				$user_group_color = User::get_group_color($row['groups'], $row['level']);

				$tpl->assign_block_vars('top_poster', array(
					'C_USER_GROUP_COLOR' => !empty($user_group_color),
					'ID' => $i,
					'LOGIN' => $row['display_name'],
					'USER_LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'USER_GROUP_COLOR' => $user_group_color,
					'USER_POST' => $row['posted_msg'],
					'U_USER_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				));

				$i++;
			}
			$result->dispose();
		}
		elseif ($visit || $visit_year) //Visites par jour classées par mois.
		{
			//On affiche les visiteurs totaux et du jour
			$visit_counter = array('nbr_ip' => 0, 'total' => 0);
			try {
				$visit_counter = $this->db_querier->select_single_row(DB_TABLE_VISIT_COUNTER, array('ip AS nbr_ip', 'total'), 'WHERE id = :id', array('id' => 1));
			} catch (RowNotFoundException $e) {}

			$visit_counter_total = !empty($visit_counter['nbr_ip']) ? $visit_counter['nbr_ip'] : 1;
			$visit_counter_day = !empty($visit_counter['total']) ? $visit_counter['total'] : 1;

			$tpl->put_all(array(
				'L_TODAY' => $date_lang['today'],
				'L_TOTAL' => $LANG['total'],
				'L_AVERAGE' => $LANG['average'],
				'L_VISITORS' => $LANG['guest_s'],
				'L_VISITS_DAY' => $LANG['guest_s'],
				'L_DAY' => $date_lang['date'],
				'L_MONTH' => $date_lang['month'],
				'L_SUBMIT' => $LANG['submit']
			));

			$time = Date::to_format(Date::DATE_NOW, 'Ym');
			$current_year = TextHelper::substr($time, 0, 4);
			$current_month = TextHelper::substr($time, 4, 2);

			$month = (int)retrieve(GET, 'm', (int)$current_month);
			$year = (int)retrieve(GET, 'y', (int)$current_year);
			if ($visit_year)
				$year = $visit_year;

			//Gestion des mois pour s'adapter au array défini dans lang/main.php
			$array_l_months = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
			$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);

			if (!empty($visit_year)) //Visites par mois classées par ans.
			{
				//Années précédente et suivante
				$next_year = $visit_year + 1;
				$previous_year = $visit_year - 1;

				//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
				$info = array('max_month' => 0, 'sum_month' => 0, 'nbr_month' => 0);
				try {
					$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(nbr) as max_month', 'SUM(nbr) as sum_month', 'COUNT(DISTINCT(stats_month)) as nbr_month'), 'WHERE stats_year=:year GROUP BY stats_year', array('year' => $visit_year));
				} catch (RowNotFoundException $e) {}

				$tpl->put_all(array(
					'C_STATS_VISIT' => true,
					'TYPE' => 'visit',
					'VISIT_TOTAL' => $visit_counter_total,
					'VISIT_DAY' => $visit_counter_day,
					'YEAR' => $visit_year,
					'COLSPAN' => 14,
					'SUM_NBR' => $info['sum_month'],
					'MAX_NBR' => $info['max_month'],
					'MOY_NBR' => !empty($info['nbr_month']) ? NumberHelper::round($info['sum_month']/$info['nbr_month'], 1) : 1,
					'U_NEXT_LINK' =>  url('.php?year=' . $next_year),
					'U_PREVIOUS_LINK' => url('.php?year=' . $previous_year)
				));

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				$years = '';
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				$tpl->put_all(array(
					'C_STATS_YEAR' => true,
					'STATS_YEAR' => $years
				));

				if (@extension_loaded('gd'))
				{
					$tpl->put_all(array(
						'GRAPH_RESULT' => '<img src="display_stats.php?visit_year=1&amp;year=' . $visit_year . '" alt="' . $LANG['total_visit'] . '" />'
					));

					//On fait la liste des visites journalières
					$result = $this->db_querier->select("SELECT stats_month, SUM(nbr) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $visit_year
					));
					while ($row = $result->fetch())
					{
						//On affiche les stats numériquement dans un tableau en dessous
						$tpl->assign_block_vars('value', array(
							'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
							'NBR' => $row['total']
						));
					}
					$result->dispose();
				}
				else
				{
					$max_month = 1;
					$result = $this->db_querier->select("SELECT SUM(nbr) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $visit_year
					));
					while ($row = $result->fetch())
					{
						$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
					}
					$result->dispose();

					$tpl->put_all(array(
						'C_STATS_NO_GD' => true
					));

					$i = 1;
					$last_month = 1;
					$months_not_empty = array();
					$result = $this->db_querier->select("SELECT stats_month, SUM(nbr) AS total
					FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $visit_year
					));
					while ($row = $result->fetch())
					{
						$diff = 0;
						if ($row['stats_month'] != $i)
						{
							$diff = $row['stats_month'] - $i;
							for ($j = 0; $j < $diff; $j++)
							{
								$tpl->assign_block_vars('values', array(
									'HEIGHT' => 0
								));
							}
						}

						$i += $diff;

						//On a des stats pour ce mois-ci, on l'enregistre
						array_push($months_not_empty, $row['stats_month']);

						//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
						$height = $row['total'] / $max_month * 200;

						$tpl->assign_block_vars('values', array(
							'HEIGHT' => ceil($height)
						));

						$tpl->assign_block_vars('values.head', array(
						));

						//On affiche les stats numériquement dans un tableau en dessous
						$tpl->assign_block_vars('value', array(
							'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
							'NBR' => $row['total']
						));

						$last_month = $row['stats_month'];
						$i++;
					}
					$result->dispose();

					//Génération des td manquants.
					$date_day = isset($date_day) ? $date_day : 1;
					for	($i = $last_month; $i < 12; $i++)
					{
						$tpl->assign_block_vars('end_td', array(
							'END_TD' => '<td style="width:13px;">&nbsp;</td>'
						));
					}
					//On liste les jours en dessous du graphique
					$i = 1;
					foreach ($array_l_months as $value)
					{
						$tpl->assign_block_vars('legend', array(
							'LEGEND' => (in_array($i, $months_not_empty)) ? '<a href="stats' . url('.php?m=' . $i . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . TextHelper::substr($value, 0, 3) . '</a>' : TextHelper::substr($value, 0, 3)
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
					$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(nbr) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(nbr) as sum_nbr', 'AVG(nbr) as avg_nbr'), 'WHERE stats_year=:year AND stats_month=:month GROUP BY stats_month', array('year' => $year, 'month' => $month));
				} catch (RowNotFoundException $e) {}

				$tpl->put_all(array(
					'C_STATS_VISIT' => true,
					'TYPE' => 'visit',
					'VISIT_TOTAL' => $visit_counter_total,
					'VISIT_DAY' => $visit_counter_day,
					'COLSPAN' => $array_month[$month-1] + 2,
					'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
					'MONTH' => $array_l_months[$month - 1],
					'MAX_NBR' => $info['max_nbr'],
					'MOY_NBR' => NumberHelper::round($info['avg_nbr'], 1),
					'U_NEXT_LINK' => url('.php?m=' . $next_month . '&amp;y=' . $next_year . '&amp;visit=1', '-visit.php?m=' . $next_month . '&amp;y=' . $next_year),
					'U_PREVIOUS_LINK' => url('.php?m=' . $previous_month . '&amp;y=' . $previous_year . '&amp;visit=1', '-visit.php?m=' . $previous_month . '&amp;y=' . $previous_year),
					'U_YEAR' => '<a href="stats' . url('.php?year=' . $year) . '#stats">' . $year . '</a>',
					'U_VISITS_MORE' => '<a href="stats' . url('.php?year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
				));

				$months = '';
				for ($i = 1; $i <= 12; $i++)
				{
					$selected = ($i == $month) ? ' selected="selected"' : '';
					$months .= '<option value="' . $i . '"' . $selected . '>' . $array_l_months[$i - 1] . '</option>';
				}

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				$years = '';
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				$tpl->put_all(array(
					'C_STATS_YEAR' => true,
					'C_STATS_MONTH' => true,
					'STATS_YEAR' => $years,
					'STATS_MONTH' => $months
				));

				if (@extension_loaded('gd'))
				{
					$tpl->put_all(array(
						'GRAPH_RESULT' => '<img src="display_stats.php?visit_month=1&amp;year=' . $year . '&amp;month=' . $month . '" alt="' . $LANG['total_visit'] . '" />'
					));

					//On fait la liste des visites journalières
					$result = $this->db_querier->select("SELECT nbr, stats_day AS day
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
						$tpl->assign_block_vars('value', array(
							'U_DETAILS' => $date_day . '/' . sprintf('%02d', $month) . '/' . $year,
							'NBR' => $row['nbr']
						));
					}
					$result->dispose();
				}
				else
				{
					//Mois selectionné.
					if (!empty($month) && !empty($year))
					{
						$tpl->put_all(array(
							'C_STATS_NO_GD' => true
						));

						//On rajoute un 0 devant tous les mois plus petits que 10
						$month = ($month < 10) ? '0' . $month : $month;
						unset($i);

						//On fait la liste des visites journalières
						$j = 0;
						$result = $this->db_querier->select("SELECT nbr, stats_day AS day
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
									$tpl->assign_block_vars('values', array(
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
									$tpl->assign_block_vars('values', array(
										'HEIGHT' => 0
									));
								}
							}
							$i += $diff;

							//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
							$height = ($row['nbr'] / $info['max_nbr']) * 200;

							$tpl->assign_block_vars('values', array(
								'HEIGHT' => ceil($height)
							));

							$tpl->assign_block_vars('values.head', array(
							));

							$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];

							//On affiche les stats numériquement dans un tableau en dessous
							$tpl->assign_block_vars('value', array(
								'U_DETAILS' => $date_day . '/' . sprintf('%02d', $month) . '/' . $year,
								'NBR' => $row['nbr']
							));

							$i++;
						}
						$result->dispose();

						//Génération des td manquants.
						$date_day = isset($date_day) ? $date_day : 1;
						for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
						{
							$tpl->assign_block_vars('end_td', array(
								'END_TD' => '<td style="width:13px;">&nbsp;</td>'
							));
						}

						//On liste les jours en dessous du graphique
						for ($i = 1; $i <= $array_month[$month - 1]; $i++)
						{
							$tpl->assign_block_vars('legend', array(
								'LEGEND' => $i
							));
						}
					}
				}
			}
		}
		elseif ($pages || $pages_year) //Pages par jour classées par mois.
		{
			$time = Date::to_format(Date::DATE_NOW, 'Ymj');
			$current_year = TextHelper::substr($time, 0, 4);
			$current_month = TextHelper::substr($time, 4, 2);
			$current_day = TextHelper::substr($time, 6, 2);

			$day = (int)retrieve(GET, 'd', (int)$current_day);
			$month = (int)retrieve(GET, 'm', (int)$current_month);

			if ($pages_year)
			{
				$condition = 'WHERE stats_year=:year AND pages_detail <> \'\' GROUP BY stats_month';
				$year = $pages_year;
			}
			elseif ((bool)retrieve(GET, 'd', false))
			{
				$condition = 'WHERE stats_year=:year AND stats_month=:month AND stats_day=:day AND pages_detail <> \'\' GROUP BY stats_month';
				$year = (int)retrieve(GET, 'y', (int)$current_year);
			}
			else
			{
				$condition = 'WHERE stats_year=:year AND stats_month=:month AND pages_detail <> \'\' GROUP BY stats_month';
				$year = (int)retrieve(GET, 'y', (int)$current_year);
			}

			if (empty($pages_year))
			{
				//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
				$info = array('max_nbr' => 0, 'min_day' => 0, 'sum_nbr' => 0, 'avg_nbr' => 0);
				try {
					$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(pages) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(pages) as sum_nbr', 'AVG(pages) as avg_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'), $condition, array('year' => $year, 'month' => $month, 'day' => $day));
				} catch (RowNotFoundException $e) {}
			}

			//On affiche les visiteurs totaux et du jour
			$pages_total = $this->db_querier->get_column_value(StatsSetup::$stats_table, 'SUM(pages)', '');
			$pages_day = array_sum(StatsSaver::retrieve_stats('pages')) + 1;
			$pages_total = $pages_total + $pages_day;
			$pages_day = !empty($pages_day) ? $pages_day : 1;

			$tpl->put_all(array(
				'L_TODAY' => $date_lang['today'],
				'L_TOTAL' => $LANG['total'],
				'L_AVERAGE' => $LANG['average'],
				'L_VISITORS' => $LANG['page_s'] . ':',
				'L_VISITS_DAY' => $LANG['page_s'],
				'L_DAY' => $date_lang['date'],
				'L_MONTH' => $date_lang['month'],
				'L_SUBMIT' => $LANG['submit']
			));

			//Gestion des mois pour s'adapter au array défini dans lang/main.php
			$array_l_months = array($date_lang['january'], $date_lang['february'], $date_lang['march'], $date_lang['april'], $date_lang['may'], $date_lang['june'],
			$date_lang['july'], $date_lang['august'], $date_lang['september'], $date_lang['october'], $date_lang['november'], $date_lang['december']);

			if (!empty($pages_year)) //Visites par mois classées par ans.
			{
				//Années précédente et suivante
				$next_year = $pages_year + 1;
				$previous_year = $pages_year - 1;

				//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
				$info = array('max_nbr' => 0, 'sum_nbr' => 0, 'nbr_month' => 0);
				try {
					$info = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(pages) as max_nbr', 'SUM(pages) as sum_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'), 'WHERE stats_year = :year AND pages_detail <> \'\' GROUP BY stats_year', array('year' => $pages_year));
				} catch (RowNotFoundException $e) {}

				$tpl->put_all(array(
					'C_STATS_VISIT' => true,
					'TYPE' => 'pages',
					'VISIT_TOTAL' => $pages_total,
					'VISIT_DAY' => $pages_day,
					'YEAR' => $pages_year,
					'COLSPAN' => 13,
					'SUM_NBR' => $info['sum_nbr'],
					'MAX_NBR' => $info['max_nbr'],
					'MOY_NBR' => !empty($info['nbr_month']) ? NumberHelper::round($info['sum_nbr']/$info['nbr_month'], 1) : 0,
					'U_NEXT_LINK' =>  url('.php?pages_year=' . $next_year),
					'U_PREVIOUS_LINK' => url('.php?pages_year=' . $previous_year)
				));

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}

				$years = '';
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				$tpl->put_all(array(
					'C_STATS_YEAR' => true,
					'STATS_YEAR' => $years
				));

				if (@extension_loaded('gd'))
				{
					$tpl->put_all(array(
						'GRAPH_RESULT' => '<img src="display_stats.php?pages_year=1&amp;year=' . $pages_year . '" alt="' . $LANG['total_visit'] . '" />'
					));

					//On fait la liste des visites journalières
					$result = $this->db_querier->select("SELECT  stats_month, SUM(pages) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $pages_year
					));
					while ($row = $result->fetch())
					{
						//On affiche les stats numériquement dans un tableau en dessous
						$tpl->assign_block_vars('value', array(
							'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
							'NBR' => $row['total']
						));
					}
					$result->dispose();
				}
				else
				{
					$result = $this->db_querier->select("SELECT SUM(nbr) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $visit_year
					));
					$max_month = 1;
					while ($row = $result->fetch())
					{
						$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
					}

					$tpl->put_all(array(
						'C_STATS_NO_GD' => true
					));

					$i = 1;
					$last_month = 1;
					$months_not_empty = array();
					$result = $this->db_querier->select("SELECT stats_month, SUM(pages) AS total
						FROM " . StatsSetup::$stats_table . "
						WHERE stats_year = :stats_year
						GROUP BY stats_month", array(
							'stats_year' => $visit_year
					));
					while ($row = $result->fetch())
					{
						$diff = 0;
						if ($row['stats_month'] != $i)
						{
							$diff = $row['stats_month'] - $i;
							for ($j = 0; $j < $diff; $j++)
							{
								$tpl->assign_block_vars('values', array(
									'HEIGHT' => 0
								));
							}
						}

						$i += $diff;

						//On a des stats pour ce mois-ci, on l'enregistre
						array_push($months_not_empty, $row['stats_month']);

						//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
						$height = $row['total'] / $info['max_month'] * 200;

						$tpl->assign_block_vars('months', array(
							'HEIGHT' => ceil($height)
						));

						$tpl->assign_block_vars('values.head', array(
						));

						//On affiche les stats numériquement dans un tableau en dessous
						$tpl->assign_block_vars('value', array(
							'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
							'NBR' => $row['total']
						));

						$last_month = $row['stats_month'];
						$i++;
					}
					$result->dispose();

					//Génération des td manquants.
					$date_day = isset($date_day) ? $date_day : 1;
					for	($i = $last_month; $i < 12; $i++)
					{
						$tpl->assign_block_vars('end_td', array(
							'END_TD' => '<td style="width:13px;">&nbsp;</td>'
						));
					}
					//On liste les jours en dessous du graphique
					$i = 1;
					foreach ($array_l_months as $value)
					{
						$tpl->assign_block_vars('legend', array(
							'LEGEND' => (in_array($i, $months_not_empty)) ? '<a href="stats' . url('.php?m=' . $i . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . TextHelper::substr($value, 0, 3) . '</a>' : TextHelper::substr($value, 0, 3)
						));
						$i++;
					}
				}
			}
			elseif (retrieve(GET, 'd', false))
			{
				//Nombre de jours pour chaque mois (gestion des années bissextiles)
				$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;
				$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);

				//Mois précédent et suivant
				$check_day = $day < $array_month[$month-1];
				$next_day = $check_day ? $day + 1 : 1;
				$next_month = (!$check_day && $month < 12) ? $month + 1 : $month;
				$next_year = (!$check_day && $month == 12) ? $year + 1 : $year;
				$previous_day = ($day > 1) ? $day - 1 : $array_month[$month-1];
				$previous_month = ($month > 1) ? ($day == 1 ? $month - 1 : $month) : 12;
				$previous_year = ($month > 1) ? $year : $year - 1;

				$tpl->put_all(array(
					'C_STATS_VISIT' => true,
					'TYPE' => 'pages',
					'VISIT_TOTAL' => $pages_total,
					'VISIT_DAY' => $pages_day,
					'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
					'MONTH' => $array_l_months[$month - 1],
					'MAX_NBR' => $info['max_nbr'],
					'MOY_NBR' => NumberHelper::round($info['avg_nbr'], 1),
					'U_NEXT_LINK' => url('.php?d=' . $next_day . '&amp;m=' . $next_month . '&amp;y=' . $next_year . '&amp;pages=1', '-pages.php?d=' . $next_day . '&amp;m=' . $next_month . '&amp;y=' . $next_year),
					'U_PREVIOUS_LINK' => url('.php?d=' . $previous_day . '&amp;m=' . $previous_month . '&amp;y=' . $previous_year . '&amp;pages=1', '-pages.php?d=' . $previous_day . '&amp;m=' . $previous_month . '&amp;y=' . $previous_year),
					'U_YEAR' => '<a href="stats' . url('.php?pages_year=' . $year) . '#stats">' . $year . '</a>',
					'U_VISITS_MORE' => '<a href="stats' . url('.php?pages_year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
				));

				$days = '';
				for ($i = 1; $i <= $array_month[$month-1]; $i++)
				{
					$selected = ($i == $day) ? ' selected="selected"' : '';
					$days .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}

				$months = '';
				for ($i = 1; $i <= 12; $i++)
				{
					$selected = ($i == $month) ? ' selected="selected"' : '';
					$months .= '<option value="' . $i . '"' . $selected . '>' . $array_l_months[$i - 1] . '</option>';
				}

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				$years = '';
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}

				$tpl->put_all(array(
					'C_STATS_DAY' => true,
					'C_STATS_MONTH' => true,
					'C_STATS_YEAR' => true,
					'STATS_DAY' => $days,
					'STATS_MONTH' => $months,
					'STATS_YEAR' => $years,
					'GRAPH_RESULT' => '<img src="display_stats.php?pages_day=1&amp;year=' . $year . '&amp;month=' . $month . '&amp;day=' . $day . '" alt="' . $LANG['total_visit'] . '" />'
				));

				//On fait la liste des visites journalières
				$result = $this->db_querier->select("SELECT pages, stats_day, stats_month, stats_year
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
					$tpl->assign_block_vars('value', array(
						'U_DETAILS' => '<a href="stats' . url('.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year'] . '&amp;pages=1', '-pages.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year']) . '#stats">' . $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'] . '</a>',
						'NBR' => $row['pages']
					));
				}
				$result->dispose();
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

				$tpl->put_all(array(
					'C_STATS_VISIT' => true,
					'TYPE' => 'pages',
					'VISIT_TOTAL' => $pages_total,
					'VISIT_DAY' => $pages_day,
					'COLSPAN' => $array_month[$month-1] + 2,
					'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
					'MONTH' => $array_l_months[$month - 1],
					'MAX_NBR' => $info['max_nbr'],
					'MOY_NBR' => NumberHelper::round($info['avg_nbr'], 1),
					'U_NEXT_LINK' => url('.php?m=' . $next_month . '&amp;y=' . $next_year . '&amp;pages=1', '-pages.php?m=' . $next_month . '&amp;y=' . $next_year),
					'U_PREVIOUS_LINK' => url('.php?m=' . $previous_month . '&amp;y=' . $previous_year . '&amp;pages=1', '-pages.php?m=' . $previous_month . '&amp;y=' . $previous_year),
					'U_YEAR' => '<a href="stats' . url('.php?pages_year=' . $year) . '#stats">' . $year . '</a>',
					'U_VISITS_MORE' => '<a href="stats' . url('.php?pages_year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
				));

				$months = '';
				for ($i = 1; $i <= 12; $i++)
				{
					$selected = ($i == $month) ? ' selected="selected"' : '';
					$months .= '<option value="' . $i . '"' . $selected . '>' . $array_l_months[$i - 1] . '</option>';
				}

				//Année maximale
				$info_year = array('max_year' => 0, 'min_year' => 0);
				try {
					$info_year = $this->db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}
				$years = '';
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				$tpl->put_all(array(
					'C_STATS_YEAR' => true,
					'C_STATS_MONTH' => true,
					'STATS_YEAR' => $years,
					'STATS_MONTH' => $months
				));

				if (@extension_loaded('gd'))
				{
					$tpl->put_all(array(
						'GRAPH_RESULT' => '<img src="display_stats.php?pages_month=1&amp;year=' . $year . '&amp;month=' . $month . '" alt="' . $LANG['total_visit'] . '" />'
					));

					//On fait la liste des visites journalières
					$result = $this->db_querier->select("SELECT pages, stats_day, stats_month, stats_year
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
						$tpl->assign_block_vars('value', array(
							'U_DETAILS' => '<a href="stats' . url('.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year'] . '&amp;pages=1', '-pages.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year']) . '#stats">' . $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'] . '</a>',
							'NBR' => $row['pages']
						));
					}
					$result->dispose();
				}
				else
				{
					//Mois selectionné.
					if (!empty($month) && !empty($year))
					{
						$tpl->put_all(array(
							'C_STATS_NO_GD' => true
						));

						//On rajoute un 0 devant tous les mois plus petits que 10
						$month = ($month < 10) ? '0' . $month : $month;
						unset($i);

						//On fait la liste des visites journalières
						$j = 0;
						$result = $this->db_querier->select("SELECT pages, stats_day AS day, stats_month, stats_year
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
									$tpl->assign_block_vars('days', array(
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
									$tpl->assign_block_vars('days', array(
										'HEIGHT' => 0
									));
								}
							}
							$i += $diff;

							//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
							$height = ($row['pages'] / $info['max_nbr']) * 200;

							$tpl->assign_block_vars('values', array(
								'HEIGHT' => ceil($height)
							));

							$tpl->assign_block_vars('values.head', array(
							));

							$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];

							//On affiche les stats numériquement dans un tableau en dessous
							$tpl->assign_block_vars('value', array(
								'U_DETAILS' => $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'],
								'NBR' => $row['pages']
							));

							$i++;
						}
						$result->dispose();

						//Génération des td manquants.
						$date_day = isset($date_day) ? $date_day : 1;
						for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
						{
							$tpl->assign_block_vars('end_td', array(
								'END_TD' => '<td style="width:13px;">&nbsp;</td>'
							));
						}

						//On liste les jours en dessous du graphique
						for ($i = 1; $i <= $array_month[$month - 1]; $i++)
						{
							$tpl->assign_block_vars('legend', array(
								'LEGEND' => $i
							));
						}
					}
				}
			}
		}
		elseif ($referer)
		{
			include_once(PATH_TO_ROOT . '/stats/stats_functions.php');

			$nbr_referer = $this->db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = :type', array('type' => 0), 'DISTINCT(url)');

			$page = AppContext::get_request()->get_getint('p', 1);
			$pagination = new ModulePagination($page, $nbr_referer, $_NBR_ELEMENTS_PER_PAGE);
			$pagination->set_url(new Url('/stats/stats.php?referer=1&amp;p=%d'));
Debug::dump($_NBR_ELEMENTS_PER_PAGE);

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
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
			while ($row = $result->fetch())
			{
				$trend_parameters = get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

				$tpl->assign_block_vars('referer_list', array(
					'ID' => $row['id'],
					'URL' => $row['url'],
					'NBR_LINKS' => $row['count'],
					'TOTAL_VISIT' => $row['total_visit'],
					'AVERAGE_VISIT' => $trend_parameters['average'],
					'LAST_UPDATE' => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
					'TREND' => ($trend_parameters['picture'] ? '<i class="fa fa-trend-' . $trend_parameters['picture'] . '"></i> ' : '') . '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)'
				));
			}
			$result->dispose();

			$tpl->put_all(array(
				'C_STATS_REFERER' => true,
				'C_REFERERS' => $nbr_referer,
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display(),
				'L_URL' => $LANG['url'],
				'L_TOTAL_VISIT' => $LANG['total_visit'],
				'L_AVERAGE_VISIT' => $LANG['average_visit'],
				'L_TREND' => $LANG['trend'],
				'L_LAST_UPDATE' => $LANG['last_update'],
				'L_NO_REFERER' => $LANG['no_referer'],
			));
		}
		elseif ($keyword)
		{
			include_once(PATH_TO_ROOT . '/stats/stats_functions.php');

			$nbr_keyword = $this->db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = :type', array('type' => 1), 'DISTINCT(relative_url)');

			$page = AppContext::get_request()->get_getint('p', 1);
			$pagination = new ModulePagination($page, $nbr_keyword, $_NBR_ELEMENTS_PER_PAGE);
			$pagination->set_url(new Url('/stats/stats.php?keyword=1&amp;p=%d'));
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
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));
			while ($row = $result->fetch())
			{
				$trend_parameters = get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);

				$tpl->assign_block_vars('keyword_list', array(
					'ID' => $row['id'],
					'KEYWORD' => $row['relative_url'],
					'NBR_LINKS' => $row['count'],
					'TOTAL_VISIT' => $row['total_visit'],
					'AVERAGE_VISIT' => $trend_parameters['average'],
					'LAST_UPDATE' => Date::to_format($row['last_update'], Date::FORMAT_DAY_MONTH_YEAR),
					'TREND' => ($trend_parameters['picture'] ? '<i class="fa fa-trend-' . $trend_parameters['picture'] . '"></i> ' : '') . '(' . $trend_parameters['sign'] . $trend_parameters['trend'] . '%)'
				));
			}
			$result->dispose();

			$tpl->put_all(array(
				'C_STATS_KEYWORD' => true,
				'C_KEYWORDS' => $nbr_keyword,
				'C_PAGINATION' => $pagination->has_several_pages(),
				'PAGINATION' => $pagination->display(),
				'L_SEARCH_ENGINE' => $LANG['keyword_s'],
				'L_TOTAL_VISIT' => $LANG['total_visit'],
				'L_AVERAGE_VISIT' => $LANG['average_visit'],
				'L_TREND' => $LANG['trend'],
				'L_LAST_UPDATE' => $LANG['last_update'],
				'L_NO_KEYWORD' => $LANG['no_keyword'],
			));
		}
		elseif ($browser || $os || $user_lang) //Graphiques camenbert.
		{
			$path = '../images/stats/';
			if (!empty($browser))
			{
				$tpl->put_all(array(
					'C_STATS_BROWSERS' => true,
					'GRAPH_RESULT' => !file_exists('../cache/browsers.png') ? '<img src="display_stats.php?browsers=1" alt="' . $LANG['browser_s'] . '" />' : '<img src="../cache/browsers.png" alt="' . $LANG['browser_s'] . '" />',
					'L_BROWSERS' => $LANG['browser_s'],
					'L_COLORS' => $LANG['colors'],
					'L_PERCENTAGE' => $LANG['percentage']
				));
				$stats_menu = 'browsers';
				$array_stats_info = $stats_array_browsers;
				$path = 'browsers/';
			}
			elseif (!empty($os))
			{
				$tpl->put_all(array(
					'C_STATS_OS' => true,
					'GRAPH_RESULT' => !file_exists('../cache/os.png') ? '<img src="display_stats.php?os=1" alt="' . $LANG['os'] . '" />' : '<img src="../cache/os.png" alt="' . $LANG['os'] . '" />',
					'L_OS' => $LANG['os'],
					'L_COLORS' => $LANG['colors'],
					'L_PERCENTAGE' => $LANG['percentage']
				));
				$stats_menu = 'os';
				$array_stats_info = $stats_array_os;
				$path = 'os/';
			}
			elseif (!empty($user_lang))
			{
				$tpl->put_all(array(
					'C_STATS_LANG' => true,
					'GRAPH_RESULT' => !file_exists('../cache/lang.png') ? '<img src="display_stats.php?lang=1" alt="' . $LANG['stat_lang'] . '" />' : '<img src="../cache/lang.png" alt="' . $LANG['stat_lang'] . '" />',
					'L_LANG' => $LANG['stat_lang'],
					'L_COLORS' => $LANG['colors'],
					'L_PERCENTAGE' => $LANG['percentage']
				));
				$stats_menu = 'lang';
				$array_stats_info = $stats_array_lang;
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
					$stats_img = !empty($array_stats_info['other'][1]) ? '<img src="'. TPL_PATH_TO_ROOT . '/images/stats/' . $array_stats_info['other'][1] . '" alt="' . $LANG['other'] . '" />' : '<img src="' . TPL_PATH_TO_ROOT . '/images/stats/other.png" alt="' . $LANG['other'] . '" />';
					$name_stats = $LANG['other'];
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
				$tpl->assign_block_vars('list', array(
					'COLOR' => 'RGB(' . trim(implode(', ', $array_stats_tmp[$value_name][1]), ', ') . ')',
					'IMG' => $array_stats_tmp[$value_name][2],
					'L_NAME' => $array_stats_tmp[$value_name][0],
					'PERCENT' => NumberHelper::round(($angle_value/3.6), 1),
				));
			}
		}
		elseif ($bot)
		{
			$array_robot = StatsSaver::retrieve_stats('robots');
			if (isset($array_robot['unknow_bot']))
			{
				$array_robot[$LANG['unknown']] = $array_robot['unknow_bot'];
				unset($array_robot['unknow_bot']);
			}
			$robots_visits_number = 0;
			foreach ($array_robot as $key => $value)
			{
				$robots_visits_number += $value;
			}

			if ($robots_visits_number)
			{
				$Stats = new ImagesStats();
				$Stats->load_data($array_robot, 'ellipse');
				foreach ($Stats->data_stats as $key => $angle_value)
				{
						$array_color = $Stats->array_allocated_color[$Stats->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
						$tpl->assign_block_vars('list', array(
							'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
							'VIEWS' => $array_robot[$key],
							'PERCENT' => NumberHelper::round(($angle_value/3.6), 1),
							'L_NAME' => $key
						));
				}
			}

			$tpl->put_all(array(
				'C_STATS_ROBOTS' => true,
				'C_ROBOTS_DATA' => $robots_visits_number,
				'L_ERASE_RAPPORT' => $LANG['erase_rapport'],
				'L_ERASE' => $LANG['erase'],
				'L_COLORS' => $LANG['colors'],
				'L_VIEW_NUMBER' => $LANG['number_r_visit'],
				'L_LAST_UPDATE' => $LANG['last_update']
			));
		}
		else
		{
			$tpl->put_all(array(
				'C_STATS_SITE' => true,
				'START' => GeneralConfig::load()->get_site_install_date()->format(Date::FORMAT_DAY_MONTH_YEAR),
				'VERSION' => GeneralConfig::load()->get_phpboost_major_version(),
				'L_START' => $LANG['start'],
				'L_VERSION' => $LANG['version']
			));
		}

		return $tpl;
	}

	private function check_authorizations()
	{
		if (!StatsAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
