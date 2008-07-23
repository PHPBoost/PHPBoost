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

define('FEED_PATH', PATH_TO_ROOT . '/cache/syndication/');
define('ERROR_GETTING_CACHE', 'Error regenerating and / or retrieving the syndication cache of the %s (%s)');

require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php');
require_once(PATH_TO_ROOT . '/kernel/framework/content/syndication/feed_data.class.php');

function display_feed($module_id, $idcat = 0, $tpl = false, $number = 10, $begin_at = 0)
{
    // Choose the correct template
	if( $tpl !== false )
        $template = $tpl->copy();
    else
	{
		require_once(PATH_TO_ROOT . '/kernel/framework/io/template.class.php');
        $template = new Template($module_id . '/framework/content/syndication/feed.tpl');
    }
    
    // Get the cache content or recreate it if not existing
    $iteration = 0;
    while( ($feed_data = @file_get_contents_emulate(PATH_TO_ROOT . '/cache/syndication/' . $module_id . '_' . $idcat . '.php')) === false )
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/modules/modules.class.php');
        $modules = new Modules();
        $module = $modules->get_module($module_id);
        $data = $module->syndication_data($idcat);
        feeds_update_cache($module_id, $data, $idcat);
        
        if( $iteration++ > 1 )
            user_error(sprintf(ERROR_GETTING_CACHE, $module_id, $idcat), E_USER_WARNING);
    }
    
    $feed = new Feed($module_id);
    $data = new FeedData($feed_data);
    $feed->load_data($data->subitems($number, $begin_at));
    
    return $feed->export($template);
}

function feeds_update_cache($feed_name, &$data, $idcat = 0)
{
	$file = fopen(FEED_PATH . $feed_name . '_' . $idcat . '.php', 'w+');
    fputs($file, $data->serialize());
    fclose($file);
}

class Feed
{
    ## Public Methods ##
    function Feed($feed_name) { $this->name = $feed_name; }

    function load_data($data) { $this->data = $data; }
    function load_file($url) { }

    function export($template = false)
    /**
     *  Export the feed as a string parsed by the <$tpl> template
     */
    {
        if( $template === false )    // A specific template is used
            $tpl = $this->tpl->copy();
        else
            $tpl = $template->copy();
        
        if( !empty($this->data) )
        {
            $tpl->Assign_vars(array(
                'DATE' => $this->data->get_date(),
                'DATE_RFC822' => $this->data->get_date_rfc822(),
                'DATE_RFC3339' => $this->data->get_date_rfc3339(),
                'TITLE' => $this->data->get_title(),
                'U_LINK' => $this->data->get_link(),
                'HOST' => $this->data->get_host(),
                'DESC' => $this->data->get_desc(),
                'LANG' => $this->data->get_lang()
            ));

            $item = null;
            foreach( $this->data->get_items() as $item )
            {
                $tpl->Assign_block_vars('item', array(
                    'TITLE' => $item->get_title(),
                    'U_LINK' => $item->get_link(),
                    'U_GUID' => $item->get_guid(),
                    'DESC' => $item->get_desc(),
                    'DATE' => $item->get_date(),
                    'DATE_RFC822' => $item->get_date_rfc822(),
                    'DATE_RFC3339' => $item->get_date_rfc3339(),
                    'C_IMG' => ($item->get_image_url() != '') ? true : false,
                    'U_IMG' => $item->get_image_url()
                ));
            }
        }
        return $tpl->parse(TEMPLATE_STRING_MODE);
    }

    function read() { return file_get_contents_emulate(FEED_PATH . $this->name); }

    function cache()
    {
        if( empty($this->str) )
            $this->str = $this->export();
        $file = fopen(FEED_PATH . $this->name, 'w+');
        fputs($file, $this->str);
        fclose($file);
    }

    function is_in_cache() { return file_exists(FEED_PATH . $this->name); }
    
    ## Private Methods ##
    ## Private attributes ##
    var $name = '';         // Feed Name
    var $str = '';          // The feed as a string
    var $tpl = null;        // The feed Template to use
    var $data = null;        // The feed Template to use
}

?>