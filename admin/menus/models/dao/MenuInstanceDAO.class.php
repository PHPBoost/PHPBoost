<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 25
*/

class MenuInstanceDAO extends SQLDAO
{
	private static $instance;

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
		$classname = 'MenuInstance';
		$tablename = PREFIX . 'menu_instance';
		$primary_key = new MappingModelField('id');

		$fields = array(
		new MappingModelField('menu_id'),
		new MappingModelField('menu_configuration_id'),
		new MappingModelField('block'),
		new MappingModelField('position'),
		);

		$model = new MappingModel($classname, $tablename, $primary_key, $fields);

		parent::__construct($model, PersistenceContext::get_querier());
	}
}
?>
