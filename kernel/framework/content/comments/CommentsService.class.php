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
	private static $comments_configuration;
	private static $template;
	
	public static function __static()
	{
		self::$user = AppContext::get_user();
		self::$lang = LangLoader::get('main');
		self::$comments_configuration = CommentsConfig::load();
		self::$template = new FileTemplate('framework/content/comments/comments.tpl');
	}
	
	public static function display(CommentsTopic $comments_topic)
	{
		$module_id = $comments_topic->get_module_id();
		$id_in_module = $comments_topic->get_id_in_module();
		$authorizations = self::get_authorizations($module_id, $id_in_module);
		
		$edit_comment = AppContext::get_request()->get_int('edit_comment', 0);
		$delete_comment = AppContext::get_request()->get_int('delete_comment', 0);
		
		if ($authorizations->is_authorized_read())
		{
			$user_read_only = self::$user->get_attribute('user_readonly');
			if ($comments_topic->get_is_locked())
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$lang['com_locked'], E_USER_SUCCESS, 4));
			}
			else if (!empty($user_read_only) && $user_read_only > time())
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display('Read Only', E_USER_SUCCESS, 4));
			}
			else
			{
				if (!CommentsDAO::comments_topic_exists($module_id, $id_in_module))
				{
					CommentsDAO::create_comments_topic($comments_topic);
				}
				
				if (empty($edit_comment))
				{
					if ($authorizations->is_authorized_post())
					{
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'COMMENT_FORM' => self::add_comment_form($module_id, $id_in_module)
						));
					}
					else
					{
						//TODO change lang
						throw new Exception('Vous n\'êtes pas autorisé à poster des commentaires !');
					}
				}
				else
				{
					if (!CommentsDAO::comment_exists($edit_comment))
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
					
					$user_id_posted_comment = CommentsDAO::get_user_id_posted_comment($edit_comment);
					
					if ($authorizations->is_authorized_moderation()
					 || $user_id_posted_comment == self::$user->get_attribute('user_id'))
					{
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'COMMENT_FORM' => self::update_comment_form($edit_comment)
						));
					}
					else
					{
						//TODO change lang
						throw new Exception('Vous n\'êtes pas autorisé à éditer ce commentaire !');
					}
				}

				$number_comments_display = self::get_number_comments_display($module_id, $id_in_module);
				self::$template->put_all(array(
					'COMMENTS_LIST' => self::display_comments($module_id, $id_in_module, 
					$number_comments_display, $authorizations),
					'MODULE_ID' => $module_id,
					'ID_IN_MODULE' => $id_in_module,
				));
			}
		}
		else
		{
			//TODO change lang
			throw new Exception('Vous n\'êtes pas autorisé à lire les commentaires !');
		}
	
		return self::$template;
	}

	/*
	 * Required Instance Comments class and setter function module id.
	*/
	public static function delete_comments_module($module_id)
	{
		if (CommentsTopicDAO::comments_topic_exists_by_module_id($module_id))
		{
			CommentsDAO::delete_all_comments_by_module_name($module_id);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module id and id in module.
	*/
	public static function delete_comments_by_id_in_module($module_id, $id_in_module)
	{
		if (CommentsTopicDAO::comments_topic_exists($module_id, $id_in_module))
		{
			CommentsDAO::delete_comments_by_id_in_module($module_id, $id_in_module);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module name, and module id.
	*/
	public static function get_number_comments($module_id, $id_in_module)
	{
		return CommentsDAO::number_comments($module_id, $id_in_module);
	}
	
	private static function add_comment_form($module_id, $id_in_module)
	{
		$is_visitor = !self::$user->check_level(MEMBER_LEVEL);
		$form = new HTMLForm('comments');
		$fieldset = new FormFieldsetHTML('add_comment', self::$lang['add_comment']);
		$form->add_fieldset($fieldset);
		
		if ($is_visitor)
		{
			$fieldset->add_field(new FormFieldTextEditor('name', self::$lang['pseudo'], self::$lang['guest'], array('maxlength' => 25)));
		}
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', self::$lang['message'], '', array(
			'formatter' => self::get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => self::$lang['require_text']),
			array(new FormFieldConstraintMaxLinks(self::$comments_configuration->get_max_links_comment()),
				//new FormFieldConstraintAntiFlood(CommentsDAO::get_last_comment_added_user(self::$user->get_id())),
				new FormFieldConstraintAntiFlood(time())
			)
		));
		
		if (self::$comments_configuration->get_display_captcha())
		{
			$fieldset->add_field(new FormFieldCaptcha('captcha', self::get_captcha()));
		}

		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		if ($submit_button->has_been_submited() && $form->validate())
		{
			$comment = new Comment();
			if (!$is_visitor)
			{
				$comment->set_user_id(self::$user->get_id());
			}
			else
			{
				$comment->set_name_visitor($form->get_value('name'));
				$comment->set_ip_visitor(USER_IP);
			}
			
			$comment->set_message($form->get_value('message'));
			$comment->set_module_id($module_id);
			$comment->set_id_in_module($id_in_module);
			CommentsDAO::add_comment($comment);
			
			//TODO change lang
			self::$template->put('KEEP_MESSAGE', MessageHelper::display('Posted successfully', E_USER_SUCCESS, 4));
		}
		
		return $form->display();
	}
	
	public static function get_number_and_lang_comments($module_id, $id_in_module)
	{
		$number_comments = self::get_number_comments($module_id, $id_in_module);
		$lang = $number_comments > 1 ? self::$lang['com_s'] : self::$lang['com'];
		
		return !empty($number_comments) ? $lang . ' (' . $number_comments . ')' : self::$lang['post_com'];
	}

	private static function update_comment_form($comment_id)
	{
		$data = CommentsDAO::get_data_comment($comment_id);

		$form = new HTMLForm('comments');
		$fieldset = new FormFieldsetHTML('edit_comment', self::$lang['edit_comment']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', self::$lang['message'], $data['message'], array(
			'formatter' => self::get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => self::$lang['require_text']),
			array(new FormFieldConstraintMaxLinks(self::$comments_configuration->get_max_links_comment()))
		));
		
		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		if ($submit_button->has_been_submited() && $form->validate())
		{
			CommentsDAO::edit_comment($comment_id, $form->get_value('message'));
			
			//TODO change lang
			self::$template->put('KEEP_MESSAGE', MessageHelper::display('Edit successfully', E_USER_SUCCESS, 4));
		}
		return $form->display();
	}
	
	public static function display_comments($module_id, $id_in_module, $number_comments_display, $authorizations, $display_from_number_comments = false)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');

		if ($authorizations->is_authorized_read() && self::is_display($module_id, $id_in_module))
		{
			$result = self::select_request($module_id, $id_in_module, $number_comments_display, $display_from_number_comments);
			
			while ($row = $result->fetch())
			{
				$template->assign_block_vars('comments_list', array(
					'MESSAGE' => $row['message'],
					'COMMENT_ID' => $row['id'],
					'EDIT_COMMENT' => self::get_url_built($module_id, $id_in_module, array('edit_comment' => $row['id']))->absolute()
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
	
	private static function select_request($module_id, $id_in_module, $number_comments_display, $display_from_number_comments = false)
	{
		if (!$display_from_number_comments)
		{
			return PersistenceContext::get_querier()->select("
			SELECT comments.*, topic.*
			FROM " . DB_TABLE_COMMENTS . " comments
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
			WHERE topic.module_id = :module_id AND topic.id_in_module = :id_in_module
			ORDER BY comments.timestamp ASC
			LIMIT ".  $number_comments_display ."
			",
				array(
					'module_id' =>  $module_id,
					'id_in_module' =>  $id_in_module
				), SelectQueryResult::FETCH_ASSOC
			);
		}
		else
		{
			return PersistenceContext::get_querier()->select("
			SELECT comments.*, topic.*
			FROM " . DB_TABLE_COMMENTS . " comments
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id_topic
			WHERE topic.module_id = :module_id AND topic.id_in_module = :id_in_module
			ORDER BY comments.timestamp ASC
			LIMIT ".  $number_comments_display .", 18446744073709551615
			",
				array(
					'module_id' =>  $module_id,
					'id_in_module' =>  $id_in_module
				), SelectQueryResult::FETCH_ASSOC
			);
		}
	}
	
	private static function get_formatter()
	{
		$formatter = AppContext::get_content_formatting_service()->create_factory();
		$formatter->set_forbidden_tags(self::$comments_configuration->get_forbidden_tags());
		return $formatter;
	}
	
	private static function get_captcha()
	{
		$captcha = new Captcha();
		$captcha->set_difficulty(self::$comments_configuration->get_captcha_difficulty());
		return $captcha;
	}
	
	private static function is_display($module_id, $id_in_module)
	{
		return CommentsProvidersService::is_display($module_id, $id_in_module);
	}
	
	private static function get_authorizations($module_id, $id_in_module)
	{
		return CommentsProvidersService::get_authorizations($module_id, $id_in_module);
	}
	
	private static function get_number_comments_display($module_id, $id_in_module)
	{
		return CommentsProvidersService::get_number_comments_display($module_id, $id_in_module);
	}
	
	private static function get_url_built($module_id, $id_in_module, $parameters)
	{
		return CommentsProvidersService::get_url_built($module_id, $id_in_module, $parameters);
	}
}
?>