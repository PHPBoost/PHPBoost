<?php
/*##################################################
 *                       FaqConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : January 2, 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
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
		$faq_config->set_columns_number_per_line($config['number_columns']);
		$faq_config->set_display_type($config['display_mode']);
		$faq_config->set_root_category_description($config['root_cat_description']);
		
		FaqConfig::save();
		
		return true;
	}
}
?>