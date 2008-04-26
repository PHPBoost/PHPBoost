<?php
/*##################################################
 *                              atom.class.php
 *                            -------------------
 *   begin                : April 21, 2008
 *   copyright         : (C) 2005 Loïc Rouchon
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
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

class ATOM extends Feed
{
	## Public Methods #
    function ATOM($feedPath, $feedName)
    /**
     * Constructor
     */
    {
		$this->name = $feedName;
		$this->path = $feedPath;
    }

    function Parse($nbItem = 5)
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array and <false> if it couldn't open the feed.
     */
    {
        
        return false;
    }

    function Generate(&$feedInformations)
    /**
     * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
	 * and also the HTML cache for direct includes.
     */
    {
        
    }

    ## Private Methods ##
    
    ## Private attributes ##
	var $name = ''; // Feed Name
    var $path = ''; // Path where the feeds are stored
}

?>