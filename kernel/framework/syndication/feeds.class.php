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

define('FEED_PATH', PATH_TO_ROOT . '/cache/syndication');
define('DEFAULT_FEED_TPL', 'framework/syndication/feed.tpl');

define('USE_RSS', 0x01);
define('USE_ATOM', 0x02);
define('EXTERN_FEED', 0x04);
define('ALL_FEEDS', USE_RSS|USE_ATOM);

define('STATIC_MODE', 0x01);
define('DYNAMIC_MODE', 0x02);

class Feeds
{
    ## Public Methods ##
    function Feeds($feedName, $type = ALL_FEEDS, $feedPath = FEED_PATH)
    /**
     * Constructor
     */
    {
        $this->name = $feedName;
        $this->path = trim($feedPath, '/') . '/';
        
        if ( $type & USE_ATOM )
        {
            require_once(PATH_TO_ROOT . '/kernel/framework/syndication/atom.class.php');
            $this->feeds[USE_ATOM] = new ATOM($this->name, $this->path);
            $this->type = 'atom';
        }
        if ( $type & USE_RSS )
        {
            require_once(PATH_TO_ROOT . '/kernel/framework/syndication/rss.work.class.php');
            $this->feeds[USE_RSS] = new RSS($this->name, $this->path);
            $this->type = 'rss';
        }
    }

    function get($nbItems = 5, $tpl = DEFAULT_FEED_TPL)
    /**
     * Return the results of the HTML feed generated as a string
     */
    {
        if ( ($nbItems == 5) && ($tpl == DEFAULT_FEED_TPL) )
        {
            if ( ($HTMLfeed = @file_get_contents_emulate($this->path . $this->name . '.html')) !== false )
                return $HTMLfeed;
        }
        return $this->get_html_feed($this->Parse($nbItems), $tpl);
    }
    
    function display()
    /**
     * Display the feed from the rss or atom file
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
        foreach ( $this->feeds as $feed )
        {
            if ( ($parsed = $feed->Parse($nbItem)) !== false )
                return $parsed;
        }
        return array();
    }

    function generate(&$feedInformations, $mode = STATIC_MODE)
    /**
     * Generate the feed contained into the files <$feedFile>.rss and <$feedFile>.atom
     * and also the HTML cache for direct includes.
     */
    {
        foreach ( $this->feeds as $feed )
        {
            $feed->generate_cache($feedInformations);
            if ( $mode != STATIC_MODE )
                break;
        }
        if ( $mode == STATIC_MODE )
            $this->generate_html_cache($feedInformations);
    }
    
    function generate_html_cache(&$feedInformations, $tpl = 'framework/syndication/feed.tpl')
    /**
     * Generate the HTML cache for direct includes.
     */
    {
        $file = fopen($this->path . $this->name . '.html', 'w+');
        fputs($file, $this->_get_parsed_feed($feedInformations, $tpl));
        fclose($file);
    }
    
    ## Private Methods ##
    function _get_parsed_feed(&$feedInformations, $tpl)
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
                    'U_IMG' => !empty($feedInformations['img']) ? $feedInformations['img'] : '',
                    'C_IMG' => !empty($feedInformations['img']) ? true : false
                ));
            }
        }
        return $Template->parse(TEMPLATE_STRING_MODE);
    }
    
    function _generate_feed(&$feedInformations, $tpl, $extension)
    {
        $file = fopen($this->path . $this->name . $extension, 'w+');
        fputs($file, $this->_get_parsed_feed($feedInformations, $tpl));
        fclose($file);
    }
    
    ## Private attributes ##
    var $name = '';         // Feed Name
    var $path = '';         // Path where the feeds are stored
    var $feeds = array();   // Feeds objects
    var $type = '';         // Type of feed to use by default
}

?>