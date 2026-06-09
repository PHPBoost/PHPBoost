<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 21
*/

class LobbyLastcoms
{
	/**
	 * Returns the last comments view.
	 * This is a kernel-level block (reads DB_TABLE_COMMENTS) with no LobbyProvider,
	 * configured directly in AdminLobbyConfigController.
	 */
	public static function get_lastcoms_view(): FileTemplate
	{
		$user_accounts_config = UserAccountsConfig::load();
		$modules_config       = ModulesConfig::load();
		$lobby_config         = LobbyConfig::load();
		$modules              = LobbyModulesList::load();
		$module_name          = LobbyConfig::MODULE_LASTCOMS;

		// FileTemplate handles theme overrides automatically
		$view = new FileTemplate('/lobby/templates/pagecontent/MessagesLobbyProvider.tpl');

		$view->add_lang(LangLoader::get_all_langs('lobby'));

		$module = isset($modules[$module_name]) ? $modules[$module_name] : new LobbyModule();

		$result = PersistenceContext::get_querier()->select('
                SELECT
                    c.id, c.user_id, c.pseudo, c.message, c.timestamp,
                    ct.module_id, ct.is_locked, ct.path,
                    m.*,
                    ext_field.user_avatar
                FROM ' . DB_TABLE_COMMENTS . ' AS c
                LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' AS ct ON ct.id_topic = c.id_topic
                LEFT JOIN ' . DB_TABLE_MEMBER . ' AS m ON c.user_id = m.user_id
                LEFT JOIN ' . DB_TABLE_MEMBER_EXTENDED_FIELDS . ' ext_field ON ext_field.user_id = m.user_id
                WHERE ct.is_locked = 0
                AND ct.module_id != :forbidden_module
                ORDER BY c.timestamp DESC
                LIMIT :limit
            ', [
				'forbidden_module' => 'user',
				'limit'            => $module->get_elements_number_displayed(),
			]
		);

		$view->put_all([
			'C_NO_ITEM'       => $result->get_rows_count() == 0,
			'C_PARENT'        => true,
			'C_AVATAR_IMG'    => $user_accounts_config->is_default_avatar_enabled(),
			'MODULE_NAME'     => $module_name,
			'MODULE_POSITION' => $lobby_config->get_module_position_by_id($module_name),
			'L_MODULE_TITLE'  => LangLoader::get_message('lobby.module.lastcoms', 'common', 'lobby'),
		]);

		while ($row = $result->fetch())
		{
			$contents     = @strip_tags(FormatingHelper::second_parse($row['message']), '<br><br/>');
			$char_limit   = $module->get_characters_number_displayed();
			$cut_contents = TextHelper::cut_string($contents, (int) $char_limit);

			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : $user_accounts_config->get_default_avatar();

			$author = new User();
			if (!empty($row['user_id']))
				$author->set_properties($row);
			else
				$author->init_visitor_user();

			$user_group_color = User::get_group_color($author->get_groups(), $author->get_level(), true);

			$view->assign_block_vars('items', [
				'C_AUTHOR_GROUP_COLOR' => !empty($user_group_color),
				'C_AUTHOR_EXISTS'      => $author->get_id() !== User::VISITOR_LEVEL,
				'C_READ_MORE'          => strlen($contents) > $char_limit,
				'AUTHOR_DISPLAY_NAME'  => $author->get_display_name(),
				'AUTHOR_LEVEL_CLASS'   => UserService::get_level_class($author->get_level()),
				'AUTHOR_GROUP_COLOR'   => $user_group_color,
				'DATE'                 => Date::to_format($row['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
				'SORT_DATE'            => $row['timestamp'],
				'TOPIC'                => $modules_config->get_module($row['module_id']) ? $modules_config->get_module($row['module_id'])->get_configuration()->get_name() : '',
				'CONTENT'              => $cut_contents,
				'U_AVATAR_IMG'         => $user_avatar,
				'U_AUTHOR_PROFILE'     => UserUrlBuilder::profile($author->get_id())->rel(),
				'U_TOPIC'              => Url::to_rel($row['path']),
				'U_ITEM'               => Url::to_rel($row['path'] . '#com' . $row['id']),
			]);
		}
		$result->dispose();

		return $view;
	}
}
?>
