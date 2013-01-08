<?php
/*##################################################
 *                       CalendarConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 29, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class CalendarConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('calendar');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$calendar_config = CalendarConfig::load();
		$calendar_config->set_authorizations($this->build_authorizations($config['calendar_auth']));
		CalendarConfig::save();
        
		return true;
	}
	
	private function build_authorizations($old_auth)
	{
		switch ($old_auth) {
			case -1:
				return array('r-1' => 2, 'r0' => 2, 'r1' => 6);
			break;
			case 0:
				return array('r0' => 2, 'r1' => 6);
			break;
			case 1:
				return array('r1' => 6);
			break;
			case 2:
				return array('r2' => 6);
			break;
			default:
				return array('r-1' => 2, 'r0' => 2, 'r1' => 6);
			break;
		}
	}
}
?>