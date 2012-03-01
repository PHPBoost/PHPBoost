<?php
/*##################################################
 *                       ShoutboxConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 29, 2012
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

class ShoutboxConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('shoutbox');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$config_shoutbox = ShoutboxConfig::load();
		$config_shoutbox->set_max_messages_number($config['shoutbox_max_msg']);
		$config_shoutbox->set_authorization($this->build_authorizations($config['shoutbox_auth']));
		$config_shoutbox->set_forbidden_formatting_tags($config['shoutbox_forbidden_tags']);
		$config_shoutbox->set_max_links_number_per_message($config['shoutbox_max_link']);
		$config_shoutbox->set_refresh_delay($config['shoutbox_refresh_delay']);
		ShoutboxConfig::save();
        
		return true;
	}
	
	private function build_authorizations($old_auth)
	{
		switch ($old_auth) {
			case -1:
				return array ('r-1' => 3, 'r0' => 3, 'r1' => 7);
			break;
			case 0:
				return array('r-1' => 1, 'r0' => 3, 'r1' => 7);
			break;
			case 1:
				return array('r-1' => 1, 'r0' => 1, 'r1' => 7);
			break;
			case 2:
				return array('r-1' => 1, 'r0' => 1, 'r1' => 1, 'r2' => 7);
			break;
			default:
				return array ('r-1' => 3, 'r0' => 3, 'r1' => 7);
			break;
		}
	}
}
?>