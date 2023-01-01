<?php
/**
 * @package     Content
 * @subpackage  Item\bridges
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 6.0 - 2020 01 27
*/

class DefaultScheduledJobsModule extends AbstractScheduledJobExtensionPoint
{
	/**
	 * @var string the module identifier
	 */
	protected $module_id;

	public function __construct($module_id)
	{
		$this->module_id = $module_id;
	}

	public function on_changepage()
	{
		if (Environment::get_running_module_name() == $this->module_id)
			$this->deferred_publication_processing();
	}

	protected function deferred_publication_processing()
	{
		$module_configuration = ModuleConfigurationManager::get($this->module_id);

		if ($module_configuration->feature_is_enabled('deferred_publication') && $module_configuration->has_rich_config_parameters())
		{
			$config_class = $module_configuration->get_configuration_name();
			$config = $module_configuration->get_configuration_parameters();
			
			$deferred_operations = $config->get_deferred_operations();

			if (!empty($deferred_operations))
			{
				$now = new Date();
				$is_modified = false;

				foreach ($deferred_operations as $id => $timestamp)
				{
					if ($timestamp <= $now->get_timestamp())
					{
						unset($deferred_operations[$id]);
						$is_modified = true;
					}
				}

				if ($is_modified)
				{
					ItemsService::get_items_manager($this->module_id)->clear_cache();
					$config->set_deferred_operations($deferred_operations);
					$config_class::save();
				}
			}
		}
	}
}
?>
