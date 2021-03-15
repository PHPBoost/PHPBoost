<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class OnlineModuleMiniMenu extends ModuleMiniMenu
{
	private $robot_number = 0;
	private $visitor_number = 0;
	private $member_number = 0;
	private $moderator_number = 0;
	private $administrator_number = 0;
	private $total_users = 0;

	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-online';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('online', 'common', 'online');
	}

	public function is_displayed()
	{
		return !Url::is_current_url('/online/') && OnlineAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		$tpl = new FileTemplate('online/OnlineModuleMiniMenu.tpl');

		$lang = LangLoader::get('common', 'online');
		$tpl->add_lang($lang);

		MenuService::assign_positions_conditions($tpl, $this->get_block());

		$online_config = OnlineConfig::load();
		$condition = 'WHERE s.timestamp > :time ORDER BY '. $online_config->get_display_order_request();
		$parameters = array(
			'time' => (time() - SessionsConfig::load()->get_active_session_duration())
		);

		$users = OnlineService::get_online_users($condition, $parameters);
		foreach ($users as $user)
		{
			$this->increment_level($user);

			if ($online_config->are_robots_displayed() || ($user->get_level() != User::ROBOT_LEVEL))
			{
				if ($this->total_users <= $online_config->get_members_number_displayed())
				{
					$group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

					if ($user->get_level() != User::VISITOR_LEVEL)
					{
						$tpl->assign_block_vars('users', array(
							'C_ROBOT' => $user->get_level() == User::ROBOT_LEVEL,
							'U_PROFILE' => UserUrlBuilder::profile($user->get_id())->rel(),
							'PSEUDO' => $user->get_display_name(),
							'LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
							'C_GROUP_COLOR' => !empty($group_color),
							'GROUP_COLOR' => $group_color,
						));
					}
				}
			}
		}

		if (!$online_config->are_robots_displayed())
			$this->visitor_number += $this->robot_number;

		$main_lang = LangLoader::get('main');
		$tpl->put_all(array(
			'C_DISPLAY_ROBOTS' => $online_config->are_robots_displayed(),
			'C_MORE_USERS' => $this->total_users > $online_config->get_members_number_displayed(),
			'L_ROBOT' => $this->robot_number > 1 ? $main_lang['robot_s'] : $main_lang['robot'],
			'L_VISITOR' => $this->visitor_number > 1 ? $main_lang['guest_s'] : $main_lang['guest'],
			'L_MEMBER' => $this->member_number > 1 ? $main_lang['member_s'] : $main_lang['member'],
			'L_MODO' => $this->moderator_number > 1 ? $main_lang['modo_s'] : $main_lang['modo'],
			'L_ADMIN' => $this->administrator_number > 1 ? $main_lang['admin_s'] : $main_lang['admin'],
			'L_USERS_ONLINE' => $this->total_users > 1 ? $lang['online_users'] : $lang['online_user'],
			'L_TOTAL' => $main_lang['total'],
			'TOTAL_USERS_CONNECTED' => $this->total_users,
			'TOTAL_ROBOT_CONNECTED' => $this->robot_number,
			'TOTAL_VISITOR_CONNECTED' => $this->visitor_number,
			'TOTAL_MEMBER_CONNECTED' => $this->member_number,
			'TOTAL_MODERATOR_CONNECTED' => $this->moderator_number,
			'TOTAL_ADMINISTRATOR_CONNECTED' => $this->administrator_number
		));

		return $tpl->render();
	}

	private function increment_level(User $user)
	{
		$this->increment_user();
		switch ($user->get_level())
		{
			case User::ROBOT_LEVEL:
				$this->increment_robot();
			break;
			case User::VISITOR_LEVEL:
				$this->increment_visitor();
			break;
			case User::MEMBER_LEVEL:
				$this->increment_member();
			break;
			case User::MODERATOR_LEVEL:
				$this->increment_moderator();
			break;
			case User::ADMIN_LEVEL:
				$this->increment_administrator();
			break;
		}
	}

	private function increment_user()
	{
		$this->total_users++;
	}

	private function increment_robot()
	{
		$this->robot_number++;
	}

	private function increment_visitor()
	{
		$this->visitor_number++;
	}

	private function increment_member()
	{
		$this->member_number++;
	}

	private function increment_moderator()
	{
		$this->moderator_number++;
	}

	private function increment_administrator()
	{
		$this->administrator_number++;
	}
}
?>
