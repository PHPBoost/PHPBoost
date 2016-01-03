<?php
/*##################################################
 *                       ShoutboxConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
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

class ShoutboxConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('shoutbox');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$shoutbox_config = ShoutboxConfig::load();
		
		if ($config['max_messages_number'] == '-1')
		{
			$shoutbox_config->disable_max_messages_number();
			$shoutbox_config->set_max_messages_number(50);
		}
		else
		{
			$shoutbox_config->enable_max_messages_number();
		}
		
		if ($config['max_links_number_per_message'] == '-1')
		{
			$shoutbox_config->disable_max_links_number_per_message();
			$shoutbox_config->set_max_links_number_per_message(2);
		}
		else
		{
			$shoutbox_config->enable_max_links_number_per_message();
		}
		
		ShoutboxConfig::save();
		
		return true;
	}
}
?>