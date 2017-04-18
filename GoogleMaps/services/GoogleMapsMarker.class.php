<?php
/*##################################################
 *                        GoogleMapsMarker.class.php
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

class GoogleMapsMarker
{
	private $name;
	private $address;
	private $latitude;
	private $longitude;
	private $zoom;
	private $address_displayed_on_label;
	
	/**
	 * @desc Constructs a GoogleMapsMarker.
	 * @param string $address Marker address
	 * @param string $latitude Marker latitude
	 * @param string $longitude Marker longitude
	 * @param string $name Marker name
	 * @param int $zoom Map zoom
	 */
	public function __construct($address = '', $latitude = '', $longitude = '', $name = '', $zoom = 0, $address_displayed_on_label = true)
	{
		$this->address = $address;
		$this->latitude = $latitude;
		$this->longitude = $longitude;
		$this->name  = $name;
		$this->zoom = $zoom;
		$this->address_displayed_on_label = $address_displayed_on_label;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_address($address)
	{
		$this->address = $address;
	}
	
	public function get_address()
	{
		return $this->address;
	}
	
	public function set_latitude($latitude)
	{
		$this->latitude = $latitude;
	}
	
	public function get_latitude()
	{
		return $this->latitude;
	}
	
	public function set_longitude($longitude)
	{
		$this->longitude = $longitude;
	}
	
	public function get_longitude()
	{
		return $this->longitude;
	}
	
	public function set_zoom($zoom)
	{
		$this->zoom = $zoom;
	}
	
	public function get_zoom()
	{
		return $this->zoom;
	}
	
	public function display_address_on_label()
	{
		$this->address_displayed_on_label = true;
	}
	
	public function hide_address_on_label()
	{
		$this->address_displayed_on_label = false;
	}
	
	public function is_address_displayed_on_label()
	{
		return $this->address_displayed_on_label;
	}
	
	public function get_properties()
	{
		return array(
			'name' => $this->get_name(),
			'address' => $this->get_address(),
			'latitude' => $this->get_latitude(),
			'longitude' => $this->get_longitude(),
			'zoom' => $this->get_zoom(),
			'address_displayed_on_label' => (int)$this->is_address_displayed_on_label()
		);
	}
	
	public function set_properties(array $properties)
	{
		if (isset($properties['address']))
			$this->address = $properties['address'];
		
		if (isset($properties['latitude']))
			$this->latitude = $properties['latitude'];
		
		if (isset($properties['longitude']))
			$this->longitude = $properties['longitude'];
		
		if (isset($properties['name']))
			$this->name = $properties['name'];
		
		if (isset($properties['zoom']))
			$this->zoom = $properties['zoom'];
		
		if (isset($properties['address_displayed_on_label']))
			$this->address_displayed_on_label = (bool)$properties['address_displayed_on_label'];
	}
	
	public function get_array_tpl_vars()
	{
		return array(
			'C_ADDRESS' => $this->get_address(),
			'C_COORDONATES' => $this->get_latitude() && $this->get_longitude(),
			'C_ZOOM' => $this->get_zoom() > 0,
			'MARKER_NAME' => $this->get_name(),
			'LABEL' => ($this->get_name() ? '<strong>' . $this->get_name() . '</strong><br />' : '') . ($this->is_address_displayed_on_label() ? $this->get_address() : ''),
			'ADDRESS' => $this->get_address(),
			'LATITUDE' => $this->get_latitude(),
			'LONGITUDE' => $this->get_longitude(),
			'ZOOM' => $this->get_zoom()
		);
	}
}
?>
