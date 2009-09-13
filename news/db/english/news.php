<?php
/*##################################################
 *                                news.php
 *                            -------------------
 *   begin                : June 20, 2005
 *   copyright          : (C) 2005 Viarre Régis
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

if (defined('PHPBOOST') !== true) exit;

require_once PATH_TO_ROOT . '/news/news_constant.php';

$user_id = (isset($Session) && is_object($Session) && $User->get_attribute('user_id') != '') ? $User->get_attribute('user_id') : 1;
$Sql->query_inject("UPDATE " . DB_TABLE_NEWS . " SET user_id = '" . $user_id . "' WHERE id = 1", __LINE__, __FILE__);

?>