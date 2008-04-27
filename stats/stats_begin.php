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

if( defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('stats'); //Chargement de la langue du module.
include_once('../lang/' . $CONFIG['lang'] . '/stats.php'); //Chargement de la langue.

$visit = request_var(GET, 'visit', false);
$visit_year = request_var(GET, 'year', 0);
$pages = request_var(GET, 'pages', false);
$pages_year = request_var(GET, 'pages_year', 0);
$referer = request_var(GET, 'referer', false);
$keyword = request_var(GET, 'keyword', false);
$members = request_var(GET, 'members', false);
$browser = request_var(GET, 'browser', false);
$os = request_var(GET, 'os', false);
$all = request_var(GET, 'all', false);
$user_lang = request_var(GET, 'lang', false);

$l_title = $LANG['site'];
$l_title = ($visit || $visit_year) ? $LANG['guest_s'] : $l_title;
$l_title = $pages ? $LANG['page_s'] : $l_title;
$l_title = $referer ? $LANG['referer_s'] : $l_title;
$l_title = $keyword ? $LANG['keyword_s'] : $l_title;
$l_title = $members ? $LANG['member_s'] : $l_title;
$l_title = $browser ? $LANG['browser_s'] : $l_title;
$l_title = $os ? $LANG['os'] : $l_title;
$l_title = $user_lang ? $LANG['stat_lang'] : $l_title;
$l_title = !empty($l_title) ? $l_title : '';

if( !empty($l_title) ) 
	$Bread_crumb->Add_link($LANG['stats'], transid('stats.php'));
	$Bread_crumb->Add_link($l_title, '');	
define('TITLE', $LANG['stats'] . (!empty($l_title) ? ' - ' . $l_title : ''));

?>