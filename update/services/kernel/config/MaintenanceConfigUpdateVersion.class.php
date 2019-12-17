<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 26
 * @since       PHPBoost 5.0 - 2017 04 05
*/

class MaintenanceConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('kernel-maintenance', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		$maintenance_config = MaintenanceConfig::load();
		$maintenance_config->set_property('enabled', true);
		$maintenance_config->set_property('unlimited', true);
		$maintenance_config->set_property('message', $old_config->get_property('message'));
		$this->save_new_config('kernel-maintenance', $maintenance_config);

		return true;
	}
}
?>
