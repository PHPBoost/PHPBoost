<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 13
 * @since       PHPBoost 5.3 - 2020 01 10
*/

class DefaultModuleConfig extends AbstractConfigData
{
	/**
	 * @var string the module identifier
	 */
	protected $module_id;

	const AUTHORIZATIONS = 'authorizations';

	public function __construct($module_id = '')
	{
		$this->module_id = $module_id;
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
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration of the module.
	 */
	public static function load()
	{
		return ConfigManager::load(get_called_class(), $this->module_id, 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save($this->module_id, self::load(), 'config');
	}
}
?>
