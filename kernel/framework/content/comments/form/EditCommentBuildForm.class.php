<?php
/**
 * @package     Content
 * @subpackage  Comments\form
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 06
 * @since       PHPBoost 3.0 - 2011 09 25
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class EditCommentBuildForm extends AbstractCommentsBuildForm
{
	private int $id_comment = 0;
	private mixed $user = null;
	private mixed $comments_configuration = null;
	private mixed $topic_path = null;

	public static function create(int $id_comment, mixed $topic_path): mixed
	{
		$instance = new self($id_comment, $topic_path);

		$instance->create_form();

		if ($instance->has_been_submited())
		{
			$instance->handle_submit();
		}

		return $instance;
	}

	public function __construct(int $id_comment, string $topic_path)
	{
		$this->id_comment = $id_comment;
		$this->user = AppContext::get_current_user();
		$this->topic_path = $topic_path;
		$this->comments_configuration = CommentsConfig::load();
	}

	protected function create_form(): HTMLForm
	{
		$lang = LangLoader::get_all_langs();

		$form = new HTMLForm('comments', REWRITED_SCRIPT . '#comments-list');
		$fieldset = new FormFieldsetHTML('edit_comment', $lang['comment.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldRichTextEditor('message', $lang['form.content'], $this->get_contents(), [
			'formatter' => $this->get_formatter(),
			'rows' => 10, 'cols' => 47, 'required' => true],
			[(!$this->user->is_moderator() && !$this->user->is_admin() ? new FormFieldConstraintMaxLinks($this->comments_configuration->get_max_links_comment()) : '')]
		));

		$form->add_button($submit_button = new FormButtonDefaultSubmit());
		$form->add_button(new FormButtonReset());

		$this->set_form($form);
		$this->set_submit_button($submit_button);

		return $form;
	}

	protected function handle_submit(): void
	{
		$form = $this->get_form();
		CommentsManager::edit_comment($this->id_comment, $form->get_value('message'));
		AppContext::get_response()->redirect(CommentsUrlBuilder::comment_added($this->topic_path, $this->id_comment));
	}

	private function get_formatter(): mixed
	{
		$formatter = AppContext::get_content_formatting_service()->get_default_factory();
		$formatter->set_forbidden_tags($this->comments_configuration->get_forbidden_tags());
		return $formatter;
	}

	private function get_contents(): mixed
	{
		$comment = CommentsCache::load()->get_comment($this->id_comment);
		return $comment['message'];
	}
}
?>
