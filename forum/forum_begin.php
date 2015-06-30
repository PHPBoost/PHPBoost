<?php
/*##################################################
 *                             forum_begin.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright            : (C) 2007 Viarre régis
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

if (defined('PHPBOOST') !== true)
    exit;

load_module_lang('forum'); //Chargement de la langue du module.

$config = ForumConfig::load();
require_once(PATH_TO_ROOT . '/forum/forum_defines.php');

//Supprime les menus suivant configuration du site.
$columns_disabled = ThemesManager::get_theme(AppContext::get_current_user()->get_theme())->get_columns_disabled();
if ($config->is_left_column_disabled()) 
	$columns_disabled->set_disable_left_columns(true);
if ($config->is_right_column_disabled()) 
	$columns_disabled->set_disable_right_columns(true);
    
//Fonction du forum.
require_once(PATH_TO_ROOT . '/forum/forum_functions.php');

?>
