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

require_once(PATH_TO_ROOT . '/kernel/framework/functions.inc.php');
require_once(PATH_TO_ROOT . '/kernel/framework/syndication/feed_data.class.php');

function feeds_update_cache($feed_name, &$data, $tpl = false)
{
    require_once('../kernel/framework/syndication/rss.work.class.php');
    require_once('../kernel/framework/syndication/atom.class.php');
    $RSS = new RSS($feed_name);
    $ATOM = new ATOM($feed_name);

    $RSS->load_data($data);
    $RSS->cache();

    $ATOM->load_data($data);
    $ATOM->cache();

    if( $tpl !== false )
    {
        $HTML = new Feed($feed_name);
        $template = $tpl->copy();
        
        $HTML->load_data($data);
        
        $file = fopen(FEED_PATH . $feed_name . '.php', 'w+');
        fputs($file, '<?php echo \'' . addslashes($HTML->export($template)) . '\' ?>');
        fclose($file);
    }
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
                    'DATE' => $this->data->get_date(),
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