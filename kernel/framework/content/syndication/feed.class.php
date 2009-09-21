<?php
/*##################################################
 *                            feed.class.php
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

import('functions', INC_IMPORT);
import('content/syndication/feed_data');

/**
 * @author Loïc Rouchon <horn@phpboost.com>
 * @desc This class could be used to export feeds
 * @abstract  Do not use this class, but one of its children like RSS or ATOM
 * @package content
 * @subpackage syndication
 */
class Feed
{
	## Public Methods ##

	/**
	 * @desc Builds a new feed object
	 * @param string $module_id its module_id
	 * @param string $name the feeds name / type. default is DEFAULT_FEED_NAME
	 * @param int $id_cat the feed category id
	 */
	function Feed($module_id, $name = DEFAULT_FEED_NAME, $id_cat = 0)
	{
		$this->module_id = $module_id;
		$this->name = $name;
		$this->id_cat = $id_cat;
	}

	/**
	 * @desc Loads a FeedData element
	 * @param FeedData $data the element to load
	 */
	function load_data($data) { $this->data = $data; }
	/**
	 * @desc Loads a feed by its url
	 * @param string $url the feed url
	 */
	function load_file($url) { }

	/**
	 * @desc Exports the feed as a string parsed by the <$tpl> template
	 * @param mixed $template If false, uses de default tpl. If an associative array,
	 * uses the default tpl but assigns it the array vars first.
	 * It could also be a Template object
	 * @param int $number the number of item to display
	 * @param int $begin_at the first item to display
	 * @return string The exported feed
	 */
	function export($template = false, $number = 10, $begin_at = 0)
	{
		import('content/parser/content_second_parser');

		if ($template === false)
		{    // A specific template is used
			$tpl = $this->tpl->copy();
		}
		else
		{
			$tpl = $template->copy();
		}

		global $User, $MODULES;
		if ($User->check_auth($MODULES[$this->module_id]['auth'], ACCESS_MODULE))
		{
			if (!empty($this->data))
			{
				$tpl->assign_vars(array(
                    'DATE' => $this->data->get_date(),
                    'DATE_RFC822' => $this->data->get_date_rfc822(),
                    'DATE_RFC3339' => $this->data->get_date_rfc3339(),
                    'TITLE' => $this->data->get_title(),
                    'U_LINK' => $this->data->get_link(),
                    'HOST' => $this->data->get_host(),
                    'DESC' => htmlspecialchars($this->data->get_desc()),
                    'LANG' => $this->data->get_lang()
				));

				$items = $this->data->subitems($number, $begin_at);
				foreach ($items as $item)
				{
					$desc = $item->get_desc(); // Allow the by reference call of the second_parse method
					$tpl->assign_block_vars('item', array(
                        'TITLE' => $item->get_title(),
                        'U_LINK' => $item->get_link(),
                        'U_GUID' => $item->get_guid(),
                        'DESC' => htmlspecialchars(second_parse($desc)),
                        'DATE' => $item->get_date(),
                        'DATE_RFC822' => $item->get_date_rfc822(),
                        'DATE_RFC3339' => $item->get_date_rfc3339(),
                        'C_IMG' => ($item->get_image_url() != '') ? true : false,
                        'U_IMG' => $item->get_image_url()
					));
				}
			}
		}
		return $tpl->parse(Template::TEMPLATE_PARSER_STRING);
	}

	/**
	 * @desc Loads the feed data in cache and export it
	 * @return string the exported feed
	 */
	function read()
	{
		if ($this->is_in_cache())
		{
			$include = @include($this->get_cache_file_name());
			if ($include)
			{
				$this->data = $__feed_object;
				return $this->export();
			}
		}
		return '';
	}

	/**
	 * @desc Send the feed data in the cache
	 */
	function cache()
	{
		Feed::update_cache($this->module_id, $this->name, $this->data, $this->id_cat);
	}

	/**
	 * @desc Returns true if the feed data are in the cache
	 * @return bool true if the feed data are in the cache
	 */
	function is_in_cache() { return file_exists($this->get_cache_file_name()); }
	 
	/**
	 * @desc Returns the feed data cache filename
	 * @return string the feed data cache filename
	 */
	function get_cache_file_name() { return FEEDS_PATH . $this->module_id . '_' . $this->name . '_' . $this->id_cat . '.php'; }
	 
	## Private Methods ##
	## Private attributes ##
	var $module_id = '';        // Module ID
	var $id_cat = 0;            // ID cat
	var $name = '';             // Feed Name
	var $str = '';              // The feed as a string
	var $tpl = null;            // The feed Template to use
	var $data = null;           // The data structure

	## Statics Methods ##

	// clear the cache

	/**
	 * @desc Clear the cache of the specified module_id.
	 * @param mixed $module_id the module module_id or false. If false,
	 * Clear all feeds data from the cache
	 * @static
	 */
	/*static*/ function clear_cache($module_id = false)
	{
		import('io/filesystem/folder');
		$folder = new Folder(FEEDS_PATH, OPEN_NOW);
		$files = null;
		if ($module_id !== false)
		{   // Clear only this module cache
			$files = $folder->get_files('`' . $module_id . '_.*`');
		}
		else
		{   // Clear the whole cache
			$files = $folder->get_files();
		}
		 
		foreach ($files as $file)
		$file->delete();
	}


	/**
	 * @desc Update the cache of the $module_id, $name, $idcat feed with $data
	 * @param string $module_id the module id
	 * @param string $name the feed name / type
	 * @param &FeedData $data the data to put in the cache
	 * @param int $idcat the feed data category
	 * @static
	 */
	/*static*/ function update_cache($module_id, $name, &$data, $idcat = 0)
	{
		import('io/filesystem/file');
		$file = new File(FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php', WRITE);
		$file->write('<?php $__feed_object = unserialize(' . var_export($data->serialize(), true) . '); ?>');
		$file->close();
	}

	/**
	 * @desc Export a feed
	 * @param string $module_id the module id
	 * @param string $name the feed name / type
	 * @param int $idcat the feed data category
	 * @param mixed $tpl If false, uses de default tpl. If an associative array,
	 * uses the default tpl but assigns it the array vars first.
	 * It could also be a Template object
	 * @param int $number the number of item to display
	 * @param int $begin_at the first item to display
	 * @return string The exported feed
	 * @static
	 */
	/*static*/ function get_parsed($module_id, $name = DEFAULT_FEED_NAME, $idcat = 0, $tpl = false, $number = 10, $begin_at = 0)
	{
		// Choose the correct template
		if (of_class($tpl, 'template'))
		{
			$template = $tpl->copy();
		}
		else
		{
			import('io/template/template');
			$template = new Template($module_id . '/framework/content/syndication/feed.tpl');
			if (gettype($tpl) == 'array')
			$template->assign_vars($tpl);
		}
		 
		// Get the cache content or recreate it if not existing
		$feed_data_cache_file = FEEDS_PATH . $module_id . '_' . $name . '_' . $idcat . '.php';
		$result = @include($feed_data_cache_file);
		if ($result === false)
		{
			import('modules/modules_discovery_service');
			$modules = new ModulesDiscoveryService();
			$module = $modules->get_module($module_id);

			if ( $module->got_error() || !$module->has_functionality('get_feed_data_struct') )
			{   // If the module is not installed or doesn't have the get_feed_data_struct
				// functionality we break
				return '';
			}

			$data = $module->functionality('get_feed_data_struct', $idcat);
			if (!$module->got_error())
			{
				Feed::update_cache($module_id, $name, $data, $idcat);
			}
		}
		if (!DEBUG)
		{
			$result = @include($feed_data_cache_file);
		}
		else
		{
			if (file_exists($feed_data_cache_file))
			{
				$result = include($feed_data_cache_file);
			}
			else
			{
				$result = FALSE;
			}
		}
		if ( $result === false)
		{
			user_error(sprintf(ERROR_GETTING_CACHE, $module_id, $idcat), E_USER_WARNING);
			return '';
		}

		$feed = new Feed($module_id, $name);
		$feed->load_data($__feed_object);
		return $feed->export($template, $number, $begin_at);
	}
	
	/**
	 * @static
	 * @desc Generates the code which shows all the feeds formats.
	 * @param string $feed_url Feed URL
	 * @return string The HTML code to display.
	 */
	function get_feed_menu($feed_url)
	{
	    global $LANG, $CONFIG;
	    
	    $feed_menu = new Template('framework/content/syndication/menu.tpl');
	    
	    $feed_absolut_url = $CONFIG['server_name'] . $CONFIG['server_path'] . '/' . trim($feed_url, '/');
	    
	    $feed_menu->assign_vars(array(
	        'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'PATH_TO_MENU' => dirname($feed_menu->get_template_filepath()),
	        'THEME' => get_utheme(),
	        'U_FEED' => $feed_absolut_url,
	        'SEPARATOR' => strpos($feed_absolut_url, '?') !== false ? '&amp;' : '?',
	        'L_RSS' => $LANG['rss'],
	        'L_ATOM' => $LANG['atom']
	    ));
	    
	    return $feed_menu->parse(Template::TEMPLATE_PARSER_STRING);
	}
}
?>
