<?php
/*##################################################
 *                           OnlineConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : March 8, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class OnlineConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('online');
	}

	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		switch ($config['display_order_online'])
		{
				case 's.level DESC':
				$display_order = 'level_display_order';
			break;
			
				case 's.session_time DESC':
				$display_order = 'session_time_display_order';
			break;
			
				case 's.level DESC, s.session_time DESC':
				$display_order = 'level_and_session_time_display_order';
			break;
			default:
				$display_order = 'level_and_session_time_display_order';
		}
		
		$online_config = OnlineConfig::load();
		$online_config->set_display_order($display_order);
		$online_config->set_number_member_displayed($config['online_displayed']);
		OnlineConfig::save();

		return true;
	}
}
?>