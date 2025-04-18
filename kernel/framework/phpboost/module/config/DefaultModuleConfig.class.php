<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\config
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 01 09
 * @since       PHPBoost 6.0 - 2020 01 10
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultModuleConfig extends AbstractConfigData
{
	/**
	 * @var string the module identifier
	 */
	protected static $module_id;

	const ITEMS_PER_PAGE = 'items_per_page';
	const AUTHORIZATIONS = 'authorizations';

	public static function __static()
	{
		$module_id = ClassLoader::get_module_id_from_class_name(get_called_class());
		self::$module_id = ($module_id && !in_array($module_id, ['admin', 'kernel', 'user']) ? $module_id : Environment::get_running_module_name());
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array_merge(
			[
				self::ITEMS_PER_PAGE => 15,
				self::AUTHORIZATIONS => ['r-1' => 1, 'r0' => 5, 'r1' => 21]
			],
			$this->get_kernel_additional_default_values(),
			$this->get_additional_default_values()
		);
	}

	/**
	 * Returns the default values of additional parameters defined in other config classes of the kernel.
	 */
	public function get_kernel_additional_default_values()
	{
		return [];
	}

	/**
	 * Returns the default values of additional parameters of the module.
	 */
	public function get_additional_default_values()
	{
		return [];
	}

	/**
	 * Returns the configuration of the module.
	 */
	public static function load($module_id = '')
	{
		!empty($module_id) ? self::$module_id = $module_id : '';
		return ConfigManager::load(get_called_class(), (!empty($module_id) ? $module_id : self::$module_id), 'config');
	}

	/**
	 * Saves the configuration in the database. As it become persistent.
	 */
	public static function save($module_id = '')
	{
		ConfigManager::save((!empty($module_id) ? $module_id : self::$module_id), self::load((!empty($module_id) ? $module_id : self::$module_id)), 'config');
	}

	/**
	 * Getters/Setters virtualization.
	 *
	 * It allows to call get or set methods without declaring them in ModuleConfig.class.php
	 * You only need to declare constants and a return array of default values in a get_default_value() method.
	 *
	 * @param string $method method name ( ex : get_constant_value or set_constant_value($arguments) )
	 * @param array|string $arguments parameter to set.
	 */
	public function __call($method, $arguments)
	{
		if ( !$this->current_class_has_method($method) && !empty($this->get_class_constants()) )
		{
			//Do a get
			if (preg_match('#^get_(.+)#', $method, $matches))
			{
				$constant_value = $matches[1];
				if ( in_array($constant_value, $this->get_class_constants()) )
				{
					return $this->get_property($constant_value);
				}
			}
			//Do a set
			if (preg_match('#^set_(.+)#', $method, $matches))
			{
				$constant_value = $matches[1];
				if ( in_array($constant_value, $this->get_class_constants()) && $this->current_class_has_method('get_default_value'))
				{
					if ($arguments[0] === null)
					{
						throw new Exception('Method ' . $matches[0] . '() must contain one argument.');
					}

					$argument_type = gettype($arguments[0]);
					$default_argument_type = gettype($this->get_default_values()[$constant_value]);
					if ($argument_type != $default_argument_type)
						settype($arguments[0], $default_argument_type);

					return $this->set_property($constant_value, $arguments[0]);
				}
			}
		}
	}

	/**
	 * @return array All constants array in current class.
	 */
	public function get_class_constants()
	{
		$reflect = new ReflectionClass(get_class($this));
		return $reflect->getConstants();
	}

	/**
	 * @return bool For checking if a method exists in current class.
	 */
	public function current_class_has_method($method)
	{
		return method_exists(get_class($this), $method);
	}
}
?>
