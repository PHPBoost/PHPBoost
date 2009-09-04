<?php
/*##################################################
 *                              stats.php
 *                            -------------------
 *   begin                : January 31, 2006
 *   copyright          : (C) 2005 Viarre Régis / Sautel Benoît
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

require_once('../kernel/begin.php');
require_once('../stats/stats_begin.php');
require_once('../kernel/header.php');

import('core/stats_saver');

$Template->set_filenames(array('stats'=> 'stats/stats.tpl'));

$Template->assign_vars(array(
	'U_STATS_SITE' => url('.php?site=1', '-site.php'),
	'U_STATS_USERS' => url('.php?members=1', '-members.php'),
	'U_STATS_VISIT' => url('.php?visit=1', '-visit.php'),
	'U_STATS_PAGES' => url('.php?pages=1', '-pages.php'),
	'U_STATS_REFERER' => url('.php?referer=1', '-referer.php'),
	'U_STATS_KEYWORD' => url('.php?keyword=1', '-keyword.php'),
	'U_STATS_BROWSER' => url('.php?browser=1', '-browser.php'),
	'U_STATS_OS' => url('.php?os=1', '-os.php'),
	'U_STATS_LANG' => url('.php?lang=1', '-lang.php'),
	'L_SITE' => $LANG['site'],
	'L_STATS' => $LANG['stats'],
	'L_USERS' => $LANG['member_s'],
	'L_VISITS' => $LANG['guest_s'],
	'L_PAGES' => $LANG['page_s'],
	'L_REFERER' => $LANG['referer_s'],
	'L_KEYWORD' => $LANG['keyword_s'],
	'L_BROWSERS' => $LANG['browser_s'],
	'L_OS' => $LANG['os'],
	'L_LANG' => $LANG['stat_lang']
));

if ($members)
{
	$last_user = $Sql->query_array(DB_TABLE_MEMBER, 'user_id', 'login', "ORDER BY user_id DESC " . $Sql->limit(0, 1), __LINE__, __FILE__);
	$nbr_member = $Sql->count_table('member', __LINE__, __FILE__);
	
	$Template->assign_vars(array(
		'C_STATS_USERS' => true,
		'LAST_USER' => $last_user['login'],
		'U_LAST_USER_ID' => url('.php?id=' . $last_user['user_id'], '-' . $last_user['user_id'] . '.php'),
		'USERS' => $nbr_member,
		'GRAPH_RESULT_THEME' => !file_exists('../cache/theme.png') ? '<img src="display_stats.php?theme=1" alt="" />' : '<img src="../cache/theme.png" alt="" />',
		'GRAPH_RESULT_SEX' => !file_exists('../cache/sex.png') ? '<img src="display_stats.php?sex=1" alt="" />' : '<img src="../cache/sex.png" alt="" />',
		'L_LAST_USER' => $LANG['last_member'],
		'L_TEMPLATES' => $LANG['theme_s'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_MSG' => $LANG['message_s'],
		'L_TOP_TEN_POSTERS' => $LANG['top_10_posters'],
		'L_COLORS' => $LANG['colors'],
		'L_USERS' => $LANG['member_s'],
		'L_SEX' => $LANG['sex']
	));
	
	$stats_array = array();
	$result = $Sql->query_while ("SELECT at.theme, COUNT(m.user_theme) AS compt
	FROM " . DB_TABLE_THEMES . " at
	LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_theme = at.theme
	GROUP BY at.theme
	ORDER BY compt DESC", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$info_theme = load_ini_file('../templates/' . $row['theme'] . '/config/', get_ulang());
		$name = isset($info_theme['name']) ? $info_theme['name'] : $row['theme'];
		$stats_array[$name] = $row['compt'];
	}
	$Sql->query_close($result);
	import('util/images_stats');
	$Stats = new Stats();
		
	$Stats->load_data($stats_array, 'ellipse');
	foreach ($Stats->data_stats as $name => $angle_value)
	{
		$array_color = $Stats->array_allocated_color[$Stats->_image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
		$Template->assign_block_vars('templates', array(
			'NBR_THEME' => number_round(($angle_value*$Stats->nbr_entry)/360, 0),
			'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
			'THEME' => ($name == 'Other') ? $LANG['other'] : $name,
			'PERCENT' => number_round(($angle_value/3.6), 1)
		));
	}

	$stats_array = array();
	$result = $Sql->query_while ("SELECT count(user_sex) as compt, user_sex
	FROM " . PREFIX . "member
	GROUP BY user_sex
	ORDER BY compt", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		switch ($row['user_sex'])
		{
			case 0:
			$name = $LANG['unknow'];
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
	$Sql->query_close($result);
	
	$Stats->color_index = 0;
	$Stats->load_data($stats_array, 'ellipse');
	foreach ($Stats->data_stats as $name => $angle_value)
	{
		$array_color = $Stats->array_allocated_color[$Stats->_image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
		$Template->assign_block_vars('sex', array(
			'NBR_MBR' => number_round(($angle_value*$Stats->nbr_entry)/360, 0),
			'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
			'SEX' => ($name == 'Other') ? $LANG['other'] : $name,
			'PERCENT' => number_round(($angle_value/3.6), 1)
		));
	}
	
	$i = 1;
	$result = $Sql->query_while("SELECT user_id, login, user_msg
	FROM " . DB_TABLE_MEMBER . "
	ORDER BY user_msg DESC
	" . $Sql->limit(0, 10), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$Template->assign_block_vars('top_poster', array(
			'ID' => $i,
			'U_USER_ID' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'LOGIN' => $row['login'],
			'USER_POST' => $row['user_msg']
		));
		
		$i++;
	}
	$Sql->query_close($result);
}
elseif ($visit || $visit_year) //Visites par jour classées par mois.
{
	//On affiche les visiteurs totaux et du jour
	$compteur = $Sql->query_array(DB_TABLE_VISIT_COUNTER, 'ip AS nbr_ip', 'total', "WHERE id = 1", __LINE__, __FILE__);
	$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
	$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';
	
	$Template->assign_vars(array(
		'L_TODAY' => $LANG['today'],
		'L_TOTAL' => $LANG['total'],
		'L_AVERAGE' => $LANG['average'],
		'L_VISITORS' => $LANG['guest_s'] . ':',
		'L_VISITS_DAY' => $LANG['guest_s'],
		'L_DAY' => $LANG['date'],
		'L_MONTH' => $LANG['month'],
		'L_SUBMIT' => $LANG['submit']
	));
	
	$time = gmdate_format('Ym');
	$current_year = substr($time, 0, 4);
	$current_month = substr($time, 4, 2);
	
	$month = retrieve(GET, 'm', (int)$current_month);
	$year = retrieve(GET, 'y', (int)$current_year);
	if ($visit_year)
		$year = $visit_year;
	
	//Gestion des mois pour s'adapter au array défini dans lang/main.php
	$array_l_months = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'],
	$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);

	if (!empty($visit_year)) //Visites par mois classées par ans.
	{
		//Années précédente et suivante
		$next_year = $visit_year + 1;
		$previous_year = $visit_year - 1;

		//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
		$info = $Sql->query_array(DB_TABLE_STATS, 'MAX(nbr) as max_month', 'SUM(nbr) as sum_month', 'COUNT(DISTINCT(stats_month)) as nbr_month', "WHERE stats_year = '" . $visit_year . "' GROUP BY stats_year", __LINE__, __FILE__);

		$Template->assign_vars(array(
			'C_STATS_VISIT' => true,
			'TYPE' => 'visit',
			'VISIT_TOTAL' => $compteur_total,
			'VISIT_DAY' => $compteur_day,
			'YEAR' => $visit_year,
			'COLSPAN' => 14,
			'SUM_NBR' => $info['sum_month'],
			'MAX_NBR' => $info['max_month'],
			'MOY_NBR' => !empty($info['nbr_month']) ? number_round($info['sum_month']/$info['nbr_month'], 1) : 1,
			'U_NEXT_LINK' =>  url('.php?year=' . $next_year),
			'U_PREVIOUS_LINK' => url('.php?year=' . $previous_year)
		));

		//Année maximale
		$info_year = $Sql->query_array(DB_TABLE_STATS, 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
		$years = '';
		for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
		{
			$selected = ($i == $year) ? ' selected="selected"' : '';
			$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$Template->assign_vars(array(
			'C_STATS_YEAR' => true,
			'STATS_YEAR' => $years
		));
		
		if (@extension_loaded('gd'))
		{
			$Template->assign_vars(array(
				'GRAPH_RESULT' => '<img src="display_stats.php?visit_year=1&amp;year=' . $visit_year . '" alt="" />'
			));
			
			//On fait la liste des visites journalières
			$result = $Sql->query_while ("SELECT stats_month, SUM(nbr) AS total
			FROM " . DB_TABLE_STATS . "
			WHERE stats_year = '" . $visit_year . "'
			GROUP BY stats_month", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//On affiche les stats numériquement dans un tableau en dessous
				$Template->assign_block_vars('value', array(
					'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
					'NBR' => $row['total']
				));
			}
			$Sql->query_close($result);
		}
		else
		{
			$result = $Sql->query_while ("SELECT SUM(nbr) AS total
			FROM " . DB_TABLE_STATS . "
			WHERE stats_year = '" . $visit_year . "'
			GROUP BY stats_month", __LINE__, __FILE__);
			$max_month = 1;
			while ($row = $Sql->fetch_assoc($result))
			{
				$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
			}
				
			$Template->assign_vars(array(
				'C_STATS_NO_GD' => true
			));
			
			$i = 1;
			$last_month = 1;
			$months_not_empty = array();
			$result = $Sql->query_while ("SELECT stats_month, SUM(nbr) AS total
			FROM " . DB_TABLE_STATS . "
			WHERE stats_year = '" . $visit_year . "'
			GROUP BY stats_month", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$diff = 0;
				if ($row['stats_month'] != $i)
				{
					$diff = $row['stats_month'] - $i;
					for ($j = 0; $j < $diff; $j++)
					{
						$Template->assign_block_vars('values', array(
							'HEIGHT' => 0
						));
					}
				}
				
				$i += $diff;
				
				//On a des stats pour ce mois-ci, on l'enregistre
				array_push($months_not_empty, $row['stats_month']);
				
				//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
				$height = $row['total'] / $max_month * 200;
				
				$Template->assign_block_vars('values', array(
					'HEIGHT' => ceil($height)
				));

				$Template->assign_block_vars('values.head', array(
				));
					
				//On affiche les stats numériquement dans un tableau en dessous
				$Template->assign_block_vars('value', array(
					'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
					'NBR' => $row['total']
				));
				
				$last_month = $row['stats_month'];
				$i++;
			}
			$Sql->query_close($result);

			//Génération des td manquants.
			$date_day = isset($date_day) ? $date_day : 1;
			for	($i = $last_month; $i < 12; $i++)
			{
				$Template->assign_block_vars('end_td', array(
					'END_TD' => '<td style="width:13px;">&nbsp;</td>'
				));
			}
			//On liste les jours en dessous du graphique
			$i = 1;
			foreach ($array_l_months as $value)
			{
				$Template->assign_block_vars('legend', array(
					'LEGEND' => (in_array($i, $months_not_empty)) ? '<a href="stats' . url('.php?m=' . $i . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . substr($value, 0, 3) . '</a>' : substr($value, 0, 3)
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
		$info = $Sql->query_array(DB_TABLE_STATS, 'MAX(nbr) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(nbr) as sum_nbr', 'AVG(nbr) as avg_nbr', "WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' GROUP BY stats_month", __LINE__, __FILE__);
			
		$Template->assign_vars(array(
			'C_STATS_VISIT' => true,
			'TYPE' => 'visit',
			'VISIT_TOTAL' => $compteur_total,
			'VISIT_DAY' => $compteur_day,
			'COLSPAN' => $array_month[$month-1] + 2,
			'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
			'MONTH' => $array_l_months[$month - 1],
			'MAX_NBR' => $info['max_nbr'],
			'MOY_NBR' => number_round($info['avg_nbr'], 1),
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
		$info_year = $Sql->query_array(DB_TABLE_STATS, 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
		$years = '';
		for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
		{
			$selected = ($i == $year) ? ' selected="selected"' : '';
			$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$Template->assign_vars(array(
			'C_STATS_YEAR' => true,
			'C_STATS_MONTH' => true,
			'STATS_YEAR' => $years,
			'STATS_MONTH' => $months
		));
		
		if (@extension_loaded('gd'))
		{
			$Template->assign_vars(array(
				'GRAPH_RESULT' => '<img src="display_stats.php?visit_month=1&amp;year=' . $year . '&amp;month=' . $month . '" alt="" />'
			));
			
			//On fait la liste des visites journalières
			$result = $Sql->query_while("SELECT nbr, stats_day AS day
			FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "'
			ORDER BY stats_day", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];
				
				//On affiche les stats numériquement dans un tableau en dessous
				$Template->assign_block_vars('value', array(
					'U_DETAILS' => $date_day . '/' . $month . '/' . $year,
					'NBR' => $row['nbr']
				));
			}
			$Sql->query_close($result);
		}
		else
		{
			//Mois selectionné.
			if (!empty($month) && !empty($year))
			{
				$Template->assign_vars(array(
					'C_STATS_NO_GD' => true
				));
				
				//On rajoute un 0 devant tous les mois plus petits que 10
				$month = ($month < 10) ? '0' . $month : $month;
				unset($i);
				
				//On fait la liste des visites journalières
				$j = 0;
				$result = $Sql->query_while("SELECT nbr, stats_day AS day
				FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "'
				ORDER BY stats_day", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					//Complétion des jours précédent le premier enregistrement du mois.
					if ($j == 0)
					{
						for ($z = 1; $z < $row['day']; $z++)
						{
							$Template->assign_block_vars('values', array(
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
							$Template->assign_block_vars('values', array(
								'HEIGHT' => 0
							));
						}
					}
					$i += $diff;
					
					//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
					$height = ($row['nbr'] / $info['max_nbr']) * 200;
					
					$Template->assign_block_vars('values', array(
						'HEIGHT' => ceil($height)
					));
					
					$Template->assign_block_vars('values.head', array(
					));
						
					$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];
						
					//On affiche les stats numériquement dans un tableau en dessous
					$Template->assign_block_vars('value', array(
						'U_DETAILS' => $date_day . '/' . $month . '/' . $year,
						'NBR' => $row['nbr']
					));

					$i++;
				}
				$Sql->query_close($result);
				
				//Génération des td manquants.
				$date_day = isset($date_day) ? $date_day : 1;
				for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
				{
					$Template->assign_block_vars('end_td', array(
						'END_TD' => '<td style="width:13px;">&nbsp;</td>'
					));
				}
				
				//On liste les jours en dessous du graphique
				for ($i = 1; $i <= $array_month[$month - 1]; $i++)
				{
					$Template->assign_block_vars('legend', array(
						'LEGEND' => $i
					));
				}
			}
		}
	}
}
elseif ($pages || $pages_year) //Pages par jour classées par mois.
{
	$time = gmdate_format('Ymj');
	$current_year = substr($time, 0, 4);
	$current_month = substr($time, 4, 2);
	$current_day = substr($time, 6, 2);

	$day = retrieve(GET, 'd', (int)$current_day);
	$month = retrieve(GET, 'm', (int)$current_month);
	if ($pages_year)
	{
		$year = $pages_year;
		$clause = '';
	}
	elseif (retrieve(GET, 'd', false))
	{
		$clause = " AND stats_month = '" . $month . "' AND stats_day = '" . $day . "'";
		$year = retrieve(GET, 'y', (int)$current_year);
	}
	else
	{
		$clause = " AND stats_month = '" . $month . "'";
		$year = retrieve(GET, 'y', (int)$current_year);
	}
	
	//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
	$info = $Sql->query_array(DB_TABLE_STATS, 'MAX(pages) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(pages) as sum_nbr', 'AVG(pages) as avg_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month', 'pages', "WHERE stats_year = '" . $year . "'" . $clause . " AND pages_detail <> '' GROUP BY stats_month", __LINE__, __FILE__);

	//On affiche les visiteurs totaux et du jour
	$compteur_total = $Sql->query("SELECT SUM(pages) FROM " . PREFIX . "stats", __LINE__, __FILE__);
	$compteur_day = array_sum(pages_displayed(NO_UPDATE_PAGES)) + 1;
	$compteur_total = $compteur_total + $compteur_day;
	$compteur_day = !empty($compteur_day) ? $compteur_day : '1';

	$Template->assign_vars(array(
		'L_TODAY' => $LANG['today'],
		'L_TOTAL' => $LANG['total'],
		'L_AVERAGE' => $LANG['average'],
		'L_VISITORS' => $LANG['page_s'] . ':',
		'L_VISITS_DAY' => $LANG['page_s'],
		'L_DAY' => $LANG['date'],
		'L_MONTH' => $LANG['month'],
		'L_SUBMIT' => $LANG['submit']
	));

	//Gestion des mois pour s'adapter au array défini dans lang/main.php
	$array_l_months = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'],
	$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);

	if (!empty($pages_year)) //Visites par mois classées par ans.
	{
		//Années précédente et suivante
		$next_year = $pages_year + 1;
		$previous_year = $pages_year - 1;
		
		//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
		$info = $Sql->query_array(DB_TABLE_STATS, 'MAX(pages) as max_nbr', 'SUM(pages) as sum_nbr', 'COUNT(DISTINCT(stats_month)) as nbr_month', "WHERE stats_year = '" . $pages_year . "' AND pages_detail <> '' GROUP BY stats_year", __LINE__, __FILE__);
	
		$Template->assign_vars(array(
			'C_STATS_VISIT' => true,
			'TYPE' => 'pages',
			'VISIT_TOTAL' => $compteur_total,
			'VISIT_DAY' => $compteur_day,
			'YEAR' => $pages_year,
			'COLSPAN' => 13,
			'SUM_NBR' => $info['sum_nbr'],
			'MAX_NBR' => $info['max_nbr'],
			'MOY_NBR' => !empty($info['nbr_month']) ? number_round($info['sum_nbr']/$info['nbr_month'], 1) : 0,
			'U_NEXT_LINK' =>  url('.php?pages_year=' . $next_year),
			'U_PREVIOUS_LINK' => url('.php?pages_year=' . $previous_year)
		));

		//Année maximale
		$info_year = $Sql->query_array(DB_TABLE_STATS, 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
		$years = '';
		for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
		{
			$selected = ($i == $year) ? ' selected="selected"' : '';
			$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$Template->assign_vars(array(
			'C_STATS_YEAR' => true,
			'STATS_YEAR' => $years
		));
		
		if (@extension_loaded('gd'))
		{
			$Template->assign_vars(array(
				'GRAPH_RESULT' => '<img src="display_stats.php?pages_year=1&amp;year=' . $pages_year . '" alt="" />'
			));
			
			//On fait la liste des visites journalières
			$result = $Sql->query_while ("SELECT stats_month, SUM(pages) AS total
			FROM " . DB_TABLE_STATS . "
			WHERE stats_year = '" . $pages_year . "'
			GROUP BY stats_month", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//On affiche les stats numériquement dans un tableau en dessous
				$Template->assign_block_vars('value', array(
					'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
					'NBR' => $row['total']
				));
			}
			$Sql->query_close($result);
		}
		else
		{
			$result = $Sql->query_while ("SELECT SUM(nbr) AS total
			FROM " . DB_TABLE_STATS . "
			WHERE stats_year = '" . $visit_year . "'
			GROUP BY stats_month", __LINE__, __FILE__);
			$max_month = 1;
			while ($row = $Sql->fetch_assoc($result))
			{
				$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
			}
						
			$Template->assign_vars(array(
				'C_STATS_NO_GD' => true
			));
			
			$i = 1;
			$last_month = 1;
			$months_not_empty = array();
			$result = $Sql->query_while ("SELECT stats_month, SUM(pages) AS total
			FROM " . DB_TABLE_STATS . "
			WHERE stats_year = '" . $pages_year . "'
			GROUP BY stats_month", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$diff = 0;
				if ($row['stats_month'] != $i)
				{
					$diff = $row['stats_month'] - $i;
					for ($j = 0; $j < $diff; $j++)
					{
						$Template->assign_block_vars('values', array(
							'HEIGHT' => 0
						));
					}
				}
				
				$i += $diff;
				
				//On a des stats pour ce mois-ci, on l'enregistre
				array_push($months_not_empty, $row['stats_month']);
				
				//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
				$height = $row['total'] / $info['max_month'] * 200;
				
				$Template->assign_block_vars('months', array(
					'HEIGHT' => ceil($height)
				));

				$Template->assign_block_vars('values.head', array(
				));
				
				//On affiche les stats numériquement dans un tableau en dessous
				$Template->assign_block_vars('value', array(
					'U_DETAILS' => '<a href="stats' . url('.php?m=' . $row['stats_month'] . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
					'NBR' => $row['total']
				));
				
				$last_month = $row['stats_month'];
				$i++;
			}
			$Sql->query_close($result);

			//Génération des td manquants.
			$date_day = isset($date_day) ? $date_day : 1;
			for	($i = $last_month; $i < 12; $i++)
			{
				$Template->assign_block_vars('end_td', array(
					'END_TD' => '<td style="width:13px;">&nbsp;</td>'
				));
			}
			//On liste les jours en dessous du graphique
			$i = 1;
			foreach ($array_l_months as $value)
			{
				$Template->assign_block_vars('legend', array(
					'LEGEND' => (in_array($i, $months_not_empty)) ? '<a href="stats' . url('.php?m=' . $i . '&amp;y=' . $pages_year . '&amp;pages=1') . '#stats">' . substr($value, 0, 3) . '</a>' : substr($value, 0, 3)
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
		$next_month = ($check_day && $month < 12) ? $month + 1 : $month;
		$next_year = ($month < 12) ? $year : $year + 1;
		$previous_day = ($day > 1) ? $day - 1 : $array_month[$month-1];
		$previous_month = ($month > 1) ? ($day == 1 ? $month - 1 : $month) : 12;
		$previous_year = ($month > 1) ? $year : $year - 1;

		$Template->assign_vars(array(
			'C_STATS_VISIT' => true,
			'TYPE' => 'pages',
			'VISIT_TOTAL' => $compteur_total,
			'VISIT_DAY' => $compteur_day,
			'SUM_NBR' => !empty($info['pages']) ? $info['pages'] : 0,
			'MONTH' => $array_l_months[$month - 1],
			'MAX_NBR' => $info['max_nbr'],
			'MOY_NBR' => number_round($info['pages']/24, 1),
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
		$info_year = $Sql->query_array(DB_TABLE_STATS, 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
		$years = '';
		for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
		{
			$selected = ($i == $year) ? ' selected="selected"' : '';
			$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}

		$Template->assign_vars(array(
			'C_STATS_DAY' => true,
			'C_STATS_MONTH' => true,
			'C_STATS_YEAR' => true,
			'STATS_DAY' => $days,
			'STATS_MONTH' => $months,
			'STATS_YEAR' => $years,
			'GRAPH_RESULT' => '<img src="display_stats.php?pages_day=1&amp;year=' . $year . '&amp;month=' . $month . '&amp;day=' . $day . '" alt="" />'
		));
		
		//On fait la liste des visites journalières
		$result = $Sql->query_while("SELECT pages, stats_day, stats_month, stats_year
		FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "'
		ORDER BY stats_day", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			$date_day = ($row['stats_day'] < 10) ? 0 . $row['stats_day'] : $row['stats_day'];
			
			//On affiche les stats numériquement dans un tableau en dessous
			$Template->assign_block_vars('value', array(
				'U_DETAILS' => '<a href="stats' . url('.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year'] . '&amp;pages=1', '-pages.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year']) . '#stats">' . $date_day . '/' . $row['stats_month'] . '/' . $row['stats_year'] . '</a>',
				'NBR' => $row['pages']
			));
		}
		$Sql->query_close($result);
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
		
		$Template->assign_vars(array(
			'C_STATS_VISIT' => true,
			'TYPE' => 'pages',
			'VISIT_TOTAL' => $compteur_total,
			'VISIT_DAY' => $compteur_day,
			'COLSPAN' => $array_month[$month-1] + 2,
			'SUM_NBR' => !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0,
			'MONTH' => $array_l_months[$month - 1],
			'MAX_NBR' => $info['max_nbr'],
			'MOY_NBR' => number_round($info['avg_nbr'], 1),
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
		$info_year = $Sql->query_array(DB_TABLE_STATS, 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
		$years = '';
		for ($i = $info_year['min_year']; $i <= $info_year['max_year']; $i++)
		{
			$selected = ($i == $year) ? ' selected="selected"' : '';
			$years .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$Template->assign_vars(array(
			'C_STATS_YEAR' => true,
			'C_STATS_MONTH' => true,
			'STATS_YEAR' => $years,
			'STATS_MONTH' => $months
		));
		
		if (@extension_loaded('gd'))
		{
			$Template->assign_vars(array(
				'GRAPH_RESULT' => '<img src="display_stats.php?pages_month=1&amp;year=' . $year . '&amp;month=' . $month . '" alt="" />'
			));
			
			//On fait la liste des visites journalières
			$result = $Sql->query_while("SELECT pages, stats_day, stats_month, stats_year
			FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "'
			ORDER BY stats_day", __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				$date_day = ($row['stats_day'] < 10) ? 0 . $row['stats_day'] : $row['stats_day'];
				
				//On affiche les stats numériquement dans un tableau en dessous
				$Template->assign_block_vars('value', array(
					'U_DETAILS' => '<a href="stats' . url('.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year'] . '&amp;pages=1', '-pages.php?d=' . $row['stats_day'] . '&amp;m=' . $row['stats_month'] . '&amp;y=' . $row['stats_year']) . '#stats">' . $date_day . '/' . $row['stats_month'] . '/' . $row['stats_year'] . '</a>',
					'NBR' => $row['pages']
				));
			}
			$Sql->query_close($result);
		}
		else
		{
			//Mois selectionné.
			if (!empty($month) && !empty($year))
			{
				$Template->assign_vars(array(
					'C_STATS_NO_GD' => true
				));
				
				//On rajoute un 0 devant tous les mois plus petits que 10
				$month = ($month < 10) ? '0' . $month : $month;
				unset($i);
				
				//On fait la liste des visites journalières
				$j = 0;
				$result = $Sql->query_while("SELECT pages, stats_day AS day, stats_month, stats_year
				FROM " . DB_TABLE_STATS . " WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "'
				ORDER BY stats_day", __LINE__, __FILE__);
				while ($row = $Sql->fetch_assoc($result))
				{
					//Complétion des jours précédent le premier enregistrement du mois.
					if ($j == 0)
					{
						for ($z = 1; $z < $row['day']; $z++)
						{
							$Template->assign_block_vars('days', array(
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
							$Template->assign_block_vars('days', array(
								'HEIGHT' => 0
							));
						}
					}
					$i += $diff;
					
					//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
					$height = ($row['pages'] / $info['max_nbr']) * 200;
					
					$Template->assign_block_vars('values', array(
						'HEIGHT' => ceil($height)
					));
					
					$Template->assign_block_vars('values.head', array(
					));
						
					$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];
						
					//On affiche les stats numériquement dans un tableau en dessous
					$Template->assign_block_vars('value', array(
						'U_DETAILS' => $date_day . '/' . $row['stats_month'] . '/' . $row['stats_year'],
						'NBR' => $row['pages']
					));

					$i++;
				}
				$Sql->query_close($result);
				
				//Génération des td manquants.
				$date_day = isset($date_day) ? $date_day : 1;
				for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
				{
					$Template->assign_block_vars('end_td', array(
						'END_TD' => '<td style="width:13px;">&nbsp;</td>'
					));
				}
				
				//On liste les jours en dessous du graphique
				for ($i = 1; $i <= $array_month[$month - 1]; $i++)
				{
					$Template->assign_block_vars('legend', array(
						'LEGEND' => $i
					));
				}
			}
		}
	}
}
elseif ($referer)
{
	import('util/pagination');
	$Pagination = new Pagination();
	
	$nbr_referer = $Sql->query("SELECT COUNT(DISTINCT(url)) FROM " . DB_TABLE_STATS_REFERER . " WHERE type = 0", __LINE__, __FILE__);
	$result = $Sql->query_while ("SELECT id, COUNT(*) as count, url, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
	FROM " . PREFIX . "stats_referer
	WHERE type = 0
	GROUP BY url
	ORDER BY total_visit DESC
	" . $Sql->limit($Pagination->get_first_msg(15, 'p'), 15), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$average = ($row['total_visit'] / $row['nbr_day']);
		if ($row['yesterday_visit'] > $average)
		{
			$trend_img = 'up.png';
			$sign = '+';
			$trend = number_round((($row['yesterday_visit'] * 100) / $average) - 100, 1);
		}
		elseif ($row['yesterday_visit'] < $average)
		{
			$trend_img = 'down.png';
			$sign = '-';
			$trend = number_round(100 - (($row['yesterday_visit'] * 100) / $average), 1);
		}
		else
		{
			$trend_img = 'right.png';
			$sign = '+';
			$trend = 0;
		}
			
		$Template->assign_block_vars('referer_list', array(
			'ID' => $row['id'],
			'URL' => $row['url'],
			'IMG_MORE' => '<img src="../templates/' . get_utheme() . '/images/upload/plus.png" alt="" onclick="XMLHttpRequest_referer(' . $row['id'] . ')" class="valign_middle" id="img_url' . $row['id'] . '" />',
			'NBR_LINKS' => $row['count'],
			'TOTAL_VISIT' => $row['total_visit'],
			'AVERAGE_VISIT' => number_round($average, 1),
			'LAST_UPDATE' => gmdate_format('date_format_short', $row['last_update']),
			'TREND' => '<img src="../templates/' . get_utheme() . '/images/admin/' . $trend_img . '" alt="" class="valign_middle" /> (' . $sign . $trend . '%)'
		));
	}
	$Sql->query_close($result);
	
	$Template->assign_vars(array(
		'C_STATS_REFERER' => true,
		'PAGINATION' => $Pagination->display('stats' . url('.php?referer=1&amp;p=%d', '-referer.php?p=%d'), $nbr_referer, 'p', 15, 3),
		'L_URL' => $LANG['url'],
		'L_TOTAL_VISIT' => $LANG['total_visit'],
		'L_AVERAGE_VISIT' => $LANG['average_visit'],
		'L_TREND' => $LANG['trend'],
		'L_LAST_UPDATE' => $LANG['last_update'],
	));
}
elseif ($keyword)
{
	import('util/pagination');
	$Pagination = new Pagination();
	
	$nbr_keyword = $Sql->query("SELECT COUNT(DISTINCT(relative_url)) FROM " . DB_TABLE_STATS_REFERER . " WHERE type = 1", __LINE__, __FILE__);
	$result = $Sql->query_while ("SELECT id, count(*) as count, relative_url, SUM(total_visit) as total_visit, SUM(today_visit) as today_visit, SUM(yesterday_visit) as yesterday_visit, nbr_day, MAX(last_update) as last_update
	FROM " . PREFIX . "stats_referer
	WHERE type = 1
	GROUP BY relative_url
	ORDER BY total_visit DESC
	" . $Sql->limit($Pagination->get_first_msg(15, 'p'), 15), __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		$average = ($row['total_visit'] / $row['nbr_day']);
		if ($row['yesterday_visit'] > $average)
		{
			$trend_img = 'up.png';
			$sign = '+';
			$trend = number_round((($row['yesterday_visit'] * 100) / $average), 1) - 100;
		}
		elseif ($row['yesterday_visit'] < $average)
		{
			$trend_img = 'down.png';
			$sign = '-';
			$trend = 100 - number_round((($row['yesterday_visit'] * 100) / $average), 1);
		}
		else
		{
			$trend_img = 'right.png';
			$sign = '+';
			$trend = 0;
		}
			
		$Template->assign_block_vars('keyword_list', array(
			'ID' => $row['id'],
			'KEYWORD' => $row['relative_url'],
			'IMG_MORE' => '<img src="../templates/' . get_utheme() . '/images/upload/plus.png" alt="" onclick="XMLHttpRequest_referer(' . $row['id'] . ')" class="valign_middle" id="img_url' . $row['id'] . '" />',
			'NBR_LINKS' => $row['count'],
			'TOTAL_VISIT' => $row['total_visit'],
			'AVERAGE_VISIT' => number_round($average, 1),
			'LAST_UPDATE' => gmdate_format('date_format_short', $row['last_update']),
			'TREND' => '<img src="../templates/' . get_utheme() . '/images/admin/' . $trend_img . '" alt="" class="valign_middle" /> (' . $sign . $trend . '%)'
		));
	}
	$Sql->query_close($result);
		
	$Template->assign_vars(array(
		'C_STATS_KEYWORD' => true,
		'PAGINATION' => $Pagination->display('stats' . url('.php?keyword=1&amp;p=%d', '-keyword.php?p=%d'), $nbr_keyword, 'p', 15, 3),
		'L_SEARCH_ENGINE' => $LANG['keyword_s'],
		'L_TOTAL_VISIT' => $LANG['total_visit'],
		'L_AVERAGE_VISIT' => $LANG['average_visit'],
		'L_TREND' => $LANG['trend'],
		'L_LAST_UPDATE' => $LANG['last_update'],
	));
}
elseif ($browser || $os || $user_lang) //Graphiques camenbert.
{
	include_once('../lang/' . get_ulang() . '/stats.php');
	
	$path = '../images/stats/';
	if (!empty($browser))
	{
		$Template->assign_vars(array(
			'C_STATS_BROWSERS' => true,
			'GRAPH_RESULT' => !file_exists('../cache/browsers.png') ? '<img src="display_stats.php?browsers=1" alt="" />' : '<img src="../cache/browsers.png" alt="" />',
			'L_BROWSERS' => $LANG['browser_s']
		));
		$stats_menu = 'browsers';
		$array_stats_info = $stats_array_browsers;
		$path = '../images/stats/browsers/';
	}
	elseif (!empty($os))
	{
		$Template->assign_vars(array(
			'C_STATS_OS' => true,
			'GRAPH_RESULT' => !file_exists('../cache/os.png') ? '<img src="display_stats.php?os=1" alt="" />' : '<img src="../cache/os.png" alt="" />',
			'L_OS' => $LANG['os']
		));
		$stats_menu = 'os';
		$array_stats_info = $stats_array_os;
		$path = '../images/stats/os/';
	}
	elseif (!empty($user_lang))
	{
		$Template->assign_vars(array(
			'C_STATS_LANG' => true,
			'GRAPH_RESULT' => !file_exists('../cache/lang.png') ? '<img src="display_stats.php?lang=1" alt="" />' : '<img src="../cache/lang.png" alt="" />',
			'L_LANG' => $LANG['stat_lang']
		));
		$stats_menu = 'lang';
		$array_stats_info = $stats_array_lang;
		$path = '../images/stats/countries/';
	}
	
	import('util/images_stats');
	$Stats = new Stats();
		
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
			$stats_img = !empty($array_stats_info['other'][1]) ? '<img src="' . $path . $array_stats_info['other'][1] . '" alt="' . $LANG['other'] . '" />' : '<img src="../templates/' . get_utheme() . '/images/stats/other.png" alt="' . $LANG['other'] . '" />';
			$name_stats = $LANG['other'];
		}
		else
		{
			$stats_img = !empty($array_stats_info[$value_name][1]) ? '<img src="' . $path . $array_stats_info[$value_name][1] . '" alt="' . $array_stats_info[$value_name][0] . '" />' : '-';
			$name_stats = $array_stats_info[$value_name][0];
		}
		
		if (!isset($array_order[$value_name]))
		{
			$array_color = $Stats->array_allocated_color[$Stats->_image_color_allocate_dark(false, NO_ALLOCATE_COLOR)];
			$array_stats_tmp[$value_name] = array($name_stats, $array_color, $stats_img);
			$array_order[$value_name] = $angle_value;
		}
	}
	
	//Affichage.
	foreach ($array_order as $value_name => $angle_value)
	{
		$Template->assign_block_vars('list', array(
			'COLOR' => 'RGB(' . trim(implode(', ', $array_stats_tmp[$value_name][1]), ', ') . ')',
			'IMG' => $array_stats_tmp[$value_name][2],
			'L_NAME' => $array_stats_tmp[$value_name][0],
			'PERCENT' => number_round(($angle_value/3.6), 1),
		));
	}
}
else
{
	$Template->assign_vars(array(
		'C_STATS_SITE' => true,
		'START' => gmdate_format('date_format_short', $CONFIG['start']),
		'VERSION' => $CONFIG['version'],
		'L_START' => $LANG['start'],
		'L_VERSION' => $LANG['version']
	));
}

$Template->pparse('stats');

require_once('../kernel/footer.php');

?>