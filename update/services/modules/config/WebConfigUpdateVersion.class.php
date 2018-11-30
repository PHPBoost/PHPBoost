<?php
/*##################################################
 *                       WebConfigUpdateVersion.class.php
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

class WebConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('web-config', false);
	}
	
	protected function build_new_config()
	{
		$old_config = $this->get_old_config();
		
		if (class_exists('WebConfig') && !empty($old_config))
		{
			$config = WebConfig::load();
			$config->set_partners_sort_field($old_config->get_property('sort_type'));
			$config->set_partners_sort_mode($old_config->get_property('sort_mode'));
			WebConfig::save();
			
			return true;
		}
		return false;
	}
}
?>