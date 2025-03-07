<?php
/**
 * This Class allow you to call methods on a ExtensionPointProvider extended class
 * that you're not sure of the method's availality. It also provides a set of
 * generic methods that you could use to integrate your module with others, or
 * allow your module to share services.
 * @package     PHPBoost
 * @subpackage  Extension-provider
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 18
 * @since       PHPBoost 2.0 - 2008 01 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
	 * ExtensionPointProvider constructor
	 * @param string $extension_provider_id the provider id. It's the name of the folder in witch
	 * the extension provider is
	 */
	public function __construct($extension_provider_id = '')
	{
		$this->id = $extension_provider_id;
		$this->extensions_points = $this->get_provider_extensions_points($this);
	}

	/**
	 * @return string Return the id of the ExtensionPoint
	 */
	public function get_id()
	{
		return $this->id;
	}

	/**
	 * Check the existance of the extension point and if exists call it.
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
	 * Check the availability of the extension_point (hook)
	 * @param string $extension_point the name of the method you want to check the availability
	 * @return bool true if the extension point exists, false otherwise
	 */
	public function has_extension_point($extension_point)
	{
		return in_array($extension_point, $this->extensions_points);
	}

	/**
	 * Check the availability of the extensions points (hook)
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

	private function get_provider_extensions_points($provider)
	{
		$module_methods = get_class_methods($provider);
		$generics_methods = get_class_methods('ExtensionPointProvider');
		return array_values(array_diff($module_methods, $generics_methods));
	}

	protected function get_class($extension_point_label, $extension_point_full_name = '')
	{
		$extension_point_full_name = !empty($extension_point_full_name) ? $extension_point_full_name : $extension_point_label;
		$class = TextHelper::ucfirst($this->get_id()) . $extension_point_label;
		$default_class = 'Default' . $extension_point_label;
		
		if (ClassLoader::is_class_registered_and_valid($class) && (in_array($extension_point_full_name, class_implements($class)) || is_subclass_of($class, $extension_point_full_name)))
			return new $class($this->get_id());
		else if (ClassLoader::is_class_registered_and_valid($default_class) && (in_array($extension_point_full_name, class_implements($default_class)) || is_subclass_of($default_class, $extension_point_full_name)))
			return new $default_class($this->get_id());
		else
			return false;
	}
}
?>
