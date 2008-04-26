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
define('RSS', 0x01);
define('ATOM', 0x02);

class Feed
{
    ## Public Methods #
    function Feed($feedPath, $feedName, $type = ALL_FEEDS)
    /**
     * Constructor
     */
    {
		$this->name = $feedName;
		$this->path = $feedPath;
		
        if ( $type & RSS )
		{
			require_once('../kernel/framework/syndication/rss.work.class.php');
			$this->feeds[RSS] = new Rss($feedPath, $feedName);
		}
		else if ( $type & ATOM )
		{
			require_once('../kernel/framework/syndication/atom.class.php');
			$this->feeds[ATOM] = new Atom($feedPath, $feedName);
        }
    }

    function Parse($nbItem = 5)
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array.
     */
    {
        $parsed = array();
        if ( in_array(RSS, array_keys($this->feeds)) &&
             ($file = @fopen(trim($feedPath, '/') . '/' . $feedName . RSS, 'r' )) )
        {
            
        }
        elseif ( in_array(ATOM, array_keys($this->feeds)) &&
             ($file = @fopen(trim($feedPath, '/') . '/' . $feedName . ATOM, 'r' )) )
        {
            
        }
        @fclose($file);
        return $parsed;
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
		$Template->Set_filenames(array('feed'=> $tpl));
		
		$Template->Assign_vars(array(
			'DATE' => isset($feedInformations['items']) ? $feedInformations['items'] : '',
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
		
		$Template->Pparse('feed');	
    }

    ## Private attributes ##
	var $name = ''; // Feed Name
    var $path = ''; // Path where the feeds are stored
    var $feeds = array();
}

?>