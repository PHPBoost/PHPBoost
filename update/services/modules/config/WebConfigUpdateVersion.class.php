<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 30
 * @since       PHPBoost 5.0 - 2017 04 05
*/

class WebConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('web-config', false);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if (class_exists('WebConfig') && !empty($old_config))
		{
			$config = WebConfig::load();
			$config->set_partners_sort_field($old_config->get_property('sort_type'));
			$config->set_partners_sort_mode($old_config->get_property('sort_mode'));
			WebConfig::save();

			return true;
		}
		return false;
	}
}
?>
