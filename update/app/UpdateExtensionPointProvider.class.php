<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2016 07 31
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class UpdateExtensionPointProvider extends ExtensionPointProvider
{
    public function __construct()
    {
        parent::__construct('update');
    }

    public function commands(): CLICommandsList
    {
        return new CLICommandsList(['update' => 'CLIUpdateCommand']);
    }
}
