<?php
/**
 * This static class allows you to handler easily the administrator alerts which can be made in PHPBoost.
 * @package     PHPBoost
 * @subpackage  Event
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 11 18
 * @since       PHPBoost 2.0 - 2008 08 29
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

 //Flag which distinguishes an alert and a contribution in the database
define('ADMINISTRATOR_ALERT_TYPE', 1);

class AdministratorAlertService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	/**
	 * Builds an alert knowing its id.
	 * @param int $alert_id Id of the alert.
	 * @return AdministratorAlert The wanted alert. If it's not found, it returns null.
	 */
	public static function find_by_id($alert_id)
	{
		//Selection query
		$result = self::$db_querier->select("SELECT id, entitled, fixing_url, current_status, id_in_module, identifier, type, priority, creation_date, description
		FROM " . DB_TABLE_EVENTS  . "
		WHERE id = :alert_id
		ORDER BY creation_date DESC", array(
			'alert_id' => $alert_id
		));

		$properties = $result->fetch();

		$result->dispose();

		if ((int)$properties['id'] > 0)
		{
			//Creation of the object we are going to return
			$alert = new AdministratorAlert();
			$alert->build($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['current_status'], new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE), $properties['id_in_module'], $properties['identifier'], $properties['type'], $properties['priority']);
			return $alert;
		}
		else
		{
			return null;
		}
	}

	/**
	 * Builds a list of alerts matching the required criteria(s). You can specify many criterias. When you use several of them, it's a AND condition.
	 * It will only return the alert which match all the criterias.
	 * @param int $id_in_module Id in the module.
	 * @param string $type Alert type.
	 * @param string $identifier Alert identifier.
	 * @return AdministratorAlert[] The list of the matching alerts.
	 */
	public static function find_by_criteria($id_in_module = null, $type = null, $identifier = null)
	{
		$criterias = array();

		if ($id_in_module != null)
		{
			$criterias[] = "id_in_module = '" . intval($id_in_module) . "'";
		}

		if ($type != null)
		{
			$criterias[] = "type = '" . TextHelper::strprotect($type) . "'";
		}

		if ($identifier != null)
		{
			$criterias[] = "identifier = '" . TextHelper::strprotect($identifier). "'";
		}

		//Restrictive criteria
		if (!empty($criterias))
		{
			$array_result = array();
			$result = self::$db_querier->select("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
			FROM " . DB_TABLE_EVENTS  . "
			WHERE contribution_type = '" . ADMINISTRATOR_ALERT_TYPE . "' AND " . implode(' AND ', $criterias));

			while ($row = $result->fetch())
			{
				$alert = new AdministratorAlert();
				$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date($row['creation_date'], Timezone::SERVER_TIMEZONE), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
				$array_result[] = $alert;
			}
			$result->dispose();

			return $array_result;
		}
		//There is no criteria, we return all alerts
		else
		{
			return AdministratorAlertCache::load()->get_all_alerts_number();
		}
	}

	/**
	 * Builds a list of unread alerts.
	 * @return AdministratorAlert[] The list of the matching alerts.
	 */
	public static function get_unread_alerts()
	{
		$array_result = array();
		
		$result = self::$db_querier->select("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
			FROM " . DB_TABLE_EVENTS  . "
			WHERE contribution_type = :contribution_type AND current_status = :current_status", array(
				'contribution_type' => ADMINISTRATOR_ALERT_TYPE,
				'current_status' => AdministratorAlert::ADMIN_ALERT_STATUS_UNREAD
		));

		while ($row = $result->fetch())
		{
			$alert = new AdministratorAlert();
			$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date($row['creation_date'], Timezone::SERVER_TIMEZONE), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
			$array_result[] = $alert;
		}
		$result->dispose();

		return $array_result;
	}

	/**
	 * Finds an alert knowing its identifier and maybe its type.
	 * @param string $identifier The identifier of the alerts you look for.
	 * @param string $type The type of the alert you look for.
	 * @return AdministratorAlert[] The list of the matching alerts.
	 */
	public static function find_by_identifier($identifier, $type = '')
	{
		$result = self::$db_querier->select("SELECT id, entitled, fixing_url, current_status, creation_date, id_in_module, priority, identifier, type, description
			FROM " . DB_TABLE_EVENTS  . "
			WHERE identifier = :identifier" . (!empty($type) ? " AND type = :type" : '') . " ORDER BY creation_date DESC
			LIMIT 1;", array(
				'identifier' => $identifier,
				'type' => $type
			));

		if ($row = $result->fetch())
		{
			$alert = new AdministratorAlert();
			$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date($row['creation_date'], Timezone::SERVER_TIMEZONE), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);

			return $alert;
		}
		$result->dispose();

		return null;
	}

	/**
	 * Lists all the alerts of the site. You can order them. You can also choose how much alerts you want.
	 * @param string $criteria The criteria according to which you want to order. It can be id, entitled, fixing_url,
	 * current_status, creation_date, identifier, id_in_module, type, priority, description.
	 * @param string $order asc or desc.
	 * @param int $begin You want all the alert from the ($begin+1)(th).
	 * @param int $number The number of alerts you want.
	 * @return AdministratorAlerts[] The list of the alerts.
	 */
	public static function get_all_alerts($criteria = 'creation_date', $order = 'desc', $begin = 0, $number = 20)
	{
		$array_result = array();

		//On liste les alertes
		$result = self::$db_querier->select("SELECT id, entitled, fixing_url, current_status, creation_date, identifier, id_in_module, type, priority, description
		FROM " . DB_TABLE_EVENTS  . "
		WHERE contribution_type = " . ADMINISTRATOR_ALERT_TYPE . "
		ORDER BY " . $criteria . " " . TextHelper::strtoupper($order) . "
		LIMIT :pagination_number OFFSET :display_from", array(
			'pagination_number' => $number,
			'display_from' => $begin
		));
		while ($row = $result->fetch())
		{
			$alert = new AdministratorAlert();
			$alert->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['current_status'], new Date($row['creation_date'], Timezone::SERVER_TIMEZONE), $row['id_in_module'], $row['identifier'], $row['type'], $row['priority']);
			$array_result[] = $alert;
		}
		$result->dispose();

		return $array_result;
	}

	/**
	 * Create or updates an alert in the database. It creates it whether it doesn't exist or updates it if it already exists.
	 * @param AdministratorAlert $alert The alert to create or update.
	 */
	public static function save_alert($alert)
	{
		// If it exists already in the data base
		if ($alert->get_id() > 0)
		{
			self::$db_querier->update(DB_TABLE_EVENTS, array('entitled' => $alert->get_entitled(), 'description' => $alert->get_alert_properties(), 'fixing_url' => $alert->get_fixing_url(), 'module' => '', 'current_status' => $alert->get_status(), 'creation_date' => $alert->get_creation_date()->get_timestamp(), 'id_in_module' => $alert->get_id_in_module(), 'identifier' => $alert->get_identifier(), 'type' => $alert->get_type(), 'priority' => $alert->get_priority()), 'WHERE id = :id', array('id' => $alert->get_id()));

			//Regeneration of the member cache file
			if ($alert->get_must_regenerate_cache())
			{
				AdministratorAlertCache::invalidate();
				$alert->set_must_regenerate_cache(false);
			}
		}
		else //We create it
		{
			$creation_date = new Date();
			$result = self::$db_querier->insert(DB_TABLE_EVENTS, array('entitled' => $alert->get_entitled(), 'description' => $alert->get_alert_properties(), 'fixing_url' => $alert->get_fixing_url(), 'module' => '', 'current_status' => $alert->get_status(), 'creation_date' => $creation_date->get_timestamp(), 'id_in_module' => $alert->get_id_in_module(), 'identifier' => $alert->get_identifier(), 'type' => $alert->get_type(), 'priority' => $alert->get_priority()));
			$alert->set_id($result->get_last_inserted_id());

			//Cache regeneration
			AdministratorAlertCache::invalidate();
		}
	}

	/**
	 * Deletes an alert from the database.
	 * @param AdministratorAlert $alert The alert to delete.
	 */
	public static function delete_alert($alert)
	{
		// If it exists in the data base
		if ($alert->get_id() > 0)
		{
			self::$db_querier->delete(DB_TABLE_EVENTS, 'WHERE id = :id', array('id' => $alert->get_id()));
			$alert->set_id(0);
			AdministratorAlertCache::invalidate();
		}
		//Else it's not present in the database, we have nothing to delete
	}

	/**
	 * Returns the number of unread alerts.
	 * @return int The number of unread alerts.
	 */
	public static function get_number_unread_alerts()
	{
		return AdministratorAlertCache::load()->get_unread_alerts_number();
	}

	/**
	 * Returns the number of alerts.
	 * @return int The number of alerts.
	 */
	public static function get_number_alerts()
	{
		return AdministratorAlertCache::load()->get_all_alerts_number();
	}
}
?>
