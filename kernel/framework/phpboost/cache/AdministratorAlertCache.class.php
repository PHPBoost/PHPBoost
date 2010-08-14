<?php
/*##################################################
 *                      	 AdministratorAlertCache.class.php
 *                            -------------------
 *   begin                : August 0, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
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
		$db_connection = PersistenceContext::get_sql();

		$this->unread_administrator_alert = $db_connection->query("SELECT count(*) FROM ".DB_TABLE_EVENTS  . " WHERE current_status = '" . AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD . "' AND contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__);
		$this->all_administrator_alert =  $db_connection->query("SELECT count(*) FROM " . DB_TABLE_EVENTS . " WHERE contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "'", __LINE__, __FILE__);
		
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