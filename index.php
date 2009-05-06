<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright            : (C) 2007 CrowkaiT
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

define('PATH_TO_ROOT', '.');

require_once(PATH_TO_ROOT.'/kernel/begin.php');

//Si PHPBoost n'est pas installé, on renvoie vers l'installateur
if (!defined('PHPBOOST_INSTALLED'))
{
    import('util/unusual_functions', INC_IMPORT);
    redirect(get_server_url_page('install/install.php'));
}
elseif (empty($CONFIG))
{   // Si la configuration n'existe pas mais que PHPBoost est installé
    // on renvoie vers la page membre du noyau dont on est sûr qu'elle existe
    import('util/unusual_functions', INC_IMPORT);
    redirect(get_server_url_page('member/member.php'));
}

//Sinon, c'est que tout a bien marché, on renvoie sur la page de démarrage
$start_page = get_start_page();

if ('index.php' != strtolower(basename($start_page))) {
	if ( 'news.php' == strtolower(basename($start_page))) {
		$file = basename($start_page);
		$dir  = basename(dirname($start_page));
		require (PATH_TO_ROOT . '/' . $dir . '/' . $file);
	} else {
		redirect($start_page);
	}
} else {
	require (PATH_TO_ROOT.'/member/member.php');
}
