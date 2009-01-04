<?php
/*##################################################
 *                        extern_feed.class.php
 *                         -------------------
 *   begin                : May 29, 2008
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

import('content/syndication/feeds');

class ExternFeed
{
    ## Public Methods #
    function ExternFeed($url)
    /**
     * Constructor
     */
    {
        $this->url = $url;
        $this->str = @file_get_contents_emulate($this->url);
        
        // Vérification du type de flux
        if ( preg_match('`<rss[^>]*>`', $this->str) )
        {   // RSS
//             $this->feed = new RSS($this->name, $this->path);
//             echo 'RSS';
        }
        else if ( preg_match('`<feed[^>]+atom.*>`', $this->str) )
        {   // ATOM
//             $this->feed = new ATOM($this->name, $this->path);
//             echo 'ATOM';
        }
    }

    function display()
    /**
     * Print the feed from the rss or atom file
     */
    {
        if ( $feed = @file_get_contents_emulate($this->path . $this->name . '.' . $this->type) )
            echo $feed;
    }

    function parse($nbItem = 5)
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array.
     */
    {
        if ( ($parsed = $feed->parse($nbItem)) !== false )
        {
                return $parsed;
        }
        return array();
    }
    
    ## Private Methods ##
    function get_html_feed(&$feedInformations, $tpl)
    /**
     * Return a HTML String of a parsed feed.
     */
    {
        import('io/template');
        $Template = new Template($tpl);
        
        $Template->assign_vars(array(
            'DATE' => isset($feedInformations['date']) ? $feedInformations['date'] : '',
            'TITLE' => isset($feedInformations['title']) ? $feedInformations['title'] : '',
            'U_LINK' => isset($feedInformations['link']) ? $feedInformations['link'] : '',
            'HOST' => HOST,
            'DESC' => isset($feedInformations['desc']) ? $feedInformations['desc'] : '',
            'LANG' => isset($feedInformations['lang']) ? $feedInformations['lang'] : ''
        ));
        
        if ( isset($feedInformations['items']) )
        {
            foreach ( $feedInformations['items'] as $item )
            {
                $Template->assign_block_vars('item', array(
                    'DATE' => isset($item['date']) ? $item['date'] : '',
                    'U_LINK' => isset($item['link']) ? $item['link'] : '',
                    'U_GUID' => isset($item['guid']) ? $item['guid'] : '',
                    'DESC' => isset($item['desc']) ? $item['desc'] : '',
                    'TITLE' => isset($item['title']) ? $item['title'] : ''
                ));
            }
        }
        return $Template->parse(TEMPLATE_STRING_MODE);
    }
    
    ## Private attributes ##
    var $url = '';          // URL of the feed
    var $str = '';          // String of feed to use by default
    var $feed = '';         // Feed object
}

?>