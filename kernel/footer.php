<?php
/*##################################################
 *                               footer.php
 *                            -------------------
 *   begin                : June 25, 2005
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

if (defined('PHPBOOST') !== true)
{
    exit;
}
global $Sql, $Template, $MENUS, $LANG, $THEME, $CONFIG, $Bench;

$Sql->close(); //Fermeture de mysql

$Template->set_filenames(array(
	'footer'=> 'footer.tpl'
));

$Template->assign_vars(array(
	'HOST' => HOST,
	'DIR' => DIR,
	'THEME' => get_utheme(),
	'C_MENUS_BOTTOM_CENTRAL_CONTENT' => !empty($MENUS[BLOCK_POSITION__BOTTOM_CENTRAL]),
	'MENUS_BOTTOMCENTRAL_CONTENT' => $MENUS[BLOCK_POSITION__BOTTOM_CENTRAL],
	'C_MENUS_TOP_FOOTER_CONTENT' => !empty($MENUS[BLOCK_POSITION__TOP_FOOTER]),
	'MENUS_TOP_FOOTER_CONTENT' => $MENUS[BLOCK_POSITION__TOP_FOOTER],
	'C_MENUS_FOOTER_CONTENT' => !empty($MENUS[BLOCK_POSITION__FOOTER]),
	'MENUS_FOOTER_CONTENT' => $MENUS[BLOCK_POSITION__FOOTER],
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

//Stockage du nbr de pages vue par heures.
pages_displayed();

if ($CONFIG['bench'])
{
    $Bench->stop(); //On arrête le bench.
    $Template->assign_vars(array(
		'C_DISPLAY_BENCH' => true,
		'BENCH' => $Bench->to_string(), //Fin du benchmark
		'REQ' => $Sql->get_executed_requests_number(),
		'L_REQ' => $LANG['sql_req'],
		'L_ACHIEVED' => $LANG['achieved'],
		'L_UNIT_SECOND' => $LANG['unit_seconds_short']
    ));
}

$Template->pparse('footer');

ob_end_flush();

?>