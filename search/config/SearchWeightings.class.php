<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 05 10
 * @since       PHPBoost 3.0 - 2012 02 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

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
			if ($provider->search() !== false)
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
