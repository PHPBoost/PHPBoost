<?php
/***************************************************************************
 *                              syndication.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
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

header("Content-Type: application/xml; charset=iso-8859-1");
define('NO_SESSION_LOCATION', true);

require_once('../kernel/begin.php');
require_once('../kernel/header_no_display.php');

if (retrieve(GET, 'feed', 'rss') == 'rss')
{
	import('content/syndication/rss');
    $Feed = new RSS('media');
}
else
{
	import('content/syndication/atom');
    $Feed = new ATOM('media');
}

if ($Feed->is_in_cache())
{
    echo $Feed->read();
}
else
{
    require_once('media_interface.class.php');
    $media = new MediaInterface();

    $Feed->load_data($media->get_feed_data_struct());
    $Feed->cache();

    echo $Feed->export();
}

require_once('../kernel/footer_no_display.php');

?>
