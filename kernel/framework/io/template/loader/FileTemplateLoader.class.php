<?php
/**
 * This loader is the most used. It takes a file identifier as input. This identifier corresponds
 * to a file that can be different from the user's theme. In fact, when it loads a template, its looks for
 * it at several places. For a module template, it can be the default one which is in the /module/templates directory,
 * but if the file is specialized by the theme, it loads it from the theme directory. All that is explained
 * in the {@link FileTemplate} class description.
 * This loader supports caching and stores cache files in the /cache/tpl directory, their name are related to
 * their source's real path.
 * @package     IO
 * @subpackage  Template\loader
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 04 25
 * @since       PHPBoost 3.0 - 2009 06 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FileTemplateLoader implements TemplateLoader
{
	private $filepath;
	private $real_filepath = '';
	private $cache_filepath = '';
	private $pictures_data_path = '';

	private $module;
	private $file;

	private $templates_folder;
	private $default_templates_folder;
	private $theme_templates_folder;

	/**
	 * Constructs a {@link FileTemplateLoader} from the file's identifier.
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
	 * Computes the path of the file to load dinamycally according to the user theme and the kind of file (kernel, module, menu or framework file).
	 * @return string The path to load.
	 */
	private function compute_real_file_path()
	{
		if (TextHelper::strpos($this->filepath, '/') === 0)
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
		 */

		$i = TextHelper::strpos($this->filepath, '/');
		$this->module = TextHelper::substr($this->filepath, 0, $i);
		$this->file = trim(TextHelper::substr($this->filepath, $i), '/');
		$this->filename = trim(TextHelper::substr($this->filepath, TextHelper::strrpos($this->filepath, '/')));

		$this->templates_folder = PATH_TO_ROOT . '/templates/';
		$this->default_templates_folder = $this->templates_folder . 'default/';
		$this->theme_templates_folder = $this->templates_folder . AppContext::get_current_user()->get_theme() . '/';

		if (empty($this->module) || !TextHelper::strpos($this->filepath, '/'))
		{
			$this->get_template_paths();
		}
		else if (!in_array($this->module, array('default', 'admin', 'framework')))
		{
			// Module - Templates priority order
			//      /templates/$theme/modules/$module/$file.tpl
			//      /$module/templates/$file.tpl
			$this->get_module_paths();
		}
		else
		{
			// Kernel - Templates priority order
			//      /templates/$theme/default/.../$file.tpl
			//      /templates/default/.../$file.tpl
			$this->get_kernel_paths();
		}
	}

	private function get_template_paths()
	{
		$this->get_template_real_filepaths_and_data_path(array(
			$this->theme_templates_folder . $this->filepath,
			$this->theme_templates_folder . 'default/' . $this->filepath,
			$this->default_templates_folder . $this->filepath
		));
	}

	private function get_kernel_paths()
	{
		$this->get_template_real_filepaths_and_data_path(array(
			$this->theme_templates_folder . 'default/' . str_replace('default/', '', $this->filepath),
			($this->module == 'default' ? $this->templates_folder : $this->default_templates_folder) . $this->filepath
		));
	}

	private function get_module_paths()
	{
		$this->get_template_real_filepaths_and_data_path(array(
			$this->theme_templates_folder . 'modules/' . $this->module . '/' . $this->file,
			PATH_TO_ROOT . '/' . $this->module . '/templates/' . $this->file
		));
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
		return TPL_PATH_TO_ROOT . TextHelper::substr($path_to_root_filepath, TextHelper::strlen(PATH_TO_ROOT));
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
