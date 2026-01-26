<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 01 26
 * @since       PHPBoost 2.0 - 2008 07 07
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DatabaseExtensionPointProvider extends ExtensionPointProvider
{
    public function __construct()
    {
        parent::__construct('database');
    }

    public function commands(): CLICommandsList
    {
        return new CLICommandsList(['dump' => 'CLIDumpCommand', 'restoredb' => 'CLIRestoreDBCommand']);
    }

    public function css_files(): ModuleCssFiles
    {
        $module_css_files = new ModuleCssFiles();
        $module_css_files->adding_running_module_displayed_file('database.css');
        return $module_css_files;
    }

    public function tree_links(): DatabaseTreeLinks
    {
        return new DatabaseTreeLinks();
    }

    public function url_mappings(): UrlMappings
    {
        return new UrlMappings([new DispatcherUrlMapping('/database/index.php')]);
    }
}
?>
