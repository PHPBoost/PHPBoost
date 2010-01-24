<?php
/*##################################################
 *                                maintain.php
 *                            -------------------
 *   begin                : tuesday 22, 2006
 *   copyright            : (C) 2006 Viarre Régis
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

include_once('../kernel/begin.php'); 
define('TITLE', $LANG['title_maintain']);
include_once('../kernel/header_no_display.php');

if ($CONFIG['maintain'] != -1 && $CONFIG['maintain'] <= time())
{	
	header('location: ' . get_home_page());
	exit;
}

$Template->set_filenames(array(
	'maintain'=> 'member/maintain.tpl'
));

//Durée de la maintenance.
$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800'); 
$array_delay = array(0 => $LANG['unspecified'], 1 => '', 2 => '1 ' . $LANG['minute'], 3 => '5 ' . $LANG['minutes'], 4 => '15 ' . $LANG['minutes'], 5 => '30 ' . $LANG['minutes'], 6 => '1 ' . $LANG['hour'], 7 => '2 ' . $LANG['hours'], 8 => '1 ' . $LANG['day'], 9 => '2 ' . $LANG['days'], 10 => '1 ' . $LANG['week']);

//Retourne le délai de maintenance le plus proche. 
if ($CONFIG['maintain'] != -1)
{
	$key = 0;
	$current_time = time();
	for ($i = 10; $i >= 0; $i--)
	{					
		$delay = ($CONFIG['maintain'] - $current_time) - $array_time[$i];		
		if ($delay >= $array_time[$i]) 
		{	
			$key = $i;
			break;
		}
	}
	
	//Calcul du format de la date
	$seconds = gmdate_format('s', $CONFIG['maintain'], TIMEZONE_SITE);
	$array_release = array(
	gmdate_format('Y', $CONFIG['maintain'], TIMEZONE_SITE), (gmdate_format('n', $CONFIG['maintain'], TIMEZONE_SITE) - 1), gmdate_format('j', $CONFIG['maintain'], TIMEZONE_SITE), 
	gmdate_format('G', $CONFIG['maintain'], TIMEZONE_SITE), gmdate_format('i', $CONFIG['maintain'], TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds );

	$seconds = gmdate_format('s', time(), TIMEZONE_SITE);
    $array_now = array(
    gmdate_format('Y', time(), TIMEZONE_SITE), (gmdate_format('n', time(), TIMEZONE_SITE) - 1), gmdate_format('j', time(), TIMEZONE_SITE),
    gmdate_format('G', time(), TIMEZONE_SITE), gmdate_format('i', time(), TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
}	
else //Délai indéterminé.
{	
	$key = -1;
	$array_release = array('0', '0', '0', '0', '0', '0');
	$array_now = array('0', '0', '0', '0', '0', '0');
}

$Template->assign_vars(array(	
	'SITE_NAME' => $CONFIG['site_name'],
	'VERSION' => $CONFIG['version'],
	'THEME' => get_utheme(),
	'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0',
	'MAINTAIN_NOW_FORMAT' => implode(',', $array_now),
	'MAINTAIN_RELEASE_FORMAT' => implode(',', $array_release),
	'U_INDEX' => !$User->check_level(ADMIN_LEVEL) ? '<a href="../admin/admin_index.php">' . $LANG['admin'] . '</a>' : '<a href="' . get_home_page() . '">' . $LANG['home'] . '</a>',	
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'L_MAINTAIN' => (!empty($CONFIG['maintain_text']) ? FormatingHelper::second_parse($CONFIG['maintain_text']) : $LANG['maintain']),
	'L_MAINTAIN_TITLE' => $LANG['title_maintain'],
	'L_LOADING' => $LANG['loading'],
	'L_DAYS' => $LANG['days'],
	'L_HOURS' => $LANG['hours'],
	'L_MIN' => $LANG['minutes'],
	'L_SEC' => $LANG['seconds'],
	'L_POWERED_BY' => $LANG['powered_by'],
	'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
	'PHPBOOST_VERSION' => $CONFIG['version']
));

if ($CONFIG['maintain_delay'] == 1 && $CONFIG['maintain'] != -1)
{
	$Template->assign_vars(array(
		'C_DISPLAY_DELAY' => true,
		'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0',
		'L_MAINTAIN_DELAY' => $LANG['maintain_delay']
	));
}

$Template->pparse('maintain');

?>