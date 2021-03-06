<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 24
 * @since       PHPBoost 6.0 - 2021 04 24
*/

class PollConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('poll');

		$this->config_parameters_to_modify = array(
			'displayed_in_mini_module_list' => 'mini_module_selected_items'
		);
	}

	protected function build_new_config()
	{
		$this->modify_config_parameters();
		
		$old_config = $this->get_old_config();

		if ($old_config)
		{
			$config = PollConfig::load();
			$config->set_property('authorizations', $this->build_authorizations($old_config->get_property('authorizations')));
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
