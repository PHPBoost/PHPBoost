<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\js
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 06
 * @since       PHPBoost 6.0 - 2024 06 25
*/

class ModuleJsFiles implements JsFilesExtensionPoint
{
    /**
     * @var string[]
     */
    private $js_files_always_displayed = [];

    /**
     * @var string[]
     */
    private $js_files_running_module_displayed = [];

    const POSITION_JS_TOP = 'top';
    const POSITION_JS_BOTTOM = 'bottom';


    /**
     * Adding js files to display on all pages
     * @param string $js_file containing js file name
     * @param int $position POSITION_TOP_JS | POSITION_BOTTOM_JS
     */
    public function adding_always_displayed_file($js_file, $position = self::POSITION_JS_TOP)
    {
        $this->js_files_always_displayed[$position][] = $js_file;
    }

    /**
     * Adding js files to the module to display only on the pages of the module
     * @param string $js_file containing js file name
     * @param string $module_id Module name
     * @param int $position POSITION_TOP_JS | POSITION_BOTTOM_JS
     */
    public function adding_running_module_displayed_file($js_file, $module_id = '', $position = self::POSITION_JS_TOP)
    {
        $this->js_files_running_module_displayed[$position][] = ['js_file' => $js_file, 'module_id' => $module_id];
    }

    /**
     * Get all JS files to display everytime, in the top_js
     * @return string[] JS files
     */
    public function get_top_js_files_always_displayed():array
    {
        return $this->js_files_always_displayed[self::POSITION_JS_TOP] ?? [];
    }

    /**
     * Get all JS files to display everytime, in the bottom_js
     * @return string[] JS files
     */
    public function get_bottom_js_files_always_displayed():array
    {
        return $this->js_files_always_displayed[self::POSITION_JS_BOTTOM] ?? [];
    }

    /**
     * Get all JS files to display only in module, in the top_js
     * @return string[] JS files
     */
    public function get_top_js_files_running_module_displayed():array
    {
        return $this->js_files_running_module_displayed[self::POSITION_JS_TOP] ?? [];
    }

    /**
     * Get all JS files to display only in module, in the bottom_js
     * @return string[] JS files
     */
    public function get_bottom_js_files_running_module_displayed():array
    {
        return $this->js_files_running_module_displayed[self::POSITION_JS_BOTTOM] ?? [];
    }
}