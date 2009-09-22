<?php
/*##################################################
 *                          file_template_loader.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

import('io/template/template_loader');

class FileTemplateLoader implements TemplateLoader
{
	private $filepath;
	private $real_filepath = '';
	private $pictures_data_path = '';

	private $module;
	private $file;
	private $folder;
	private $filename;

	private $default_templates_folder;
	private $theme_templates_folder;
	
	public function __construct(Template $template)
	{
		$this->filepath = $template->get_identifier();
		$this->check_file();
		$template->assign_vars(array('PICTURES_DATA_PATH' => $this->pictures_data_path));
	}
	
	public function is_cache_file_valid($cache_filepath)
	{
		if (file_exists($cache_filepath))
		{
			return @filemtime($this->real_filepath) <= @filemtime($cache_filepath) && @filesize($cache_filepath) !== 0;
		}
		return false;
	}
	
	
	public function get_identifier()
	{
		return $this->real_filepath;
	}
	
	public function load()
	{
		$this->template_resource = @file_get_contents_emulate($this->real_filepath);
		if ($this->template_resource === false)
		{
			throw new TemplateLoaderException($this->filepath, $this->real_filepath . ' template loading failed.');
		}
	}
	
	public function get_resource_as_string()
	{
		return $this->template_resource;
	}
	
	/**
	  * @desc Computes the path of the file to load dinamycally according to the user theme and the kind of file (kernel, module, menu or framework file).
	  * @return string The path to load.
	  */
	private function check_file()
	{
		/*
		 Samples :
		 $this->filepath = /forum/forum_topic.tpl
		 $this->filepath = forum/forum_topic.tpl
		 $module = forum
		 $filename = forum_topic.tpl
		 $file = forum_topic.tpl
		 $folder =
		 $this->filepath = /news/framework/content/syndication/last_news.tpl
		 $this->filepath = news/framework/content/syndication/menu.tpl
		 $module = news
		 $filename = menu.tpl
		 $file = framework/content/syndication/menu.tpl
		 $folder = framework/content/syndication
		 */
		
		if (strpos($this->filepath, '/') === 0)
		{
			// Load the file from its absolute location
			// (Not overlaodable)
			if (file_exists(PATH_TO_ROOT . $this->filepath))
			{
				$this->get_template_real_filepaths_and_data_path(array(PATH_TO_ROOT . $this->filepath));
			}
		}
		
		$i = strpos($this->filepath, '/');
		$this->module = substr($this->filepath, 0, $i);
		$this->file = trim(substr($this->filepath, $i), '/');
		$this->folder = trim(substr($this->file, 0, strpos($this->file, '/')), '/');
		$this->filename = trim(substr($this->filepath, strrpos($this->filepath, '/')));

		$this->default_templates_folder = PATH_TO_ROOT . '/templates/default/';
		$this->theme_templates_folder = PATH_TO_ROOT . '/templates/' . get_utheme() . '/';
		
		if (empty($this->module) || in_array($this->module, array('admin', 'framework') ))
		{   // Kernel - Templates priority order
			//      /templates/$theme/.../$file.tpl
			//      /templates/default/.../$file.tpl
			$this->get_kernel_paths();
		}
		elseif ($this->module == 'menus')
		{   // Framework - Templates priority order
			//      /templates/$theme/menus/$menu/filename.tpl
			//      /menus/$menu/default/framework/.../$file.tpl
			$this->get_menus_paths();
		}
		else
		{   // Module - Templates
			$this->get_module_paths();
		}
	}
	
	private function get_kernel_paths()
	{
		$this->get_template_real_filepaths_and_data_path(array(
			$this->theme_templates_folder . $this->filepath,
			$this->default_templates_folder . $this->filepath
		));
	}
	
	private function get_menus_paths()
	{
		$menu = substr($folder, 0, strpos($folder, '/'));
		if (empty($menu))
		{
			$menu = $folder;
		}
		
		$this->get_template_real_filepaths_and_data_path(array(
			$this->theme_templates_folder . '/menus/' . $menu . '/' . $this->filename,
			PATH_TO_ROOT . '/menus/' . $menu . '/templates/' . $this->filename
		));
	}
	
	private function get_module_paths()
	{
		$theme_module_templates_folder = $this->theme_templates_folder . 'modules/' . $this->module . '/';
		$module_templates_folder = PATH_TO_ROOT . '/' . $this->module . '/templates/';

		if ($this->folder == 'framework')
		{   // Framework - Templates priority order
			//      /templates/$theme/modules/$module/framework/.../$file.tpl
			//      /templates/$theme/framework/.../$file.tpl
			//      /$module/templates/framework/.../$file.tpl
			//      /templates/default/framework/.../$file.tpl
			
			$this->get_template_real_filepaths_and_data_path(array(
				$theme_module_templates_folder . $this->file,
				$module_templates_folder . 'framework/' . $this->file,
				$theme_templates_folder . $this->filepath,
				$default_templates_folder . $this->file
			));
		}
		else
		{
			$this->get_template_real_filepaths_and_data_path(array(
				$theme_module_templates_folder . $this->file,
				$module_templates_folder . $this->file
			));
		}
	}
	
	private function get_template_real_filepaths_and_data_path($paths)
	{
		foreach ($paths as $path)
		{
			if ($dirpath = (file_exists(dirname($path) . '/images')))
			{
				$this->pictures_data_path = $this->convert_to_tpl_path($dirpath);
				break;
			}
		}
		
		foreach ($paths as $path)
		{
			if (file_exists($path))
			{
				$this->real_filepath = $path;
				break;
			}
		}
		
		if (empty($this->real_filepath) && count($paths) > 0)
		{	// Adds the default path looking for in the exception trace
			$this->real_filepath = $paths[count($paths) - 1];
		}
	}
	
	private function convert_to_tpl_path($path_to_root_filepath)
	{
		return TPL_PATH_TO_ROOT . substr($path_to_root_filepath, strlen(PATH_TO_ROOT));
	}
}
?>