<?php
/*##################################################
 *                           MaintenanceConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : April 5, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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