<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 07 31
 * @since       PHPBoost 3.0 - 2012 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class ConfigUpdateVersion implements UpdateVersion
{
	protected $config_name;
	protected $querier;
	protected $delete_old_config = true;

	public function __construct($config_name, $delete_old_config = true)
	{
		$this->config_name = $config_name;
		$this->delete_old_config = $delete_old_config;
		$this->querier = PersistenceContext::get_querier();
	}

	public function get_config_name()
	{
		return $this->config_name;
	}

	public function execute()
	{
		try {
			if ($this->build_new_config())
			{
				if ($this->delete_old_config)
				{
					$this->delete_old_config();
				}
			}
		} catch (RowNotFoundException $e) {
		}
	}

	protected function get_old_config($serialize = true)
	{
		if ($serialize)
		{
			mb_internal_encoding('utf-8');
			return @unserialize($this->querier->get_column_value(DB_TABLE_CONFIGS, 'value', 'WHERE name = :config_name', array('config_name' => $this->get_config_name())));
		}
		return $this->querier->get_column_value(DB_TABLE_CONFIGS, 'value', 'WHERE name = :config_name', array('config_name' => $this->get_config_name()));
	}

	protected function save_new_config($name, ConfigData $data)
	{
		return $this->querier->inject('UPDATE ' . DB_TABLE_CONFIGS . ' SET value = :value WHERE name = :config_name', array('value' => TextHelper::serialize($data), 'config_name' => $name));
	}

	protected function build_new_config()
	{
		return true;
	}

	protected function delete_old_config()
	{
		$this->querier->delete(DB_TABLE_CONFIGS, 'WHERE name = :config_name', array('config_name' => $this->get_config_name()));
	}
}
?>
