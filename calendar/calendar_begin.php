<?php
/*##################################################
 *                              calendar_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
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
	
load_module_lang('calendar'); //Chargement de la langue du module.
define('TITLE', $LANG['title_calendar']);
$calendar_config = CalendarConfig::load();

$date = new Date();
$array_time = explode('-',$date->to_date());

$year = retrieve(GET, 'y', $array_time[0]);
$year = empty($year) ? 0 : $year;
$month = retrieve(GET, 'm', $array_time[1]);
$month = empty($month) ? 0 : $month;
$day = retrieve(GET, 'd', $array_time[2]);
$day = empty($day) ? 0 : $day;
$bissextile = (date("L", mktime(0, 0, 0, 1, 1, $year)) == 1) ? 29 : 28;

$get_event = retrieve(GET, 'e', '');
$id = retrieve(GET, 'id', 0);
$add = retrieve(GET, 'add', false);
$delete = retrieve(GET, 'delete', false);
$edit = retrieve(GET, 'edit', false);

?>