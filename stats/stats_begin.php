<?php
/*##################################################
 *                              online_begin.php
 *                            -------------------
 *   begin                : November 28, 2007
 *   copyright          : (C) 2007 Viarre régis
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

if( defined('PHP_BOOST') !== true)	
	exit;
	
//Autorisation sur le module.
if( !$groups->check_auth($SECURE_MODULE['stats'], ACCESS_MODULE) )
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 

include_once('../lang/' . $CONFIG['lang'] . '/stats.php'); //Chargement de la langue.

$visit = !empty($_GET['visit']) ? true : false;
$visit_year = !empty($_GET['year']) ? numeric($_GET['year']) : '';
$pages = !empty($_GET['pages']) ? true : false;
$pages_year = !empty($_GET['pages_year']) ? numeric($_GET['pages_year']) : '';
$referer = !empty($_GET['referer']) ? true : false;
$keyword = !empty($_GET['keyword']) ? true : false;
$members = !empty($_GET['members']) ? true : false;
$browser = !empty($_GET['browser']) ? true : false;
$os = !empty($_GET['os']) ? true : false;
$all = !empty($_GET['all']) ? true : false;
$user_lang = !empty($_GET['lang']) ? true : false;

$l_title = $LANG['site'];
$l_title = (!empty($_GET['visit']) || !empty($_GET['year'])) ? $LANG['guest_s'] : $l_title;
$l_title = $pages ? $LANG['page_s'] : $l_title;
$l_title = $referer ? $LANG['referer_s'] : $l_title;
$l_title = $keyword ? $LANG['keyword_s'] : $l_title;
$l_title = $members ? $LANG['member_s'] : $l_title;
$l_title = $browser ? $LANG['browser_s'] : $l_title;
$l_title = $os ? $LANG['os'] : $l_title;
$l_title = $user_lang ? $LANG['stat_lang'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if( !empty($l_title) ) 
	$speed_bar->Add_link($LANG['title_stats'], transid('stats.php'));
	$speed_bar->Add_link($l_title, '');	
define('TITLE', $LANG['title_stats'] . (!empty($l_title) ? ' - ' . $l_title : ''));

?>