<?php
/*##################################################
 *                              CommentsService.class.php
 *                            -------------------
 *   begin                : March 31, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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
	
	public static function display(Comments $comments)
	{
		$edit_comment = AppContext::get_request()->get_int('edit_comment', 0);
		$delete_comment = AppContext::get_request()->get_int('delete_comment', 0);
		if ($comments->get_authorizations()->is_authorized_read())
		{
			if ($comments->get_is_locked() || self::$user->get_attribute('user_readonly') <= time())
			{
				if ($edit_comment == 0)
				{
					if ($comments->get_authorizations()->is_authorized_post())
					{
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'COMMENT_FORM' => self::add_comment_form($comments)
						));
					}
					else
					{
						//TODO
						throw new Exception('Vous n\'êtes pas autorisé à poster des commentaires !');
					}
				}
				else
				{
					$comment = new Comment();
					$comment->set_id($edit_comment);
					
					if (!CommentsDAO::comment_exist($comment))
					{
						$error_controller = PHPBoostErrors::unexisting_page();
						DispatchManager::redirect($error_controller);
					}
					
					$user_id_posted_comment = CommentsDAO::get_user_id_posted_comment($comment);
					if ($comments->get_authorizations()->is_authorized_moderation() || $user_id_posted_comment == self::$user->get_attribute('user_id'))
					{
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'COMMENT_FORM' => self::update_comment_form($comments, $comment)
						));
					}
					else
					{
						//TODO
						throw new Exception('Vous n\'êtes pas autorisé d\'éditer ce commentaire !');
					}
				}
			}
			elseif (self::$user->get_attribute('user_readonly') <= time())
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display('Read Only', E_USER_SUCCESS, 4));
			}
			else
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$lang['com_locked'], E_USER_SUCCESS, 4));
			}
			
			self::$template->put('COMMENTS_LIST', self::display_comments_list($comments));
		}
		else
		{
			//TODO
			throw new Exception('Vous n\'êtes pas autorisé à lire les commentaires !');
		}
	
		return self::$template;
	}
	
	/*
	 * Required Instance Comments class and setters functions module name, module id and visibility.
	*/
	public static function change_visibility(Comments $comments)
	{
		CommentsDAO::change_visibility($comments);
	}
	
	/*
	 * Required Instance Comments class and setter function module name.
	*/
	public static function delete_all_comments_by_module_name(Comments $comments)
	{
		CommentsDAO::delete_all_comments_by_module_name($comments);
	}
	
	/*
	 * Required Instance Comments class and setter function module name, and module id.
	*/
	public static function get_number_comments(Comments $comments)
	{
		CommentsDAO::number_comments($comments);
	}
	
	private static function add_comment_form(Comments $comments)
	{
		$is_visitor = !self::$user->check_level(MEMBER_LEVEL);
		$form = new HTMLForm('comments');
		$fieldset = new FormFieldsetHTML('add_comment', self::$lang['add_comment']);
		$form->add_fieldset($fieldset);
		
		if ($is_visitor)
		{
			$fieldset_config->add_field(new FormFieldTextEditor('name', self::$lang['pseudo'], self::$lang['guest'], array(
				'title' => self::$lang['pseudo'], 'required' => self::$lang['require_pseudo'], 'maxlength' => 25)
			));
		}
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', self::$lang['message'], '', array(
			'formatter' => self::get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => self::$lang['require_text'])
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
				$comment->set_user_id(self::$user->get_attribute('user_id'));
			}
			else
			{
				$comment->set_ip_visitor(USER_IP);
			}
			
			/*
			$last_comment = CommentsDAO::get_last_comment_user($comment);
			if ($last_comment !== null && $last_comment >= (time() - ContentManagementConfig::load()->get_anti_flood_duration()))
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$lang['e_flood']), E_USER_NOTICE, 4);
			}
			else 
			*/
			if (!TextHelper::check_nbr_links($form->get_value('message'), self::$comments_configuration->get_max_links_comment()))
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(sprintf(self::$lang['e_l_flood'], self::$comments_configuration->get_max_links_comment()), E_USER_NOTICE, 4));
			}
			else
			{
				$comment->set_name_visitor($form->get_value('name', ''));
				$comment->set_message($form->get_value('message'));
				$comment->set_module_name($comments->get_module_name());
				$comment->set_id_module($comments->get_id_module());
				CommentsDAO::add_comment($comment);
				
				//TODO
				self::$template->put('KEEP_MESSAGE', MessageHelper::display('Posted successfully', E_USER_SUCCESS, 4));
			}
		}
		
		return $form->display();
	}
	
	private static function update_comment_form(Comments $comments, Comment $comment)
	{
		$data = CommentsDAO::get_data_comment($comment);

		$form = new HTMLForm('comments');
		$fieldset = new FormFieldsetHTML('edit_comment', self::$lang['edit_comment']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', self::$lang['message'], $data['message'], array(
			'formatter' => self::get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => self::$lang['require_text'])
		));
		
		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		if ($submit_button->has_been_submited() && $form->validate())
		{
			if (TextHelper::check_nbr_links($form->get_value('message'), self::$comments_configuration->get_max_links_comment()))
			{
				$comment->set_message($form->get_value('message'));
				CommentsDAO::edit_comment($comment);
				
				//TODO
				self::$template->put('KEEP_MESSAGE', MessageHelper::display('Edit successfully', E_USER_SUCCESS, 4));
			}
			else
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(sprintf(self::$lang['e_l_flood'], self::$comments_configuration->get_max_links_comment()), E_USER_NOTICE, 4));
			}
		}
		return $form->display();
	}
	
	private static function display_comments_list(Comments $comments)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');
	
	
		return $template;
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
}

?>