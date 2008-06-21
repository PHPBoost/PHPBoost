<?php
/***************************************************************************
 *                              syndication.php
 *                            -------------------
 *   begin                : June 01, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
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
 ###################################################
 * Print the feed in the RSS 2.0 or ATOM 1.0 format
 ###################################################*/

//Header's generation
header("Content-Type: application/xml; charset=iso-8859-1");

require_once('../kernel/begin.php');

$mode = retrieve(GET, 'feed', 'rss');
if ( !(($mode == 'atom') || ($mode == 'rss')) )
    $mode = 'rss';

// if ( $file = @file_get_contents_emulate('../cache/syndication/news.' . $mode) )
{   // If the file exist, we print it
//     echo $file;
}
// else
{   // Otherwise, we regenerate it before printing it
    require_once('../kernel/header_no_display.php');
    
    // Feeds Regeneration
    require_once('../news/syndication_regeneration.php');
    $Feed = regenerate_syndication($mode == 'rss' ? USE_RSS : USE_ATOM);

    $Feed->display();                    // Print the feed
    
	require_once('../kernel/footer_no_display.php');
}

?>