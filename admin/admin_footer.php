<?php
/*##################################################
 *                               admin_footer.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Admin, v 1.0.0
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/


########################admin_body_footer.tpl#######################

$Template->set_filenames(array(
	'admin_footer'=> 'admin/admin_footer.tpl'
));

//On récupère la configuration du thème actuel, afin de savoir si il faut placer les séparateurs de colonnes (variable sur chaque thème).
$THEME = load_ini_file(PATH_TO_ROOT . '/templates/' . get_utheme() . '/config/', get_ulang());
	
$Template->assign_vars(array(
	'HOST' => HOST,
	'DIR' => DIR,
	'VERSION' => $CONFIG['version'],
	'THEME' => get_utheme(),
	'C_DISPLAY_AUTHOR_THEME' => ($CONFIG['theme_author'] ? true : false),
	'L_POWERED_BY' => $LANG['powered_by'],
	'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
	'L_THEME' => $LANG['theme'],
	'L_THEME_NAME' => $THEME['name'],
	'L_BY' => strtolower($LANG['by']),
	'L_THEME_AUTHOR' => $THEME['author'],
	'U_THEME_AUTHOR_LINK' => $THEME['author_link'],
    'PHPBOOST_VERSION' => $CONFIG['version']
));

if ($CONFIG['bench'])
{
	EnvironmentServices::get_bench()->stop(); //On arrête le bench.
	$Template->assign_vars(array(
		'C_DISPLAY_BENCH' => true,
		'BENCH' => EnvironmentServices::get_bench()->to_string(), //Fin du benchmark
		'REQ' => $Sql->get_executed_requests_number(),
		'L_UNIT_SECOND' => HOST,
		'L_REQ' => $LANG['sql_req'],
		'L_ACHIEVED' => $LANG['achieved'],
		'L_UNIT_SECOND' => $LANG['unit_seconds_short']
	));
}

$Template->pparse('admin_footer'); // traitement du modele

EnvironmentServices::close_sql_querier();

ob_end_flush();

?>