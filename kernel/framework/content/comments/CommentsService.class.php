<?php
/*##################################################
 *                              CommentsService.class.php
 *                            -------------------
 *   begin                : March 31, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
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
	
	public static function display(CommentsTopic $comments_topic)
	{
		$module_id = $comments_topic->get_module_id();
		$id_in_module = $comments_topic->get_id_in_module();
		
		$provider = CommentsProvidersService::get_provider($module_id);
		$authorizations = $provider->get_authorizations($module_id, $id_in_module);
		
		$edit_comment_id = AppContext::get_request()->get_int('edit_comment', 0);
		$delete_comment_id = AppContext::get_request()->get_int('delete_comment', 0);
		
		if (!$authorizations->is_authorized_read())
		{
			self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comments.not-authorized.read'], MessageHelper::NOTICE, 4));
		}
		else
		{
			if (!empty($delete_comment_id))
			{
				if (!self::is_authorized_edit_or_delete_comment($authorizations, $delete_comment_id))
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
	
				CommentsManager::delete_comment($delete_comment_id);
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$comments_lang['comment.delete.success'], MessageHelper::NOTICE, 4));
			}
	
			if (!empty($edit_comment_id))
			{
				if (!CommentsManager::comment_exists($edit_comment_id) || !self::is_authorized_edit_or_delete_comment($authorizations, $edit_comment_id))
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);		
				}
				
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
					$comments_topic_locked = CommentsManager::comment_topic_locked($module_id, $id_in_module);
					$user_read_only = self::$user->get_attribute('user_readonly');
					if (!$authorizations->is_authorized_moderation() && $comments_topic_locked)
					{
						self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$lang['com_locked'], MessageHelper::NOTICE, 4));
					}
					if (!empty($user_read_only) && $user_read_only > time())
					{
						self::$template->put('KEEP_MESSAGE', MessageHelper::display('Read Only', MessageHelper::SUCCESS, 4));
					}
					else
					{
						$add_comment_form = AddCommentBuildForm::create($module_id, $id_in_module, $comments_topic->get_path());
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
				
			$number_comments_display = $provider->get_number_comments_display($module_id, $id_in_module);
			$number_comments = self::$comments_cache->get_count_comments_by_module($module_id, $id_in_module);
			
			self::$template->put_all(array(
				'COMMENTS_LIST' => self::display_comments($module_id, $id_in_module, 
					$number_comments_display, $authorizations),
				'MODULE_ID' => $module_id,
				'ID_IN_MODULE' => $id_in_module,
				'C_DISPLAY_VIEW_ALL_COMMENTS' => $number_comments > $number_comments_display
			));
		}

		return self::$template;
	}
	
	public static function get_number_and_lang_comments($module_id, $id_in_module)
	{
		$number_comments = CommentsManager::get_number_comments($module_id, $id_in_module);
		$lang = $number_comments > 1 ? self::$lang['com_s'] : self::$lang['com'];
		
		return !empty($number_comments) ? $lang . ' (' . $number_comments . ')' : self::$lang['post_com'];
	}
	

	/*
	 * Required Instance Comments class and setter function module id.
	*/
	public static function delete_comments_module($module_id)
	{
		if (CommentsTopicDAO::comments_topic_exists_by_module_id($module_id))
		{
			CommentsManager::delete_comments_module($module_id);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module id and id in module.
	*/
	public static function delete_comments_topic_module($module_id, $id_in_module)
	{
		if (CommentsTopicDAO::comments_topic_exists($module_id, $id_in_module))
		{
			CommentsManager::delete_comments_topic_module($module_id, $id_in_module);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module name, and module id.
	*/
	public static function get_number_comments($module_id, $id_in_module)
	{
		return CommentsManager::get_number_comments($module_id, $id_in_module);
	}

	public static function display_comments($module_id, $id_in_module, $number_comments_display, $authorizations, $display_from_number_comments = false)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');

		$provider = CommentsProvidersService::get_provider($module_id);

		if ($authorizations->is_authorized_read() && $authorizations->is_authorized_access_module())
		{
			$comments = self::get_comments($module_id, $id_in_module, $number_comments_display, $display_from_number_comments);
			foreach ($comments as $id_comment => $comment)
			{
				$edit_comment_url = $comment['path'] . '&edit_comment=' . $id_comment . '#comments_message';
				$delete_comment_url = $comment['path'] . '&delete_comment=' . $id_comment . '#comments_list';
				
				$template->assign_block_vars('comments_list', array(
					'MESSAGE' => $comment['message'],
					'COMMENT_ID' => $id_comment,
					'EDIT_COMMENT' => $edit_comment_url,
					'DELETE_COMMENT' => $delete_comment_url
				));
			}
		}
		
		$template->put_all(array(
			'MODULE_ID' => $module_id,
			'ID_IN_MODULE' => $id_in_module,
			'C_IS_MODERATOR' => $authorizations->is_authorized_moderation()
		));

		return $template;
	}
	
	private static function get_comments($module_id, $id_in_module, $number_comments_display, $display_from_number_comments)
	{
		if (!$display_from_number_comments)
		{
			return self::$comments_cache->get_comments_sliced($module_id, $id_in_module, 0, $number_comments_display);
		}
		else
		{
			return self::$comments_cache->get_comments_sliced($module_id, $id_in_module, $number_comments_display);
		}
	}
	
	private static function is_authorized_edit_or_delete_comment($authorizations, $comment_id)
	{
		$user_id_posted_comment = CommentsManager::get_user_id_posted_comment($comment_id);
		return ($authorizations->is_authorized_moderation() || $user_id_posted_comment == self::$user->get_attribute('user_id')) && $authorizations->is_authorized_access_module();
	}
}
?>