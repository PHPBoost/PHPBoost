<?php
/*##################################################
 *                                fatal.php
 *                            -------------------
 *   begin                : April 12, 2007
 *   copyright            : (C) 2007 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *
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
 ###################################################
 */

define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/framework/functions.inc.php';

$CONFIG = array();
$Cache = new Cache();
$Cache->load('config');

$user_data = array(
    'm_user_id' => 1,
    'login' => 'login',
    'level' => ADMIN_LEVEL,
    'user_groups' => '',
    'user_lang' => $CONFIG['lang'],
    'user_theme' => DISTRIBUTION_THEME,
    'user_mail' => '',
    'user_pm' => 0,
    'user_editor' => 'bbcode',
    'user_timezone' => 1,
    'avatar' => '',
    'user_readonly' => 0,
    'user_id' => 1,
    'session_id' => ''
);
$user_groups = array();
$User = new User($user_data, $user_groups);


if (ServerEnvironmentConfig::load()->is_output_gziping_enabled())
{
    ob_start('ob_gzhandler'); //Activation de la compression de données
}
else
{
    ob_start();
}

$LANG = array();
require_once(PATH_TO_ROOT . '/lang/' . get_ulang() . '/admin.php');
require_once PATH_TO_ROOT . '/lang/' . get_ulang() . '/main.php';
require_once PATH_TO_ROOT . '/lang/' . get_ulang() . '/errors.php';

define('TPL_PATH_TO_ROOT', GeneralConfig::load()->get_site_path());

header('Content-type: text/html; charset=iso-8859-1');
header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
header('Pragma: no-cache');


$tpl = new FileTemplate('framework/fatal.tpl');
$tpl->assign_vars(array(
    'ERROR_TITLE' => $LANG['too_many_connections'],
    'ERROR_EXPLAIN' => $LANG['too_many_connections_explain'],
    'PREVIOUS_PAGE' =>  $LANG['previous_page']
));
$tpl->display();


?>