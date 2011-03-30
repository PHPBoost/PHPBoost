<?php
/*##################################################
 *                               CSSCacheManager.class.php
 *                            -------------------
 *   begin                : March 29, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
*/
class CSSCacheManager
{
	private $css_optimizer;
	private $files = array();
	private $cache_file_location = '';

	public function __construct()
	{
		$this->css_optimizer = new CSSOptimizeFiles();
		$this->optimize_file_intensity = CSSOptimizeFiles::MEDIUM_OPTIMIZE;
	}
	
	public function set_files(Array $files)
	{
		foreach ($files as $file)
		{
			$this->css_optimizer->add_file(PATH_TO_ROOT . $file);
		}
		$this->files = $files;
	}
	
	public function execute($location = '')
	{
		if (empty($location))
		{
			$location = PATH_TO_ROOT . '/templates/' . get_utheme() . '/theme/cache.css';
		}
		if (!file_exists($location))
		{
			$this->css_optimizer->optimize($this->optimize_file_intensity);
			$this->css_optimizer->export_to_file($location);
		}
		else
		{
			foreach ($this->css_optimizer->get_files() as $file)
			{
				if(filemtime($file) > filemtime($location))
				{
					$this->css_optimizer->optimize($this->optimize_file_intensity);
					$this->css_optimizer->export_to_file($location);
				}
			}
		}
		$this->cache_file_location = $location;
	}
	
	public function get_cache_file_location()
	{
		return $this->cache_file_location;
	}
	
	public function force_regenerate_cache($theme = '')
	{
		if (empty($theme))
		{
			$theme = get_utheme();
		}
		$location = PATH_TO_ROOT . '/templates/' . $theme . '/theme/cache.css';
		$this->css_optimizer->optimize($this->optimize_file_intensity);
		$this->css_optimizer->export_to_file($location);
	}	
}
?>