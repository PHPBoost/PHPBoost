<?php
/*##################################################
 *                                maintain.php
 *                            -------------------
 *   begin                : tuesday 22, 2006
 *   copyright          : (C) 2006 Viarre Régis
 *   email                : crowkait@phpboost.com
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
define('TITLE', $LANG['title_maintain']);
include_once('../includes/header_no_display.php');

if( $CONFIG['maintain'] == 0 )
{	
	header('location: ' . get_start_page());
	exit;
}

$template->set_filenames(array(
	'maintain' => '../templates/' . $CONFIG['theme'] . '/maintain.tpl')
);

//Durée de la maintenance.
$array_time = array(0 => '-1', 1 => '0', 2 => '60', 3 => '300', 4 => '900', 5 => '1800', 6 => '3600', 7 => '7200', 8 => '86400', 9 => '172800', 10 => '604800'); 
$array_delay = array(0 => $LANG['unspecified'], 1 => '', 2 => '1 ' . $LANG['minute'], 3 => '5 ' . $LANG['minutes'], 4 => '15 ' . $LANG['minutes'], 5 => '30 ' . $LANG['minutes'], 6 => '1 ' . $LANG['hour'], 7 => '2 ' . $LANG['hours'], 8 => '1 ' . $LANG['day'], 9 => '2 ' . $LANG['days'], 10 => '1 ' . $LANG['week']);

//Retourne le délai de maintenance le plus proche. 
if( $CONFIG['maintain'] != -1 )
{
	$key = 0;
	$current_time = time();
	for($i = 10; $i >= 0; $i--)
	{					
		$delay = ($CONFIG['maintain'] - $current_time) - $array_time[$i];
		
		if( $delay >= $array_time[$i] ) 
		{	
			$key = $i;
			break;
		}
	}

	//Maintenance terminée, accès aux visiteurs de nouveau autorisé.
	if( $key == 0 )
	{
		$CONFIG['maintain'] = 0;
		$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($CONFIG)) . "' WHERE name = 'config'", __LINE__, __FILE__);
	
		###### Régénération du cache $CONFIG #######
		$cache->generate_file('config');
		
		header('location: ' . get_start_page());
		exit;
	}	
	
	//Calcul du format de la date
	$array_release = array(
	date('Y', $CONFIG['maintain']), (date('m', $CONFIG['maintain']) - 1), date('d', $CONFIG['maintain']), 
	date('H', $CONFIG['maintain']), date('i', $CONFIG['maintain']), date('s', $CONFIG['maintain']));
}	
else //Délai indéterminé.
{	
	$key = -1;
	$array_release = array('', '', '', '', '', '');
}

$template->assign_vars(array(	
	'SITE_NAME' => $CONFIG['site_name'],
	'VERSION' => $CONFIG['version'],
	'THEME' => $CONFIG['theme'],
	'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0',	
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'L_MAINTAIN' => (!empty($CONFIG['maintain_text']) ? $CONFIG['maintain_text'] : $LANG['maintain']),
	'L_LOADING' => $LANG['loading'],
	'L_REALEASE_FORMAT' => implode(',', $array_release),
	'L_DAYS' => $LANG['days'],
	'L_HOURS' => $LANG['hours'],
	'L_MIN' => $LANG['minutes'],
	'L_SEC' => $LANG['seconds'],
	'L_POWERED_BY' => $LANG['powered_by'],
	'L_PHPBOOST_RIGHT' => $LANG['phpboost_right']
));

if( $CONFIG['maintain_delay'] == 1 && $CONFIG['maintain'] != -1 )
{
	$template->assign_block_vars('delay', array(
		'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0',
		'L_MAINTAIN_DELAY' => $LANG['maintain_delay']
	));
}

//On ne propose pas la connexion à l'administrateur connecté... forcément. 
if( !$session->check_auth($session->data, 2) )
{
	$template->assign_vars(array(
		'U_INDEX' => '<a href="../admin/admin_index.php">' . $LANG['admin'] . '</a>',
	));
}
else //Admin connecté, on affiche la page de demarrage
{
	$template->assign_vars(array(
		'U_INDEX' => '<a href="' . get_start_page() . '">' . $LANG['index'] . '</a>',
	));
}


$template->pparse('maintain');

?>