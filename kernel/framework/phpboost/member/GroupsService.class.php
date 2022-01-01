<?php
/**
 * This class provides methods to manage user in groups.
 * @package     PHPBoost
 * @subpackage  Member
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 09 02
 * @since       PHPBoost 1.6 - 2007 05 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

define('ADMIN_NOAUTH_DEFAULT', false); //Admin non obligatoirement sélectionné.
define('GROUP_DEFAULT_IDSELECT', '');
define('GROUP_DISABLE_SELECT', 'disabled="disabled" ');
define('GROUP_DISABLED_ADVANCED_AUTH', true); //Désactivation des autorisations avancées.

class GroupsService
{
	private static $db_querier;

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	/**
	 * Adds a member in a group
	 * @param int $user_id User id
	 * @param int $idgroup Group id
	 * @return boolean True if the member has been succefully added.
	 */
	public static function add_member($user_id, $idgroup)
	{
		//On insère le groupe au champ membre.
		$user_groups = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'user_groups', 'WHERE user_id = :user_id', array('user_id' => $user_id));
		$user_groups = explode('|', $user_groups);
		if (!in_array($idgroup, $user_groups)) //Le membre n'appartient pas déjà au groupe.
		{
			array_push($user_groups, $idgroup);
			self::$db_querier->update(DB_TABLE_MEMBER, array('user_groups' => (trim(implode('|', $user_groups), '|'))), 'WHERE user_id = :user_id', array('user_id' => $user_id));
			$return = true;
		}
		else
		{
			$return = false;
		}

		//On insère le membre dans le groupe.
		$group_members = self::$db_querier->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id = :id', array('id' => $idgroup));
		$group_members = explode('|', $group_members);
		if (!in_array($user_id, $group_members)) //Le membre n'appartient pas déjà au groupe.
		{
			array_push($group_members, $user_id);
			self::$db_querier->update(DB_TABLE_GROUP, array('members' => (trim(implode('|', $group_members), '|'))), 'WHERE id = :id', array('id' => $idgroup));
			$return = true;
		}
		else
		{
			$return = false;
		}

		return $return;
	}

	/**
	 * Edits the user groups, compute difference between previous and new groups.
	 * @param int $user_id The user id
	 * @param array $array_user_groups The new array of groups.
	 */
	public static function edit_member($user_id, $array_user_groups)
	{
		//Récupération des groupes précédent du membre.
		$user_groups_old = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'user_groups', 'WHERE user_id = :user_id', array('user_id' => $user_id));
		$array_user_groups_old = explode('|', $user_groups_old);

		//Insertion du différentiel positif des groupes précédent du membre et ceux choisis dans la table des groupes.
		$array_diff_pos = array_diff($array_user_groups, $array_user_groups_old);
		foreach ($array_diff_pos as $key => $idgroup)
		{
			if (!empty($idgroup))
			{
				self::add_member($user_id, $idgroup);
			}
		}

		//Insertion du différentiel négatif des groupes précédent du membre et ceux choisis dans la table des groupes.
		$array_diff_neg = array_diff($array_user_groups_old, $array_user_groups);
		foreach ($array_diff_neg as $key => $idgroup)
		{
			if (!empty($idgroup))
			{
				self::remove_member($user_id, $idgroup);
			}
		}
	}

	/**
	 * Returns the list of the names of the groups, array: id => name
	 * @return  The array groups
	 */
	public static function get_groups_names()
	{
		static $groups_names = null;
		if ($groups_names === null)
		{
			$groups_names = array();
			$group_config_data = GroupsCache::load();
			foreach ($group_config_data->get_groups() as $idgroup => $array_group_info)
			{
				$groups_names[$idgroup] = $array_group_info['name'];
			}
		}
		return $groups_names;
	}

	/**
	 * Returns the list of the groups
	 * @return array The array groups
	 */
	public static function get_groups()
	{
		static $groups = null;
		if ($groups === null)
		{
			$config = GroupsCache::load();
			$groups = $config->get_groups();
		}
		return $groups;
	}

	/**
	 * Removes a member in a group.
	 * @param int $user_id The user id
	 * @param int $idgroup The id group.
	 */
	public static function remove_member($user_id, $idgroup)
	{
		//Suppression dans la table des membres.
		$user_groups = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'user_groups', 'WHERE user_id = :user_id', array('user_id' => $user_id));

		$user_groups = explode('|', $user_groups);

		$key = array_search($idgroup, $user_groups);
		if($key !== false)
			unset($user_groups[$key]);

		self::$db_querier->update(DB_TABLE_MEMBER, array('user_groups' => implode('|', $user_groups)), 'WHERE user_id = :user_id', array('user_id' => $user_id));

		//Suppression dans la table des groupes.
		$members_group = '';
		try {
			$members_group = self::$db_querier->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id = :id', array('id' => $idgroup));
		} catch (RowNotFoundException $e) {}

		$members_group = explode('|', $members_group);

		$key = array_search($user_id, $members_group);
		if($key !== false)
			unset($members_group[$key]);

		self::$db_querier->update(DB_TABLE_GROUP, array('members' => implode('|', $members_group)), 'WHERE id = :id', array('id' => $idgroup));
	}
}
?>
