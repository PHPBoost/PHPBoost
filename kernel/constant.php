<?php
/*##################################################
 *                               constant.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright            : (C) 2005 Viarre Régis
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

@ini_set('open_basedir', NULL);
@set_magic_quotes_runtime(0); //Désactivation du magic_quotes_runtime (échappe les guillemets des sources externes).
//Si register_globals activé, suppression des variables qui trainent.
if (@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
{
    require_once(PATH_TO_ROOT . '/kernel/framework/util/unusual_functions.inc.php');
    securit_register_globals();
}

//Magic quotes
if (get_magic_quotes_gpc())
{
    //If magic_quotes_sybase is enabled
    if (ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase')) != "off"))
    {
        //We consider the magic quotes as disabled
        define('MAGIC_QUOTES', false);

        //We treat the content: it must be as if the magic_quotes option is disabled
        foreach ($_REQUEST as $var_name => $value)
        {
            $_REQUEST[$var_name] = str_replace('\'\'', '\'', $value);
        }
    }
    //Magic quotes GPC
    else
    {
        define('MAGIC_QUOTES', true);
    }
}
else
{
    define('MAGIC_QUOTES', false);
}

### Définition des constantes utiles. ###
define('GUEST_LEVEL', 		-1); //Niveau Visiteur.
define('MEMBER_LEVEL', 		0); //Niveau Membre.
define('MODO_LEVEL', 		1); //Niveau Modo.
define('MODERATOR_LEVEL', 	1); //Niveau Modo.
define('ADMIN_LEVEL', 		2); //Niveau Admin.
define('SCRIPT', 			$_SERVER['PHP_SELF']); //Adresse relative à la racine du script.
define('QUERY_STRING', 		addslashes($_SERVER['QUERY_STRING'])); //Récupère la chaine de variables $_GET.
define('PHPBOOST', 			true); //Permet de s'assurer des inclusions.
define('ERROR_REPORTING', 	E_ALL | E_NOTICE);
define('E_TOKEN', 			-3); // Token error
define('E_USER_REDIRECT', 	-1); //Erreur avec redirection
define('E_USER_SUCCESS', 	-2); //Succès.
define('HTML_UNPROTECT', 	false); //Non protection de l'html.

### Autorisations ###
define('AUTH_MENUS', 		0x01); //Autorisations en lecture des menus.
define('AUTH_FILES', 		0x01); //Configuration générale des fichiers
define('AUTH_MAINTAIN', 	0x01); //Autorisations d'accès du site maintenance.
define('ACCESS_MODULE', 	0x01); //Accès à un module.
define('AUTH_FLOOD', 		'auth_flood'); //Droit de flooder.
define('PM_GROUP_LIMIT', 	'pm_group_limit'); //Aucune limite de messages privés.
define('DATA_GROUP_LIMIT', 	'data_group_limit'); //Aucune limite de données uploadables.

//Types des variables en request.
define('GET', 		1);
define('POST', 		2);
define('REQUEST', 	3);
define('COOKIE', 	4);
define('FILES', 	5);

define('TBOOL', 			'boolean');
define('TINTEGER', 			'integer');
define('TDOUBLE', 			'double');
define('TFLOAT', 			'double');
define('TSTRING', 			'string');
define('TSTRING_PARSE', 	'string_parse');
define('TSTRING_UNCHANGE', 	'string_unsecure');
define('TSTRING_HTML', 		'string_html');
define('TSTRING_AS_RECEIVED', 'string_unchanged');
define('TARRAY', 			'array');
define('TUNSIGNED_INT', 	'uint');
define('TUNSIGNED_DOUBLE', 	'udouble');
define('TUNSIGNED_FLOAT', 	'udouble');
define('TNONE', 			'none');

define('USE_DEFAULT_IF_EMPTY', 1);

//Récupération de l'ip, essaye de récupérer la véritable ip avec un proxy.
if ($_SERVER)
{
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif (isset($_SERVER['HTTP_CLIENT_IP']))
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
}
else
{
    if (getenv('HTTP_X_FORWARDED_FOR'))
    {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_CLIENT_IP'))
    {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    else
    {
        $ip = getenv('REMOTE_ADDR');
    }
}
define('USER_IP', addslashes($ip));

// Regex multiplicity options
define('REGEX_MULTIPLICITY_NOT_USED', 0x01);
define('REGEX_MULTIPLICITY_OPTIONNAL', 0x02);
define('REGEX_MULTIPLICITY_REQUIRED', 0x03);
define('REGEX_MULTIPLICITY_AT_LEAST_ONE', 0x04);
define('REGEX_MULTIPLICITY_ALL', 0x05);

?>