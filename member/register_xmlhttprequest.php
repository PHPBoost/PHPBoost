<?php
/*##################################################
 *                                register_xmlhttprequest.php
 *                            -------------------
 *   begin                : November 20, 2008
 *   copyright            : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
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

define('NO_SESSION_LOCATION', true); //Permet de ne pas mettre jour la page dans la session.
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

$login = substr(retrieve(POST, 'login', ''), 0, 25);
$email = retrieve(POST, 'mail', '');

if (!empty($login) && !empty($email))
	echo $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $email . "' AND login <> '" . $login . "'", __LINE__, __FILE__);
elseif (!empty($login))
	echo $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE login = '" . $login . "'", __LINE__, __FILE__);
elseif (!empty($email))
	echo $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER . " WHERE user_mail = '" . $email . "'", __LINE__, __FILE__);
else
	echo -1;
	
include_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');

?>