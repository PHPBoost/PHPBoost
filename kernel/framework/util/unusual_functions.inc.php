<?php
/*##################################################
 *                         unusual_functions.inc.php
 *                            -------------------
 *   begin                : April 26, 2008
 *   copyright            : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

/**
* @package util
*/

/**
 * @desc Unsets all the variables automatically set by the register_globals option.
 * This function must be called only if the register_globals option is enable, otherwise it is useless.
 * @author PHPBB 3 <http://www.phpbb.com/>
 */
function cancel_register_globals_effect()
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

    foreach ($input as $varname)
    {
        if (isset($not_unset[$varname]))
        {
            // Hacking attempt. No point in continuing unless it's a COOKIE
            if ($varname !== 'GLOBALS' || isset($_GET['GLOBALS']) || isset($_POST['GLOBALS']) || isset($_SERVER['GLOBALS']) || isset($_SESSION['GLOBALS']) || isset($_ENV['GLOBALS']) || isset($_FILES['GLOBALS']))
                exit;
            else
            {
                $cookie = &$_COOKIE;
                while (isset($cookie['GLOBALS']))
                {
                    foreach ($cookie['GLOBALS'] as $registered_var => $value)
                    {
                        if (!isset($not_unset[$registered_var]))
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

?>