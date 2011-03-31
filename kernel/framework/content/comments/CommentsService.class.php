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
	/*
	 * DataBase Fields :
	 * Comments => id, module_name, module_id, number_comments, is_locked
	 * Comment_posted => id, user_id, name_visitor, ip_visitor, note
	 *
	*/
	
	private static $user;
	private static $lang;
	private static $comments_configuration;
	
	public static function __static()
	{
		self::$user = AppContext::get_user();
		self::$lang = LangLoader::get('main');
		self::$comments_configuration = CommentsConfig::load();
	}
	
	public static function display(Comments $comments)
	{
		$template = new FileTemplate('framework/content/com.tpl');
		
		
		
		return $template->render();
	}
	
	public static function delete_all_comments_by_module_name($name)
	{
		CommentsDAO::delete_all_comments_by_module_name($name);
	}
	
	private static function add_comment_form()
	{
		$is_visitor = !$User->check_level(MEMBER_LEVEL);
		$form = new HTMLForm('comments');
		$fieldset = new FormFieldsetHTML('add_comment', self::$lang['add_comment']);
		$form->add_fieldset($fieldset);
		
		if ($is_visitor)
		{
			$fieldset->add_field(new FormFieldTextEditor('name', self::$lang['guest'], array(
				'title' => self::$lang['pseudo'], 'required' => self::$lang['require_pseudo'],
				'maxlength' => 25)
			));
		}
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', self::$lang['message'], '', array(
			'formatter' => self::get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => self::$lang['require_text'])
		));
		if (self::$comments_configuration->get_display_captcha()) //Code de vérification, anti-bots.
		{
			$fieldset->add_field(new FormFieldCaptcha('captcha', self::get_captcha()));
		}

		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		if ($submit_button->has_been_submited() && $form->validate())
		{
			CommentsDAO::add_comment();
			$tpl->put('KEEP_MESSAGE', MessageHelper::display('Posted successfully', E_USER_SUCCESS, 4));
		}
	}
	
	private static function update_comment_form()
	{
		
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