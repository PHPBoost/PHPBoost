<?php
/*##################################################
 *                              mediaExtensionPointProvider.class.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
 *
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/



define('MEDIA_MAX_SEARCH_RESULTS', 100);

class MediaExtensionPointProvider extends ExtensionPointProvider
{
	private $db_querier;

	## Public Methods ##
	function __construct()
	{
		$this->db_querier = PersistenceContext::get_querier();
		parent::__construct('media');
	}

	//Récupération du cache.
	function get_cache()
	{
		require_once PATH_TO_ROOT . '/media/media_constant.php';

		//Configuration
		$i = 0;
		$config = array();
		$config = unserialize($this->db_querier->select_single_row(DB_TABLE_CONFIGS, 'value', 'WHERE name = \'media\''));
		$root_config = $config['root'];
		unset($config['root']);

		$string = 'global $MEDIA_CONFIG, $MEDIA_CATS;' . "\n\n" . '$MEDIA_CONFIG = $MEDIA_CATS = array();' . "\n\n";
		$string .= '$MEDIA_CONFIG = ' . var_export($config, true) . ';' . "\n\n";

		//List of categories and their own properties
		$string .= '$MEDIA_CATS[0] = ' . var_export($root_config, true) . ';' . "\n\n";
		$result = $this->db_querier->select("SELECT * FROM " . PREFIX . "media_cat ORDER BY id_parent, c_order ASC");

		while ($row = $result->fetch())
		{
			$string .= '$MEDIA_CATS[' . $row['id'] . '] = ' . var_export(array(
				'id_parent' => (int)$row['id_parent'],
				'order' => (int)$row['c_order'],
				'name' => $row['name'],
				'desc' => $row['description'],
				'visible' => (bool)$row['visible'],
				'image' => $row['image'],
				'num_media' => (int)$row['num_media'],
				'mime_type' => (int)$row['mime_type'],
				'active' => (int)$row['active'],
				'auth' => (array)unserialize($row['auth'])
			), true) . ';' . "\n\n";
		}

		$result->dispose();

		return $string;
	}
	
	public function css_files()
	{
		$module_css_files = new ModuleCssFiles();
		$module_css_files->adding_running_module_displayed_file('media.css');
		return $module_css_files;
	}
	
	public function comments()
	{
		return new CommentsTopics(array(new MediaCommentsTopic()));
	}
	
	public function feeds()
	{
		return new MediaFeedProvider();
	}
	
	public function home_page()
	{
		return new MediaHomePageExtensionPoint();
	}
	
	public function search()
	{
		return new MediaSearchable();
	}
	
	public function sitemap()
	{
		return new MediaSitemapExtensionPoint();
	}
	
	public function tree_links()
	{
		return new MediaTreeLinks();
	}
}
?>