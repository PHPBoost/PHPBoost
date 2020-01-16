<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 16
 * @since       PHPBoost 5.3 - 2020 01 10
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
		self::$module_id = Environment::get_running_module_name();
	}

	public function __construct($module_id = '')
	{
		if ($module_id)
			self::$module_id = $module_id;
		else
			self::__static();
	}

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page($value)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 15,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration of the module.
	 */
	public static function load()
	{
		return ConfigManager::load(get_called_class(), self::$module_id, 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save(self::$module_id, self::load(), 'config');
	}
}
?>
