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

//On ne vérifie pas lors de la première connexion.
if( !isset($_POST['connect']) && !isset($_POST['disconnect']) ) 
	$session->session_check(SCRIPT, QUERY_STRING, TITLE); //Vérification de la session.

//Gestion de la maintenance du site.
if( $CONFIG['maintain'] != 0 && $session->data['level'] !== 2 )
{	
	if( SCRIPT !== (DIR . '/includes/maintain.php') )
	{ 
		header('location: ' . HOST . DIR . '/includes/maintain.php');
		exit;	
	}
}
	
$template->set_filenames(array(
	'header' => '../templates/' . $CONFIG['theme'] . '/header.tpl',
	'end_left' => '../templates/' . $CONFIG['theme'] . '/end_left.tpl',
	'start_right' => '../templates/' . $CONFIG['theme'] . '/start_right.tpl',
	'speed_bar' => '../templates/' . $CONFIG['theme'] . '/speed_bar.tpl'
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
$_info_theme = @parse_ini_file('../templates/' . $CONFIG['theme'] . '/config/' . $CONFIG['lang'] . '/config.ini');
	
$template->assign_vars(array(
	'SERVER_NAME' => $CONFIG['site_name'],
	'SITE_NAME' => $CONFIG['site_name'],
	'TITLE' => stripslashes(TITLE),
	'SITE_DESCRIPTION' => $CONFIG['site_desc'],
	'SITE_KEYWORD' => $CONFIG['site_keyword'],
	'THEME' => $CONFIG['theme'],
	'ALTERNATIVE_CSS' => $alternative_css,
	'L_XML_LANGUAGE' => $LANG['xml_lang'],	
	'L_VISIT' => $LANG['guest_s'],
	'L_TODAY' => $LANG['today'],
	'L_REQUIRE_PSEUDO' => $LANG['require_pseudo'],
	'L_REQUIRE_PASSWORD' => $LANG['require_password']
));

//Si le compteur de visites est activé, on affiche le tout.
if( $CONFIG['compteur'] == 1 )
{
	$compteur = $sql->query_array('compteur', 'ip AS nbr_ip', 'total', 'WHERE id = "1"', __LINE__, __FILE__);
	$compteur_total = !empty($compteur['nbr_ip']) ? $compteur['nbr_ip'] : '1';
	$compteur_day = !empty($compteur['total']) ? $compteur['total'] : '1';
	
	$template->assign_block_vars('compteur', array(
		'COMPTEUR_TOTAL' => $compteur_total,
		'COMPTEUR_DAY' => $compteur_day
	));
}

//Gestion de l'affichage des modules.
if( !defined('NO_LEFT_COLUMN') )
	define('NO_LEFT_COLUMN', false);
if( !defined('NO_RIGHT_COLUMN') )
	define('NO_RIGHT_COLUMN', false);
	
$left_column = ($_info_theme['left_column'] && !NO_LEFT_COLUMN);
$right_column = ($_info_theme['right_column'] && !NO_RIGHT_COLUMN);	

//Début de la colonne de gauche.
if( $left_column )
	$template->assign_block_vars('start_left', array(	
	));

$template->pparse('header');

if( $left_column ) 
{
	//Gestion des blocs.
	$BLOCK_top = true;
	$BLOCK_bottom = false;
	include('../includes/modules_mini.php');	
	
	if( !$right_column ) //Affichage des modules droits à gauche sur les thèmes à une colonne (gauche).
	{
		$BLOCK_top = false;
		$BLOCK_bottom = true;
		include('../includes/modules_mini.php');
	}

	$template->pparse('end_left');
}	
if( $right_column )
{
	$template->pparse('start_right');	
	$BLOCK_top = false;
	$BLOCK_bottom = true;
	include('../includes/modules_mini.php');
	
	if( !$left_column ) //Affichage des modules gauches à droite sur les thèmes à une colonne (droite).
	{
		$BLOCK_top = true;
		$BLOCK_bottom = false;
		include('../includes/modules_mini.php');
	}
	
	$template->assign_block_vars('end_right', array(	
	));
}

//Gestion du fil d'ariane, et des titres des pages dynamiques.
include_once('../includes/speed_bar.php');	
?>