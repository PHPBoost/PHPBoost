<?php
/*##################################################
 *                             forum_auth.php
 *                            -------------------
 *   begin                : October 18, 2007
 *   copyright          : (C) 2007 Viarre régis
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

if( defined('PHP_BOOST') !== true)	
	exit;

//Configuration générale du forum
define('FLOOD_FORUM', 0x01);
define('EDIT_MARK_FORUM', 0x02);
define('TRACK_TOPIC_FORUM', 0x04);

//Configuration sur la catégorie.
define('READ_CAT_FORUM', 0x01);
define('WRITE_CAT_FORUM', 0x02);
define('EDIT_CAT_FORUM', 0x04);

$cache->load_file('forum');

//Supprime les menus suivant configuration du site.
if( $CONFIG_FORUM['no_left_column'] == 1 ) 
	define('NO_LEFT_COLUMN', true);
if( $CONFIG_FORUM['no_right_column'] == 1 ) 
	define('NO_RIGHT_COLUMN', true);
	
?>