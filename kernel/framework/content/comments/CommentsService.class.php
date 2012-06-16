<?php
/*##################################################
 *                              CommentsService.class.php
 *                            -------------------
 *   begin                : March 31, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class CommentsService
{	
	private static $user;
	private static $lang;
	private static $comments_lang;
	private static $comments_configuration;
	private static $comments_cache;
	private static $template;
	
	public static function __static()
	{
		self::$user = AppContext::get_current_user();
		self::$lang = LangLoader::get('main');
		self::$comments_lang = LangLoader::get('comments-common');
		self::$comments_configuration = CommentsConfig::load();
		self::$comments_cache = CommentsCache::load();
		self::$template = new FileTemplate('framework/content/comments/comments.tpl');
	}
	
	public static function display(CommentsTopic $topic)
	{
		$module_id = $topic->get_module_id();
		$id_in_module = $topic->get_id_in_module();
		$topic_identifier = $topic->get_topic_identifier();
		$authorizations = $topic->get_authorizations();
				
		if (!$authorizations->is_authorized_read())
		{
			self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comments.not-authorized.read'], MessageHelper::NOTICE, 4));
		}
		else
		{
			$edit_comment_id = AppContext::get_request()->get_int('edit_comment', 0);
			$delete_comment_id = AppContext::get_request()->get_int('delete_comment', 0);
			
			if (!empty($delete_comment_id))
			{
				self::verificate_authorized_edit_or_delete_comment($authorizations, $delete_comment_id);

				CommentsManager::delete_comment($delete_comment_id);
				
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comment.delete.success'], MessageHelper::SUCCESS, 4));
			}
			elseif (!empty($edit_comment_id))
			{
				self::verificate_authorized_edit_or_delete_comment($authorizations, $edit_comment_id);
	
				$edit_comment_form = EditCommentBuildForm::create($edit_comment_id);
				self::$template->put_all(array(
					'C_DISPLAY_FORM' => true,
					'KEEP_MESSAGE' => $edit_comment_form->get_message_response(),
					'COMMENT_FORM' => $edit_comment_form->display()
				));
			}
			else
			{
				if ($authorizations->is_authorized_post() && $authorizations->is_authorized_access_module())
				{
					$comments_topic_locked = CommentsManager::comment_topic_locked($module_id, $id_in_module, $topic_identifier);
					$user_read_only = self::$user->get_attribute('user_readonly');
					if (!$authorizations->is_authorized_moderation() && $comments_topic_locked)
					{
						self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$lang['com_locked'], MessageHelper::NOTICE, 4));
					}
					elseif (!empty($user_read_only) && $user_read_only > time())
					{
						self::$template->put('KEEP_MESSAGE', MessageHelper::display('Read Only', MessageHelper::NOTICE, 4));
					}
					else
					{
						$add_comment_form = AddCommentBuildForm::create($topic);
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'KEEP_MESSAGE' => $add_comment_form->get_message_response(),
							'COMMENT_FORM' => $add_comment_form->display()
						));
					}
				}
				else
				{
					self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comments.not-authorized.post'], MessageHelper::NOTICE, 4));
				}
			}
				
			$number_comments_display = $topic->get_number_comments_display();
			$number_comments = self::$comments_cache->get_count_comments_by_module($module_id, $id_in_module, $topic_identifier);
			
			self::$template->put_all(array(
				'COMMENTS_LIST' => self::display_comments($module_id, $id_in_module, $topic_identifier, $number_comments_display, $authorizations),
				'MODULE_ID' => $module_id,
				'ID_IN_MODULE' => $id_in_module,
				'TOPIC_IDENTIFIER' => $topic_identifier,
				'C_DISPLAY_VIEW_ALL_COMMENTS' => $number_comments > $number_comments_display
			));
		}

		return self::$template;
	}
	
	public static function get_number_and_lang_comments($module_id, $id_in_module, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		$number_comments = CommentsManager::get_number_comments($module_id, $id_in_module, $topic_identifier);
		$lang = $number_comments > 1 ? self::$lang['com_s'] : self::$lang['com'];
	
		return !empty($number_comments) ? $lang . ' (' . $number_comments . ')' : self::$lang['post_com'];
	}

	/*
	 * Required Instance Comments class and setter function module id.
	*/
	public static function delete_comments_module($module_id)
	{
		try {
			CommentsManager::delete_comments_module($module_id);
		} catch (Exception $e) {
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module id and id in module.
	*/
	public static function delete_comments_topic_module($module_id, $id_in_module)
	{
		try {
			CommentsManager::delete_comments_topic_module($module_id, $id_in_module);
		} catch (Exception $e) {
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module name, and module id.
	*/
	public static function get_number_comments($module_id, $id_in_module, $topic_identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		return CommentsManager::get_number_comments($module_id, $id_in_module, $topic_identifier);
	}

	public static function display_comments($module_id, $id_in_module, $topic_identifier, $number_comments_display, $authorizations, $display_from_number_comments = false)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');

		if ($authorizations->is_authorized_read() && $authorizations->is_authorized_access_module())
		{
			$user_accounts_config = UserAccountsConfig::load();
			
			$condition = !$display_from_number_comments ? ' LIMIT '. $number_comments_display : ' LIMIT ' . $number_comments_display . ',18446744073709551615';
			$result = PersistenceContext::get_querier()->select("
					SELECT comments.*, topic.*, member.*, ext_field.user_avatar
					FROM " . DB_TABLE_COMMENTS . " comments
					LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
					LEFT JOIN " . DB_TABLE_MEMBER . " member ON member.user_id = comments.user_id
					LEFT JOIN " . DB_TABLE_MEMBER_EXTENDED_FIELDS . " ext_field ON ext_field.user_id = comments.user_id
					WHERE topic.module_id = '". $module_id ."' AND topic.id_in_module = '". $id_in_module ."' AND topic.topic_identifier = '". $topic_identifier ."'
					ORDER BY comments.timestamp " . CommentsConfig::load()->get_order_display_comments() . " " . $condition
			);
			
			while ($row = $result->fetch())
			{
				$id = $row['id'];
				$path = PATH_TO_ROOT . $row['path'];
				
				if (empty($row['user_avatar']))
					$user_avatar = $user_accounts_config->is_default_avatar_enabled() == '1' ? Url::to_rel(PATH_TO_ROOT .'/templates/' . get_utheme() . '/images/' .  $user_accounts_config->get_default_avatar_name()) : '';
				else
					$user_avatar = Url::to_rel($row['user_avatar']);
				
				$template->assign_block_vars('comments', array(
						
					// Comment
					'C_MODERATOR' => self::is_authorized_edit_or_delete_comment($authorizations, $id),
					'U_EDIT' => CommentsUrlBuilder::edit($path, $id)->absolute(),
					'U_DELETE' => CommentsUrlBuilder::delete($path, $id)->absolute(),
						
					'MESSAGE' => FormatingHelper::second_parse($row['message']),
					'COMMENT_ID' => $id,
						
					// User
					'USER_ID' => $row['user_id'],
					'PSEUDO' => $row['login'],
					'U_AVATAR' => $user_avatar
				));
				
				$template->put('C_IS_LOCKED', $row['is_locked']);
			}
		}

		$template->put_all(array(
			//TODO
			'C_IS_LOCKED' => false,
			//TODO
			
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
			return ($authorizations->is_authorized_moderation() || $user_id_posted_comment == self::$user->get_attribute('user_id')) && $authorizations->is_authorized_access_module();
		}
		return false;
	}
}
?>