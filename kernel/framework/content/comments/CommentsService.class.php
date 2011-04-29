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
	
	public static function display(Comments $comments)
	{
		$edit_comment = AppContext::get_request()->get_int('edit_comment', 0);
		$delete_comment = AppContext::get_request()->get_int('delete_comment', 0);
		if ($comments->get_authorizations()->is_authorized_read())
		{
			$user_read_only = self::$user->get_attribute('user_readonly');
			if ($comments->get_is_locked())
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(self::$lang['com_locked'], E_USER_SUCCESS, 4));
			}
			else if (!empty($user_read_only) && $user_read_only > time())
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display('Read Only', E_USER_SUCCESS, 4));
			}
			else
			{
				if (!CommentsDAO::comments_topic_exist($comments))
				{
					CommentsDAO::create_comments_topic($comments);
				}
				
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
						//TODO change lang
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
					
					$user_id_posted_comment = CommentsDAO::user_id_posted_comment($comment);
					if ($comments->get_authorizations()->is_authorized_moderation() || $user_id_posted_comment == self::$user->get_attribute('user_id'))
					{
						self::$template->put_all(array(
							'C_DISPLAY_FORM' => true,
							'COMMENT_FORM' => self::update_comment_form($comments, $comment)
						));
					}
					else
					{
						//TODO change lang
						throw new Exception('Vous n\'êtes pas autorisé d\'éditer ce commentaire !');
					}
				}

				self::$template->put('COMMENTS_LIST', self::display_comments_list($comments));
			}
		}
		else
		{
			//TODO change lang
			throw new Exception('Vous n\'êtes pas autorisé à lire les commentaires !');
		}
	
		return self::$template;
	}
	
	public static function get_number_and_lang_comments(Comments $comments)
	{
		$number_comments = self::get_number_comments($comments);
		$lang = $number_comments > 1 ? self::$lang['com_s'] : self::$lang['com'];
		
		return !empty($number_comments) ? $lang . ' (' . $number_comments . ')' : self::$lang['post_com'];
	}
	
	/*
	 * Required Instance Comments class and setters functions module name, module id and visibility.
	*/
	public static function change_visibility(Comments $comments)
	{
		if (CommentsDAO::comments_topic_exist($comments))
		{
			CommentsDAO::change_visibility($comments);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module name.
	*/
	public static function delete_comments_module(Comments $comments)
	{
		if (CommentsDAO::comments_topic_exist($comments))
		{
			CommentsDAO::delete_all_comments_by_module_name($comments);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module name and id in module.
	*/
	public static function delete_comments_id_in_module(Comments $comments)
	{
		if (CommentsDAO::comments_topic_exist($comments))
		{
			CommentsDAO::delete_comments_id_in_module($comments);
		}
	}
	
	/*
	 * Required Instance Comments class and setter function module name, and module id.
	*/
	public static function get_number_comments(Comments $comments)
	{
		return CommentsDAO::number_comments($comments);
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
				$comment->set_name_visitor($form->get_value('name'));
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
				$comment->set_message($form->get_value('message'));
				$comment->set_module_name($comments->get_module_name());
				$comment->set_id_in_module($comments->get_id_in_module());
				CommentsDAO::add_comment($comment);
				
				//TODO change lang
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
				
				//TODO change lang
				self::$template->put('KEEP_MESSAGE', MessageHelper::display('Edit successfully', E_USER_SUCCESS, 4));
			}
			else
			{
				self::$template->put('KEEP_MESSAGE', MessageHelper::display(sprintf(self::$lang['e_l_flood'], self::$comments_configuration->get_max_links_comment()), E_USER_NOTICE, 4));
			}
		}
		return $form->display();
	}
	
	public static function display_comments_list(Comments $comments)
	{
		$template = new FileTemplate('framework/content/comments/comments_list.tpl');
		
		$page = AppContext::get_request()->get_int('page', 1);
		$nbr_comments = PersistenceContext::get_querier()->get_column_value(DB_TABLE_COMMENTS_TOPIC, 'number_comments', "WHERE module_name = :module_name AND id_in_module = :id_in_module", 
		array('module_name' =>  $comments->get_module_name(), 'id_in_module' => $comments->get_id_in_module()));
		$nbr_pages =  ceil($nbr_comments /  $comments->get_number_comments_pagination());
		$limite_page = $page > 0 ? $page : 1;
		$limite_page = (($limite_page - 1) *  $comments->get_number_comments_pagination());

		$pagination = new Pagination($nbr_pages, $page);
		
		// TODO
		$pagination->set_url_sprintf_pattern(DispatchManager::get_url('', '/%d')->absolute());
		
		$result = PersistenceContext::get_querier()->select("
			SELECT comments.*, topic.*
			FROM " . DB_TABLE_COMMENTS . " comments
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " topic ON comments.id_topic = topic.id
			WHERE topic.module_name = :module_name AND topic.id_in_module = :id_in_module
			LIMIT ".  $comments->get_number_comments_pagination() ." OFFSET :start_limit
			",
				array(
					'start_limit' => $limite_page,
					'module_name' =>  $comments->get_module_name(),
					'id_in_module' =>  $comments->get_id_in_module()
				), SelectQueryResult::FETCH_ASSOC
		);
		
		while ($row = $result->fetch())
		{
			$template->assign_block_vars('comments_list', array(
				'MESSAGE' => $row['message']
			));
		}
		
		$template->put_all(array(
			'PAGINATION' => '&nbsp;<strong>' . self::$lang['page'] . ' :</strong> ' . $pagination->export()->render(),
			'C_IS_MODERATOR' => $comments->get_authorizations()->is_authorized_moderation()
		));

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