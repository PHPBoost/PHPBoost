<?php
/*##################################################
 *                           BugtrackerConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 7, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class BugtrackerConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('bugtracker', false);
	}

	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$status_list = array(Bug::NEW_BUG => 0, Bug::PENDING => 0, Bug::ASSIGNED => 20, Bug::IN_PROGRESS => 50, Bug::REJECTED => 0, Bug::REOPEN => 30, Bug::FIXED => 100);
		$config->set_status_list($status_list);
		
		BugtrackerConfig::save();
		
		return true;
	}
}
?>