<?php
/*##################################################
 *                               footer.php
 *                            -------------------
 *   begin                : June 25, 2005
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

$template->set_filenames(array(
	'footer' => '../templates/' . $CONFIG['theme'] . '/footer.tpl'
));
	
$sql->sql_close(); //Fermeture de mysql

//On arrête le bench
$bench->end_bench('site');

$template->assign_vars(array(
	'HOST' => HOST,
	'DIR' => DIR,
	'VERSION' => $CONFIG['version'],
	'BENCH' => $bench->show_bench('site'), //Fin du benchmark
	'REQ' => $sql->req,
	'THEME' => $CONFIG['theme'],
	'L_POWERED_BY' => $LANG['powered_by'],
	'L_PHPBOOST_RIGHT' => $LANG['phpboost_right'],
	'L_REQ' => $LANG['sql_req'],
	'L_ACHIEVED' => $LANG['achieved'],
	'L_THEME' => $LANG['theme'],
	'L_THEME_NAME' => $_info_theme['name'],
	'L_BY' => strtolower($LANG['by']),
	'L_THEME_AUTHOR' => $_info_theme['author'],
	'U_THEME_AUTHOR_LINK' => $_info_theme['author_link']
));

$template->pparse('footer');				

ob_end_flush();
?>