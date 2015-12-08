<?php
/*##################################################
 *                               admin_stats.php
 *                            -------------------
 *   begin                : July 30, 2005
 *   copyright            : (C) 2005 Viarre Régis
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
load_module_lang('stats'); //Chargement de la langue du module.

$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);

if ($valid)
{
	$stats_config = StatsConfig::load();
	$stats_config->set_authorizations(Authorizations::build_auth_array_from_form(StatsAuthorizationsService::READ_AUTHORIZATIONS));
	StatsConfig::save();
	
	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
//Sinon on remplit le formulaire
else
{
	$_NBR_ELEMENTS_PER_PAGE = 15;
	$db_querier = PersistenceContext::get_querier();
	
	$tpl = new FileTemplate('stats/admin_stats_management.tpl');
	
	$visit = $request->get_getint('visit', 0);
	$visit_year = $request->get_getint('year', 0);
	$pages = $request->get_getint('pages', 0);
	$pages_year = $request->get_getint('pages_year', 0);
	$members = $request->get_getint('members', 0);
	$referer = $request->get_getint('referer', 0);
	$keyword = $request->get_getint('keyword', 0);
	$browser = $request->get_getint('browser', 0);
	$os = $request->get_getint('os', 0);
	$all = $request->get_getint('all', 0);
	$user_lang = $request->get_getint('lang', 0);
	$bot = $request->get_getint('bot', 0);
	$erase = $request->get_postvalue('erase', false);

	if ($erase) //Suppression de robots.txt
	{
		$file = new File('../stats/cache/robots.txt');
		try
		{
			$file->delete();
		}
		catch (IOException $exception)
		{
			echo $exception->getMessage();
		}
	}

	$tpl->put_all(array(
		'L_SITE' => $LANG['site'],
		'L_STATS' => $LANG['stats'],
		'L_USERS' => $LANG['member_s'],
		'L_VISITS' => $LANG['guest_s'],
		'L_PAGES' => $LANG['page_s'],
		'L_BROWSERS' => $LANG['browser_s'],
		'L_OS' => $LANG['os'],
		'L_LANG' => $LANG['stat_lang'],
		'L_KEYWORD' => $LANG['keyword_s'],
		'L_REFERER' => $LANG['referer_s'],
		'L_ROBOTS' => $LANG['robots'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_AUTHORIZATIONS' => $LANG['admin.authorizations'],
		'L_READ_AUTHORIZATION' => $LANG['admin.authorizations.read'],
		'READ_AUTHORIZATION' => Authorizations::generate_select(StatsAuthorizationsService::READ_AUTHORIZATIONS, StatsConfig::load()->get_authorizations()),
	));

	$date_lang = LangLoader::get('date-common');

	if (!empty($members))
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
			$stats_array[$theme->get_id()] = $db_querier->count(DB_TABLE_MEMBER, "WHERE theme = '" . $theme->get_id() . "'");
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
		$result = $db_querier->select("SELECT member.user_id, count(ext_field.user_sex) as compt, ext_field.user_sex
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
		$result = $db_querier->select("SELECT user_id, display_name, level, groups, posted_msg
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
		$compteur = array('nbr_ip' => 0, 'total' => 0);
		try {
			$compteur = $db_querier->select_single_row(DB_TABLE_VISIT_COUNTER, array('ip AS nbr_ip', 'total'), 'WHERE id = :id', array('id' => 1));
		} catch (RowNotFoundException $e) {}
		
		$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
		$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';

		$tpl->put_all(array(
			'L_TODAY' => $date_lang['today'],
			'L_TOTAL' => $LANG['total'],
			'L_AVERAGE' => $LANG['average'],
			'L_VISITORS' => $LANG['guest_s'] . ':',
			'L_VISITS_DAY' => $LANG['guest_s'],
			'L_DAY' => $date_lang['date'],
			'L_MONTH' => $date_lang['month'],
			'L_SUBMIT' => $LANG['submit']
		));

		$time = Date::to_format(Date::DATE_NOW, 'Ym');
		$current_year = substr($time, 0, 4);
		$current_month = substr($time, 4, 2);

		$month = retrieve(GET, 'm', (int)$current_month);
		$year = retrieve(GET, 'y', (int)$current_year);
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
				$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(nbr) as max_month', 'SUM(nbr) as sum_month', 'COUNT(DISTINCT(stats_month)) as nbr_month'), 'WHERE stats_year=:year GROUP BY stats_year', array('year' => $visit_year));
			} catch (RowNotFoundException $e) {}
			
			$tpl->put_all(array(
				'C_STATS_VISIT' => true,
				'TYPE' => 'visit',
				'VISIT_TOTAL' => $compteur_total,
				'VISIT_DAY' => $compteur_day,
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
				$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
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
					$result = $db_querier->select("SELECT stats_month, SUM(nbr) AS total
					FROM " . StatsSetup::$stats_table . "
					WHERE stats_year = :stats_year
					GROUP BY stats_month", array(
						'stats_year' => $visit_year
					));
					while ($row = $result->fetch())
					{
						//On affiche les stats numériquement dans un tableau en dessous
						$tpl->assign_block_vars('value', array(
						'U_DETAILS' => '<a href="admin_stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
						'NBR' => $row['total']
						));
					}
					$result->dispose();
			}
			else
			{
				$tpl->put_all(array(
					'C_STATS_NO_GD' => true
				));
				
				$i = 1;
				$last_month = $max_month = 1;
				$months_not_empty = array();
				$result = $db_querier->select("SELECT stats_month, SUM(nbr) AS total
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :stats_year
				GROUP BY stats_month", array(
					'stats_year' => $visit_year
				));
				while ($row = $result->fetch())
				{
					$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
					
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
						'U_DETAILS' => '<a href="admin_stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
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
						'LEGEND' => (in_array($i, $months_not_empty)) ? '<a href="admin_stats' . url('.php?m=' . $i . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . substr($value, 0, 3) . '</a>' : substr($value, 0, 3)
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
			
			$tpl->put_all(array(
				'C_STATS_VISIT' => true,
				'TYPE' => 'visit',
				'VISIT_TOTAL' => $compteur_total,
				'VISIT_DAY' => $compteur_day,
				'COLSPAN' => $array_month[$month-1] + 2,
				'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
				'MONTH' => $array_l_months[$month - 1],
				'MAX_NBR' => $info['max_nbr'],
				'MOY_NBR' => NumberHelper::round($info['avg_nbr'], 1),
				'U_NEXT_LINK' => url('.php?m=' . $next_month . '&amp;y=' . $next_year . '&amp;visit=1'),
				'U_PREVIOUS_LINK' => url('.php?m=' . $previous_month . '&amp;y=' . $previous_year . '&amp;visit=1'),
				'U_YEAR' => '<a href="admin_stats' . url('.php?year=' . $year) . '#stats">' . $year . '</a>',
				'U_VISITS_MORE' => '<a href="admin_stats' . url('.php?year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
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
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
				} catch (RowNotFoundException $e) {}

				$years = '';
				for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
				{
					$selected = ($i == $year) ? ' selected="selected"' : '';
					$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
				}
				$tpl->put_all(array(
					'C_STATS_MONTH' => true,
					'C_STATS_YEAR' => true,
					'STATS_MONTH' => $months,
					'STATS_YEAR' => $years
				));

				if (@extension_loaded('gd'))
				{
					$tpl->put_all(array(
						'GRAPH_RESULT' => '<img src="display_stats.php?visit_month=1&amp;year=' . $year . '&amp;month=' . $month . '" alt="' . $LANG['total_visit'] . '" />'
					));
						
					//On fait la liste des visites journalières
					$result = $db_querier->select("SELECT nbr, stats_day AS day
					FROM " . StatsSetup::$stats_table . "
					WHERE stats_year = :year AND stats_month = :month
					ORDER BY stats_day", array(
						'year' => $year,
						'month' => $month
					));
					while ($row = $result->fetch())
					{
						$date_day = ($row['day'] < 10) ? '0' . $row['day'] : $row['day'];

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

							$date_day = ($row['day'] < 10) ? '0' . $row['day'] : $row['day'];

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
		$current_year = substr($time, 0, 4);
		$current_month = substr($time, 4, 2);
		$current_day = substr($time, 6, 2);

		$day = retrieve(GET, 'd', (int)$current_day);
		$month = retrieve(GET, 'm', (int)$current_month);
		if ($pages_year)
		{
			$condition = 'WHERE stats_year=:year AND pages_detail <> \'\' GROUP BY stats_month';
			$year = $pages_year;
		}
		elseif (retrieve(GET, 'd', false))
		{
			$condition = 'WHERE stats_year=:year AND stats_month=:month AND stats_day=:day AND pages_detail <> \'\' GROUP BY stats_month';
			$year = retrieve(GET, 'y', (int)$current_year);
		}
		else
		{
			$condition = 'WHERE stats_year=:year AND stats_month=:month AND pages_detail <> \'\' GROUP BY stats_month';
			$year = retrieve(GET, 'y', (int)$current_year);
		}
		
		if (empty($pages_year))
		{
			//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
			$info = array('max_nbr' => 0, 'min_day' => 0, 'sum_nbr' => 0, 'avg_nbr' => 0);
			try {
				$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(pages) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(pages) as sum_nbr', 'AVG(pages) as avg_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month', 'pages'), $condition, array('year' => $year, 'month' => $month, 'day' => $day));
			} catch (RowNotFoundException $e) {}
		}
		
		//On affiche les visiteurs totaux et du jour
		$compteur_total = $db_querier->get_column_value(StatsSetup::$stats_table, 'SUM(pages)', '');
		$compteur_day = array_sum(StatsSaver::retrieve_stats('pages')) + 1;
		$compteur_total = $compteur_total + $compteur_day;
		$compteur_day = !empty($compteur_day) ? $compteur_day : '1';

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
				$info = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(pages) as max_nbr', 'SUM(pages) as sum_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month'), 'WHERE stats_year = :year AND pages_detail <> \'\' GROUP BY stats_year', array('year' => $pages_year));
			} catch (RowNotFoundException $e) {}
			
			$tpl->put_all(array(
				'C_STATS_VISIT' => true,
				'TYPE' => 'pages',
				'VISIT_TOTAL' => $compteur_total,
				'VISIT_DAY' => $compteur_day,
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
				$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
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
				$result = $db_querier->select("SELECT stats_month, SUM(pages) AS total
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :year
				GROUP BY stats_month", array(
					'year' => $pages_year
				));
				while ($row = $result->fetch())
				{
					//On affiche les stats numériquement dans un tableau en dessous
					$tpl->assign_block_vars('value', array(
						'U_DETAILS' => '<a href="admin_stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
						'NBR' => $row['total']
					));
				}
				$result->dispose();
			}
			else
			{
				$tpl->put_all(array(
					'C_STATS_NO_GD' => true
				));
				
				$i = 1;
				$last_month = $max_month = 1;
				$months_not_empty = array();
				$result = $db_querier->select("SELECT stats_month, SUM(nbr) AS total
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :year
				GROUP BY stats_month", array(
					'year' => $pages_year
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
						'U_DETAILS' => '<a href="admin_stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
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
						'LEGEND' => (in_array($i, $months_not_empty)) ? '<a href="admin_stats' . url('.php?m=' . $i . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . substr($value, 0, 3) . '</a>' : substr($value, 0, 3)
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
				'VISIT_TOTAL' => $compteur_total,
				'VISIT_DAY' => $compteur_day,
				'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
				'MONTH' => $array_l_months[$month - 1],
				'MAX_NBR' => $info['max_nbr'],
				'MOY_NBR' => NumberHelper::round($info['avg_nbr'], 1),
				'U_NEXT_LINK' => url('.php?d=' . $next_day . '&amp;m=' . $next_month . '&amp;y=' . $next_year . '&amp;pages=1'),
				'U_PREVIOUS_LINK' => url('.php?d=' . $previous_day . '&amp;m=' . $previous_month . '&amp;y=' . $previous_year . '&amp;pages=1'),
				'U_YEAR' => '<a href="admin_stats' . url('.php?pages_year=' . $year) . '#stats">' . $year . '</a>',
				'U_VISITS_MORE' => '<a href="admin_stats' . url('.php?pages_year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
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
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
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
				$result = $db_querier->select("SELECT pages, stats_day, stats_month, stats_year
				FROM " . StatsSetup::$stats_table . "
				WHERE stats_year = :year AND stats_month = :month
				ORDER BY stats_day", array(
					'year' => $year,
					'month' => $month
				));
				while ($row = $result->fetch())
				{
					$date_day = ($row['stats_day'] < 10) ? 0 . $row['stats_day'] : $row['stats_day'];
						
					//On affiche les stats numériquement dans un tableau en dessous
					$tpl->assign_block_vars('value', array(
					'U_DETAILS' => '<a href="admin_stats' . url('.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year'] . '&amp;pages=1') . '#stats">' . $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'] . '</a>',
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
				'VISIT_TOTAL' => $compteur_total,
				'VISIT_DAY' => $compteur_day,
				'COLSPAN' => $array_month[$month-1] + 2,
				'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
				'MONTH' => $array_l_months[$month - 1],
				'MAX_NBR' => $info['max_nbr'],
				'MOY_NBR' => NumberHelper::round($info['avg_nbr'], 1),
				'U_NEXT_LINK' => url('.php?m=' . $next_month . '&amp;y=' . $next_year . '&amp;pages=1'),
				'U_PREVIOUS_LINK' => url('.php?m=' . $previous_month . '&amp;y=' . $previous_year . '&amp;pages=1'),
				'U_YEAR' => '<a href="admin_stats' . url('.php?pages_year=' . $year) . '#stats">' . $year . '</a>',
				'U_VISITS_MORE' => '<a href="admin_stats' . url('.php?pages_year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
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
					$info_year = $db_querier->select_single_row(StatsSetup::$stats_table, array('MAX(stats_year) as max_year', 'MIN(stats_year) as min_year'), '');
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
					$result = $db_querier->select("SELECT pages, stats_day, stats_month, stats_year
					FROM " . StatsSetup::$stats_table . "
					WHERE stats_year = :year AND stats_month = :month
					ORDER BY stats_day", array(
						'year' => $year,
						'month' => $month
					));
					while ($row = $result->fetch())
					{
						$date_day = ($row['stats_day'] < 10) ? 0 . $row['stats_day'] : $row['stats_day'];

						//On affiche les stats numériquement dans un tableau en dessous
						$tpl->assign_block_vars('value', array(
						'U_DETAILS' => '<a href="admin_stats' . url('.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year'] . '&amp;pages=1') . '#stats">' . $date_day . '/' . sprintf('%02d', $row['stats_month']) . '/' . $row['stats_year'] . '</a>',
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
						$result = $db_querier->select("SELECT pages, stats_day AS day, stats_month, stats_year
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

							$date_day = ($row['day'] < 10) ? '0' . $row['day'] : $row['day'];

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
	elseif (!empty($referer))
	{
		include_once(PATH_TO_ROOT . '/stats/stats_functions.php');
		
		$nbr_referer = $db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = 0', array(), 'DISTINCT(url)');
		
		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $nbr_referer, $_NBR_ELEMENTS_PER_PAGE);
		$pagination->set_url(new Url('/stats/admin_stats.php?referer=1&amp;p=%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$result = $db_querier->select("SELECT id, count(*) as count, url, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
		FROM " . PREFIX . "stats_referer
		WHERE type = 0
		GROUP BY url
		ORDER BY total_visit DESC
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		while ($row = $result->fetch())
		{
			$trend_parameters = get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);
			
			$tpl->assign_block_vars('referer_list', array(
				'ID' => $row['id'],
				'URL' => $row['url'],
				'IMG_MORE' => '<a class="fa fa-plus-square-o" style="cursor:pointer;" onclick="XMLHttpRequest_referer(' . $row['id'] . ')" id="img_url' . $row['id'] . '"></a>',
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
	elseif (!empty($keyword))
	{
		include_once(PATH_TO_ROOT . '/stats/stats_functions.php');
		
		$nbr_keyword = $db_querier->count(StatsSetup::$stats_referer_table, 'WHERE type = 1', array(), 'DISTINCT(relative_url)');
		
		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $nbr_keyword, $_NBR_ELEMENTS_PER_PAGE);
		$pagination->set_url(new Url('/stats/admin_stats.php?keyword=1&amp;p=%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$result = $db_querier->select("SELECT id, count(*) as count, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
		FROM " . PREFIX . "stats_referer
		WHERE type = 1
		GROUP BY relative_url
		ORDER BY total_visit DESC
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		while ($row = $result->fetch())
		{
			$trend_parameters = get_trend_parameters($row['total_visit'], $row['nbr_day'], $row['yesterday_visit'], $row['today_visit']);
			
			$tpl->assign_block_vars('keyword_list', array(
				'ID' => $row['id'],
				'KEYWORD' => $row['relative_url'],
				'IMG_MORE' => '<a class="fa fa-plus-square-o" style="cursor:pointer;" onclick="XMLHttpRequest_referer(' . $row['id'] . ')" id="img_url' . $row['id'] . '"></a>',
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
	elseif (!empty($browser) || !empty($os) || !empty($user_lang)) //Graphiques camenbert.
	{

		if (!empty($browser))
		{
			$tpl->put_all(array(
				'C_STATS_BROWSERS' => true,
				'GRAPH_RESULT' => '<img src="display_stats.php?browsers=1" alt="' . $LANG['browser_s'] . '" />',
				'L_BROWSERS' => $LANG['browser_s'],
				'L_COLORS' => $LANG['colors'],
				'L_PERCENTAGE' => $LANG['percentage']
			));
			$stats_menu = 'browsers';
			$array_stats_info = $stats_array_browsers;
			$path = '../images/stats/browsers/';
		}
		elseif (!empty($os))
		{
			$tpl->put_all(array(
				'C_STATS_OS' => true,
				'GRAPH_RESULT' => '<img src="display_stats.php?os=1" alt="' . $LANG['os'] . '" />',
				'L_OS' => $LANG['os'],
				'L_COLORS' => $LANG['colors'],
				'L_PERCENTAGE' => $LANG['percentage']
			));
			$stats_menu = 'os';
			$array_stats_info = $stats_array_os;
			$path = '../images/stats/os/';
		}
		elseif (!empty($user_lang))
		{
			$tpl->put_all(array(
				'C_STATS_LANG' => true,
				'GRAPH_RESULT' => '<img src="display_stats.php?lang=1" alt="' . $LANG['stat_lang'] . '" />',
				'L_LANG' => $LANG['stat_lang'],
				'L_COLORS' => $LANG['colors'],
				'L_PERCENTAGE' => $LANG['percentage']
			));
			$stats_menu = 'lang';
			$array_stats_info = $stats_array_lang;
			$path = '../images/stats/countries/';
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
				$stats_img = '<img src="' . PATH_TO_ROOT . '/images/stats/other.png" alt="' . $LANG['other'] . '" />';
				$name_stats = $LANG['other'];
			}
			else
			{
				$stats_img = !empty($array_stats_info[$value_name][1]) ? '<img src="' . $path . $array_stats_info[$value_name][1] . '" alt="' . $array_stats_info[$value_name][0] . '" />' : '-';
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
		$stats_array = array();
		$robots_visits_number = 0;
		if (is_array($array_robot))
		{
			foreach ($array_robot as $key => $value)
			{
				if ($key == 'unknow_bot')
					$key = addslashes($LANG['unknown_bot']);
				
				$array_info = explode('/', $value);
				$robots_visits_number = $robots_visits_number + $array_info[0];
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
		
		if ($robots_visits_number)
		{
			$Stats = new ImagesStats();
			$Stats->load_data($stats_array, 'ellipse');
			foreach ($Stats->data_stats as $key => $angle_value)
			{
					$array_color = $Stats->array_allocated_color[$Stats->image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
					$name = ucfirst($key);
					$tpl->assign_block_vars('list', array(
						'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
						'VIEWS' => NumberHelper::round(($angle_value * $Stats->nbr_entry)/360, 0),
						'PERCENT' => NumberHelper::round(($angle_value/3.6), 1),
						'L_NAME' => ($name == 'Other') ? $LANG['other'] : $name
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
			'VERSION' => Environment::get_phpboost_version(),
			'BUILD' => GeneralConfig::load()->get_phpboost_major_version(),
			'L_START' => $LANG['start'],
			'L_KERNEL_VERSION' => $LANG['kernel_version']
		));
	}

	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>