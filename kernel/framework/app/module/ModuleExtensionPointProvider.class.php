<?php
/**
 * @package     PHPBoost
 * @subpackage  Module
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 6.0 - 2020 01 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleExtensionPointProvider extends ExtensionPointProvider
{
    /**
     * @var Module parameters of the module
     */
    protected Module $module;

    public function __construct(string $extension_provider_id = '')
    {
        parent::__construct($extension_provider_id);
        $this->module = ModulesManager::get_module($extension_provider_id);
    }

    public function comments(): mixed
    {
        if ($this->module && $this->module->get_configuration()->feature_is_enabled('comments'))
        {
            $class = $this->get_class('CommentsTopic');
            return ($class !== false) ? new CommentsTopics([$class]) : $class;
        }
        return false;
    }

    public function css_files(): mixed
    {
        $css_file = new File(ModulesManager::get_module_path($this->get_id()) . '/templates/' . $this->get_id() . '.css');
        $css_mini_file = new File(ModulesManager::get_module_path($this->get_id()) . '/templates/' . $this->get_id() . '_mini.css');
        if ($css_file->exists() || $css_mini_file->exists())
        {
            $module_css_files = new ModuleCssFiles();

            if ($css_file->exists())
            {
                $module_css_files->adding_running_module_displayed_file($this->get_id() . '.css');
            }
            if ($css_mini_file->exists())
            {
                $module_css_files->adding_always_displayed_file($this->get_id() . '_mini.css');
            }

            return $module_css_files;
        }
        return false;
    }

    public function js_files(): mixed
    {
        $js_file = new File(ModulesManager::get_module_path($this->get_id()) . '/templates/js/' . $this->get_id() . '.js');
        $js_mini_file = new File(ModulesManager::get_module_path($this->get_id()) . '/templates/js/' . $this->get_id() . '.mini.js');
        if ($js_file->exists() || $js_mini_file->exists())
        {
            $module_js_files = new ModuleJsFiles();

            if ($js_file->exists())
            {
                $module_js_files->adding_running_module_displayed_file($this->get_id() . '.js');
            }
            if ($js_mini_file->exists())
            {
                $module_js_files->adding_always_displayed_file($this->get_id() . '.mini.js');
            }

            return $module_js_files;
        }
        return false;
    }

    public function hook(): mixed
    {
        if ($class = $this->get_class('Hook'))
        {
            return $class;
        }
        return false;
    }

    public function menus(): mixed
    {
        if ($class = $this->get_class('ModuleMiniMenu'))
        {
            return new ModuleMenus([$class]);
        }
        return false;
    }

    public function tree_links(): mixed
    {
        return $this->get_class('TreeLinks', 'ModuleTreeLinksExtensionPoint');
    }

    public function url_mappings(): mixed
    {
        return new UrlMappings([new DispatcherUrlMapping('/' . $this->get_id() . '/index.php')]);
    }
}
?>
