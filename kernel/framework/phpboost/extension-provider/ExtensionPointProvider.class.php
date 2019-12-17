<?php
/**
 * This Class allow you to call methods on a ExtensionPointProvider extended class
 * that you're not sure of the method's availality. It also provides a set of
 * generic methods that you could use to integrate your module with others, or
 * allow your module to share services.
 * @package     PHPBoost
 * @subpackage  Extension-provider
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 2.0 - 2008 01 15
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
	 * @return string Return the id of the EtensionPoint
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
}
?>
