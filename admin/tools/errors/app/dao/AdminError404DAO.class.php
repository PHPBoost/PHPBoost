<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 14
 * @since       PHPBoost 3.0 - 2009 12 13
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminError404DAO extends SQLDAO
{
	private static ?AdminError404DAO $instance = null;

	public static function instance(): AdminError404DAO
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct()
	{
		$classname = 'AdminError404';
		$tablename = PREFIX . 'errors_404';
		$primary_key = new MappingModelField('id');

		$fields = [new MappingModelField('requested_url'), new MappingModelField('from_url'), new MappingModelField('times')];

		$model = new MappingModel($classname, $tablename, $primary_key, $fields);

		parent::__construct($model, PersistenceContext::get_querier());
	}
}
?>
