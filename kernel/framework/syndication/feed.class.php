<?php
/*##################################################
 *                         feed.class.php
 *                         -------------------
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

class Feed
{
    ## Public Methods #
    function Feed($feedName, $feedPath, $type)
    /**
     * Constructor
     */
    {
        $this->name = $feedName;
        $this->path = $feedPath;
        $this->type = $type;
    }

    function TParse()
    /**
     * Print the feed from the rss or atom file
     */
    {
        if ( $feed = @file_get_contents_emulate($this->path . $this->name . '.' . $this->type) )
            echo $feed;
    }

    function Parse($nbItem = 5) // Will be virtual with PHP5
    /**
     * Parse the feed contained in the file /<$feedPath>/<$feedName>.rss or
     * /<$feedPath>/<$feedName>.atom if the rss one does not exist et return
     * the result as an Array.
     */
    {
        return array();
    }

    function GetParsed(&$feedInformations, $tpl)
    /**
     * Return a String of a feed parsed by the <$tpl> template.
     */
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/template.class.php');
        $Template = new Template($tpl);
        
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
                    'TITLE' => isset($item['title']) ? $item['title'] : '',
                ));
            }
        }
        return $Template->Tparse(TEMPLATE_STRING_MODE);
    }
    
    function GenerateCache(&$feedInformations, $tpl, $extension)
    {
        $file = fopen($this->path . $this->name . $extension, 'w+');
        fputs($file, $this->GetParsed($feedInformations, $tpl));
        fclose($file);
    }
    
    ## Private Methods ##
    
    ## Private attributes ##
    var $name = '';         // Feed Name
    var $path = '';         // Path where the feeds are stored
    var $type = '';         // Type of feed to use by default
}

?>