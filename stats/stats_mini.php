<?php
/*##################################################
 *                               stats.php
 *                            -------------------
 *   begin                : August 03, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

//Chargement de la langue du module.
@load_module_lang('stats', $CONFIG['lang']);

#########################Stats.tpl###########################
$template->set_filenames(array(
	'stats_mini' => '../templates/' . $CONFIG['theme'] . '/stats/stats_mini.tpl'
));

$cache->load_file('stats');
$l_member_registered = ($nbr_members > 1) ? $LANG['member_registered_s'] : $LANG['member_registered'];

$template->assign_vars(array(
	'SID' => SID,
	'L_STATS' => $LANG['stats'],
	'L_MORE_STAT' => $LANG['more_stats'],
	'L_MEMBER_REGISTERED' => sprintf($l_member_registered, $nbr_members),
	'L_LAST_REGISTERED_MEMBER' => $LANG['last_member'],
	'U_LINK_LAST_MEMBER' => '<a href="' . HOST . DIR . '/member/member' . transid('.php?id=' . $last_member_id, '-' . $last_member_id  . '.php') . '">' . $last_member_login . '</a>'
));

$template->pparse('stats_mini');

?>