<?php
/*##################################################
 *                         feed.class.php
 *                            -------------------
 *   begin                : April 21, 2008
 *   copyright            : (C) 2005 Loïc Rouchon
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

define('ALL_FEEDS', 0xffffffff);
define('USE_RSS', 0x01);
define('USE_ATOM', 0x02);

class Feed
{
    ## Public Methods #
    function Feed($feedPath, $feedName, $type = ALL_FEEDS)
    /**
     * Constructor
     */
    {
		$this->name = $feedName;
		$this->path = trim($feedPath, '/') . '/';
		
        if ( $type & USE_RSS )
		{
			require_once('../kernel/framework/syndication/rss.work.class.php');
			$this->feeds[USE_RSS] = new RSS($this->path, $feedName);
		}
		else if ( $type & USE_ATOM )
		{
			require_once('../kernel/framework/syndication/atom.class.php');
			$this->feeds[USE_ATOM] = new ATOM($this->path, $feedName);
        }
    }

    function Parse($nbItem = 5)
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array.
     */
    {
        foreach ( $this->feeds as $feed )
        {
            if ( ($parsed = $feed->Parse($nbItem = 5)) !== false )
                return $parsed;
        }
        return array();
    }

    function Generate(&$feedInformations, $tpl = 'feed.tpl')
    /**
     * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
	 * and also the HTML cache for direct includes.
     */
    {
        foreach ( $this->feeds as $feed )
		{
			$feed->Generate($feedInformations);
		}
		$this->generateCache($feedInformations, $tpl);
    }

    ## Private Methods ##
	function generateCache(&$feedInformations, $tpl)
    /**
     * Generate the HTML cache for direct includes.
     */
    {
        global $Template;
		$Template->Set_filenames(array('feed'=> $tpl));
		
		$Template->Assign_vars(array(
			'DATE' => isset($feedInformations['date']) ? $feedInformations['date'] : '',
			'TITLE' => isset($feedInformations['title']) ? $feedInformations['title'] : '',
			'HOST' => HOST,	
			'DESC' => isset($feedInformations['desc']) ? $feedInformations['desc'] : '',
			'LANG' => isset($feedInformations['lang']) ? $feedInformations['lang'] : ''
		));
		
		foreach ( $feedInformations['items'] as $item )
		{
			$Template->Assign_block_vars('items', array(
				'DATE' => $item['date'],
				'U_LINK' => $item['link'],
				'TITLE' => $item['title']
			));
		}
		
		$file = fopen($this->path . $this->name, 'w+');
        fputs($file, $Template->Pparse('feed', TEMPLATE_STRING_MODE));
        fclose($file);
    }

    ## Private attributes ##
	var $name = ''; // Feed Name
    var $path = ''; // Path where the feeds are stored
    var $feeds = array();
}

?>