<?php
/**
 * @package     Ajax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 08 23
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
*/

define('PATH_TO_ROOT', '../../..');

require_once(PATH_TO_ROOT . '/kernel/begin.php');
AppContext::get_session()->no_session_location();
require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$request = AppContext::get_request();

$login        = TextHelper::substr($request->get_postvalue('login', ''), 0, 25);
$display_name = TextHelper::substr($request->get_postvalue('display_name', ''), 0, 100);
$email        = $request->get_postvalue('mail', '');
$user_id      = $request->get_postvalue('user_id', '');

$db_querier = PersistenceContext::get_querier();

if (!empty($login) && !empty($user_id))
	echo $db_querier->count(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE login=:login AND user_id != :user_id', ['login' => $login, 'user_id' => $user_id]);
elseif (!empty($email) && !empty($user_id))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE email=:email AND user_id != :user_id', ['email' => $email, 'user_id' => $user_id]);
elseif (!empty($display_name) && !empty($user_id))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE display_name=:display_name AND user_id != :user_id', ['display_name' => $display_name, 'user_id' => $user_id]);
elseif (!empty($display_name))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE display_name=:display_name', ['display_name' => $display_name]);
elseif (!empty($login))
	echo $db_querier->count(DB_TABLE_INTERNAL_AUTHENTICATION, 'WHERE login=:login', ['login' => $login]);
elseif (!empty($email))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE email=:email', ['email' => $email]);
else
	echo -1;
?>
