<?php
/*##################################################
 *                         ExtensionPointProviderService.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Rouchon Loïc
 *   email                : loic.rouchon@phpboost.com
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

/**
 * @author Loïc Rouchon <loic.rouchon@phpboost.com>
 * @desc This class is a ExtensionPointProvider factory providing some services like
 * mass operations (on several modules at the same time) or identifications
 * methods to get all ExtensionPointProvider that provide a given extension point
 * @package extension-provider
 */
class ExtensionPointProviderService
{
	const EXTENSION_POINT_PROVIDER_SUFFIX = 'Interface';

	private $loaded_providers = array();
	private $available_providers_ids = array();

	/**
	 * @desc Builds a new ExtensionPointProvider factory
	 */
	public function __construct()
	{
		foreach (ModulesManager::get_installed_modules_map() as $provider_id => $module)
		{
			if ($module->is_activated())
			{
				$this->register_provider($provider_id);
			}
		}
		$this->register_provider('kernel');
		$this->register_provider('install');
	}

	/**
	 * @desc Call the method called $extension_point on each speficied ExtensionPointProvider
	 * @param string $extension_point The method name to call on ExtensionPointProviders
	 * @param mixed[string] $providers The modules arguments in an array which keys
	 * are modules ids and values specifics arguments for those modules.
	 * @return mixed[string] The results of the call method on all
	 * modules. This array has keys that are the modules ids and the associated
	 * value is the return value for this particular module.
	 */
	public function call($extension_point, $providers)
	{
		$results = array();
		foreach ($providers as $provider_id => $args)
		{
			$provider = $this->get_provider($provider_id);
			if ($provider->has_extension_point($extension_point) == true)
			{
				$results[$provider_id] = $provider->call($extension_point, $args);
			}
		}
		return $results;
	}

	/**
	 * @desc Returns the ExtensionPointProvider list.
	 * @param string $extension_point the extension point name. By default, returns
	 * all availables modules interfaces.
	 * @param ExtensionPointProvider[] $providersList If specified, only keep modules
	 * interfaces having the requested extension point. Else, search in all
	 * availables modules interfaces.
	 * @return ExtensionPointProvider[] the ExtensionPointProvider list.
	 */
	public function get_available_modules($extension_point, $providers_list = array())
	{
		$providers = array();
		if (empty($providers_list))
		{
			foreach ($this->available_providers_ids as $extension_provider_id)
			{
				$provider = $this->get_provider($extension_provider_id);
				if ($provider->has_extension_point($extension_point))
				{
					$providers[$provider->get_id()] = $provider;
				}
			}
		}
		else
		{
			foreach ($providers_list as $provider)
			{
				if ($provider->has_extension_point($extension_point))
				{
					$providers[$provider->get_id()] = $provider;
				}
			}
		}
		return $providers;
	}


	/**
	 * @desc Returns the ExtensionPointProvider of the provider which id is $provider_id.
	 * @param string $provider_id The provider id.
	 * @return ExtensionPointProvider The corresponding ExtensionPointProvider.
	 * @throws UnexistingExtensionPointProviderException
	 */
	public function get_provider($provider_id = '')
	{
		if (!array_key_exists($provider_id, $this->loaded_providers))
		{
			if (!in_array($provider_id, $this->available_providers_ids))
			{
				throw new UnexistingExtensionPointProviderException($provider_id);
			}
			$classname = $this->compute_provider_classname($provider_id);
			$this->loaded_providers[$provider_id] = new $classname();
		}
		return $this->loaded_providers[$provider_id];
	}

	public function get_provider_extensions_points($provider)
	{
		$module_methods = get_class_methods($provider);
		$generics_methods = get_class_methods('ExtensionPointProvider');
		return array_values(array_diff($module_methods, $generics_methods));
	}

	private function register_provider($provider_id)
	{
		if ($this->check_provider($provider_id))
		{
			$this->available_providers_ids[] = $provider_id;
		}
	}

	private function check_provider($provider_id)
	{
		$provider_classname = $this->compute_provider_classname($provider_id);
		return ClassLoader::is_class_registered_and_valid($provider_classname);
	}

	private function compute_provider_classname($provider_id)
	{
		return ucfirst($provider_id) . self::EXTENSION_POINT_PROVIDER_SUFFIX;
	}
}

?>
