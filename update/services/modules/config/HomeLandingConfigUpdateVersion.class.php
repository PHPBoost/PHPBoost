<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 10 19
 * @since       PHPBoost 6.0 - 2020 07 29
*/

class HomeLandingConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('homelanding-config', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		
		if (ModulesManager::is_module_installed('HomeLanding') && ModulesManager::get_module('HomeLanding')->get_configuration()->get_compatibility() == UpdateServices::NEW_KERNEL_VERSION && class_exists('HomeLandingConfig') && !empty($old_config))
		{
			$config = HomeLandingConfig::load();
			
			$config->set_anchors_menu((bool)$old_config->get_property('onepage_menu'));
			
			$modules = $config->get_modules();
			foreach($modules as &$module)
			{
				if ($module['module_id'] == 'onepage_menu')
					$module['module_id'] = 'anchors_menu';
			}
			
			$config->set_modules($modules);
			
			HomeLandingConfig::save();
			
			return true;
		}
		return false;
	}
}
?>
