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
	private $real_filepath;

	public function __construct($template_identifier)
	{
		$this->filepath = $template_identifier;
		$this->real_filepath = $this->check_file();
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
				return PATH_TO_ROOT . $this->filepath;
			}
		}
		
		$i = strpos($this->filepath, '/');
		$module = substr($this->filepath, 0, $i);
		$file = trim(substr($this->filepath, $i), '/');
		$folder = trim(substr($file, 0, strpos($file, '/')), '/');
		$filename = trim(substr($this->filepath, strrpos($this->filepath, '/')));

		$default_templates_folder = PATH_TO_ROOT . '/templates/default/';
		$theme_templates_folder = PATH_TO_ROOT . '/templates/' . get_utheme() . '/';
		if (empty($module) || in_array($module, array('admin') ))
		{   // Kernel - Templates priority order
			//      /templates/$theme/.../$file.tpl
			//      /templates/default/.../$file.tpl
			if (file_exists($file_path = $theme_templates_folder . $this->filepath))
			{
				return $file_path;
			}
			return $default_templates_folder . $this->filepath;
		}
		elseif ($module == 'framework')
		{   // Framework - Templates priority order
			//      /templates/$theme/framework/.../$file.tpl
			//      /templates/default/framework/.../$file.tpl
			if (file_exists($file_path = $theme_templates_folder . $this->filepath))
			{
				return $file_path;
			}

			return $default_templates_folder . $this->filepath;
		}
		elseif ($module == 'menus')
		{   // Framework - Templates priority order
			//      /templates/$theme/menus/$menu/filename.tpl
			//      /menus/$menu/default/framework/.../$file.tpl
			$menu = substr($folder, 0, strpos($folder, '/'));
			if (empty($menu))
			{
				$menu = $folder;
			}
			if (file_exists($file_path = $theme_templates_folder . '/menus/' . $menu . '/' . $filename))
			{
				return $file_path;
			}

			return PATH_TO_ROOT . '/menus/' . $menu . '/templates/' . $filename;
		}
		else
		{   // Module - Templates
			$theme_module_templates_folder = $theme_templates_folder . 'modules/' . $module . '/';
			$module_templates_folder = PATH_TO_ROOT . '/' . $module . '/templates/';

			if ($folder == 'framework')
			{   // Framework - Templates priority order
				//      /templates/$theme/modules/$module/framework/.../$file.tpl
				//      /templates/$theme/framework/.../$file.tpl
				//      /$module/templates/framework/.../$file.tpl
				//      /templates/default/framework/.../$file.tpl
				if (file_exists($file_path = $theme_module_templates_folder . $file))
				{
					return $file_path;
				}
				if (file_exists($file_path = $theme_templates_folder . $this->filepath))
				{
					return $file_path;
				}
				if (file_exists($file_path = ($module_templates_folder . 'framework/' . $file)))
				{
					return $file_path;
				}
				return $default_templates_folder . $file;
			}

			//module data path
			if (!isset($this->module_data_path[$module]))
			{
				if (is_dir($theme_module_templates_folder . '/images'))
				{
					$this->module_data_path[$module] = TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/' . 'modules/' . $module;
				}
				else
				{
					$this->module_data_path[$module] = TPL_PATH_TO_ROOT . '/' . trim($module . '/templates/', '/');
				}
			}

			if (file_exists($file_path = $theme_module_templates_folder . $file))
			{
				return $file_path;
			}
			else
			{
				return $module_templates_folder . $file;
			}
		}
	}
}
?>