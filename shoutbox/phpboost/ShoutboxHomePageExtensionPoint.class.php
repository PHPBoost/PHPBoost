<?php
/*##################################################
 *                     ShoutboxHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 08, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class ShoutboxHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $LANG;
		
		load_module_lang('shoutbox');
		
		return $LANG['title_shoutbox'];
	}
	
	private function get_view()
	{
		$this->check_authorizations();
		
		global $LANG, $Cache,  $auth_write, $Bread_crumb;
		
		require_once(PATH_TO_ROOT . '/shoutbox/shoutbox_begin.php');
		
		$tpl = new FileTemplate('shoutbox/shoutbox.tpl');
		
		$shoutbox_config = ShoutboxConfig::load();
		
		//Pseudo du membre connecté.
		if (AppContext::get_current_user()->get_id() !== -1)
			$tpl->put_all(array(
				'SHOUTBOX_PSEUDO' => AppContext::get_current_user()->get_attribute('login'),
				'C_HIDDEN_SHOUT' => true
			));
		else
			$tpl->put_all(array(
				'SHOUTBOX_PSEUDO' => $LANG['guest'],
				'C_VISIBLE_SHOUT' => true
			));
				
		$formatter = AppContext::get_content_formatting_service()->create_factory();
		$formatter->set_forbidden_tags($shoutbox_config->get_forbidden_formatting_tags());
		
		$form = new HTMLForm('shoutboxform', PATH_TO_ROOT . '/shoutbox/shoutbox.php?add=1&amp;token=' . AppContext::get_session()->get_token());
		$fieldset = new FormFieldsetHTML('add_msg', $LANG['add_msg']);
		if (!AppContext::get_current_user()->check_level(User::MEMBER_LEVEL)) //Visiteur
		{
			$fieldset->add_field(new FormFieldTextEditor('shoutbox_pseudo', $LANG['pseudo'], $LANG['guest'], array(
				'maxlength' => 25, 'required' => true)
			));
		}
		$fieldset->add_field(new FormFieldRichTextEditor('shoutbox_contents', $LANG['message'], '', array(
			'formatter' => $formatter, 
			'rows' => 10, 'cols' => 47, 'required' => true)
		));
		
		$form->add_fieldset($fieldset);
		$form->add_button(new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		//On crée une pagination si le nombre de messages est trop important.
		$nbr_messages = PersistenceContext::get_querier()->count(PREFIX . 'shoutbox');
		$pagination = new ModulePagination(AppContext::get_request()->get_getint('p', 1), $nbr_messages, 10);
		$pagination->set_url(new Url('shoutbox' . url('.php?p=%d')));
		
		$tpl->put_all(array(
			'C_POST_MESSAGE' => ShoutboxAuthorizationsService::check_authorizations()->write(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'SHOUTBOX_FORM' => $form->display(),
			'TITLE' => $LANG['title_shoutbox'],
			'E_UNAUTHORIZED' => $LANG['e_unauthorized']
		));
		
		//Gestion des rangs.
		$ranks_cache = ForumRanksCache::load()->get_ranks();
		$result = PersistenceContext::get_querier()->select("SELECT s.id, s.login, s.user_id, s.timestamp, m.display_name as mlogin, m.level, m.email, m.show_email, m.registration_date AS registered, ext_field.user_avatar, m.warning_percentage, m.delay_banned, m.groups, se.user_id AS connect, s.contents
		FROM " . PREFIX . "shoutbox s
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " se ON se.user_id = s.user_id AND se.timestamp > :timestamp
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = s.user_id
		GROUP BY s.id
		ORDER BY s.timestamp DESC 
		LIMIT :number_items_per_page OFFSET :display_from",
			array(
				'timestamp' => (time() - SessionsConfig::load()->get_active_session_duration()),
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			)
		);
		while ($row = $result->fetch())
		{
			$row['user_id'] = (int)$row['user_id'];

			$user_accounts_config = UserAccountsConfig::load();
			
			//Avatar
			$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');

			$group_color = User::get_group_color($row['groups'], $row['level']);
			
			$tpl->assign_block_vars('messages', array(
				'C_MODERATOR' => ShoutboxAuthorizationsService::check_authorizations()->moderation() || ($row['user_id'] === AppContext::get_current_user()->get_id() && AppContext::get_current_user()->get_id() !== -1),
				'C_VISITOR' => $row['user_id'] === -1,
				'C_GROUP_COLOR' => !empty($group_color),
				'C_AVATAR' => $row['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
				'ID' => $row['id'],
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'MESSAGE' => FormatingHelper::second_parse($row['contents']),
				'USER_ID' => $row['user_id'],
				'PSEUDO' => (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']),
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				'L_LEVEL' => (($row['warning_percentage'] < '100' || (time() - $row['delay_banned']) < 0) ? UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1') : $LANG['banned']),
				'U_EDIT' => PATH_TO_ROOT . '/shoutbox/shoutbox' . url('.php?edit=1&amp;id=' . $row['id']),
				'U_DELETE' => PATH_TO_ROOT . '/shoutbox/shoutbox' . url('.php?del=1&amp;id=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_AVATAR' => $user_avatar
			));
		}
		$result->dispose();

		return $tpl;
	}
	
	private function check_authorizations()
	{
		if (!ShoutboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>