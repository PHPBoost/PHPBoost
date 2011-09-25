<?php
/*##################################################
 *                              EditCommentBuildForm.class.php
 *                            -------------------
 *   begin                : September 25, 2011
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
class EditCommentBuildForm extends AbstractCommentsBuildForm
{
	private $id_comment = 0;
	private $user;
	private $lang;
	private $comments_lang;
	private $comments_configuration;
	
	public static function create($id_comment)
	{
		$instance = new self($id_comment);
		
		$instance->create_form();
		
		if ($instance->has_been_submited())
		{
			$instance->handle_submit();
		}
		
		return $instance;
	}
	
	public function __construct($id_comment)
	{
		$this->id_comment = $id_comment;
		$this->user = AppContext::get_user();
		$this->lang = LangLoader::get('main');
		$this->comments_lang = LangLoader::get('comments-common');
		$this->comments_configuration = CommentsConfig::load();
	}
	
	protected function create_form()
	{
		$form = new HTMLForm('comments');
		$fieldset = new FormFieldsetHTML('edit_comment', $this->lang['edit_comment']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldRichTextEditor('message', $this->lang['message'], $this->get_contents(), array(
			'formatter' => $this->get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => $this->lang['require_text']),
			array(new FormFieldConstraintMaxLinks($this->comments_configuration->get_max_links_comment()))
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
		CommentsManager::edit_comment($this->id_comment, $form->get_value('message'));
		$this->set_message_response(MessageHelper::display($this->comments_lang['comment.add.success'], E_USER_SUCCESS, 4));
	}
	
	private function get_formatter()
	{
		$formatter = AppContext::get_content_formatting_service()->create_factory();
		$formatter->set_forbidden_tags($this->comments_configuration->get_forbidden_tags());
		return $formatter;
	}
	
	private function get_contents()
	{
		$comment = CommentsCache::load()->get_comment($this->id_comment);
		return $comment['message'];
	}
}
?>