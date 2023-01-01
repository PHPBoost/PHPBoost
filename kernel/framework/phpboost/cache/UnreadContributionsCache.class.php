<?php
/**
 * This cache is used to know if there are unread contributions.
 * If there are, it's able to tell us how much there are for the administrator, and for the other
 * types of users, it's only able to tell if there are unread contributions.
 * @package     PHPBoost
 * @subpackage  Cache
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2009 11 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UnreadContributionsCache implements CacheData
{
	private $admin = 0;
	private $moderators = false;
	private $members = false;
	private $groups = array();
	private $users = array();

	/**
	 * {@inheritdoc}
	 */
	public function synchronize()
	{
		$result = ContributionService::compute_number_contrib_for_each_profile();

		$this->set_values($result);
	}

	/**
	 * Tells whether there are unread contributions
	 * @return bool true if there are, false otherwise
	 */
	public function are_there_unread_contributions()
	{
		return $this->get_admin_unread_contributions_number() > 0;
	}

	/**
	 * Tells how much contributions there are for administrators.
	 * @return int The number of contributions.
	 */
	public function get_admin_unread_contributions_number()
	{
		return $this->admin;
	}

	/**
	 * Sets the number of unread contributions for the administrator.
	 * This method should be private but is public for unit tests.
	 * @param int $number The number
	 */
	public function set_admin_unread_contributions_number($number)
	{
		$this->admin = $number;
	}

	/**
	 * Tells whether moderators have unread contributions.
	 * @return bool true if they have, false otherwise
	 */
	public function have_moderators_unread_contributions()
	{
		return $this->moderators;
	}

	/**
	 * Tells whether a user has unread contributions (it only deals with user-specific contributions
	 * and not with rank or group contributions).
	 * @param int $user_id The id if the user
	 * @return bool true if the user has, false if it doesn't
	 */
	public function has_user_unread_contributions($user_id)
	{
		return in_array($user_id, $this->get_users_with_unread_contributions());
	}

	/**
	 * Tells whether a group has unread contributions (it only deals with user-specific contributions
	 * and not with rank or group contributions).
	 * @param int $group_id The id of the group
	 * @return bool true if the group has, false otherwise
	 */
	public function has_group_unread_contributions($group_id)
	{
		return in_array($group_id, $this->get_groups_with_unread_contributions());
	}

	/**
	 * Sets whether there are unread contributions for moderators.
	 * This method should be private but is public for unit tests.
	 * @param bool $have true if there are, false otherwise
	 */
	public function set_moderators_have_unread_contributions($have)
	{
		$this->moderators = $have;
	}

	/**
	 * Tells whether members have unread contributions.
	 * @return bool true if they have, false otherwise
	 */
	public function have_members_unread_contributions()
	{
		return $this->members;
	}

	/**
	 * Sets whether there are unread contributions for members.
	 * This method should be private but is public for unit tests.
	 * @param bool $have true if there are, false otherwise
	 */
	public function set_members_have_unread_contributions($have)
	{
		$this->members = $have;
	}

	/**
	 * Returns the list of the groups which have unread contributions.
	 * @return int[] The list of their ids
	 */
	public function get_groups_with_unread_contributions()
	{
		return $this->groups;
	}

	/**
	 * Ass a group to the list of groups which have unread contributions.
	 * This method should be private but is public for unit tests.
	 * @param int $id The id of the group
	 */
	public function add_group_with_unread_contributions($id)
	{
		$this->add_unique_item_in_list($this->groups, $id);
	}

	/**
	 * Returns the list of the users who have unread contributions.
	 * @return int[] The list of their ids.
	 */
	public function get_users_with_unread_contributions()
	{
		return $this->users;
	}

	/**
	 * Ass a group to the list of users who have unread contributions.
	 * This method should be private but is public for unit tests.
	 * @param int $id The id of the user
	 */
	public function add_user_with_unread_contributions($id)
	{
		$this->add_unique_item_in_list($this->users, $id);
	}

	/**
	 * Sets the values from the method of ContributionService which returns the number of
	 * unread contributions for which profile.
	 * @param array $numbers The array returned by
	 * ContributionService::compute_number_contrib_for_each_profile
	 * This method should be private but is public for unit tests.
	 */
	public function set_values(array $numbers)
	{
		$this->set_admin_unread_contributions_number($numbers['r2']);
		$this->set_moderators_have_unread_contributions((bool)$numbers['r1']);
		$this->set_members_have_unread_contributions((bool)$numbers['r0']);

		unset($numbers['r2']);
		unset($numbers['r1']);
		unset($numbers['r0']);

		foreach ($numbers as $profile => $number)
		{
			if ($number > 0)
			{
				if ($profile[0] == 'g')
				{
					$this->add_group_with_unread_contributions((int) TextHelper::substr($profile, 1));
				}
				else if ($profile[0] == 'm')
				{
					$this->add_user_with_unread_contributions((int) TextHelper::substr($profile, 1));
				}
			}
		}
	}

	/**
	 * Loads and returns the unread contribution cached data.
	 * @return UnreadContributionsCache The cached data
	 */
	public static function load()
	{
		return CacheManager::load(__CLASS__, 'kernel', 'unread-contributions');
	}

	/**
	 * Invalidates the current modules css files cached data.
	 */
	public static function invalidate()
	{
		CacheManager::invalidate('kernel', 'unread-contributions');
	}

	private function add_unique_item_in_list(&$list, $item)
	{
		if (!in_array($item, $list))
		{
			$list[] = $item;
		}
	}
}
?>
