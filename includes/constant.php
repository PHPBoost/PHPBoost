<?php
/*##################################################
 *                                constant.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Constantes utiles
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

@include_once('auth/config.php'); //Fichier de configuration.

//PHPBoost installé? Si non redirection manuelle, car chemin non connu.
if( !defined('PHPBOOST_INSTALLED') )
{
	$server_name = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
	$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
	if( !$server_path )
		$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$install_path = trim(dirname($server_path)) . '/install/install.php';
	
	//Suppression du dossier courant, et trim du chemin de l'installateur.
	header('Location: http://' . $server_name . preg_replace('`(.*)/[a-z]+/(install/install\.php)(.*)`i', '$1/$2', $install_path));
	exit;
}

set_magic_quotes_runtime(0); //Désactivation du magic_quotes_runtime (échappe les guillemets des sources externes).
//Si register_globals activé, suppression des variables qui trainent.
if( @ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on' )
	securit_register_globals();

//Définition des constantes utiles.
define('DBTYPE', $dbtype); ///Type de base de données utilisée.
define('SCRIPT', $_SERVER['PHP_SELF']); //Adresse relative à la racine du script.
define('QUERY_STRING', addslashes($_SERVER['QUERY_STRING'])); //Récupère la chaine de variables $_GET.
define('MAGIC_QUOTES', get_magic_quotes_gpc()); //Récupère la valeur du magic quotes.
define('PHP_BOOST', 1); //Permet de s'assurer des inclusions.
define('PREFIX', $table_prefix); //Prefix des tables SQL.
define('ERROR_REPORTING', E_ALL | E_NOTICE);
define('E_USER_REDIRECT', -1); //Erreur avec redirection
define('E_USER_SUCCESS', -2); //Succès.
define('HTML_UNPROTECT', false); //Non protection de l'html.

//Récupération de l'ip, essaye de récupérer la véritable ip avec un proxy.
if( $_SERVER ) 	
{
	if( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif( isset($_SERVER['HTTP_CLIENT_IP']) ) $ip = $_SERVER['HTTP_CLIENT_IP'];
	else $ip = $_SERVER['REMOTE_ADDR'];
}
else 
{
	if( getenv('HTTP_X_FORWARDED_FOR') ) $ip = getenv('HTTP_X_FORWARDED_FOR');
	elseif( getenv('HTTP_CLIENT_IP') )	$ip = getenv('HTTP_CLIENT_IP');
	else $ip = getenv('REMOTE_ADDR');
}
//On sécurise l'ip => never trust user input!
define('USER_IP', securit($ip));

?>