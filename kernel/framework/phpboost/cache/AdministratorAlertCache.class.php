<?php
/**
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 08 10
*/

class AdministratorAlertCache implements CacheData
{
	private $all_administrator_alert;

	private $unread_administrator_alert;
	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$querier = PersistenceContext::get_querier();

		$parameters = array(
			'current_status' => AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD,
			'contribution_type' => ADMINISTRATOR_ALERT_TYPE
		);
		$this->unread_administrator_alert = $querier->count(DB_TABLE_EVENTS, 'WHERE current_status = :current_status AND contribution_type = :contribution_type', $parameters);

		$parameters = array('contribution_type' => ADMINISTRATOR_ALERT_TYPE);
		$this->all_administrator_alert = $querier->count(DB_TABLE_EVENTS, 'WHERE contribution_type = :contribution_type', $parameters);

	}

	public function get_all_alerts_number()
	{
		return $this->all_administrator_alert;
	}

	public function get_unread_alerts_number()
	{
		return $this->unread_administrator_alert;
	}

	/**
	 * Loads and returns the administrator alert cached data.
	 * @return AdministratorAlertCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'administrator-alert');
	}

	/**
	 * Invalidates the current administrator alert cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'administrator-alert');
	}
}
?>
