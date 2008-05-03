<?php
/*##################################################
 *                              atom.class.php
 *                            -------------------
 *   begin                : April 21, 2008
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
    function ATOM($feedName, $feedPath)
    /**
     * Constructor
     */
    {
        $this->name = $feedName;
        $this->path = $feedPath;
    }

    function Parse($nbItems = 5)
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array and <false> if it couldn't open the feed.
     */
    {
        $file = @file_get_contents_emulate($this->path . $this->name . '.atom');
        if( $file !== false )
        {
            if( preg_match('`<entry>(.*)</entry>`is', $file) )
            {
                $expParsed = explode('<entry>', $file);
                $nbItems = (count($expParsed) - 1) > $nbItems ? $nbItems : count($expParsed) - 1;
                
                $parsed = array();
                $parsed['date'] = preg_match('`<updated>(.*)</updated>`is', $expParsed[0], $var) ? $var[1] : '';
                $parsed['title'] = preg_match('`<title>(.*)</title>`is', $expParsed[0], $var) ? $var[1] : '';
                $parsed['link'] = preg_match('`<link href="(.*)"/>`is', $expParsed[0], $var) ? $var[1] : '';
                $parsed['host'] = preg_match('`<link href="(.*)"/>`is', $expParsed[0], $var) ? $var[1] : '';
                $parsed['items'] = array();
                
                for($i = 1; $i <= $nbItems; $i++)
                {
                    $title = preg_match('`<title>(.*)</title>`is', $expParsed[$i], $title) ? $title[1] : '';
                    $url = preg_match('`<link href="(.*)"/>`is', $expParsed[$i], $url) ? $url[1] : '';
                    $guid = preg_match('`<id>(.*)</id>`is', $expParsed[$i], $guid) ? $guid[1] : '';
                    $date = preg_match('`<updated>(.*)</updated>`is', $expParsed[$i], $date) ? gmdate_format('date_format_tiny', strtotime($date[1])) : '';
                    $desc = preg_match('`<summary>(.*)</summary>`is', $expParsed[$i], $desc) ? $desc[1] : '';
                    array_push($parsed['items'], array('title' => $title, 'link' => $url, 'guid' => $guid, 'desc' => $desc, 'date' => $date));
                    unset($parsed['items'][$i]);
                }
                return $parsed;
            }
            return array();
        }
        return false;
    }

    function Generate(&$feedInformations)
    /**
     * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
     * and also the HTML cache for direct includes.
     */
    {
        require_once('../kernel/framework/template.class.php');
        $Template = new Template('syndication/atom.tpl');
        
        $Template->Assign_vars(array(
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
                $Template->Assign_block_vars('item', array(
                    'DATE' => isset($item['date']) ? $item['date'] : '',
                    'U_LINK' => isset($item['link']) ? $item['link'] : '',
                    'U_GUID' => isset($item['guid']) ? $item['guid'] : '',
                    'DESC' => isset($item['desc']) ? $item['desc'] : '',
                    'TITLE' => isset($item['title']) ? $item['title'] : ''
                ));
            }
        }
        
        $file = fopen($this->path . $this->name . '.atom', 'w+');
        fputs($file, $Template->Tparse(TEMPLATE_STRING_MODE));
        fclose($file);
    }

    ## Private Methods ##
    
    ## Private attributes ##
    var $name = ''; // Feed Name
    var $path = ''; // Path where the feeds are stored
}

?>