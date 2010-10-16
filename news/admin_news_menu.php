<?php
/*##################################################
 *                               admin_news_menu.php
 *                            -------------------
 *   begin               	: August 11, 2009
 *   copyright           	: (C) 2009 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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
 ###################################################*/

if (defined('PHPBOOST') !== true) exit;

$tpl_menu = new FileTemplate('news/admin_news_menu.tpl');

$tpl_menu->Assign_vars(array(
	'L_NEWS_MANAGEMENT' => $NEWS_LANG['news_management'],
	'L_ADD_NEWS' => $NEWS_LANG['add_news'],
	'L_CONFIG_NEWS' => $NEWS_LANG['configuration_news'],
	'L_CAT_NEWS' => $NEWS_LANG['category_news'],
	'L_ADD_CAT' => $NEWS_LANG['add_category']
));

$admin_menu = $tpl_menu->render();

?>