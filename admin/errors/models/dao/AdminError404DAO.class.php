<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 12 13
*/

class AdminError404DAO extends SQLDAO
{
	private static $instance;

	/**
	 * @return AdminError404DAO
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
		$classname = 'AdminError404';
		$tablename = PREFIX . 'errors_404';
		$primary_key = new MappingModelField('id');

		$fields = array(new MappingModelField('requested_url'), new MappingModelField('from_url'), new MappingModelField('times'));

		$model = new MappingModel($classname, $tablename, $primary_key, $fields);

		parent::__construct($model, PersistenceContext::get_querier());
	}
}
?>
