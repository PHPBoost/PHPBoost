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

define('FEEDS_PATH', PATH_TO_ROOT . '/cache/syndication/');
define('DEFAULT_FEED_NAME', 'master');
define('ERROR_GETTING_CACHE', 'Error regenerating and / or retrieving the syndication cache of the %s (%s)');

require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php');
require_once(PATH_TO_ROOT . '/kernel/framework/content/syndication/feed_data.class.php');

class Feed
{
    ## Public Methods ##
    function Feed($module_id, $name = DEFAULT_FEED_NAME, $id_cat = 0)
    {
        $this->module_id = $module_id;
        $this->name = $name;
        $this->id_cat = $is_cat
    }

    function load_data($data) { $this->data = $data; }
    function load_file($url) { }

    // Export the feed as a string parsed by the <$tpl> template
    function export($template = false)
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

    function read() { return file_get_contents_emulate($this->get_cache_file_name()); }

    function cache()
    {
        FEED::update_cache($this->module_id, $this->name, $this->data, $this->id_cat);
//         if( empty($this->str) )
//             $this->str = $this->export();
//         $file = fopen($this->get_cache_file_name(), 'w+');
//         fputs($file, $this->str);
//         fclose($file);
    }

    function is_in_cache() { return file_exists(FEEDS_PATH . $this->name); }
    
    function get_cache_file_name() { return FEEDS_PATH . $this->module_id . '_' . $this->name . '_' . $this->id_cat . '.php'; }
    
    ## Private Methods ##
    ## Private attributes ##
    var $module_id = '';        // Module ID
    var $id_cat = 0;        // ID cat
    var $name = '';             // Feed Name
    var $str = '';              // The feed as a string
    var $tpl = null;            // The feed Template to use
    var $data = null;           // The feed Template to use

    ## Statics Methods ##

    // clear the cache
    /*static*/ function clear_cache($module_id = false)
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/io/folder.class.php');
        $folder = new Folder(FEEDS_PATH, OPEN_NOW);
        
        $files = null;
        if( $module_id !== false )  // Clear only this module cache
            $files = $folder->get_files('`.+/' . $module_id . '_.*`');
        else                        // Clear the whole cache
            $files = $folder->get_files();
        
        foreach( $files as $file )
            $file->delete();
    }

    /*static*/ function update_cache($module_id, $name, &$data, $idcat = 0)
    {
        require_once(PATH_TO_ROOT . '/kernel/framework/io/file.class.php');
        $file = new File(FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php', WRITE);
        $file->write('<?php $feed_object = unserialize(\'' . var_export($data->serialize(), true) . '\'); ?>');
    }

    /*static*/ function get_parsed($module_id, $name = DEFAULT_FEED_NAME, $idcat = 0, $tpl = false, $number = 10, $begin_at = 0)
    {
        // Choose the correct template
        if( is_object($tpl) and strtolower(get_class($tpl)) == 'template' )
            $template = $tpl->copy();
        else
        {
            require_once(PATH_TO_ROOT . '/kernel/framework/io/template.class.php');
            $template = new Template($module_id . '/framework/content/syndication/feed.tpl');
            if( gettype($tpl) == 'array' )
                $template->Assign_vars($tpl);
        }
        
        // Get the cache content or recreate it if not existing
        $iteration = 0;
        $feed_data = '';
        while( ($feed_data = @include_once(FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php')) === false || $feed_data === '')
        {
            require_once(PATH_TO_ROOT . '/kernel/framework/modules/modules.class.php');
            $modules = new Modules();
            $module = $modules->get_module($module_id);
            $data = $module->syndication_data($idcat);
            Feed::update_cache($module_id, $name, $data, $idcat);
            
            if( $iteration++ > 1 )
                user_error(sprintf(ERROR_GETTING_CACHE, $module_id, $idcat), E_USER_WARNING);
        }
        
        //$feed = new Feed($module_id, $name);
        //$data = new FeedData($feed_data);
        //$feed->load_data($data->subitems($number, $begin_at));
        
        return $feed->export($template);
    }
}
?>