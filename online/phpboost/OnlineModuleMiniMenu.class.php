<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 09
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class OnlineModuleMiniMenu extends ModuleMiniMenu
{
	private $number_robot = 0;
	private $number_visitor = 0;
	private $number_member = 0;
	private $number_moderator = 0;
	private $number_administrator = 0;
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
			$this->incremente_level($user);

			if ($online_config->are_robots_displayed() || ($user->get_level() != User::ROBOT_LEVEL))
			{
				if ($this->total_users <= $online_config->get_number_member_displayed())
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
			$this->number_visitor += $this->number_robot;

		$main_lang = LangLoader::get('main');
		$tpl->put_all(array(
			'C_DISPLAY_ROBOTS' => $online_config->are_robots_displayed(),
			'C_MORE_USERS' => $this->total_users > $online_config->get_number_member_displayed(),
			'L_ROBOT' => $this->number_robot > 1 ? $main_lang['robot_s'] : $main_lang['robot'],
			'L_VISITOR' => $this->number_visitor > 1 ? $main_lang['guest_s'] : $main_lang['guest'],
			'L_MEMBER' => $this->number_member > 1 ? $main_lang['member_s'] : $main_lang['member'],
			'L_MODO' => $this->number_moderator > 1 ? $main_lang['modo_s'] : $main_lang['modo'],
			'L_ADMIN' => $this->number_administrator > 1 ? $main_lang['admin_s'] : $main_lang['admin'],
			'L_USERS_ONLINE' => $this->total_users > 1 ? $lang['online_users'] : $lang['online_user'],
			'L_TOTAL' => $main_lang['total'],
			'TOTAL_USERS_CONNECTED' => $this->total_users,
			'TOTAL_ROBOT_CONNECTED' => $this->number_robot,
			'TOTAL_VISITOR_CONNECTED' => $this->number_visitor,
			'TOTAL_MEMBER_CONNECTED' => $this->number_member,
			'TOTAL_MODERATOR_CONNECTED' => $this->number_moderator,
			'TOTAL_ADMINISTRATOR_CONNECTED' => $this->number_administrator
		));

		return $tpl->render();
	}

	private function incremente_level(User $user)
	{
		$this->incremente_user();
		switch ($user->get_level())
		{
			case User::ROBOT_LEVEL:
				$this->incremente_robot();
			break;
			case User::VISITOR_LEVEL:
				$this->incremente_visitor();
			break;
			case User::MEMBER_LEVEL:
				$this->incremente_member();
			break;
			case User::MODERATOR_LEVEL:
				$this->incremente_moderator();
			break;
			case User::ADMIN_LEVEL:
				$this->incremente_administrator();
			break;
		}
	}

	private function incremente_user()
	{
		$this->total_users++;
	}

	private function incremente_robot()
	{
		$this->number_robot++;
	}

	private function incremente_visitor()
	{
		$this->number_visitor++;
	}

	private function incremente_member()
	{
		$this->number_member++;
	}

	private function incremente_moderator()
	{
		$this->number_moderator++;
	}

	private function incremente_administrator()
	{
		$this->number_administrator++;
	}
}
?>
