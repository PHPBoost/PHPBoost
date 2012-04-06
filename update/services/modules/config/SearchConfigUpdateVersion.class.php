<?php
/*##################################################
 *                       SearchConfigUpdateVersion.class.php
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

class SearchConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('search');
	}
	
	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$search_config = SearchConfig::load();
		$search_config->set_nb_results_per_page($config['nb_results_per_page']);
		$search_config->set_unauthorized_providers($config['unauthorized_modules']);
		$search_weightings = new SearchWeightings();
		$search_weightings->set_weightings((!empty($config['modules_weighting']) ? $config['modules_weighting'] : array()));
		$search_config->set_weightings($search_weightings);
		SearchConfig::save();
        
		return true;
	}
}
?>