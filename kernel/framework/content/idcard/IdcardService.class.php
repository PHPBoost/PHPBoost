<?php
/**
 * This class represents the rating system and its parameters
 * @package     Content
 * @subpackage  Idcard
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 01
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

		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);

		if(!$user->is_guest())
		{
			try {
				$user_extended_fields = PersistenceContext::get_querier()->select_single_row(DB_TABLE_MEMBER_EXTENDED_FIELDS, array('user_avatar', 'user_biography'), 'WHERE user_id=:user_id', array('user_id' => $user->get_id()));
			} catch (RowNotFoundException $e) {
				$user_extended_fields = array();
			}
			
			$avatar = !empty($user_extended_fields) && $user_extended_fields['user_avatar'] ? Url::to_rel($user_extended_fields['user_avatar']) : $user_accounts_config->get_default_avatar();
			$biography = !empty($user_extended_fields) && $user_extended_fields['user_biography'] ? $user_extended_fields['user_biography'] : $lang['extended-field.field.no-biography'];
		}
		else
		{
			$avatar = $user_accounts_config->get_default_avatar();
			$biography = $lang['extended-field.field.no-member'];
		}

		$tpl->put_all(array(
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_AUTHOR_IS_MEMBER' => !$user->is_guest(),
			'C_AVATAR'           => $avatar || $user_accounts_config->is_default_avatar_enabled(),

			'AUTHOR_NAME'        => $user->get_display_name(),
			'BIOGRAPHY'          => $biography,
			'USER_LEVEL_CLASS'   => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR'   => $user_group_color,

			'U_AUTHOR_PROFILE'   => UserUrlBuilder::profile($user->get_id())->rel(),
			'U_AVATAR'           => $avatar
		));

		return $tpl->render();
	}
}

?>
