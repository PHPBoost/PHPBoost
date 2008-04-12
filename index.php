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
{
    require_once('../includes/unusual_functions.php');
    redirect(get_server_url_page('install/install.php'));
}
elseif( $CONFIG === array() )
{
    require_once('../includes/unusual_functions.php');
    redirect(get_server_url_page('member/member.php'));
}
define('DIR', $CONFIG['server_path']);
define('HOST', $CONFIG['server_name']);

redirect(get_start_page());

?>