<?php
/*##################################################
 *                      	 AdministratorAlertCache.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
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