<?php
/**
 * This class represents the rating system and its parameters
 * @package     Content
 * @subpackage  Idcard
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 04 28
 * @since       PHPBoost 5.2 - 2019 04 23
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class IdcardService
{
	/**
	 * Select some item author datas
	 * @param User $user
	 */

	public static function display_idcard(User $user)
	{
		$lang = LangLoader::get('user-common');
		$tpl = new FileTemplate('framework/content/idcard/idcard.tpl');
		$tpl->add_lang($lang);
		$user_accounts_config = UserAccountsConfig::load();

		$user_id = $user->get_id();
		$author_name = $user->get_display_name();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		if($user_id !== User::VISITOR_LEVEL)
		{
			$avatar = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'user_avatar', 'WHERE user_id=:user_id', array('user_id' => $user_id));
			$avatar = $avatar ? Url::to_rel($avatar) : $user_accounts_config->get_default_avatar();

			$biography = PersistenceContext::get_querier()->get_column_value(DB_TABLE_MEMBER_EXTENDED_FIELDS, 'user_biography', 'WHERE user_id=:user_id', array('user_id' => $user_id));;
			if(empty($biography))
				$biography = $lang['extended-field.field.no-biography'];

		} else {
			$avatar = $user_accounts_config->get_default_avatar();
			$biography = $lang['extended-field.field.no-member'];
		}

		$tpl->put_all(array(
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_AUTHOR_IS_MEMBER' => $user_id !== User::VISITOR_LEVEL,
			'C_AVATAR'           => $avatar || $user_accounts_config->default_avatar_enabled(),

			'AUTHOR_NAME'        => $author_name,
			'BIOGRAPHY'          => $biography,
			'USER_LEVEL_CLASS'   => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR'   => $user_group_color,

			'U_AUTHOR_PROFILE'   => UserUrlBuilder::profile($user_id)->rel(),
			'U_AVATAR'           => $avatar
		));

		return $tpl->render();
	}
}

?>
