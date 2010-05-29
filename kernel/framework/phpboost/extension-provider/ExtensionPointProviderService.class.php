<?php
/*##################################################
 *                         ExtensionPointProviderService.class.php
 *                            -------------------
 *   begin                : January 15, 2008
 *   copyright            : (C) 2008 Rouchon Loic
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc This class is a ExtensionPointProvider factory providing some services like
 * mass operations (on several modules at the same time) or identifications
 * methods to get all ExtensionPointProvider that provide a given extension point
 * @package extension-provider
 */
class ExtensionPointProviderService
{
	const EXTENSION_POINT_PROVIDER_SUFFIX = 'ExtensionPointProvider';

	/**
	 * @var RAMDataStore
	 */
	private $loaded_providers;
	private $available_providers_ids = array();

	/**
	 * @desc Builds a new ExtensionPointProvider factory
	 */
	public function __construct()
	{
		$this->loaded_providers = new RAMDataStore();
		$this->load_modules_providers();
		$this->register_provider('kernel');
		$this->register_provider('install');
	}

    /**
     * @desc Returns all extension point <code>$extension_point</code> registered implementations
     * @param string $extension_point the requested extension point
     * @param string[] $authorized_providers_ids the extension point providers that are allowed
     * to provide the extension point. If not specified, all providers are allowed
     * @return Object[string] the requested extension point implementations
     */
    public function get_extension_point($extension_point, $authorized_providers_ids = null)
    {
        $providers = $this->get_providers($extension_point, $authorized_providers_ids);
        $extensions_points = array();
        foreach ($providers as $provider)
        {
            $extensions_points[$provider->get_id()] = $provider->get_extension_point($extension_point);
        }
        return $extensions_points;
    }

    /**
     * @desc Returns all extension point <code>$extension_point</code> registered implementations
     * @param string $extension_point the requested extension point
     * @param string[] $authorized_providers_ids the extension point providers that are allowed
     * to provide the extension point. If not specified, all providers are allowed
     * @return Object[string] the requested extension point implementations
     */
    public function get_extension_point_with($extension_point, $authorized_providers_ids = null)
    {
        $providers = $this->get_providers($extension_point, $authorized_providers_ids);
        $extensions_points = array();
        foreach ($providers as $provider)
        {
            $extensions_points[$provider->get_id()] = $provider->get_extension_point($extension_point);
        }
        return $extensions_points;
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
	public function get_providers($extension_point, $authorized_providers_ids = null)
	{
		if ($authorized_providers_ids === null)
		{
			$authorized_providers_ids = $this->available_providers_ids;
		}
        $providers = array();
		foreach ($authorized_providers_ids as $extension_provider_id)
		{
			$provider = $this->get_provider($extension_provider_id);
			if ($provider->has_extension_point($extension_point))
			{
				$providers[$provider->get_id()] = $provider;
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
		if (!$this->loaded_providers->contains($provider_id))
		{
			$this->try_to_reload_modules_providers($provider_id);
			$classname = $this->compute_provider_classname($provider_id);
			$this->loaded_providers->store($provider_id, new $classname());
		}
		return $this->loaded_providers->get($provider_id);
	}

	private function try_to_reload_modules_providers($provider_id)
	{
		if (!in_array($provider_id, $this->available_providers_ids))
		{
			$this->load_modules_providers();
			if (!in_array($provider_id, $this->available_providers_ids))
			{
				throw new UnexistingExtensionPointProviderException($provider_id);
			}
		}
	}

	public function get_provider_extensions_points($provider)
	{
		$module_methods = get_class_methods($provider);
		$generics_methods = get_class_methods('ExtensionPointProvider');
		return array_values(array_diff($module_methods, $generics_methods));
	}

	private function load_modules_providers()
	{
		try
		{
			foreach (ModulesManager::get_installed_modules_map() as $provider_id => $module)
			{
				if ($module->is_activated())
				{
					$this->register_provider($provider_id);
				}
			}
		}
		catch (DBConnectionException $exception) { }
	}

	private function register_provider($provider_id)
	{
		if ($this->check_provider($provider_id) && !in_array($provider_id, $this->available_providers_ids))
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