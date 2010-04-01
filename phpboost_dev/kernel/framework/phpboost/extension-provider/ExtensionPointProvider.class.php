<?php
/*##################################################
 *                        ExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 *###################################################
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
 *###################################################
 */

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This Class allow you to call methods on a ExtensionPointProvider extended class
 * that you're not sure of the method's availality. It also provides a set of
 * generic methods that you could use to integrate your module with others, or
 * allow your module to share services.
 * @package modules
 */
abstract class ExtensionPointProvider
{
	/**
	 * @var string the module identifier
	 */
	private $id;

	/**
	 * @var string[] list of the extensions points provided
	 */
	private $extensions_points = array();

	/**
	 * @desc ExtensionPointProvider constructor
	 * @param string $extension_provider_id the provider id. It's the name of the folder in witch
	 * the extension provider is
	 */
	public function __construct($extension_provider_id = '')
	{
		$this->id = $extension_provider_id;
		$ext_point_provider_service = AppContext::get_extension_provider_service();
		$this->extensions_points = $ext_point_provider_service->get_provider_extensions_points($this);
	}

	/**
	 * @return string Return the id of the EtensionPoint
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * @desc Check the existance of the extension point and if exists call it.
	 * @param string $extension_point the name of the method you want to call
	 * @param mixed $args the args you want to pass to the $extension_point method
	 * @return mixed the $extension_point returns
	 * @throws ExtensionPointNotFoundException
	 */
	public function get_extension_point($extension_point, $args = null)
	{
		if ($this->has_extension_point($extension_point))
		{
			return $this->$extension_point($args);
		}
		throw new ExtensionPointNotFoundException($extension_point);
	}

	/**
	 * @desc Check the availability of the extension_point (hook)
	 * @param string $extension_point the name of the method you want to check the availability
	 * @return bool true if the extension point exists, false otherwise
	 */
	public function has_extension_point($extension_point)
	{
		return in_array($extension_point, $this->extensions_points);
	}

	/**
	 * @desc Check the availability of the extensions points (hook)
	 * @param string[] $extensions_points the names of the methods you want to check the availability
	 * @return bool true if all extensions points exist, false otherwise
	 */
	public function has_extensions_points(array $extensions_points)
	{
		foreach($extensions_points as $extension_point)
		{
			if (!$this->has_extension_point($extension_point))
			{
				return false;
			}
		}
		return true;
	}
}

?>
