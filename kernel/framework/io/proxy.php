<?php
/*##################################################
 *                               proxy.php
 *                            -------------------
 *   begin                : June 18, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
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

/**
* @package io
*/

/**
* Constant definition
*/
define('ERROR_REPORTING', E_ALL | E_NOTICE);
define('UNAUTHORIZED_PROTOCOL', 'UNAUTHORIZED PROTOCOL');
define('INVALID_URL', 'INVALID URL');

define('PATH_TO_ROOT', '../../..');

require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php');
require_once(PATH_TO_ROOT . '/kernel/constant.php');

$url = retrieve(GET, 'url', '', TSTRING_UNCHANGE);
$content_type = retrieve(GET, 'ctype', '', TSTRING_UNCHANGE);

if ($content_type == 'iso-8859-1')
	header('Content-type: text/html; charset=iso-8859-1');
	
if (!empty($url))
{
    $authorized_protocols = array('http', 'https');
    $used_protocol = explode('://', $url);
    if (count($used_protocol) > 1)
    {
        if (in_array($used_protocol[0], $authorized_protocols))
        {
            echo @file_get_contents_emulate($url);
            exit(0);
        }
    }
    die(UNAUTHORIZED_PROTOCOL);
}
die(INVALID_URL);

?>