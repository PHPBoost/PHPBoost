<?php
/*##################################################
 *                       ConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 27, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

abstract class ConfigUpdateVersion implements UpdateVersion
{
	protected $config_name;
	protected $querier;
	
	public function __construct($config_name)
	{
		$this->config_name = $config_name;
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
				$this->delete_old_config();
			}
		} catch (RowNotFoundException $e) {
		}
	}
	
	protected function get_old_config($serialize = true)
	{
		if ($serialize)
		{
			return unserialize($this->querier->select_single_row(DB_TABLE_CONFIGS, 'value', 'WHERE name = :config_name', array('config_name' => $this->get_config_name())));
		}
		return $this->querier->select_single_row(DB_TABLE_CONFIGS, 'value', 'WHERE name = :config_name', array('config_name' => $this->get_config_name()));
	}
	
	abstract protected function build_new_config()
	{
		return true;
	}
	
	protected function delete_old_config()
	{
		$this->querier->delete(DB_TABLE_CONFIGS, 'WHERE name = :config_name', array('config_name' => $this->get_config_name()));
	}
}
?>