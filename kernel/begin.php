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

defined('PATH_TO_ROOT') or define('PATH_TO_ROOT', '..');

require_once PATH_TO_ROOT . '/kernel/framework/core/environment/environment.class.php';
Environment::load_imports();

/* DEPRECATED VARS */
$Errorh = new Errors();
$Cache = new Cache();
/* END DEPRECATED */

Environment::init();

/* DEPRECATED VARS */
$Sql = EnvironmentServices::get_sql();
$Bread_crumb = EnvironmentServices::get_breadcrumb();
$Session = EnvironmentServices::get_session();
$User = EnvironmentServices::get_user();

// This is also a deprecated variable and has to be created
// after the environment initialization
$Template = new DeprecatedTemplate();
/* END DEPRECATED */

?>