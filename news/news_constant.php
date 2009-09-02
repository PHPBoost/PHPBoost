<?php
/*##################################################
 *                              news_constant.php
 *                            -------------------
 *   begin                : August 11, 2009
 *   copyright            : (C) 2009 Roguelon Geoffrey
 *   email                : liaght@gmail.com
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

// Authorizations of categories.
define('AUTH_NEWS_READ', 1);
define('AUTH_NEWS_CONTRIBUTE', 2);
define('AUTH_NEWS_WRITE', 4);
define('AUTH_NEWS_MODERATE', 8);

// Name of table from database
define('DB_TABLE_NEWS', PREFIX . 'news');
define('DB_TABLE_NEWS_CAT', PREFIX . 'news_cat');

// Name of syndication file.
define('NEWS_MASTER_0', PATH_TO_ROOT . '/cache/syndication/news_master_0.php');
?>