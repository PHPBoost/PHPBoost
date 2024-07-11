<?php
/**
 * Update Stats for new charts
 * @copyright   &copy; 2005-2024 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Maxence CAUDERLIER <mxkoder@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 07 06
 * @since       PHPBoost 6.0 - 2024 07 06
*/

class StatsModuleUpdateVersion extends ModuleUpdateVersion
{
    public function __construct()
    {
        parent::__construct('stats');

        self::$delete_old_files_list = [
            '/controllers/StatsGraphsController.class.php',
            '/services/ImagesStats.class.php',
            '/services/StatsDisplayService.class.php',
            '/templates/images/stats.png',
            '/templates/images/stats2.png'
        ];

        self::$delete_old_folders_list = [
            '/templates/images'
        ];
    }
}