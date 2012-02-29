<?php
/*##################################################
 *                       WebConfigUpdateVersion.class.php
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

class WebConfigUpdateVersion extends ConfigUpdateVersion
{
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$web_config = WebConfig::load();
		$web_config->set_max_nbr_weblinks($config['nbr_web_max']);   
        $web_config->set_max_nbr_category($config['nbr_cat_max']);
        $web_config->set_number_columns($config['nbr_column']);
        $web_config->set_note_max($config['note_max']);
        WebConfig::save();
        
		return true;
	}
}
?>