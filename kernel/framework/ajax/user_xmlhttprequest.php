<?php
/*##################################################
 *                         user_xmlhttprequest.php
 *                            -------------------
 *   begin                : August 23, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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
 ###################################################*/

define('PATH_TO_ROOT', '../../..');
define('NO_SESSION_LOCATION', true);

require_once(PATH_TO_ROOT . '/kernel/begin.php');
require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$login = substr(retrieve(POST, 'login', ''), 0, 25);
$email = retrieve(POST, 'mail', '');

$db_querier = PersistenceContext::get_querier();

if (!empty($login) && !empty($email))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE user_mail=:email AND login=:login', array('email' => $email, 'login' => $login));
elseif (!empty($login))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE login=:login', array('login' => $login));
elseif (!empty($email))
	echo $db_querier->count(DB_TABLE_MEMBER, 'WHERE user_mail=:email', array('email' => $email));
else
	echo -1;

?>