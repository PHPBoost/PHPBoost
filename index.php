<?php
/*##################################################
 *                                index.php
 *                            -------------------
 *   begin                : August 23 2007
 *   copyright          : (C) 2007 CrowkaiT
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

require_once('./includes/auth/config.php'); //Fichier de configuration.
unset($sql_host, $sql_login, $sql_pass); //Destruction des identifiants bdd.
require_once('./includes/function.php');

$CONFIG = array();
@include_once('./cache/config.php');
if( !defined('PHPBOOST_INSTALLED') )
	redirect(get_install_page());
elseif( $CONFIG == array() )
{
	$server_name = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
	$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	if( !$server_path )
		$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$install_path = trim(dirname($server_path)) . '/member/member.php';
	
	//Suppression du dossier courant, et trim du chemin de l'installateur.
	redirect('http://' . $server_name . preg_replace('`(.*)/[a-z]+/(member/member\.php)(.*)`i', '$1/$2', $install_path));
}
define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);

redirect(get_start_page());

?>