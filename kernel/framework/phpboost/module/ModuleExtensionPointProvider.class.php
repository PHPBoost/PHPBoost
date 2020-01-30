<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 30
 * @since       PHPBoost 5.3 - 2020 01 02
*/

class ModuleExtensionPointProvider extends ExtensionPointProvider
{
	/**
	 * @var mixed[] parameters of the module
	 */
	private $module;
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($extension_provider_id = '')
	{
		parent::__construct($extension_provider_id);
		$this->module = ModulesManager::get_module($extension_provider_id);
	}

	/**
	 * @return mixed[] Return the parameters of the module
	 */
	public function get_module()
	{
		return $this->module;
	}

	public function comments()
	{
		if ($this->module->get_configuration()->feature_is_enabled('comments'))
		{
			$class = $this->get_class('CommentsTopic');
			return ($class !== false) ? new CommentsTopics(array($class)) : $class;
		}
		return false;
	}

	public function css_files()
	{
		$css_file = new File(PATH_TO_ROOT . '/' . $this->get_id() . '/templates/' . $this->get_id() . '.css');
		$css_mini_file = new File(PATH_TO_ROOT . '/' . $this->get_id() . '/templates/' . $this->get_id() . '_mini.css');
		if ($css_file->exists() || $css_mini_file->exists())
		{
			$module_css_files = new ModuleCssFiles();
			
			if ($css_file->exists())
				$module_css_files->adding_running_module_displayed_file($this->get_id() . '.css');
			if ($css_mini_file->exists())
				$module_css_files->adding_always_displayed_file($this->get_id() . '_mini.css');
			
			return $module_css_files;
		}
		return false;
	}

	public function feeds()
	{
		if ($class = $this->get_class('FeedProvider'))
			return $class;
		else
			return $this->module->get_configuration()->has_categories() ? new DefaultCategoriesFeedProvider($this->get_id()) : false;
	}

	public function home_page()
	{
		return false;
	}

	public function menus()
	{
		if ($class = $this->get_class('ModuleMiniMenu'))
			return new ModuleMenus(array(new $class()));
	}

	public function scheduled_jobs()
	{
		if ($class = $this->get_class('ScheduledJobs', 'ScheduledJobExtensionPoint'))
			return $class;
		else
			return new DefaultScheduledJobsModule($this->get_id());
	}

	public function search()
	{
		if ($this->module->get_configuration()->feature_is_enabled('search'))
			return $this->get_class('Searchable', 'SearchableExtensionPoint');
		else
			return false;
	}

	public function sitemap()
	{
		if ($this->home_page())
		{
			if ($class = $this->get_class('Sitemap', 'SitemapExtensionPoint'))
				return $class;
			else
				return $this->module->get_configuration()->has_categories() ? new DefaultSitemapCategoriesModule($this->get_id()) : new DefaultSitemapModule($this->get_id());
		}
	}

	public function tree_links()
	{
		return $this->get_class('TreeLinks', 'ModuleTreeLinksExtensionPoint');
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/' . $this->get_id() . '/index.php')));
	}

	private function get_class($extension_point_label, $extension_point_full_name = '')
	{
		$extension_point_full_name = !empty($extension_point_full_name) ? $extension_point_full_name : $extension_point_label;
		$class = TextHelper::ucfirst($this->get_id()) . $extension_point_label;
		$default_class = 'Default' . $extension_point_label;
		
		if (class_exists($class) && in_array($extension_point_full_name, class_implements($class)))
			return new $class($this->get_id());
		else if (class_exists($default_class) && in_array($extension_point_full_name, class_implements($default_class)))
			return new $default_class($this->get_id());
		else
			return false;
	}
}
?>
