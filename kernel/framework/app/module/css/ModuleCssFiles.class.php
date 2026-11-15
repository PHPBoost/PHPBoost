<?php
/**
 * This class allows you to manage CSS files of a module
 * @package     PHPBoost
 * @subpackage  Module\css
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 03 27
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleCssFiles implements CssFilesExtensionPoint
{
    /**
     * @var array
     */
    private array $css_files_always_displayed = [];

    /**
     * @var array
     */
    private array $css_files_running_module_displayed = [];

    /**
     * Adding CSS files to the module to display only the pages of the module
     * @param string $css_file containing CSS file name
     */
    public function adding_always_displayed_file(string $css_file): void
    {
        $this->css_files_always_displayed[] = $css_file;
    }

    /**
     * @return array
     */
    public function get_css_files_always_displayed(): array
    {
        return $this->css_files_always_displayed;
    }

    /**
     * Adding CSS files to display on all pages
     * @param string $css_file containing CSS file name
     * @param string $module_id
     */
    public function adding_running_module_displayed_file(string $css_file, string $module_id = ''): void
    {
        $this->css_files_running_module_displayed[] = ['css_file' => $css_file, 'module_id' => $module_id];
    }

    /**
     * @return array
     */
    public function get_css_files_running_module_displayed(): array
    {
        return $this->css_files_running_module_displayed;
    }
}
?>
