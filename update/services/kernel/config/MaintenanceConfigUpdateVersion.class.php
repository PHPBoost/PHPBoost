<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 5.0 - 2017 04 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MaintenanceConfigUpdateVersion extends ConfigUpdateVersion
{
    public function __construct()
    {
        parent::__construct('kernel', false, 'kernel-maintenance');
    }

    protected function build_new_config(): bool
    {
        $old_config = $this->get_old_config();

        // Explicitly load the class file before calling MaintenanceConfig::load()
        // which triggers unserialize(). The module_id is 'kernel' so the generic
        // class_exists('KernelConfig') in ConfigUpdateVersion::execute() does not
        // cover MaintenanceConfig — we must require_once it here directly.
        require_once PATH_TO_ROOT . '/kernel/framework/util/Date.class.php';
        require_once PATH_TO_ROOT . '/kernel/framework/io/data/config/AbstractConfigData.class.php';
        require_once PATH_TO_ROOT . '/kernel/framework/phpboost/config/MaintenanceConfig.class.php';
        $maintenance_config = MaintenanceConfig::load();

        if (!$maintenance_config->is_under_maintenance())
        {
            $maintenance_config->set_property('enabled', true);
            $maintenance_config->set_property('unlimited', true);

            if ($old_config->has_property('message'))
                $maintenance_config->set_property('message', $old_config->get_property('message'));

            $this->save_new_config('kernel-maintenance', $maintenance_config);

            return true;
        }
        return false;
    }
}
?>
