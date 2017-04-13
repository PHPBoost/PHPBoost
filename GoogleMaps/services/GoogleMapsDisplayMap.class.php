<?php
/*##################################################
 *                        GoogleMapsDisplayMap.class.php
 *                            -------------------
 *   begin                : April 3, 2017
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

class GoogleMapsDisplayMap
{
	private $markers = array();
	private $map_id;
	private $default_marker_label;
	private $hide_address_on_label;
	
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private $include_api = true;
	
	public function __construct($markers = '', $map_id = '', $default_marker_label = '', $address_displayed_on_label = true)
	{
		$unserialized_value = @unserialize($markers);
		$markers = $unserialized_value !== false ? $unserialized_value : $markers;
		
		if (is_array($markers))
		{
			if (array_key_exists(0, $markers))
			{
				foreach ($markers as $m)
				{
					if (!($m instanceof GoogleMapsMarker))
					{
						$marker = new GoogleMapsMarker();
						
						$marker->set_properties(array(
							'name' => isset($m['name']) ? $m['name'] : $default_marker_label, 
							'address' => isset($m['address']) ? $m['address'] : '', 
							'latitude' => isset($m['latitude']) ? $m['latitude'] : '',
							'longitude' => isset($m['longitude']) ? $m['longitude'] : '',
							'zoom' => isset($m['zoom']) ? $m['zoom'] : 0,
							'address_displayed_on_label' => isset($m['address_displayed_on_label']) ? $m['address_displayed_on_label'] : ''
						));
					}
					else
						$marker = $m;
					
					$this->markers[] = $marker;
				}
			}
			else
			{
				if (!($markers instanceof GoogleMapsMarker))
				{
					$marker = new GoogleMapsMarker();
					
					$marker->set_properties(array(
						'name' => isset($markers['name']) ? $markers['name'] : $default_marker_label, 
						'address' => isset($markers['address']) ? $markers['address'] : '', 
						'latitude' => isset($markers['latitude']) ? $markers['latitude'] : '',
						'longitude' => isset($markers['longitude']) ? $markers['longitude'] : '',
						'zoom' => isset($markers['zoom']) ? $markers['zoom'] : '',
						'address_displayed_on_label' => isset($markers['address_displayed_on_label']) ? $markers['address_displayed_on_label'] : ''
					));
				}
				else
					$marker = $markers;
				
				$this->markers[] = $marker;
			}
		}
		else
		{
			if (!($markers instanceof GoogleMapsMarker))
			{
				$marker = new GoogleMapsMarker();
				$marker->set_address($markers);
			}
			else
				$marker = $markers;
			
			$this->markers[] = $marker;
		}
		
		$this->map_id = !empty($map_id) ? 'map_' . $map_id : 'map';
		$this->default_marker_label = $default_marker_label;
		$this->address_displayed_on_label = $address_displayed_on_label;
	}
	
	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$config       = GoogleMapsConfig::load();
		$default_zoom = $config->get_default_zoom();
		
		$template = new FileTemplate('GoogleMaps/GoogleMap.tpl');
		$template->add_lang(LangLoader::get('common', 'GoogleMaps'));
		
		foreach ($this->markers as $marker)
		{
			$template->assign_block_vars('markers', $marker->get_array_tpl_vars());
			
			$default_zoom = ($marker->get_zoom() > $default_zoom) ? $marker->get_zoom() : $default_zoom;
		}
		
		$template->put_all(array(
			'C_INCLUDE_API' => $this->include_api,
			'C_MULTIPLE_MARKERS' => count($this->markers) > 1,
			'DEFAULT_ZOOM' => $default_zoom,
			'DEFAULT_LATITUDE' => $config->get_default_marker_latitude(),
			'DEFAULT_LONGITUDE' => $config->get_default_marker_longitude(),
			'API_KEY' => $config->get_api_key(),
			'MAP_ID' => $this->map_id
		));
		
		return $template->render();
	}
}
?>
