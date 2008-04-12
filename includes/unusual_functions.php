<?php

/*##################################################
 *                                constant.php
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

//Si register_globals activé, suppression des variables globales qui trainent. Fonction emprunté à phpBB3
function securit_register_globals()
{
    $not_unset = array(
        'GLOBALS'   => true,
        '_GET'      => true,
        '_POST'     => true,
        '_COOKIE'   => true,
        '_REQUEST'  => true,
        '_SERVER'   => true,
        '_SESSION'  => true,
        '_ENV'      => true,
        '_FILES'    => true
    );

    // Merge all into one extremely huge array; unset this later
    $input = array_merge(
        array_keys($_GET),
        array_keys($_POST),
        array_keys($_COOKIE),
        array_keys($_SERVER),
        array_keys($_ENV),
        array_keys($_FILES)
    );

    foreach($input as $varname)
    {
        if( isset($not_unset[$varname]) )
        {
            // Hacking attempt. No point in continuing unless it's a COOKIE
            if( $varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']) )
                exit;
            else
            {
                $cookie = &$_COOKIE;
                while( isset($cookie['GLOBALS']) )
                {
                    foreach($cookie['GLOBALS'] as $registered_var => $value)
                    {
                        if( !isset($not_unset[$registered_var]) )
                            unset($GLOBALS[$registered_var]);
                    }
                    $cookie = &$cookie['GLOBALS'];
                }
            }
        }
        unset($GLOBALS[$varname]);
    }
    unset($input);
}

//Récupération de la page d'installation.
function get_server_url_page($path)
{
   
    $server_name = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
    $server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
    if( !$server_path )
        $server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
        
$server_name = 'http://monsite.free.fr';
$server_path =  '/phpboost/';
    $real_path = trim(dirname($server_path)) . '/'. $path;
    
    //Suppression du dossier courant, et trim du chemin de l'installateur.
    return 'http://' . $server_name . preg_replace('`(.*)/[a-z]+/('.preg_quote($path).')(.*)`i', '$1/$2', $real_path);
}

?>