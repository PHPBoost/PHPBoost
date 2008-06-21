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



define('ATOM_EXTENSION', '.atom');
define('DEFAULT_ATOM_TEMPLATE', 'framework/syndication/atom.tpl');

require_once(PATH_TO_ROOT . '/kernel/framework/template.class.php');
require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed.class.php');

class ATOM extends Feed
{
    ## Public Methods ##
    function ATOM($feedName)
    {
        parent::Feed($feedName . ATOM_EXTENSION);
        $this->tpl = new Template(DEFAULT_ATOM_TEMPLATE);
    }

    function load_file($url)
    {
        if( ($file = @file_get_contents_emulate($url)) !== false )
        {
            $this->data = new FeedData();
            if( preg_match('`<entry>(.*)</entry>`is', $file) )
            {
                $expParsed = explode('<entry>', $file);
                $nbItems = (count($expParsed) - 1) > $nbItems ? $nbItems : count($expParsed) - 1;
                
                $this->data->set_date(preg_match('`<updated>(.*)</updated>`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_title(preg_match('`<title>(.*)</title>`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_link(preg_match('`<link href="(.*)"/>`is', $expParsed[0], $var) ? $var[1] : '');
                $this->data->set_host(preg_match('`<link href="(.*)"/>`is', $expParsed[0], $var) ? $var[1] : '');
                
                for($i = 1; $i <= $nbItems; $i++)
                {
                    $item = new FeedItem();
                    
                    $item->set_title(preg_match('`<title>(.*)</title>`is', $expParsed[$i], $title) ? $title[1] : '');
                    $item->set_link(preg_match('`<link href="(.*)"/>`is', $expParsed[$i], $url) ? $url[1] : '');
                    $item->set_guid(preg_match('`<id>(.*)</id>`is', $expParsed[$i], $guid) ? $guid[1] : '');
                    $item->set_desc(preg_match('`<summary>(.*)</summary>`is', $expParsed[$i], $desc) ? $desc[1] : '');
                    $item->set_date_rfc3339(preg_match('`<updated>(.*)</updated>`is', $expParsed[$i], $date) ? gmdate_format('date_format_tiny', strtotime($date[1])) : '');
                    
                    $this->data->add_item($item);
                }
                return true;
            }
            return false;
        }
        return false;
    }
    
    ## Private Methods ##
    ## Private attributes ##
}

?>