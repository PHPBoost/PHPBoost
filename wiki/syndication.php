<?php
/***************************************************************************
 *                              syndication.php
 *                            -------------------
 *   begin                : June 22, 2008
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

define('NO_SESSION_LOCATION', true); //Ne réactualise pas l'emplacement du visiteur/membre
require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

$idcat = retrieve(GET, 'idcat', 0);

if (retrieve(GET, 'feed', 'rss') == 'rss')
{
    require_once('../kernel/framework/content/syndication/rss.class.php');
    $Feed = new RSS('wiki');
}
else
{
    require_once('../kernel/framework/content/syndication/atom.class.php');
    $Feed = new ATOM('wiki');
}

if ( $Feed->is_in_cache() )
{   // If the file exist, we print it
    echo $Feed->read();
}
else
{   // Otherwise, we regenerate it before printing it
    // Feeds Regeneration
    require_once('wiki_interface.class.php');
    $Wiki = new WikiInterface();
    
    $Feed->load_data($Wiki->get_feed_data_struct($idcat));
    $Feed->cache();
    
    echo $Feed->export();                    // Print the feed
}
require_once('../kernel/footer_no_display.php');

?>