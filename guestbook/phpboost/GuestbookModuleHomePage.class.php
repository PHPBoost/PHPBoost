<?php
/*##################################################
 *                           GuestbookModuleHomePage.class.php
 *                            -------------------
 *   begin                : November 12, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class GuestbookModuleHomePage implements ModuleHomePage
{
	private $lang;
	private $main_lang;
	private $view;
	private $current_user;
	
	public static function get_view()
	{
		$object = new self();
		return $object->build_view();
	}
	
	private function build_view()
	{
		$this->init();
		
		$request = AppContext::get_request();
		$id_get = $request->get_int('id', 0);
		$current_page = $request->get_int('page', 1);
		
		//Configuration parameters
		$guestbook_config = GuestbookConfig::load();
		$items_per_page = $guestbook_config->get_items_per_page();
		$authorizations = $guestbook_config->get_authorizations();
		
		if ($this->current_user->check_auth($authorizations, GuestbookConfig::GUESTBOOK_WRITE_AUTH_BIT))
		{
			$is_edition_mode = !empty($id_get);
			
			$guestbook_fieldset = $this->main_lang['add_msg'];
			$guestbook_login = $this->main_lang['guest'];
			$guestbook_contents = '';
			$has_edit_auth = false;
			$user_id = -1;
			if ($is_edition_mode)
			{
				$msg = GuestbookService::get_message($id_get);
				$user_id = (int)$msg['user_id'];
				$guestbook_fieldset = $this->main_lang['update_msg'];
				$guestbook_login = $msg['login'];
				$guestbook_contents = FormatingHelper::unparse($msg['contents']);
				
				$has_edit_auth = $this->current_user->check_auth($authorizations, GuestbookConfig::GUESTBOOK_MODO_AUTH_BIT)	|| ($msg['user_id'] === $this->current_user->get_id() && $this->current_user->get_id() !== -1);
			}
			
			$is_guest = $is_edition_mode ? $user_id == -1 : !$this->current_user->check_level(User::MEMBER_LEVEL);

			$formatter = AppContext::get_content_formatting_service()->get_default_factory();
			$formatter->set_forbidden_tags($guestbook_config->get_forbidden_tags());
			
			//Post form
			$form = new HTMLForm('guestbookForm');
			$fieldset = new FormFieldsetHTML('add_msg', $guestbook_fieldset);
			if ($is_guest)
			{
				$fieldset->add_field(new FormFieldTextEditor('pseudo', $this->main_lang['pseudo'], $guestbook_login, array(
					'class' => 'text', 'required' => $this->main_lang['require_pseudo'], 'maxlength' => 25)
				));
			}
			$fieldset->add_field(new FormFieldRichTextEditor('contents',  $this->main_lang['message'], $guestbook_contents, array(
				'formatter' => $formatter, 'rows' => 10, 'cols' => 47, 'required' => $this->main_lang['require_text'])
			));
			
			if ($is_guest && !$is_edition_mode && $guestbook_config->is_captcha_enabled()) //Code de vérification, anti-bots.
			{
				$captcha = new PHPBoostCaptcha();
				$captcha->set_difficulty($guestbook_config->get_captcha_difficulty_level());
				$fieldset->add_field(new FormFieldCaptcha('captcha', $captcha));
			}
				
			if ($is_edition_mode)
			{
				$submit_button = new FormButtonSubmit($this->main_lang['update'], $this->main_lang['update']);
			}
			else
			{
				$submit_button = new FormButtonDefaultSubmit();
			}
			$form->add_button($submit_button);
			$form->add_button(new FormButtonReset());
				
			$form->add_fieldset($fieldset);
			
			//Post form has been submitted
			if ($submit_button->has_been_submited())
			{
				//user is readonly
				if ($this->current_user->get_attribute('user_readonly') > time())
				{
					$controller = PHPBoostErrors::user_in_read_only();
					DispatchManager::redirect($controller);
				}
				//anti-flood mod
				$check_time = 0;
				if ($this->current_user->get_id() !== -1 && ContentManagementConfig::load()->is_anti_flood_enabled()) 
				{
					$check_time = PersistenceContext::get_querier()->select_single_row(PREFIX . 'guestbook', array('MAX(timestamp) as timestamp'), 'WHERE user_id=:id', array(
						'id' => $this->current_user->get_id()
					));
					
					if ($check_time >= (time() - ContentManagementConfig::load()->get_anti_flood_duration())) //On calcule la fin du delai.
					{
						$controller = PHPBoostErrors::flood();
						DispatchManager::redirect($controller);
					}
				}
				
				if ($form->validate())
				{
					$guestbook_login = '';	
					if ($form->has_field('pseudo')) {
						$guestbook_login = $form->get_value('pseudo');
					}
					$guestbook_login = $this->current_user->check_level(User::MEMBER_LEVEL) ? $this->current_user->get_pseudo() : $guestbook_login;	
					$guestbook_login = empty($guestbook_login) ? $this->main_lang['guest'] : $guestbook_login;
					
					$guestbook_contents = $form->get_value('contents');
					
					if (!TextHelper::check_nbr_links($guestbook_contents, $guestbook_config->get_maximum_links_message())) 
					{
						$controller = PHPBoostErrors::link_flood($guestbook_config->get_maximum_links_message());
						DispatchManager::redirect($controller);
					}
					if (!TextHelper::check_nbr_links($guestbook_login, 0)) 
					{
						$controller = PHPBoostErrors::link_login_flood();
						DispatchManager::redirect($controller);
					}
					
					if ($is_edition_mode && $has_edit_auth) 
					{
						$columns = array(
							'contents' => $guestbook_contents
						);
						
						if ($user_id == -1) {
							$columns['login'] = $guestbook_login;
						}
						
						GuestbookService::update($columns, " WHERE id = :id", array('id' => $id_get));

						GuestbookMessagesCache::invalidate();

						AppContext::get_response()->redirect(GuestbookUrlBuilder::home($current_page . '#m' . $id_get)->absolute());
					}
					else
					{
						$columns = array(
							'contents' => $guestbook_contents,
							'login' => $guestbook_login,
							'user_id' => $this->current_user->get_id(),
							'timestamp' => time()
						);
						
						$last_msg_id = GuestbookService::insert($columns);
						
						GuestbookMessagesCache::invalidate();
			
						AppContext::get_response()->redirect(GuestbookUrlBuilder::home($current_page . '#m' . $last_msg_id)->absolute());
					}
				}
			}
			
			$this->view->put('GUESTBOOK_FORM', $form->display());
		}
		else
		{
			$tpl->put_all(array(
				'C_ERROR_WRITING_AUTH' => true,
				'L_ERROR_WRITING_AUTH' => $this->main_lang['e_unauthorized']
			));
		}
		
		//Messages number
		$nbr_messages = GuestbookService::count();
		
		$pagination = new GuestbookListPagination($current_page, $nbr_messages);
		$pagination->set_url(GuestbookUrlBuilder::home('/%d')->absolute());
		
		$limit_page = $current_page > 0 ? $current_page : 1;
		$limit_page = (($limit_page - 1) * $items_per_page);
		
		$this->view->put_all(array(
			'C_PAGINATION' => ($nbr_messages / $items_per_page) > 1 ? true : false,
			'PAGINATION' => $pagination->display()->render(),
			'L_DELETE_MSG' => $this->main_lang['alert_delete_msg'],
			'L_GUESTBOOK' => $this->lang['guestbook.module_title']
		));

		//ranks table creation
		$array_ranks = array(-1 => $this->main_lang['guest'], 0 => $this->main_lang['member'], 1 => $this->main_lang['modo'], 2 => $this->main_lang['admin']);

		$ranks_cache = RanksCache::load()->get_ranks();
		$j = 0;
		$result = PersistenceContext::get_querier()->select("SELECT g.id, g.login, g.timestamp, m.user_id, m.login as mlogin, m.level, m.user_mail, m.user_show_mail, m.timestamp AS registered, ext_field.user_avatar, m.user_msg, ext_field.user_location, ext_field.user_website, ext_field.user_sex, ext_field.user_msn, ext_field.user_yahoo, ext_field.user_sign, m.user_warning, m.user_ban, m.user_groups, s.user_id AS connect, g.contents
		FROM " . PREFIX . "guestbook g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		LEFT JOIN " . DB_TABLE_SESSIONS . " s ON s.user_id = g.user_id AND s.session_time > '" . (time() - SessionsConfig::load()->get_active_session_duration()) . "'
		LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = g.user_id
		GROUP BY g.id
		ORDER BY g.timestamp DESC
		LIMIT ". $items_per_page ." OFFSET :start_limit",
			array(
				'start_limit' => $limit_page
			), SelectQueryResult::FETCH_ASSOC
		);
		
		while ($row = $result->fetch())
		{
			$edit = '';
			$del = '';

			$is_guest = empty($row['user_id']);
			$is_modo = $this->current_user->check_level(User::MODERATOR_LEVEL);
			$warning = '';
			$readonly = '';
			if ($is_modo && !$is_guest) //Moderation.
			{
				$warning = '&nbsp;<a href="'. PATH_TO_ROOT.'/user/moderation_panel' . url('.php?action=warning&amp;id=' . $row['user_id']) . '" title="' . $this->main_lang['warning_management'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/admin/important.png" alt="' . $this->main_lang['warning_management'] .  '" class="valign_middle" /></a>';
				$readonly = '<a href="'. PATH_TO_ROOT.'/user/moderation_panel' . url('.php?action=punish&amp;id=' . $row['user_id']) . '" title="' . $this->main_lang['punishment_management'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/readonly.png" alt="' . $this->main_lang['punishment_management'] .  '" class="valign_middle" /></a>';
			}

			//Edit/delete.
			if ($this->current_user->check_auth($authorizations, GuestbookConfig::GUESTBOOK_MODO_AUTH_BIT) || ($row['user_id'] === $this->current_user->get_id() && $this->current_user->get_id() !== -1))
			{
				$edit = '&nbsp;&nbsp;<a href="'. GuestbookUrlBuilder::home($current_page . '/' . $row['id'])->absolute() . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/edit.png" alt="' . $this->main_lang['edit'] . '" title="' . $this->main_lang['edit'] . '" class="valign_middle" /></a>';
				$del = '&nbsp;&nbsp;<a href="'. GuestbookUrlBuilder::delete($row['id'], $current_page)->absolute() . '" onclick="javascript:return Confirm();"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/delete.png" alt="' . $this->main_lang['delete'] . '" title="' . $this->main_lang['delete'] . '" class="valign_middle" /></a>';
			}

			//Pseudo
			if (!$is_guest)
			{
				$guestbook_login = '<a class="msg_link_pseudo" href="'. UserUrlBuilder::profile($row['user_id'])->absolute() .'" title="' . $row['mlogin'] . '"><span style="font-weight: bold;">' . TextHelper::wordwrap_html($row['mlogin'], 13) . '</span></a>';
			}
			else
			{
				$guestbook_login = '<span style="font-style:italic;">' . (!empty($row['login']) ? TextHelper::wordwrap_html($row['login'], 13) : $this->main_lang['guest']) . '</span>';
			}

			//User rank
			$user_rank = ($row['level'] === '0') ? $this->main_lang['member'] : $this->main_lang['guest'];
			$user_group = $user_rank;
			$user_rank_icon = '';
			if ($row['level'] === '2') //special rank (admins).
			{
				$user_rank = $ranks_cache[-2]['name'];
				$user_group = $user_rank;
				$user_rank_icon = $ranks_cache[-2]['icon'];
			}
			elseif ($row['level'] === '1') //special rank (modos).
			{
				$user_rank = $ranks_cache[-1]['name'];
				$user_group = $user_rank;
				$user_rank_icon = $ranks_cache[-1]['icon'];
			}
			else
			{
				foreach ($ranks_cache as $msg => $ranks_info)
				{
					if ($msg >= 0 && $msg <= $row['user_msg'])
					{
						$user_rank = $ranks_info['name'];
						$user_rank_icon = $ranks_info['icon'];
					}
				}
			}

			//rank image
			$user_assoc_img = !empty($user_rank_icon) ? '<img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/ranks/' . $user_rank_icon . '" alt="" />' : '';

			//user's groups
			if (!empty($row['user_groups']))
			{
				$user_groups = '';
				$array_user_groups = explode('|', $row['user_groups']);
				foreach (GroupsService::get_groups() as $idgroup => $array_group_info)
				{
					if (is_numeric(array_search($idgroup, $array_user_groups)))
					{
						$user_groups .= !empty($array_group_info['img']) ? '<img src="'. PATH_TO_ROOT.'/images/group/' . $array_group_info['img'] . '" alt="' . $array_group_info['name'] . '" title="' . $array_group_info['name'] . '"/><br />' : $LANG['group'] . ': ' . $array_group_info['name'] . '<br />';
					}
				}
			}
			else
			{
				$user_groups = $this->main_lang['group'] . ': ' . $user_group;
			}

			//Is the user online ?
			$user_online = !empty($row['connect']) ? 'online' : 'offline';

			$user_accounts_config = UserAccountsConfig::load();

			//Avatar
			if (empty($row['user_avatar']))
			{
				$user_avatar = ($user_accounts_config->is_default_avatar_enabled()) ? '<img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name() . '" alt="" />' : '';
			}
			else
			{
				$user_avatar = '<img src="' . Url::to_rel($row['user_avatar']) . '" alt="" />';
			}

			//Sex and status (connected/disconnected)
			$user_sex = '';
			if ($row['user_sex'] == 1)
			{
				$user_sex = $this->main_lang['sex'] . ': <img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/man.png" alt="" /><br />';
			}
			elseif ($row['user_sex'] == 2)
			{
				$user_sex = $this->main_lang['sex'] . ': <img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/woman.png" alt="" /><br />';
			}

			//Messages number
			$user_msg = ($row['user_msg'] > 1) ? $this->main_lang['message_s'] . ': ' . $row['user_msg'] : $this->main_lang['message'] . ': ' . $row['user_msg'];

			//Location.
			if (!empty($row['user_location']))
			{
				$user_local = $this->main_lang['place'] . ': ' . $row['user_location'];
				$user_local = $user_local > 15 ? TextHelper::htmlentities(substr(TextHelper::html_entity_decode($user_local), 0, 15)) . '...<br />' : $user_local . '<br />';
			}
			else 
			{
				$user_local = '';
			}

			$this->view->assign_block_vars('guestbook', array(
				'ID' => $row['id'],
				'CONTENTS' => FormatingHelper::second_parse($row['contents']),
				'DATE' => $this->main_lang['on'] . ': ' . gmdate_format('date_format', $row['timestamp']),
				'CLASS_COLOR' => ($j % 2 == 0) ? '' : 2,
				'USER_ONLINE' => '<img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . $user_online . '.png" alt="" class="valign_middle" />',
				'USER_PSEUDO' => $guestbook_login,
				'USER_RANK' => (($row['user_warning'] < '100' || (time() - $row['user_ban']) < 0) ? $user_rank : $this->main_lang['banned']),
				'USER_IMG_ASSOC' => $user_assoc_img,
				'USER_AVATAR' => $user_avatar,
				'USER_GROUP' => $user_groups,
				'USER_DATE' => !$is_guest ? $this->main_lang['registered_on'] . ': ' . gmdate_format('date_format_short', $row['registered']) : '',
				'USER_SEX' => $user_sex,
				'USER_MSG' => !$is_guest ? $user_msg : '',
				'USER_LOCAL' => $user_local,
				'USER_MAIL' => (!empty($row['user_mail']) && ($row['user_show_mail'] == '1')) ? '<a href="mailto:' . $row['user_mail'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/email.png" alt="' . $row['user_mail']  . '" title="' . $row['user_mail']  . '" /></a>' : '',
				'USER_MSN' => !empty($row['user_msn']) ? '<a href="mailto:' . $row['user_msn'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/msn.png" alt="' . $row['user_msn']  . '" title="' . $row['user_msn']  . '" /></a>' : '',
				'USER_YAHOO' => !empty($row['user_yahoo']) ? '<a href="mailto:' . $row['user_yahoo'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/yahoo.png" alt="' . $row['user_yahoo']  . '" title="' . $row['user_yahoo']  . '" /></a>' : '',
				'USER_SIGN' => !empty($row['user_sign']) ? '____________________<br />' . FormatingHelper::second_parse($row['user_sign']) : '',
				'USER_WEB' => !empty($row['user_website']) ? '<a href="' . $row['user_website'] . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/user_web.png" alt="' . $row['user_website']  . '" title="' . $row['user_website']  . '" /></a>' : '',
				'WARNING' => (!empty($row['user_warning']) ? $row['user_warning'] : '0') . '%' . $warning,
				'PUNISHMENT' => $readonly,
				'DEL' => $del,
				'EDIT' => $edit,
				'U_USER_PM' => !$is_guest ? '<a href="'. PATH_TO_ROOT.'/user/pm' . url('.php?pm=' . $row['user_id'], '-' . $row['user_id'] . '.php') . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/pm.png" alt="" /></a>' : '',
				'U_ANCHOR' => GuestbookUrlBuilder::home('/' . $current_page . '#m' . $row['id'])->absolute()
			));
			$j++;
		}
		
		return $this->view;
	}
	
	private function init()
	{
		$this->current_user = AppContext::get_current_user();
		
		$this->lang = LangLoader::get('guestbook_common', 'guestbook');
		$this->main_lang = LangLoader::get('main');
		$this->view = new FileTemplate('guestbook/GuestbookController.tpl');
		$this->view->add_lang($this->lang);
	}
}
?>