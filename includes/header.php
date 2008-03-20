<?php
/*##################################################
 *                                header.php
 *                            -------------------
 *   begin                : July 09, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

if( defined('PHP_BOOST') !== true) exit;

if( !defined('TITLE') )
	define('TITLE', $LANG['unknow']);
	
$Session->Session_check(TITLE); //Vérification de la session.

//Gestion de la maintenance du site.
if( $CONFIG['maintain'] > time() )
{	
	if( !$Member->Check_level(ADMIN_LEVEL) ) //Non admin.
	{
		if( SCRIPT !== (DIR . '/includes/maintain.php') ) //Evite de créer une boucle infine.
			redirect(HOST . DIR . '/member/maintain.php');
	}
	elseif( $CONFIG['maintain_display_admin'] ) //Affichage du message d'alerte à l'administrateur.
	{
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
			
			//Calcul du format de la date
			$seconds = gmdate_format('s', $CONFIG['maintain'], TIMEZONE_SITE);
			$array_release = array(
			gmdate_format('Y', $CONFIG['maintain'], TIMEZONE_SITE), (gmdate_format('n', $CONFIG['maintain'], TIMEZONE_SITE) - 1), gmdate_format('j', $CONFIG['maintain'], TIMEZONE_SITE), 
			gmdate_format('G', $CONFIG['maintain'], TIMEZONE_SITE), gmdate_format('i', $CONFIG['maintain'], TIMEZONE_SITE), ($seconds < 10) ? trim($seconds, 0) : $seconds);
		}	
		else //Délai indéterminé.
		{	
			$key = -1;
			$array_release = array('', '', '', '', '', '');
		}

		$Template->Assign_vars(array(	
			'C_ALERT_MAINTAIN' => true,
			'DELAY' => isset($array_delay[$key + 1]) ? $array_delay[$key + 1] : '0',
			'L_MAINTAIN_DELAY' => $LANG['maintain_delay'],
			'L_LOADING' => $LANG['loading'],
			'L_RELEASE_FORMAT' => implode(',', $array_release),
			'L_DAYS' => $LANG['days'],
			'L_HOURS' => $LANG['hours'],
			'L_MIN' => $LANG['minutes'],
			'L_SEC' => $LANG['seconds'],
		));
	}
}
	
$Template->Set_filenames(array(
	'header' => '../templates/' . $CONFIG['theme'] . '/header.tpl',
	'subheader' => '../templates/' . $CONFIG['theme'] . '/subheader.tpl',
	'topcentral' => '../templates/' . $CONFIG['theme'] . '/topcentral.tpl'
));

$alternative_css = '';
if( defined('ALTERNATIVE_CSS') )
{	
	if( file_exists('../templates/' . $CONFIG['theme'] . '/' . ALTERNATIVE_CSS . '/' . ALTERNATIVE_CSS . '.css') )
		$alternative_css = '../templates/' . $CONFIG['theme'] . '/' . ALTERNATIVE_CSS . '/' . ALTERNATIVE_CSS . '.css';
	else
		$alternative_css = '../' . ALTERNATIVE_CSS . '/templates/' . ALTERNATIVE_CSS . '.css';
	$alternative_css =	'<link rel="stylesheet" href="' . $alternative_css . '" type="text/css" media="screen, handheld" />';
}

//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
$THEME = load_ini_file('../templates/' . $CONFIG['theme'] . '/config/', $CONFIG['lang']);

$member_connected = $Member->Check_level(MEMBER_LEVEL);
$Template->Assign_vars(array(
	'SERVER_NAME' => $CONFIG['site_name'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => stripslashes(TITLE),
	'SITE_DESCRIPTION' => $CONFIG['site_desc'],
	'SITE_KEYWORD' => $CONFIG['site_keyword'],
	'THEME' => $CONFIG['theme'],
	'ALTERNATIVE_CSS' => $alternative_css,
	'C_MEMBER_CONNECTED' => $member_connected ? true : false,
	'C_MEMBER_NOTCONNECTED' => $member_connected ? false : true,
	'L_XML_LANGUAGE' => $LANG['xml_lang'],	
	'L_VISIT' => $LANG['guest_s'],
	'L_TODAY' => $LANG['today'],
	'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
	'L_REQUIRE_PASSWORD' => $LANG['require_password']
));

//Si le compteur de visites est activé, on affiche le tout.
if( $CONFIG['compteur'] == 1 )
{
	$compteur = $Sql->Query_array('compteur', 'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);
	$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
	$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';
	
	$Template->Assign_vars(array(
		'C_COMPTEUR' => true,
		'COMPTEUR_TOTAL' => $compteur_total,
		'COMPTEUR_DAY' => $compteur_day
	));
}

//Gestion de l'affichage des modules.
if( !defined('NO_LEFT_COLUMN') )
	define('NO_LEFT_COLUMN', false);
if( !defined('NO_RIGHT_COLUMN') )
	define('NO_RIGHT_COLUMN', false);
	
$left_column = ($THEME['left_column'] && !NO_LEFT_COLUMN);
$right_column = ($THEME['right_column'] && !NO_RIGHT_COLUMN);	

//Début de la colonne de gauche.
if( $left_column )
{	
	$Template->Assign_vars(array(	
		'C_START_LEFT' => true
	));
}
$Template->Pparse('header');

//Gestion des blocs de subheader.
$MODULES_MINI['subheader'] = true;
include('../includes/modules_mini.php');	
$MODULES_MINI['subheader'] = false;

$Template->Pparse('subheader');

if( $left_column ) //Gestion des blocs de gauche.
{
	$Template->Set_filenames(array(
		'end_left' => '../templates/' . $CONFIG['theme'] . '/end_left.tpl',
	));
	
	$MODULES_MINI['left'] = true;
	include('../includes/modules_mini.php');	
	$MODULES_MINI['left'] = false;
	
	if( !$right_column ) //Affichage des modules droits à gauche sur les thèmes à une colonne (gauche).
	{
		$MODULES_MINI['right'] = true;
		include('../includes/modules_mini.php');
		$MODULES_MINI['right'] = false;
	}

	$Template->Pparse('end_left');
}	
if( $right_column )  //Gestion des blocs de droite.
{
	$Template->Set_filenames(array(
		'start_right' => '../templates/' . $CONFIG['theme'] . '/start_right.tpl'
	));

	$Template->Pparse('start_right');	
	$MODULES_MINI['right'] = true;
	include('../includes/modules_mini.php');
	$MODULES_MINI['right'] = false;
	
	if( !$left_column ) //Affichage des modules gauches à droite sur les thèmes à une colonne (droite).
	{
		$MODULES_MINI['left'] = true;
		include('../includes/modules_mini.php');
		$MODULES_MINI['left'] = false;
	}
	
	$Template->Assign_vars(array(	
		'C_END_RIGHT' => true
	));
}

//Gestion du fil d'ariane, et des titres des pages dynamiques.
$Speed_bar->Display_speed_bar();

$MODULES_MINI['topcentral'] = true;
include('../includes/modules_mini.php');
$MODULES_MINI['topcentral'] = false;

$Template->Pparse('topcentral');

?>
