<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 6.0 - 2021 04 24
*/

class PollConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('poll');
	}

	protected function build_new_config()
	{
		$this->modify_config_parameters();
		
		$old_config = $this->get_old_config();

		if ($old_config)
		{
			$config = PollConfig::load();
			$config->set_property('authorizations', $this->build_authorizations($old_config->get_property('authorizations')));
			
			if ($old_config->has_property('displayed_in_mini_module_list'))
			{
				$config->set_property('mini_module_selected_items', $old_config->get_property('displayed_in_mini_module_list'));
				$config->delete_property('displayed_in_mini_module_list');
			}
			
			$config->delete_property('display_results_before_polls_end');
			$this->save_new_config('poll-config', $config);

			return true;
		}
		return false;
	}

	private function build_authorizations($old_auth)
	{
		$new_auth = array();

		foreach ($old_auth as $level => $auth)
		{
			if (($auth == 1 || $auth == 3) && in_array($level, array("r-1", "r0", "r1")))
			{
				if ($level == 'r1')
				{
					if ($auth == 1)
						$new_auth['r1'] = 77;
					else if ($auth == 3)
						$new_auth['r1'] = 109;
					else
						$new_auth['r1'] = $auth;
				}
				else if ($level == 'r0')
				{
					if ($auth == 1)
						$new_auth['r0'] = 71;
					else if ($auth == 3)
						$new_auth['r0'] = 103;
					else
						$new_auth['r0'] = $auth;
				}
				else
				{
					if ($auth == 1)
						$new_auth[$level] = 65;
					else if ($auth == 3)
						$new_auth[$level] = 97;
					else
						$new_auth[$level] = $auth;
				}
			}
		}

		if (!isset($new_auth['r-1']))
			$new_auth['r-1'] = 97;
		if (!isset($new_auth['r0']))
			$new_auth['r0'] = 103;
		if (!isset($new_auth['r1']))
			$new_auth['r1'] = 109;

		return $new_auth;
	}
}
?>
