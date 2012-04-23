<?php
/*##################################################
 *                       FaqConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : February 29, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class FaqConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('faq');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$faq_config = FaqConfig::load();
		$faq_config->set_faq_name($config['faq_name']);
		$faq_config->set_number_columns($config['num_cols']);
		$faq_config->set_display_mode($config['display_block']);
		$faq_config->set_authorizations($config['global_auth']);
		$faq_config->set_root_cat_display_mode($config['root']['display_mode']);
		$faq_config->set_root_cat_description($config['root']['description']);
		FaqConfig::save();
        
		return true;
	}
}
?>