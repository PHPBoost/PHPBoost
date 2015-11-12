<?php
/*##################################################
 *                              AddCommentBuildForm.class.php
 *                            -------------------
 *   begin                : September 25, 2011
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
 * @package {@package}
 */
class AddCommentBuildForm extends AbstractCommentsBuildForm
{
	private $user;
	private $lang;
	private $common_lang;
	private $comments_lang;
	private $comments_configuration;
	private $module_id;
	private $id_in_module;
	private $topic_identifier;
	private $topic_path;
	private $comments_topic;
	
	public static function create(CommentsTopic $comments_topic)
	{
		$instance = new self($comments_topic);
		
		$instance->create_form();
		
		if ($instance->has_been_submited())
		{
			$instance->handle_submit();
		}
		
		return $instance;
	}
	
	public function __construct(CommentsTopic $comments_topic)
	{
		$this->module_id = $comments_topic->get_module_id();
		$this->id_in_module = $comments_topic->get_id_in_module();
		$this->topic_identifier = $comments_topic->get_topic_identifier();
		$this->topic_path = $comments_topic->get_path();
		$this->comments_topic = $comments_topic;
		$this->user = AppContext::get_current_user();
		$this->lang = LangLoader::get('main');
		$this->common_lang = LangLoader::get('common');
		$this->comments_lang = LangLoader::get('comments-common');
		$this->comments_configuration = CommentsConfig::load();
	}
	
	protected function create_form()
	{
		$form = new HTMLForm('comments', TextHelper::htmlentities($this->comments_topic->get_url()) . '#comments-list');
		$fieldset = new FormFieldsetHTML('add_comment', $this->comments_lang['comment.add']);
		$form->add_fieldset($fieldset);
		
		if (!$this->user->check_level(User::MEMBER_LEVEL))
		{
			$fieldset->add_field(new FormFieldTextEditor('name', $this->common_lang['form.name'], LangLoader::get_message('visitor', 'user-common'), array('maxlength' => 25)));
		}
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', $this->lang['message'], '', array(
			'formatter' => $this->get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => $this->lang['require_text']),
			array(new FormFieldConstraintMaxLinks($this->comments_configuration->get_max_links_comment(), true),
				new FormFieldConstraintAntiFlood(CommentsManager::get_last_comment_added($this->user->get_id()))
			)
		));

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
			$id_comment = CommentsManager::add_comment($this->module_id, $this->id_in_module, $this->topic_identifier, $this->topic_path, $form->get_value('message'), $form->get_value('name'));
		}
		else
		{
			$id_comment = CommentsManager::add_comment($this->module_id, $this->id_in_module, $this->topic_identifier, $this->topic_path, $form->get_value('message'));
		}
		
		$this->comments_topic->get_events()->execute_add_comment_event();
		
		AppContext::get_response()->redirect(CommentsUrlBuilder::comment_added($this->topic_path, $id_comment));
	}
	
	private function get_formatter()
	{
		$formatter = AppContext::get_content_formatting_service()->get_default_factory();
		$formatter->set_forbidden_tags($this->comments_configuration->get_forbidden_tags());
		return $formatter;
	}
}
?>
