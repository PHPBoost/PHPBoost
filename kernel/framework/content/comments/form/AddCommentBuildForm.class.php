<?php
/*##################################################
 *                              AddCommentBuildForm.class.php
 *                            -------------------
 *   begin                : September 25, 2011
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
class AddCommentBuildForm extends AbstractCommentsBuildForm
{
	private $user;
	private $lang;
	private $comments_lang;
	private $comments_configuration;
	private $module_id;
	private $id_in_module;
	private $topic_identifier;
	private $topic_path;
	
	public static function create(CommentsTopic $topic)
	{
		$instance = new self(
			$topic->get_module_id(), 
			$topic->get_id_in_module(), 
			$topic->get_topic_identifier(), 
			$topic->get_path()
		);
		
		$instance->create_form();
		
		if ($instance->has_been_submited())
		{
			$instance->handle_submit();
		}
		
		return $instance;
	}
	
	public function __construct($module_id, $id_in_module, $topic_identifier, $topic_path)
	{
		$this->module_id = $module_id;
		$this->id_in_module = $id_in_module;
		$this->topic_identifier = $topic_identifier;
		$this->topic_path = $topic_path;
		$this->user = AppContext::get_current_user();
		$this->lang = LangLoader::get('main');
		$this->comments_lang = LangLoader::get('comments-common');
		$this->comments_configuration = CommentsConfig::load();
	}
	
	protected function create_form()
	{
		$form = new HTMLForm('comments', REWRITED_SCRIPT . '#comments_list');
		$fieldset = new FormFieldsetHTML('add_comment', $this->comments_lang['comment.add']);
		$form->add_fieldset($fieldset);
		
		if (!$this->user->check_level(User::MEMBER_LEVEL))
		{
			$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['pseudo'], $this->lang['guest'], array('maxlength' => 25)));
		}
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', $this->lang['message'], '', array(
			'formatter' => $this->get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => $this->lang['require_text']),
			array(new FormFieldConstraintMaxLinks($this->comments_configuration->get_max_links_comment()),
				new FormFieldConstraintAntiFlood(CommentsManager::get_last_comment_added($this->user->get_id()))
			)
		));
		
		if ($this->comments_configuration->get_display_captcha() && !$this->user->check_level(User::MEMBER_LEVEL))
		{
			$fieldset->add_field(new FormFieldCaptcha('captcha', $this->get_captcha()));
		}

		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());
		
		$this->set_form($form);
		$this->set_submit_button($submit_button);

		return $form;
	}
	
	protected function handle_submit()
	{
		$form = $this->get_form();
		if ($form->has_field('name'))
		{
			CommentsManager::add_comment($this->module_id, $this->id_in_module, $this->topic_identifier, $this->topic_path, $form->get_value('message'), $form->get_value('name'));
		}
		else
		{
			CommentsManager::add_comment($this->module_id, $this->id_in_module, $this->topic_identifier, $this->topic_path, $form->get_value('message'));
		}
		
		$this->set_message_response(MessageHelper::display($this->comments_lang['comment.add.success'], MessageHelper::SUCCESS, 4));
	}
	
	private function get_formatter()
	{
		$formatter = AppContext::get_content_formatting_service()->create_factory();
		$formatter->set_forbidden_tags($this->comments_configuration->get_forbidden_tags());
		return $formatter;
	}
	
	private function get_captcha()
	{
		$captcha = new Captcha();
		$captcha->set_difficulty($this->comments_configuration->get_captcha_difficulty());
		return $captcha;
	}
}
?>