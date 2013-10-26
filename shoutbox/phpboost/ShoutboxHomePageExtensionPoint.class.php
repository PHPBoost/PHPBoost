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
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
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
		
		global $LANG, $Cache, $User, $auth_write, $Session, $Bread_crumb;
		
		require_once(PATH_TO_ROOT . '/shoutbox/shoutbox_begin.php');
		
		$tpl = new FileTemplate('shoutbox/shoutbox.tpl');
		
		$shoutbox_config = ShoutboxConfig::load();
		
		//Pseudo du membre connecté.
		if ($User->get_attribute('user_id') !== -1)
			$tpl->put_all(array(
				'SHOUTBOX_PSEUDO' => $User->get_attribute('login'),
				'C_HIDDEN_SHOUT' => true
			));
		else
			$tpl->put_all(array(
				'SHOUTBOX_PSEUDO' => $LANG['guest'],
				'C_VISIBLE_SHOUT' => true
			));
				
		$formatter = AppContext::get_content_formatting_service()->create_factory();
		$formatter->set_forbidden_tags($shoutbox_config->get_forbidden_formatting_tags());
		
		$form = new HTMLForm('shoutboxform', 'shoutbox.php?add=1&amp;token=' . $Session->get_token());
		$fieldset = new FormFieldsetHTML('add_msg', $LANG['add_msg']);
		if (!$User->check_level(User::MEMBER_LEVEL)) //Visiteur
		{
			$fieldset->add_field(new FormFieldTextEditor('shoutbox_pseudo', $LANG['pseudo'], $LANG['guest'], array(
				'class' => 'text', 'maxlength' => 25, 'required' => true)
			));
		}
		$fieldset->add_field(new FormFieldRichTextEditor('shoutbox_contents', $LANG['message'], '', array(
			'formatter' => $formatter, 
			'rows' => 10, 'cols' => 47, 'required' => true)
		));
		
		$form->add_fieldset($fieldset);
		$form->add_button(new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		$tpl->put('SHOUTBOX_FORM', $form->display());
		
		//On crée une pagination si le nombre de messages est trop important.
		$nbr_messages = $this->sql_querier->count_table(PREFIX . 'shoutbox', __LINE__, __FILE__);
		$pagination = new ModulePagination(AppContext::get_request()->get_getint('p', 1), $nbr_messages, 1);
		$pagination->set_url(new Url('shoutbox' . url('.php?p=%d')));
		
		$tpl->put_all(array(
			'TITLE' => $LANG['title_shoutbox'],
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
		));
		
		//Gestion des rangs.	
		$ranks_cache = ForumRanksCache::load()->get_ranks();
		$result = $this->sql_querier->query_while("SELECT s.id, s.login, s.user_id, s.timestamp, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, ext_field.user_avatar, m.user_msg, ext_field.user_location, ext_field.user_website, ext_field.user_sex, ext_field.user_msn, ext_field.user_yahoo, ext_field.user_sign, m.user_warning, m.user_ban, m.user_groups, se.user_id AS connect, s.contents
		FROM " . PREFIX . "shoutbox s
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = s.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " se ON se.user_id = s.user_id AND se.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = s.user_id
		GROUP BY s.id
		ORDER BY s.timestamp DESC 
		LIMIT " . $pagination->get_number_items_per_page() . " OFFSET " . $pagination->get_display_from(), __LINE__, __FILE__);	
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$row['user_id'] = (int)$row['user_id'];

			$user_accounts_config = UserAccountsConfig::load();
			
			//Avatar			
			if (empty($row['user_avatar'])) 
				$user_avatar = $user_accounts_config->is_default_avatar_enabled() == '1' ? Url::to_rel('/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '';
			else
				$user_avatar = Url::to_rel($row['user_avatar']);

			$group_color = User::get_group_color($row['user_groups'], $row['level']);
			
			$tpl->assign_block_vars('messages', array(
				'C_MODERATOR' => ShoutboxAuthorizationsService::check_authorizations()->moderation() || ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1),
				'C_VISITOR' => $row['user_id'] === -1,
				'C_GROUP_COLOR' => !empty($group_color),
				'U_DIT' => PATH_TO_ROOT . '/shoutbox/shoutbox' . url('.php?edit=1&amp;id=' . $row['id']),
				'U_DELETE' => PATH_TO_ROOT . '/shoutbox/shoutbox' . url('.php?del=1&amp;id=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_AVATAR' => $user_avatar,
				'ID' => $row['id'],
				'DATE' => gmdate_format('date_format', $row['timestamp']),
				'MESSAGE' => FormatingHelper::second_parse($row['contents']),
				'USER_ID' => $row['user_id'],
				'PSEUDO' => (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $LANG['guest']),
				'LEVEL_CLASS' => UserService::get_level_class($row['level']),
				'GROUP_COLOR' => $group_color,
				'L_LEVEL' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1') : $LANG['banned']),
			));
		}
		$this->sql_querier->query_close($result);

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