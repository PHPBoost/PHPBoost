<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\js
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 6.0 - 2024 06 25
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleJsFiles implements JsFilesExtensionPoint
{
    private array $js_files_always_displayed = [];
    private array $js_files_running_module_displayed = [];

    const POSITION_JS_TOP = 'top';
    const POSITION_JS_BOTTOM = 'bottom';


    /**
     * Adding js files to display on all pages
     * @param string $js_file containing js file name
     * @param int $position POSITION_TOP_JS | POSITION_BOTTOM_JS
     */
    public function adding_always_displayed_file(string $js_file, string $position = self::POSITION_JS_TOP): void
    {
        $this->js_files_always_displayed[$position][] = $js_file;
    }

    /**
     * Adding js files to the module to display only on the pages of the module
     * @param string $js_file containing js file name
     * @param int $position POSITION_TOP_JS | POSITION_BOTTOM_JS
     */
    public function adding_running_module_displayed_file(string $js_file, string $position = self::POSITION_JS_TOP): void
    {
        $this->js_files_running_module_displayed[$position][] = $js_file;
    }

    /**
     * Get all JS files to display everytime, in the top_js
     * @return array JS files
     */
    public function get_top_js_files_always_displayed(): array
    {
        return $this->js_files_always_displayed[self::POSITION_JS_TOP] ?? [];
    }

    /**
     * Get all JS files to display everytime, in the bottom_js
     * @return array JS files
     */
    public function get_bottom_js_files_always_displayed(): array
    {
        return $this->js_files_always_displayed[self::POSITION_JS_BOTTOM] ?? [];
    }

    /**
     * Get all JS files to display only in module, in the top_js
     * @return array JS files
     */
    public function get_top_js_files_running_module_displayed(): array
    {
        return $this->js_files_running_module_displayed[self::POSITION_JS_TOP] ?? [];
    }

    /**
     * Get all JS files to display only in module, in the bottom_js
     * @return array JS files
     */
    public function get_bottom_js_files_running_module_displayed(): array
    {
        return $this->js_files_running_module_displayed[self::POSITION_JS_BOTTOM] ?? [];
    }
}