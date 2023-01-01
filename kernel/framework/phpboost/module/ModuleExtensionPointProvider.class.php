<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 6.0 - 2020 01 02
*/

class ModuleExtensionPointProvider extends ExtensionPointProvider
{
	/**
	 * @var mixed[] parameters of the module
	 */
	protected $module;
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($extension_provider_id = '')
	{
		parent::__construct($extension_provider_id);
		$this->module = ModulesManager::get_module($extension_provider_id);
	}

	public function comments()
	{
		if ($this->module && $this->module->get_configuration()->feature_is_enabled('comments'))
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

	public function hook()
	{
		if ($class = $this->get_class('Hook'))
			return $class;
	}

	public function menus()
	{
		if ($class = $this->get_class('ModuleMiniMenu'))
			return new ModuleMenus(array($class));
	}

	public function tree_links()
	{
		return $this->get_class('TreeLinks', 'ModuleTreeLinksExtensionPoint');
	}

	public function url_mappings()
	{
		return new UrlMappings(array(new DispatcherUrlMapping('/' . $this->get_id() . '/index.php')));
	}
}
?>
