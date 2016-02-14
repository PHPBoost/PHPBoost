<?php
/*##################################################
 *                        FileTemplateLoader.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @package {@package}
 * @desc This loader is the most used. It takes a file identifier as input. This identifier corresponds
 * to a file that can be different from the user's theme. In fact, when it loads a template, its looks for
 * it at several places. For a module template, it can be the default one which is in the /module/templates directory,
 * but if the file is specialized by the theme, it loads it from the theme directory. All that is explained
 * in the {@link FileTemplate} class description.
 * This loader supports caching and stores cache files in the /cache/tpl directory, their name are related to
 * their source's real path.
 * @see FileTemplate
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 */
class FileTemplateLoader implements TemplateLoader
{
	private $filepath;
	private $real_filepath = '';
	private $cache_filepath = '';
	private $pictures_data_path = '';

	private $module;
	private $file;
	private $folder;

	private $default_templates_folder;
	private $theme_templates_folder;

	/**
	 * @desc Constructs a {@link FileTemplateLoader} from the file's identifier.
	 * @param string $identifier The file's identifier
	 * @param TemplateData $data The data which is associated to the loader. It is used to assign
	 * the PICTURES_DATA_PATH variable that corresponds the the module's pictures data path (it depends on
	 * whether the theme redefines them or not).
	 */
	public function __construct($identifier, TemplateData $data)
	{
		$this->filepath = $identifier;
		$this->compute_real_file_path();
		$this->compute_cache_file_path();
		$data->put('PICTURES_DATA_PATH', $this->pictures_data_path);
	}

	private function compute_cache_file_path()
	{
		$template_folder = new Folder(PATH_TO_ROOT . '/cache/tpl/' . AppContext::get_current_user()->get_theme());
		if (!$template_folder->exists())
			mkdir(PATH_TO_ROOT . '/cache/tpl/' . AppContext::get_current_user()->get_theme());
		
		$this->cache_filepath = PATH_TO_ROOT . '/cache/tpl/' . AppContext::get_current_user()->get_theme() . '/' . trim(str_replace(
		array('/', '.', '..', 'tpl', 'templates'),
		array('_', '', '', '', 'tpl'),
		$this->real_filepath
		), '_') . '.php';

	}

	/**
	 * {@inheritdoc}
	 */
	public function load()
	{
		if (!$this->is_cache_file_up_to_date())
		{
			$this->generate_cache_file();
		}

		return $this->get_file_cache_content();
	}

	private function is_cache_file_up_to_date()
	{
		if (file_exists($this->cache_filepath))
		{
			return @filemtime($this->real_filepath) <= @filemtime($this->cache_filepath) && @filesize($this->cache_filepath) !== 0;
		}
		return false;
	}

	private function generate_cache_file()
	{
		$real_file_content = @file_get_contents($this->real_filepath);
		if ($real_file_content === false)
		{
			throw new FileTemplateLoadingException($this->filepath, $this->real_filepath);
		}

		$parser = new TemplateSyntaxParser();
		$result = $parser->parse($real_file_content);

		try
		{
			$cache_file = new File($this->cache_filepath);
			$cache_file->open(File::WRITE);
			$cache_file->lock();
			$cache_file->write($result);
			$cache_file->unlock();
			$cache_file->close();
			$cache_file->change_chmod(0666);
		}
		catch(IOException $ex)
		{
			throw new TemplateLoadingException('The template file cache couldn\'t been written due to this problem :' . $ex->getMessage());
		}
	}

	private function get_file_cache_content()
	{
		return @file_get_contents($this->cache_filepath);
	}

	/**
	 * @desc Computes the path of the file to load dinamycally according to the user theme and the kind of file (kernel, module, menu or framework file).
	 * @return string The path to load.
	 */
	private function compute_real_file_path()
	{
		if (strpos($this->filepath, '/') === 0)
		{
			// Load the file from its absolute location
			// (Not overlaodable)
			if (file_exists(PATH_TO_ROOT . $this->filepath))
			{
				$this->get_template_real_filepaths_and_data_path(array(PATH_TO_ROOT . $this->filepath));
				return;
			}
		}

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

		$i = strpos($this->filepath, '/');
		$this->module = substr($this->filepath, 0, $i);
		$this->file = trim(substr($this->filepath, $i), '/');
		$this->folder = trim(substr($this->file, 0, strpos($this->file, '/')), '/');
		$this->filename = trim(substr($this->filepath, strrpos($this->filepath, '/')));

		$this->default_templates_folder = PATH_TO_ROOT . '/templates/default/';
		$this->theme_templates_folder = PATH_TO_ROOT . '/templates/' . AppContext::get_current_user()->get_theme() . '/';

		if (empty($this->module) || in_array($this->module, array('admin', 'framework') ))
		{   // Kernel - Templates priority order
			//      /templates/$theme/.../$file.tpl
			//      /templates/default/.../$file.tpl
			$this->get_kernel_paths();
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
			$this->theme_templates_folder . $this->filepath,
			$this->default_templates_folder . $this->file
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
			$dirpath = dirname($path);
			if (file_exists($dirpath . '/images'))
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

	/**
	 * {@inheritdoc}
	 */
	public function supports_caching()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_cache_file_path()
	{
		if (!$this->is_cache_file_up_to_date())
		{
			$this->generate_cache_file();
		}
		return $this->cache_filepath;
	}
	
	public function get_pictures_data_path() {
		return $this->pictures_data_path;
	}
}
?>