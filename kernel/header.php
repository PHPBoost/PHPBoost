<?php
/*##################################################
 *                                header.php
 *                            -------------------
 *   begin                : July 09, 2005
 *   copyright            : (C) 2005 Viarre Régis
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

if( defined('PHPBOOST') !== true) 
	exit;

if( !defined('TITLE') )
    define('TITLE', $LANG['unknow']);

$Session->session_check(TITLE); //Vérification de la session.

$Template->Set_filenames(array(
	'header'=> 'header.tpl'
));

//Gestion de la maintenance du site.
if( $CONFIG['maintain'] > time() )
{	
	if( !$Member->Check_level(ADMIN_LEVEL) ) //Non admin.
	{
		if( SCRIPT !== (DIR . '/member/maintain.php') ) //Evite de créer une boucle infine.
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

//Ajout des éventuels css alternatifs du module.
$alternative_css = '';
if( defined('ALTERNATIVE_CSS') )
{	
	$array_alternative_css = explode(',', str_replace(' ', '', ALTERNATIVE_CSS));
	$module = $array_alternative_css[0];
	foreach($array_alternative_css as $alternative)
	{
		if( file_exists(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module . '/' . $alternative . '.css') )
			$alternative = PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/modules/' . $module . '/' . $alternative . '.css';
		else
			$alternative = PATH_TO_ROOT . '/' . $module . '/templates/' . $alternative . '.css';
		$alternative_css .= '<link rel="stylesheet" href="' . $alternative . '" type="text/css" media="screen, handheld" />' . "\n";
	}
}

//On ajoute les css associés aux mini-modules.
$Cache->Load_file('css');
foreach($CSS as $css_mini_module)
	$alternative_css .= "\t\t" . '<link rel="stylesheet" href="' . PATH_TO_ROOT . $css_mini_module . '" type="text/css" media="screen, handheld" />' . "\n";

//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
$THEME = load_ini_file(PATH_TO_ROOT . '/templates/' . $CONFIG['theme'] . '/config/', $CONFIG['lang']);

$member_connected = $Member->Check_level(MEMBER_LEVEL);
$Template->Assign_vars(array(
	'SID' => SID,
	'SERVER_NAME' => $CONFIG['site_name'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => stripslashes(TITLE),
	'SITE_DESCRIPTION' => $CONFIG['site_desc'],
	'SITE_KEYWORD' => $CONFIG['site_keyword'],
	'THEME' => $CONFIG['theme'],
	'LANG' => $CONFIG['lang'],
	'ALTERNATIVE_CSS' => $alternative_css,
	'C_MEMBER_CONNECTED' => $member_connected,
	'C_MEMBER_NOTCONNECTED' => !$member_connected,
	'L_XML_LANGUAGE' => $LANG['xml_lang'],	
	'L_VISIT' => $LANG['guest_s'],
	'L_TODAY' => $LANG['today'],
	'PATH_TO_ROOT' => PATH_TO_ROOT,
	'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
	'L_REQUIRE_PASSWORD' => $LANG['require_password']
));

//Inclusion des modules
if( @!include_once(PATH_TO_ROOT . '/cache/modules_mini.php') )
{
	$Cache->Generate_file('modules_mini');
	
	//On inclue une nouvelle fois
	if( @!include_once(PATH_TO_ROOT . '/cache/modules_mini.php') )
		$Errorh->Error_handler($LANG['e_cache_modules'], E_USER_ERROR, __LINE__, __FILE__);
}
$Template->Assign_vars(array(
	'MODULES_MINI_HEADER_CONTENT' =>$MODULES_MINI['header'],
	'MODULES_MINI_SUB_HEADER_CONTENT' => $MODULES_MINI['subheader']
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
		
$left_column = ($THEME_CONFIG[$CONFIG['theme']]['left_column'] && !NO_LEFT_COLUMN);
$right_column = ($THEME_CONFIG[$CONFIG['theme']]['right_column'] && !NO_RIGHT_COLUMN);	

//Début de la colonne de gauche.
if( $left_column ) //Gestion des blocs de gauche.
{
	$Template->Assign_vars(array(	
		'C_START_LEFT' => true,
		'MODULES_MINI_LEFT_CONTENT' => $MODULES_MINI['left'] . (!$right_column ? $MODULES_MINI['right'] : '') //Affichage des modules droits à gauche sur les thèmes à une colonne (gauche).
	));
}	
if( $right_column )  //Gestion des blocs de droite.
{
	$Template->Assign_vars(array(
		'C_START_RIGHT' => true,
		'MODULES_MINI_RIGHT_CONTENT' => $MODULES_MINI['right'] . (!$right_column ? $MODULES_MINI['left'] : '') //Affichage des modules gauches à droite sur les thèmes à une colonne (droite).
	));
}

//Gestion du fil d'ariane, et des titres des pages dynamiques.
$Bread_crumb->Display_bread_crumb();

$Template->Assign_vars(array(
	'MODULES_MINI_TOPCENTRAL_CONTENT' => $MODULES_MINI['topcentral']
));

$Template->Pparse('header');

?>
