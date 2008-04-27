<?php
/*##################################################
 *                              rss.class.php
 *                            -------------------
 *   begin                : March 10, 2005
 *   copyright            : (C) 2005 Régis Viarre, Loïc Rouchon
 *   email                : crowkait@phpboost.com, horn@phpboost.com
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

class RSS
{
    ## Public Methods #
    function RSS($feedName, $feedPath)
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
        $file = file_get_contents($this->path . $this->name . '.rss');
        if( $file !== false )
        {
            if( preg_match('`<item>(.*)</item>`is', $file) ) 
            {
                $parsed = array();
                $parsed['items'] = explode('<item>', $file);
                $nbItems = count($parsed['items']);
                
                $parsed['date'] = $parsed['items'][0];
                $parsed['title'] = $parsed['items'][0];
                $parsed['host'] = $parsed['items'][0];
                $parsed['desc'] = $parsed['items'][0];
                $parsed['lang'] = $parsed['items'][0];
                
                unset($parsed['items'][0]);
                
                for($i = 1; $i < $nbItems; $i++)
                {
                    $url = preg_match('`<link>(.*)</link>`is', $parsed['items'][$i], $url) ? $url[1] : '';
                    $title = preg_match('`<title>(.*)</title>`is', $parsed['items'][$i], $title) ? $title[1] : '';
                    $date = preg_match('`<pubDate>(.*)</pubDate>`is', $parsed['items'][$i], $date) ? gmdate_format('date_format_tiny', strtotime($date[1])) : '';
                    $parsed['items']['link'] = $url;
                    $parsed['items']['title'] = $title;
                    $parsed['items']['date'] = $date;
                }
            }
        }
        else return false;
    }

    function Generate(&$feedInformations)
    /**
     * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
     * and also the HTML cache for direct includes.
     */
    {
        require_once('../kernel/framework/template.class.php');
        $Template = new Templates('syndication/rss.tpl');
        
        $Template->Assign_vars(array(
            'DATE' => isset($feedInformations['date']) ? $feedInformations['date'] : '',
            'TITLE' => isset($feedInformations['title']) ? $feedInformations['title'] : '',
            'HOST' => HOST,
            'DESC' => isset($feedInformations['desc']) ? $feedInformations['desc'] : '',
            'LANG' => isset($feedInformations['lang']) ? $feedInformations['lang'] : ''
        ));
        
        if ( isset($feedInformations['items']) )
        {
            foreach ( $feedInformations['items'] as $item )
            {
                $Template->Assign_block_vars('item', array(
                    'DATE' => $item['date'],
                    'U_LINK' => $item['link'],
                    'TITLE' => $item['title']
                ));
            }
        }
        
        $file = fopen($this->path . $this->name . '.rss', 'w+');
        fputs($file, $Template->Tparse(TEMPLATE_STRING_MODE));
        fclose($file);
    }
    
    ## Private Methods ##
    
    ## Private attributes ##
    var $name = ''; // Feed Name
    var $path = ''; // Path where the feeds are stored
}

?>