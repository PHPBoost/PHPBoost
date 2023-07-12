<?php
/**
 * This class is a ExtensionPointProvider factory providing some services like
 * mass operations (on several modules at the same time) or identifications
 * methods to get all ExtensionPointProvider that provide a given extension point
 * @package     PHPBoost
 * @subpackage  Extension-provider
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 19
 * @since       PHPBoost 2.0 - 2008 01 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
	 * Builds a new ExtensionPointProvider factory
	 */
	public function __construct()
	{
		$this->loaded_providers = new RAMDataStore();
		$this->load_modules_providers();
		$this->register_provider('kernel');
		$this->register_provider('user');
		$this->register_provider('install');
		$this->register_provider('update');
	}

    /**
     * Returns all extension point <code>$extension_point</code> registered implementations
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
	 * Returns the ExtensionPointProvider list.
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
			if ($provider->has_extension_point($extension_point) && $provider->get_extension_point($extension_point, $extension_provider_id) !== false)
			{
				$providers[$provider->get_id()] = $provider;
			}
		}
		return $providers;
	}

	/**
	 * Returns the ExtensionPointProvider of the provider which id is $provider_id.
	 * @param string $provider_id The provider id.
	 * @return ExtensionPointProvider The corresponding ExtensionPointProvider.
	 * @throws UnexistingExtensionPointProviderException
	 */
	public function get_provider($provider_id)
	{
		if (!$this->loaded_providers->contains($provider_id))
		{
			if (!$this->provider_exists($provider_id))
			{
				$this->try_to_reload_modules_providers($provider_id);
			}
			$classname = $this->compute_provider_classname($provider_id);
			$this->loaded_providers->store($provider_id, new $classname($provider_id));
		}
		return $this->loaded_providers->get($provider_id);
	}

	/**
	 * Returns true if the provider exists and has all the requested extensions points.
	 * @param string $provider_id the provider id
	 * @param mixed $extension_points the extension point list that the provider must provides.
	 *   <ul>
	 *     <li>If <code>null</code>, nothing will be checked.</li>
	 *     <li>If it's of type string, then it will check that the provider provides this extension point.</li>
	 *     <li>If it's an array, it will check that all given extensions points are provided by the provider.</li>
	 *   </ul>
	 * @return bool true if the provider exists
	 */
	public function provider_exists($provider_id, $extensions_points = null)
	{
		if (!in_array($provider_id, $this->available_providers_ids))
		{
			return false;
		}
		if (!empty($extensions_points))
		{
			$provider = $this->get_provider($provider_id);
			if (is_string($extensions_points))
			{
				return $provider->has_extension_point($extensions_points);
			}
			else
			{
				return $provider->has_extensions_points($extensions_points);
			}
		}
		return true;
	}

	private function load_modules_providers()
	{
		try
		{
			// In case that phpboost is not installed and that a command line is invoked,
			// PHPBoostNotInstalledException, DBConnectionException, SQLQuerierException and IOException could be thrown.
			// In that case, it only means that modules extension points providers won't be loaded
			foreach (ModulesManager::get_installed_modules_map() as $provider_id => $module)
			{
				if ($module->is_activated())
				{
					$this->register_provider($provider_id);
				}
			}
		}
		catch (PHPBoostNotInstalledException $exception) { }
		catch (DBConnectionException $exception) { }
		catch (SQLQuerierException $exception) { }
		catch (IOException $exception) { }
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
		$module_extension_point_class_name = TextHelper::ucfirst($provider_id) . self::EXTENSION_POINT_PROVIDER_SUFFIX;
		
		if (ModulesManager::is_module_activated($provider_id))
		{
			if (ClassLoader::is_class_registered_and_valid($module_extension_point_class_name))
				return $module_extension_point_class_name;
			else
			{
				$items_feature = false;
				$module_config_file = new File(PATH_TO_ROOT . '/' . $provider_id . '/config.ini');
				if ($module_config_file->exists())
				{
					$config = parse_ini_file($module_config_file->get_path());
					$features = !empty($config['features']) ? explode(',', preg_replace('/\s/', '', $config['features'])) : array();
					$items_feature = in_array('items', array_map('trim', $features), true) || in_array('rich_items', array_map('trim', $features), true);
				}
				
				$module_item_class_name = TextHelper::ucfirst($provider_id) . 'Item';
				$item_class = ClassLoader::is_class_registered_and_valid($module_item_class_name) && is_subclass_of($module_item_class_name, 'Item') ? $module_item_class_name : '';
				if ($item_class || $items_feature)
					return 'ItemsModule' . self::EXTENSION_POINT_PROVIDER_SUFFIX;
				else
					return 'Module' . self::EXTENSION_POINT_PROVIDER_SUFFIX;
			}
		}
		return $module_extension_point_class_name;
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
}
?>
