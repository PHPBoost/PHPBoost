<?php
/*##################################################
 *		                    SearchWeightings.class.php
 *                            -------------------
 *   begin                : February 20, 2012
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

class SearchWeightings
{
	const DEFAULT_WEIGHTING = 1;
	
	private $weightings = array();
	
	public function get_modules_weighting()
	{
		$weighting = array();
		$provider_service = AppContext::get_extension_provider_service();
		foreach ($provider_service->get_providers(SearchableExtensionPoint::EXTENSION_POINT) as $module_id => $provider)
		{
			$weighting[$module_id] = $this->get_module_weighting($module_id);
		}
		return $weighting;
	}
	
	public function get_module_weighting($module_id)
	{
		if ($this->module_weighting_exists($module_id))
		{
			return $this->weightings[$module_id];
		}
		return self::DEFAULT_WEIGHTING;
	}
	
	public function add_module_weighting($module_id, $weighting)
	{
		$this->weightings[$module_id] = $weighting;
	}
	
	private function module_weighting_exists($module_id)
	{
		return array_key_exists($module_id, $this->weightings);
	}
	
	public function set_weightings(Array $weightings)
	{
		$this->weightings = $weightings;
	}
	
	public function get_weightings()
	{
		return $this->weightings;
	}
	
	public static function load(Array $weightings)
	{
		$search_weightings = new self();
		$search_weightings->set_weightings($weightings);
		return $search_weightings;
	}
}
?>