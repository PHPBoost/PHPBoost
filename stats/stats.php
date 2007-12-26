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

include_once('../includes/begin.php'); 
include_once('../lang/' . $CONFIG['lang'] . '/stats.php'); //Chargement de la langue.

$visit = !empty($_GET['visit']) ? true : false;
$visit_year = !empty($_GET['year']) ? $_GET['year'] : '';
$members = !empty($_GET['members']) ? true : false;
$browser = !empty($_GET['browser']) ? true : false;
$os = !empty($_GET['os']) ? true : false;
$all = !empty($_GET['all']) ? true : false;
$user_lang = !empty($_GET['lang']) ? true : false;

$l_title = $LANG['site'];
$l_title = (!empty($_GET['visit']) || !empty($_GET['year'])) ? $LANG['guest_s'] : $l_title;
$l_title = !empty($_GET['members']) ? $LANG['member_s'] : $l_title;
$l_title = !empty($_GET['browser']) ? $LANG['browser_s'] : $l_title;
$l_title = !empty($_GET['os']) ? $LANG['os'] : $l_title;
$l_title = !empty($_GET['lang']) ? $LANG['stat_lang'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if( !empty($l_title) ) 
$speed_bar = array(
	$LANG['title_stats'] => transid('stats.php'),
	$l_title => ''
);		
define('TITLE', $LANG['title_stats'] . (!empty($l_title) ? ' - ' . $l_title : ''));
include_once('../includes/header.php'); 

if( !$groups->check_auth($SECURE_MODULE['stats'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

$template->set_filenames(array('stats' => '../templates/' . $CONFIG['theme'] . '/stats/stats.tpl'));

$template->assign_vars(array(
	'U_STATS_SITE' => transid('.php?site=1', '-site.php'),
	'U_STATS_MEMBERS' => transid('.php?members=1', '-members.php'),
	'U_STATS_VISIT' => transid('.php?visit=1', '-visit.php'),
	'U_STATS_BROWSER' => transid('.php?browser=1', '-browser.php'),
	'U_STATS_OS' => transid('.php?os=1', '-os.php'),
	'U_STATS_LANG' => transid('.php?lang=1', '-lang.php'),
	'L_SITE' => $LANG['site'],
	'L_STATS' => $LANG['stats'],
	'L_MEMBERS' => $LANG['member_s'],
	'L_VISITS' => $LANG['guest_s'],
	'L_BROWSERS' => $LANG['browser_s'],
	'L_OS' => $LANG['os'],
	'L_LANG' => $LANG['stat_lang']
));

if( !empty($members) )
{
	$last_user = $sql->query_array('member', 'user_id', 'login', "ORDER BY user_id DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
	$nbr_member = $sql->count_table('member', __LINE__, __FILE__);
	
	$template->assign_vars(array(
		'MODULE_DATA_PATH' => $template->module_data_path('stats'),
		'L_LAST_MEMBER' => $LANG['last_member'],
		'L_TEMPLATES' => $LANG['theme_s'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_MSG' => $LANG['message_s'],
		'L_TOP_TEN_POSTERS' => $LANG['top_10_posters'],
		'L_COLORS' => $LANG['colors'],
		'L_MEMBERS' => $LANG['member_s'],
		'L_SEX' => $LANG['sex']
	));
	
	$template->assign_block_vars('members', array(
		'LAST_USER' => $last_user['login'],
		'U_LAST_USER_ID' => transid('.php?id=' . $last_user['user_id'], '-' . $last_user['user_id'] . '.php'),
		'MEMBERS' => $nbr_member,
		'GRAPH_RESULT_THEME' => !file_exists('../cache/theme.png') ? '<img src="../includes/display_stats.php?theme=1" alt="" />' : '<img src="../cache/theme.png" alt="" />',
		'GRAPH_RESULT_SEX' => !file_exists('../cache/sex.png') ? '<img src="../includes/display_stats.php?sex=1" alt="" />' : '<img src="../cache/sex.png" alt="" />'
	));
	
	$stats_array = array();
	$result = $sql->query_while("SELECT at.theme, COUNT(m.user_theme) AS compt
	FROM ".PREFIX."themes AS at
	LEFT JOIN ".PREFIX."member AS m ON m.user_theme = at.theme
	GROUP BY at.theme
	ORDER BY compt DESC", __LINE__, __FILE__);
	while($row = $sql->sql_fetch_assoc($result))
	{
		$info_theme = @parse_ini_file('../templates/' . $row['theme'] . '/config/' . $CONFIG['lang'] . '/config.ini');
		$name = isset($info_theme['name']) ? $info_theme['name'] : $row['theme'];
		$stats_array[$name] = $row['compt'];
	}	
	$sql->close($result);
	include_once('../includes/stats.class.php');
	$stats = new Stats();
		
	$stats->load_statsdata($stats_array, 'ellipse');
	foreach($stats->data_stats as $name => $angle_value)
	{
		$array_color = $stats->array_allocated_color[$stats->imagecolorallocatedark(false, NO_ALLOCATE_COLOR)];
		$template->assign_block_vars('members.templates', array(
			'NBR_THEME' => arrondi(($angle_value*$stats->nbr_entry)/360, 0),
			'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
			'THEME' => ($name == 'Other') ? $LANG['other'] : $name,
			'PERCENT' => arrondi(($angle_value/3.6), 1)
		));
	}

	$stats_array = array();
	$result = $sql->query_while("SELECT count(user_sex) as compt, user_sex
	FROM ".PREFIX."member
	GROUP BY user_sex
	ORDER BY compt", __LINE__, __FILE__);
	while($row = $sql->sql_fetch_assoc($result))
	{
		switch($row['user_sex'])
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
	$sql->close($result);
	
	$stats->color_index = 0;	
	$stats->load_statsdata($stats_array, 'ellipse');
	foreach($stats->data_stats as $name => $angle_value)
	{
		$array_color = $stats->array_allocated_color[$stats->imagecolorallocatedark(false, NO_ALLOCATE_COLOR)];
		$template->assign_block_vars('members.sex', array(
			'NBR_MBR' => arrondi(($angle_value*$stats->nbr_entry)/360, 0),
			'COLOR' => 'RGB(' . $array_color[0] . ', ' . $array_color[1] . ', ' . $array_color[2] . ')',
			'SEX' => ($name == 'Other') ? $LANG['other'] : $name,
			'PERCENT' => arrondi(($angle_value/3.6), 1)
		));
	}
	
	$i = 1;		
	$result = $sql->query_while("SELECT user_id, login, user_msg 
	FROM ".PREFIX."member 
	ORDER BY user_msg DESC 
	" . $sql->sql_limit(0, 10), __LINE__, __FILE__);
	while($row = $sql->sql_fetch_assoc($result))
	{
		$template->assign_block_vars('members.top_poster', array(
			'ID' => $i,
			'U_MEMBER_ID' => transid('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
			'LOGIN' => $row['login'],
			'USER_POST' => $row['user_msg']
		));
		
		$i++;
	}
	$sql->close($result);
}
elseif( !empty($visit) ) //Visites par jour classées par mois.
{
	//On affiche les visiteurs totaux et du jour
	$compteur = $sql->query_array('compteur', 'ip AS nbr_ip', 'total', "WHERE id = 1", __LINE__, __FILE__);
	$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
	$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';
	
	$template->assign_block_vars('compteur_stats', array(
		'COMPTEUR_TOTAL' => $compteur_total,
		'COMPTEUR_DAY' => $compteur_day	
	));
	
	$template->assign_vars(array(
		'MODULE_DATA_PATH' => $template->module_data_path('stats'),
		'L_TOTAL' => $LANG['total'],
		'L_TODAY' => $LANG['today']
	));
	
	$time = date('Ym');
	$current_year = substr($time, 0, 4);
	$current_month = substr($time, 4, 2);
	
	$month = !empty($_GET['m']) ? numeric($_GET['m']) : (int) $current_month;
	$year = !empty($_GET['y']) ? numeric($_GET['y']) : $current_year;
	
	//Mois précédent et suivant
	$next_month = ($month < 12) ? $month + 1 : 1;
	$next_year = ($month < 12) ? $year : $year + 1;
	$precedent_month = ($month > 1) ? $month - 1 : 12;
	$precedent_year = ($month > 1) ? $year : $year - 1;		
	
	$template->assign_block_vars('select_month', array(
		'U_NEXT_MONTH' => transid('.php?m=' . $next_month . '&amp;y=' . $next_year . '&amp;visit=1'),
		'U_PREVIOUS_MONTH' => transid('.php?m=' . $precedent_month . '&amp;y=' . $precedent_year . '&amp;visit=1'),
	));
	
	$template->assign_vars(array(
		'L_SUBMIT' => $LANG['submit']
	));

	//Gestion des mois pour s'adapter au array défini dans lang/main.php
	$array_l_months = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'], 
	$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);

	for($i = 1; $i <= 12; $i++)
	{
		$selected = ($i == $month) ? 'selected="selected"' : '';
		$template->assign_block_vars('select_month.months', array(
			'MONTH' => $i,
			'L_MONTH' => $array_l_months[$i - 1],
			'SELECTED' => $selected
		));
	}

	//Année maximale
	$info = $sql->query_array('stats', 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
	for($i = $info['min_year']; $i <= $info['max_year']; $i++)
	{
		$selected = ($i == $year) ? 'selected="selected"' : '';
		$template->assign_block_vars('select_month.years', array(
			'YEAR' => $i,
			'SELECTED' => $selected
		));
	}

	//Mois selectionné.
	if( !empty($month) && !empty($year) )
	{	
		//Nombre de jours pour chaque mois (gestion des années bissextiles)
		$bissextile = (($year % 4) == 0) ? 29 : 28;
		$array_month = array(31, $bissextile, 31, 30, 31, 30 , 31, 31, 30, 31, 30, 31);
		
		//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
		$info = $sql->query_array('stats', 'MAX(nbr) as max_nbr', 'MIN(stats_day) as min_day', 'SUM(nbr) as sum_nbr',  "WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' GROUP BY stats_month", __LINE__, __FILE__);
			
		//Nombre total de visites
		$nbr_total = !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0;
		
		$template->assign_block_vars('month', array(
			'COLSPAN' => $array_month[$month-1] + 2,
			'SUM_NBR' => $nbr_total,
			'MONTH' => $array_l_months[$month - 1],
			'MAX_NBR' => $info['max_nbr'],
			'MOY_NBR' => arrondi($nbr_total/date('j'), 0),
			'U_YEAR' => '<a href="stats' . transid('.php?year=' . $year) . '#stats">' . $year . '</a>',
			'U_VISITS_YEAR' => '<a href="stats' . transid('.php?year=' . $year) . '#stats">' . $LANG['visits_year'] . ' ' . $year . '</a>'
		));
			
		$template->assign_vars(array(
			'THEME' => $CONFIG['theme'],
			'L_TOTAL' => $LANG['total'],
			'L_AVERAGE' => $LANG['average'],
			'L_VISITORS' => $LANG['guest_s'] . ':',
			'L_VISITS_DAY' => $LANG['guest_s'],
			'L_DAY' => $LANG['date']			
		));		
		
		//On rajoute un 0 devant tous les mois plus petits que 10
		$month = ($month < 10) ? '0' . $month : $month;
		unset($i);
		
		//On fait la liste des visites journalières
		$j = 0;
		$result = $sql->query_while("SELECT nbr, stats_day AS day FROM ".PREFIX."stats WHERE stats_year = '" . $year . "' AND stats_month = '" . $month . "' ORDER BY stats_day", __LINE__, __FILE__);
		while($row = $sql->sql_fetch_assoc($result))
		{	
			//Complétion des jours précédent le premier enregistrement du mois.
			if( $j == 0 )
			{
				for($z = 1; $z < $row['day']; $z++)
				{
					$template->assign_block_vars('month.days', array(
						'HEIGHT' => 0
					));
				}
				$j++;
			}
			//Remplissage des trous possibles entre les enregistrements.
			$i = !isset($i) ? $row['day'] : $i;
			$diff = 0;
			if( $row['day'] != $i )
			{
				$diff = $row['day'] - $i;
				for($j = 0; $j < $diff; $j++)
				{
					$template->assign_block_vars('month.days', array(
						'HEIGHT' => 0
					));
				}
			}
			$i += $diff;
			
			//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
			$height = ($row['nbr'] / $info['max_nbr']) * 200;
			
			$template->assign_block_vars('month.days', array(
				'HEIGHT' => ceil($height)
			));
			
			$template->assign_block_vars('month.days.head', array(
			));
				
			$date_day = ($row['day'] < 10) ? 0 . $row['day'] : $row['day'];
				
			//On affiche les stats numériquement dans un tableau en dessous
			$template->assign_block_vars('month.valeurs', array(
				'DAY' => $date_day . '/' . $month . '/' . $year,
				'NBR' => $row['nbr']
			));

			$i++;
		}
		$sql->close($result);
		
		//Génération des td manquants.
		$date_day = isset($date_day) ? $date_day : 1;
		for	($i = $date_day; $i < ($array_month[$month - 1] - 1); $i++)
		{
			$template->assign_block_vars('month.days', array(
				'END_TD' => '<td style="width:13px;">&nbsp;</td>'
			));
		}
		
		//On liste les jours en dessous du graphique
		for($i = 1; $i <= $array_month[$month - 1]; $i++)
		{
			$template->assign_block_vars('month.days_of_month', array(
				'DAY' => $i
			));
		}			
	}
}
elseif( !empty($visit_year) ) //Visites par mois classées par ans.
{
	//On affiche les visiteurs totaux et du jour
	$compteur = $sql->query_array('compteur', 'ip AS nbr_ip', 'total', "WHERE id = 1", __LINE__, __FILE__);
	$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['total'] : '1';
	$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';
	
	$template->assign_block_vars('compteur_stats', array(
		'COMPTEUR_TOTAL' => $compteur_total,
		'COMPTEUR_DAY' => $compteur_day
	));
	
	$template->assign_vars(array(
		'MODULE_DATA_PATH' => $template->module_data_path('stats'),
		'L_TOTAL' => $LANG['total'],
		'L_TODAY' => $LANG['today'],
	));
	
	$time = date('Ym');
	$current_year = substr($time, 0, 4);
	$current_month = substr($time, 4, 2);
	
	//Années précédente et suivante
	$next_year = $visit_year + 1;
	$precedent_year = $visit_year - 1;		
	
	$template->assign_block_vars('select_year', array(
		'U_NEXT_YEAR' =>  transid('.php?year=' . $next_year),
		'U_PREVIOUS_YEAR' => transid('.php?year=' . $precedent_year),
	));
	
	$template->assign_vars(array(
		'L_SUBMIT' => $LANG['submit']
	));

	//Gestion des mois pour s'adapter au array défini dans lang/main.php
	$array_l_months = array($LANG['january'], $LANG['february'], $LANG['march'], $LANG['april'], $LANG['may'], $LANG['june'], 
	$LANG['july'], $LANG['august'], $LANG['september'], $LANG['october'], $LANG['november'], $LANG['december']);
	
	//Année maximale
	$info = $sql->query_array('stats', 'MAX(stats_year) as max_year', 'MIN(stats_year) as min_year', '', __LINE__, __FILE__);
	for($i = $info['min_year']; $i <= $info['max_year']; $i++)
	{
		$selected = ($i == $visit_year) ? 'selected="selected"' : '';
		$template->assign_block_vars('select_year.years', array(
			'YEAR' => $i,
			'SELECTED' => $selected
		));
	}

	//Année selectionnée.
	if( !empty($visit_year) )
	{	
		//On va chercher le nombre de jours présents dans la table, ainsi que le record mensuel
		$result = $sql->query_while("SELECT SUM(nbr) AS total 
		FROM ".PREFIX."stats 
		WHERE stats_year = '" . $visit_year . "' 
		GROUP BY stats_month", __LINE__, __FILE__);
		$max_month = 1;
		$sum_months = 0;
		$nbr_month = 0;
		while($row = $sql->sql_fetch_assoc($result) )
		{
			$max_month = ($row['total'] <= $max_month) ? $max_month : $row['total'];
			$sum_months += $row['total'];
			$nbr_month++;
		}
					
		//Nombre total de visites
		$nbr_total = !empty($info['sum_nbr']) ? $info['sum_nbr'] : 0;
		
		$template->assign_block_vars('year', array(
			'YEAR' => $visit_year,
			'COLSPAN' => 13,
			'SUM_NBR' => $sum_months,
			'MAX_NBR' => $max_month,
			'MOY_NBR' => (!empty($sum_months) && !empty($nbr_month)) ? arrondi($sum_months / $nbr_month, 1) : 0
		));

		$template->assign_vars(array(
			'THEME' => $CONFIG['theme'],
			'L_TOTAL' => $LANG['total'],
			'L_AVERAGE' => $LANG['average'],
			'L_VISITORS' => $LANG['guest_s'] . ':',
			'L_VISITS_MONTH' => $LANG['guest_s'],
			'L_MONTH' => $LANG['month']
		));
		
		$i = 1; 
		$last_month = 1;
		$months_not_empty = array();
		
		$result = $sql->query_while("SELECT stats_month, SUM(nbr) AS total 
		FROM ".PREFIX."stats 
		WHERE stats_year = '" . $visit_year . "' 
		GROUP BY stats_month", __LINE__, __FILE__);
		while($row = $sql->sql_fetch_assoc($result))
		{	
			$diff = 0;
			if( $row['stats_month'] != $i )
			{
				$diff = $row['stats_month'] - $i;
				for($j = 0; $j < $diff; $j++)
				{
					$template->assign_block_vars('year.months', array(
						'HEIGHT' => 0
					));
				}
			}
			
			$i += $diff;
			
			//On a des stats pour ce mois-ci, on l'enregistre
			array_push($months_not_empty, $row['stats_month']);
			
			//On calcule la proportion (le maximum du mois tiendra toute la hauteur)
			$height = $row['total'] / $max_month * 200;
			
			$template->assign_block_vars('year.months', array(
				'HEIGHT' => ceil($height)
			));

			//On affiche les stats numériquement dans un tableau en dessous
			$template->assign_block_vars('year.valeurs', array(
				'U_MONTH' => '<a href="stats' . transid('.php?m=' . $row['stats_month'] . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . $array_l_months[$row['stats_month'] - 1] . '</a>',
				'NBR' => $row['total']
			));
			
			$last_month = $row['stats_month'];
			$i++;
		}

		//Génération des td manquants.
		$date_day = isset($date_day) ? $date_day : 1;
		for	($i = $last_month; $i < 12; $i++)
		{
			$template->assign_block_vars('year.end_td', array(
				'END_TD' => '<td style="width:13px;">&nbsp;</td>'
			));
		}
		//On liste les jours en dessous du graphique
		$i = 1;
		foreach($array_l_months as $value)
		{
			$template->assign_block_vars('year.months_of_year', array(
				'U_MONTH' => (in_array($i, $months_not_empty)) ? '<a href="stats' . transid('.php?m=' . $i . '&amp;y=' . $visit_year . '&amp;visit=1') . '#stats">' . substr($value, 0, 3) . '</a>' : substr($value, 0, 3)
			));
			$i++;
		}		
	}
}
elseif( !empty($browser) || !empty($os) || !empty($user_lang) ) //Graphiques camenbert.
{
	include_once('../lang/' . $CONFIG['lang'] . '/stats.php');
	
	$path = '../images/stats/';
	if( !empty($browser) )
	{
		$template->assign_vars(array(
			'L_BROWSERS' => $LANG['browser_s']
		));		
		$array_stats_info = $stats_array_browsers;
		$stats_menu = 'browsers';
		$path = '../images/stats/browsers/';
	}
	elseif( !empty($os) )
	{
		$template->assign_vars(array(
			'L_OS' => $LANG['os']
		));		
		$array_stats_info = $stats_array_os;
		$stats_menu = 'os';	
		$path = '../images/stats/os/';	
	}
	elseif( !empty($user_lang) )
	{	
		$template->assign_vars(array(
			'L_LANG' => $LANG['stat_lang']
		));	
		$array_stats_info = $stats_array_lang;
		$stats_menu = 'lang';
		$path = '../images/stats/countries/';
	}
	
	//On lit le fichier
	$file = @fopen('../cache/' . $stats_menu . '.txt', 'r');
	$stats_array = @fgets($file);
	$stats_array = !empty($stats_array) ? unserialize($stats_array) : array();
	@fclose($file);
	include_once('../includes/stats.class.php');
	$stats = new Stats();
		
	$stats->load_statsdata($stats_array, 'ellipse', 5);
	
	$template->assign_block_vars($stats_menu, array(
		'GRAPH_RESULT' => !file_exists('../cache/' . $stats_menu . '.png') ? '<img src="../includes/display_stats.php?' . $stats_menu . '=1" alt="" />' : '<img src="../cache/' . $stats_menu . '.png" alt="" />'
	));
	
	//Traitement des données.
	$array_stats_tmp = array();
	$array_order = array();
	$percent_other = 0;
	foreach($stats->data_stats as $value_name => $angle_value)
	{
		if( !isset($array_stats_info[$value_name]) || $value_name == 'other' ) //Autres, on additionne le tout.
		{	
			$value_name = 'other';
			$angle_value += $percent_other;
			$percent_other += $angle_value;
			$stats_img = '<img src="../templates/' . $CONFIG['theme'] . '/images/stats/other.png" alt="' . $LANG['other'] . '" />';
			$name_stats = $LANG['other'];
		}	
		else
		{
			$stats_img = !empty($array_stats_info[$value_name][1]) ? '<img src="' . $path . $array_stats_info[$value_name][1] . '" alt="' . $array_stats_info[$value_name][0] . '" />' : '-';
			$name_stats = $array_stats_info[$value_name][0];
		}
		
		$array_color = $stats->array_allocated_color[$stats->imagecolorallocatedark(false, NO_ALLOCATE_COLOR)];
		$array_stats_tmp[$value_name] = array($name_stats, $array_color, $stats_img);
		$array_order[$value_name] = $angle_value;
	}
	
	//Tri décroissant du tableau, manuel car arsort() foireuse.
	$max_min = 0;
	foreach($array_order as $key => $value)
	{
		$max = 0;
		foreach($array_order as $key2 => $value2)
		{
			if( $max < $value2 )
			{	
				$max = $value2;
				$last_key = $key2;
			}
		}
		$array_order_tmp[$last_key] = $max;
		unset($array_order[$last_key]);
	}	
	$array_order = $array_order_tmp;
	
	//Affichage.
	foreach($array_order as $value_name => $angle_value)
	{				
		$template->assign_block_vars($stats_menu . '.' . $stats_menu . '_list', array(
			'COLOR' => 'RGB(' . trim(implode(', ', $array_stats_tmp[$value_name][1]), ', ') . ')',
			'IMG' => $array_stats_tmp[$value_name][2],
			'L_NAME' => $array_stats_tmp[$value_name][0],
			'PERCENT' => arrondi(($angle_value/3.6), 1),
		));
	}
}
else
{
	$template->assign_vars(array(
		'MODULE_DATA_PATH' => $template->module_data_path('stats'),
		'L_START' => $LANG['start'],
		'L_VERSION' => $LANG['version']
	));
	
	$template->assign_block_vars('site', array(
		'START' => date($LANG['date_format'], $CONFIG['start']),
		'VERSION' => $CONFIG['version']
	));
}

$template->pparse('stats');

include("../includes/footer.php"); 

?>