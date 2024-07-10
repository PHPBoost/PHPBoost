<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\js
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 06 25
 * @since       PHPBoost 6.0 - 2024 06 25
*/

interface JsFilesExtensionPoint extends ExtensionPoint
{
    const EXTENSION_POINT = 'js_files';

    /**
     * @return array js files that will be loaded everytime in top_js
     */
    public function get_top_js_files_always_displayed():array;

    /**
     * @return array js files that will be loaded everytime in bottom_js
     */
    public function get_bottom_js_files_always_displayed():array;

    /**
     * @return array js files that will be loaded only in the current module at top_js
     */
    public function get_top_js_files_running_module_displayed():array;

    /**
     * @return array js files that will be loaded only in the current module at bottom_js
     */
    public function get_bottom_js_files_running_module_displayed():array;
}