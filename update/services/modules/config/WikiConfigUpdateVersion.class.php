<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 11 03
 * @since       PHPBoost 5.1 - 2018 12 19
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('wiki');

		$this->config_parameters_to_modify = array(
			'root_category_description' => array(
				'parameter_name' => 'root_category_description',
				'value' => $this->get_parsed_old_content('WikiConfig', 'index_text')
			)
		);
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if ($old_config)
		{
			$config = WikiConfig::load();
			$config->set_property('authorizations', $this->build_authorizations($old_config->get_property('authorizations')));
			$this->save_new_config('wiki-config', $config);

			return true;
		}
		return false;
	}

	private function build_authorizations($old_auth)
	{
		$new_auth = array();

		foreach ($old_auth as $level => $auth)
		{
			if (($auth - 4096) < 0 && in_array($level, array("r-1", "r0", "r1")))
				$new_auth[$level] = $auth + 4096;
		}

		if (!isset($new_auth['r-1']))
			$new_auth['r-1'] = 5120;
		if (!isset($new_auth['r0']))
			$new_auth['r0'] = 5395;
		if (!isset($new_auth['r1']))
			$new_auth['r1'] = 8191;

		return $new_auth;
	}
}
?>
