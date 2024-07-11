<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\js
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 10
 * @since       PHPBoost 6.0 - 2024 06 25
*/

class ModulesJsFilesService
{
    /**
     * @var array
     * Modules who called js_files in ExtensionPoint
     */
    private static $modules_js_files = [];

    /**
     * Static constructor, to call all modules ExtensionPoint::js_files()
     */
    public static function __static()
    {
        $extension_points = AppContext::get_extension_provider_service()->get_extension_point(JsFilesExtensionPoint::EXTENSION_POINT);
        foreach ($extension_points as $module_id => $provider)
        {
            self::$modules_js_files[$module_id] = $provider;
        }
    }

    /**
     * Get all modules JS files always displayed in the top_js
     * @return string[] JS files
     */
    public static function get_top_js_files_always_displayed():array
    {
        $js_files = [];
        foreach (self::$modules_js_files as $module_id => $module_js_files)
        {
            if ($module_js_files !== false)
            {
                foreach ($module_js_files->get_top_js_files_always_displayed() as $js_file)
                {
                    $js_files[] = self::get_real_path_js_file($module_id, $js_file);
                }
            }
        }
        return $js_files;
    }

    /**
     * Get all modules JS files always displayed in the bottom_js
     * @return string[] JS files
     */
    public static function get_bottom_js_files_always_displayed():array
    {
        $js_files = [];
        foreach (self::$modules_js_files as $module_id => $module_js_files)
        {
            if ($module_js_files !== false)
            {
                foreach ($module_js_files->get_bottom_js_files_always_displayed() as $js_file)
                {
                    $js_files[] = self::get_real_path_js_file($module_id, $js_file);
                }
            }
        }
        return $js_files;
    }

    /**
     * Get all modules JS files displayed in the module, at the top_js
     * @return string[] JS files
     */
    public static function get_top_js_files_running_module_displayed():array
    {
        $js_files = [];
        $module_id = Environment::get_running_module_name();
        if (array_key_exists($module_id, self::$modules_js_files))
        {
            if (self::$modules_js_files[$module_id] !== false)
            {
                foreach (self::$modules_js_files[$module_id]->get_top_js_files_running_module_displayed() as $js_file_options)
                {
                    if (!empty($js_file_options['js_file']))
                    {
                        $module = !empty($js_file_options['module_id']) ? $js_file_options['module_id'] : Environment::get_running_module_name();
                        $js_files[] = self::get_real_path_js_file($module, $js_file_options['js_file']);
                    }
                }
            }
        }
        return $js_files;
    }

    /**
     * Get all modules JS files displayed in the module, at the bottom_js
     * @return string[] JS files
     */
    public static function get_bottom_js_files_running_module_displayed():array
    {
        $js_files = [];
        $module_id = Environment::get_running_module_name();
        if (array_key_exists($module_id, self::$modules_js_files))
        {
            if (self::$modules_js_files[$module_id] !== false)
            {
                foreach (self::$modules_js_files[$module_id]->get_bottom_js_files_running_module_displayed() as $js_file_options)
                {
                    if (!empty($js_file_options['js_file']))
                    {
                        $module = !empty($js_file_options['module_id']) ? $js_file_options['module_id'] : Environment::get_running_module_name();
                        $js_files[] = self::get_real_path_js_file($module, $js_file_options['js_file']);
                    }
                }
            }
        }
        return $js_files;
    }

    /**
     * Return true path of the JS file
     * If URL, return as it is
     * Else, try to find in
     * - 'templates/$theme/modules/$module/$filename'
     * - 'templates/$parent_theme/modules/$module/$filename'
     * - '$module/templates/$filename'
     * - 'templates/__default__/plugins/$filename'
     * - 'kernel/lib/js/$filename'
     * Else, return '$module/templates/$filename' 
     */
    private static function get_real_path_js_file($module_id, $js_file):string
    {
        if (filter_var($js_file, FILTER_VALIDATE_URL))
        {
            return $js_file;
        }

        $theme_id = AppContext::get_current_user()->get_theme();
        $theme = ThemesManager::get_theme($theme_id);
        $parent_theme_id = $theme ? $theme->get_configuration()->get_parent_theme() . '/' : '';

        if (file_exists(PATH_TO_ROOT . '/templates/' . $theme_id . '/modules/' . $module_id . '/js/' . $js_file))
        {
            return '/templates/' . $theme_id . '/modules/' . $module_id . '/js/' . $js_file;
        }
        if (!empty($parent_theme_id) && file_exists(PATH_TO_ROOT . '/templates/' . $parent_theme_id . '/modules/' . $module_id . '/js/' . $js_file))
        {
            return '/templates/' . $parent_theme_id . '/modules/' . $module_id . '/js/' . $js_file;
        }
        if (file_exists(PATH_TO_ROOT . '/'. $module_id . '/templates/js/' . $js_file))
        {
            return '/' . $module_id . '/templates/js/' . $js_file;
        }
        if (file_exists(PATH_TO_ROOT . '/templates/__default__/plugins/' . $js_file))
        {
            return '/templates/__default__/plugins/' . $js_file;
        }
        if (file_exists(PATH_TO_ROOT . '/kernel/lib/js/' . $js_file))
        {
            return '/kernel/lib/js/' . $js_file;
        }
        return '/' . $module_id . '/templates/js/' . $js_file;
    }
}