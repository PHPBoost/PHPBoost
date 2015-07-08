<?php
/*##################################################
 *                          GroupsService.class.php
 *                            -------------------
 *   begin                : May 18, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
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

define('ADMIN_NOAUTH_DEFAULT', false); //Admin non obligatoirement sélectionné.
define('GROUP_DEFAULT_IDSELECT', '');
define('GROUP_DISABLE_SELECT', 'disabled="disabled" ');
define('GROUP_DISABLED_ADVANCED_AUTH', true); //Désactivation des autorisations avancées.

/**
 * @author Régis VIARRE <crowkait@phpboost.com>
 * @desc This class provides methods to manage user in groups.
 * @package {@package}
 */
class GroupsService
{
	private static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	/**
	 * @desc Adds a member in a group
	 * @param int $user_id User id
	 * @param int $idgroup Group id
	 * @return boolean True if the member has been succefully added.
	 */
	public static function add_member($user_id, $idgroup)
	{
		//On insère le groupe au champ membre.
		$user_groups = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'groups', 'WHERE user_id = :user_id', array('user_id' => $user_id));
		$user_groups = explode('|', $user_groups);
		if (!in_array($idgroup, $user_groups)) //Le membre n'appartient pas déjà au groupe.
		{
			array_push($user_groups, $idgroup);
			self::$db_querier->update(DB_TABLE_MEMBER, array('groups' => (trim(implode('|', $user_groups), '|'))), 'WHERE user_id = :user_id', array('user_id' => $user_id));
		}
		else
		{
			return false;
		}

		//On insère le membre dans le groupe.
		$group_members = self::$db_querier->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id = :id', array('id' => $idgroup));
		$group_members = explode('|', $group_members);
		if (!in_array($user_id, $group_members)) //Le membre n'appartient pas déjà au groupe.
		{
			array_push($group_members, $user_id);
			self::$db_querier->update(DB_TABLE_GROUP, array('members' => (trim(implode('|', $group_members), '|'))), 'WHERE id = :id', array('id' => $idgroup));
		}
		else
		{
			return false;
		}
		
		return true;
	}

	/**
	 * @desc Edits the user groups, compute difference between previous and new groups.
	 * @param int $user_id The user id
	 * @param array $array_user_groups The new array of groups.
	 */
	public static function edit_member($user_id, $array_user_groups)
	{
		//Récupération des groupes précédent du membre.
		$user_groups_old = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'groups', 'WHERE user_id = :user_id', array('user_id' => $user_id));
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
	 * @desc Returns the list of the names of the groups, array: id => name
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
	 * @desc Returns the list of the groups
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
	 * @desc Removes a member in a group.
	 * @param int $user_id The user id
	 * @param int $idgroup The id group.
	 */
	public static function remove_member($user_id, $idgroup)
	{
		//Suppression dans la table des membres.
		$user_groups = self::$db_querier->get_column_value(DB_TABLE_MEMBER, 'groups', 'WHERE user_id = :user_id', array('user_id' => $user_id));
		
		$user_groups = explode('|', $user_groups);
		
		unset($user_groups[array_search($idgroup, $user_groups)]);
		
		self::$db_querier->update(DB_TABLE_MEMBER, array('groups' => implode('|', $user_groups)), 'WHERE user_id = :user_id', array('user_id' => $user_id));
		
		//Suppression dans la table des groupes.
		$members_group = '';
		try {
			$members_group = self::$db_querier->get_column_value(DB_TABLE_GROUP, 'members', 'WHERE id = :id', array('id' => $idgroup));
		} catch (RowNotFoundException $e) {}
		
		$members_group = explode('|', $members_group);
		
		unset($members_group[array_search($user_id, $members_group)]);
		
		self::$db_querier->update(DB_TABLE_GROUP, array('members' => implode('|', $members_group)), 'WHERE id = :id', array('id' => $idgroup));
	}
}
?>