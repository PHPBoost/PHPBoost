<?php
/*##################################################
 *                               content_xmlhttprequest.php
 *                            -------------------
 *   begin                : January, 25 2007
 *   copyright          : (C) 2007 Viarre Régis
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

define('PATH_TO_ROOT', '../../..');
define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.

include_once(PATH_TO_ROOT . '/kernel/begin.php');
include_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

if( !empty($_GET['preview']) ) //Prévisualisation des messages.
{
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$ftags = !empty($_POST['ftags']) ? trim($_POST['ftags']) : '';
	$contents = second_parse(stripslashes(strparse(utf8_decode($contents), explode(',', $ftags))));

	echo !empty($contents) ? $contents : '';	
}

$Sql->Sql_close(); //Fermeture de mysql*/

?>