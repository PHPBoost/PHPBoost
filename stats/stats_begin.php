<?php
/*##################################################
 *                              online_begin.php
 *                            -------------------
 *   begin                : November 28, 2007
 *   copyright            : (C) 2007 Viarre régis
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

if (defined('PHPBOOST') !== true)	
	exit;
	
load_module_lang('stats'); //Chargement de la langue du module.

$visit = (bool)retrieve(GET, 'visit', false);
$visit_year = (int)retrieve(GET, 'year', 0);
$pages = (bool)retrieve(GET, 'pages', false);
$pages_year = (int)retrieve(GET, 'pages_year', 0);
$referer = (bool)retrieve(GET, 'referer', false);
$keyword = (bool)retrieve(GET, 'keyword', false);
$members = (bool)retrieve(GET, 'members', false);
$browser = (bool)retrieve(GET, 'browser', false);
$os = (bool)retrieve(GET, 'os', false);
$all = (bool)retrieve(GET, 'all', false);
$user_lang = (bool)retrieve(GET, 'lang', false);

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

if (!empty($l_title)) 
	$Bread_crumb->add($LANG['stats'], url('stats.php'));
	$Bread_crumb->add($l_title, '');	
define('TITLE', $LANG['stats'] . (!empty($l_title) ? ' - ' . $l_title : ''));
?>