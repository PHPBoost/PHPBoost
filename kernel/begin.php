<?php
/*##################################################
 *                               begin.php
 *                            -------------------
 *   begin                : Februar 08, 2006
 *   copyright            : (C) 2005 Viarre Régis
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

require_once 'init.php';

DeprecatedEnvironment::check_page_auth();

/* DEPRECATED VARS */
global $Cache, $Bread_crumb;
$Cache = new Cache();
$Bread_crumb = new BreadCrumb();

global $Session, $User, $Template;
$Session = AppContext::get_session();
$User = AppContext::get_current_user();
$Template = new DeprecatedTemplate();

global $Sql;
$Sql = PersistenceContext::get_sql();
/* END DEPRECATED */

?>
