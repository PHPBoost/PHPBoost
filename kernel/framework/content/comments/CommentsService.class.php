<?php
/*##################################################
 *                              CommentsService.class.php
 *                            -------------------
 *   begin                : March 31, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 
 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class allows you to use a comments system
 * @package {@package}
 */
class CommentsService
{	
	private static $user;
	private static $lang;
	private static $common_lang;
	private static $comments_lang;
	private static $comments_cache;
	private static $template;
	
	public static function __static()
	{
		self::$user = AppContext::get_current_user();
		self::$lang = LangLoader::get('main');
		self::$common_lang = LangLoader::get('common');
		self::$comments_lang = LangLoader::get('comments-common');
		self::$comments_cache = CommentsCache::load();
		self::$template = new FileTemplate('framework/content/comments/comments.tpl');
		self::$template->add_lang(self::$comments_lang);
	}
	
	/**
	 * @desc This function display the comments
	 * @param class CommentsTopic $topic
	 * @return Template is a template object
	 */
	public static function display(CommentsTopic $topic)
	{
		$module_id = $topic->get_module_id();
		$id_in_module = $topic->get_id_in_module();
		$topic_identifier = $topic->get_topic_identifier();
		$authorizations = $topic->get_authorizations();
				
		if (!$authorizations->is_authorized_read())
		{
			self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comments.not-authorized.read'], MessageHelper::NOTICE));
		}
		else
		{
			$edit_comment_id = AppContext::get_request()->get_getint('edit_comment', 0);
			$delete_comment_id = AppContext::get_request()->get_getint('delete_comment', 0);
			
			try {
				$lock = AppContext::get_request()->get_getbool('lock');
				if ($authorizations->is_authorized_moderation())
				{
					if ($lock)
					{
						if (!CommentsTopicDAO::topic_exists($module_id, $id_in_module, $topic_identifier))
						{
							CommentsTopicDAO::create_topic($module_id, $id_in_module, $topic_identifier, $topic->get_path());
						}
						CommentsManager::lock_topic($module_id, $id_in_module, $topic_identifier);
					}
					else
					{
						CommentsManager::unlock_topic($module_id, $id_in_module, $topic_identifier);
					}
				}
				AppContext::get_response()->redirect($topic->get_path());
			} catch (UnexistingHTTPParameterException $e) {
			}
			
			if (!empty($delete_comment_id))
			{
				self::verificate_authorized_edit_or_delete_comment($authorizations, $delete_comment_id);

				CommentsManager::delete_comment($delete_comment_id);
				AppContext::get_response()->redirect($topic->get_path());
			}
			elseif (!empty($edit_comment_id))
			{
				self::verificate_authorized_edit_or_delete_comment($authorizations, $edit_comment_id);
	
				$edit_comment_form = EditCommentBuildForm::create($edit_comment_id, $topic->get_path());
				self::$template->put_all(array(
					'C_DISPLAY_FORM' => true,
					'COMMENT_FORM' => $edit_comment_form->display()
				));
			}
			else
			{
				if ($authorizations->is_authorized_post() && $authorizations->is_authorized_access_module())
				{
					$comments_topic_locked = CommentsManager::comment_topic_locked($module_id, $id_in_module, $topic_identifier);
					$user_read_only = self::$user->get_delay_readonly();
					if (!$authorizations->is_authorized_moderation() && $comments_topic_locked)
					{
						self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comment.locked'], MessageHelper::NOTICE));
					}
					elseif (!empty($user_read_only) && $user_read_only > time())
					{
						self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comments.user.read-only'], MessageHelper::NOTICE));
					}
					else
					{
						$add_comment_form = AddCommentBuildForm::create($topic);
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'COMMENT_FORM' => $add_comment_form->display()
						));
					}
				}
				else
				{
					self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comments.not-authorized.post'], MessageHelper::NOTICE));
				}
			}
				
			$number_comments_display = $topic->get_number_comments_display();
			$number_comments = self::$comments_cache->get_count_comments_by_module($module_id, $id_in_module, $topic_identifier);
			
			self::$template->put_all(array(
				'COMMENTS_LIST' => self::display_comments($module_id, $id_in_module, $topic_identifier, $number_comments_display, $authorizations),
				'MODULE_ID' => $module_id,
				'ID_IN_MODULE' => $id_in_module,
				'TOPIC_IDENTIFIER' => $topic_identifier,
				'C_DISPLAY_VIEW_ALL_COMMENTS' => $number_comments > $number_comments_display,
				'C_MODERATE' => $authorizations->is_authorized_moderation(),
				'C_IS_LOCKED' => CommentsManager::comment_topic_locked($module_id, $id_in_module, $topic_identifier),
				'U_LOCK' => CommentsUrlBuilder::lock_and_unlock($topic->get_path(), true)->rel(),
				'U_UNLOCK' => CommentsUrlBuilder::lock_and_unlock($topic->get_path(), false)->rel(),
			));
		}

		return self::$template;
	}
	
	/**
	 * @desc Returns number comments and lang (example : Comments (number_comments)
	 * @param string $module_id the module identifier
	 * @param integer $id_in_module id in module used in comments system
	 * @param string $topic_identifier topic identifier (use if you have several comments system)
	 * @return string number comments (example : Comments (number_comments)
	 */
	public static function get_number_and_lang_comments($module_id, $id_in_module, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		$number_comments = CommentsManager::get_number_comments($module_id, $id_in_module, $topic_identifier);
		$lang = $number_comments > 1 ? self::$lang['com_s'] : self::$lang['com'];
	
		return !empty($number_comments) ? $lang . ' (' . $number_comments . ')' : self::$lang['post_com'];
	}
	
	/**
	 * @desc Returns lang (example : "Comments" for several comments, "comment" for one comment and "No comment" if no comment
	 * @param string $module_id the module identifier
	 * @param integer $id_in_module id in module used in comments system
	 * @param string $topic_identifier topic identifier (use if you have several comments system)
	 * @return string
	 */
	public static function get_lang_comments($module_id, $id_in_module, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		$number_comments = CommentsManager::get_number_comments($module_id, $id_in_module, $topic_identifier);
		$lang = $number_comments > 1 ? self::$comments_lang['comments'] : self::$comments_lang['comment'];
	
		return !empty($number_comments) ? ' ' .$lang : self::$comments_lang['no_comment'];
	}

	/**
	 * @desc Delete all comments module
	 * @param string $module_id the module identifier
	 */
	public static function delete_comments_module($module_id)
	{
		try {
			CommentsManager::delete_comments_module($module_id);
		} catch (RowNotFoundException $e) {
		}
	}
	
	/**
	 * @desc Delete comments topic according to module identifier and id in module
	 * @param string $module_id the module identifier
	 * @param integer $id_in_module id in module used in comments system
	 */
	public static function delete_comments_topic_module($module_id, $id_in_module)
	{
		try {
			CommentsManager::delete_comments_topic_module($module_id, $id_in_module);
		} catch (RowNotFoundException $e) {
		}
	}
	
	/**
	 * @desc Returns number comments
	 * @param string $module_id the module identifier
	 * @param integer $id_in_module id in module used in comments system
	 * @param string $topic_identifier topic identifier (use if you have several comments system)
	 * @return string number comments
	 */
	public static function get_number_comments($module_id, $id_in_module, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		return CommentsManager::get_number_comments($module_id, $id_in_module, $topic_identifier);
	}

	/**
	 * @desc Do not use, this is used for ajax display comments
	 * @param string $module_id the module identifier
	 * @param integer $id_in_module id in module used in comments system
	 * @param string $topic_identifier topic identifier (use if you have several comments system)
	 * @return object View is a view
	 */
	public static function display_comments($module_id, $id_in_module, $topic_identifier, $number_comments_display, $authorizations, $display_from_number_comments = false)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');

		if ($authorizations->is_authorized_read() && $authorizations->is_authorized_access_module())
		{
			$user_accounts_config = UserAccountsConfig::load();
			
			$condition = !$display_from_number_comments ? ' LIMIT '. $number_comments_display : ' LIMIT ' . $number_comments_display . ',18446744073709551615';
			$result = PersistenceContext::get_querier()->select("
				SELECT comments.*, comments.timestamp AS comment_timestamp, comments.id AS id_comment,
				topic.is_locked, topic.path,
				member.user_id, member.display_name, member.level, member.groups, 
				ext_field.user_avatar
				FROM " . DB_TABLE_COMMENTS . " comments
				LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
				LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = comments.user_id
				LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = comments.user_id
				WHERE topic.module_id = '". $module_id ."' AND topic.id_in_module = '". $id_in_module ."' AND topic.topic_identifier = '". $topic_identifier ."'
				ORDER BY comments.timestamp " . CommentsConfig::load()->get_order_display_comments() . " " . $condition
			);
			
			while ($row = $result->fetch())
			{
				$id = $row['id_comment'];
				$path = $row['path'];
				
				//Avatar
				$user_avatar = !empty($row['user_avatar']) ? Url::to_rel($row['user_avatar']) : ($user_accounts_config->is_default_avatar_enabled() ? Url::to_rel('/templates/' . AppContext::get_current_user()->get_theme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '');
				
				$timestamp = new Date($row['comment_timestamp'], Timezone::SERVER_TIMEZONE);
				$group_color = User::get_group_color($row['groups'], $row['level']);
				
				$template->assign_block_vars('comments', array(
					'C_MODERATOR' => self::is_authorized_edit_or_delete_comment($authorizations, $id),
					'C_VISITOR' => empty($row['display_name']),
					'C_GROUP_COLOR' => !empty($group_color),
					'C_AVATAR' => $row['user_avatar'] || ($user_accounts_config->is_default_avatar_enabled()),
					'U_EDIT' => CommentsUrlBuilder::edit($path, $id)->rel(),
					'U_DELETE' => CommentsUrlBuilder::delete($path, $id)->rel(),
					'U_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
					'U_AVATAR' => $user_avatar,
					'ID_COMMENT' => $id,
					'DATE' => $timestamp->format(Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
					'DATE_ISO8601' => $timestamp->format(Date::FORMAT_ISO8601),
					'MESSAGE' => FormatingHelper::second_parse($row['message']),
					'USER_ID' => $row['user_id'],
					'PSEUDO' => empty($row['display_name']) ? $row['pseudo'] : $row['display_name'],
					'LEVEL_CLASS' => UserService::get_level_class($row['level']),
					'GROUP_COLOR' => $group_color,
					'L_LEVEL' => UserService::get_level_lang($row['level'] !== null ? $row['level'] : '-1'),
				));
				
				$template->put_all(array(
					'L_UPDATE' => self::$common_lang['edit'],
					'L_DELETE' => self::$common_lang['delete'],
				));
			}
			$result->dispose();
		}


		self::$template->put_all(array(
			'MODULE_ID' => $module_id,
			'ID_IN_MODULE' => $id_in_module,
			'TOPIC_IDENTIFIER' => $topic_identifier
		));

		return $template;
	}
	
	private static function verificate_authorized_edit_or_delete_comment($authorizations, $comment_id)
	{
		$is_authorized = self::is_authorized_edit_or_delete_comment($authorizations, $comment_id);
		
		if (!CommentsManager::comment_exists($comment_id))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		else if (!$is_authorized)
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private static function is_authorized_edit_or_delete_comment($authorizations, $comment_id)
	{
		$user_id_posted_comment = CommentsManager::get_user_id_posted_comment($comment_id);
		if ($user_id_posted_comment !== '-1')
		{
			return ($authorizations->is_authorized_moderation() || $user_id_posted_comment == self::$user->get_id()) && $authorizations->is_authorized_access_module();
		}
		return false;
	}
}
?>