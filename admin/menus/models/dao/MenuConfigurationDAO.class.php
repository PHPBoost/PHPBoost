<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 25
*/

class MenuConfigurationDAO extends SQLDAO
{
	private static $instance;

	/**
	 * @return MenuConfigurationDAO
	 */
	public static function instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct()
	{
		$classname = 'MenuConfiguration';
		$tablename = PREFIX . 'menu_configuration';
		$primary_key = new MappingModelField('id');

		$fields = array(
		new MappingModelField('name'),
		new MappingModelField('match_regex'),
		new MappingModelField('priority')
		);

		$model = new MappingModel($classname, $tablename, $primary_key, $fields);

		parent::__construct($model, PersistenceContext::get_querier());
	}
}
?>
