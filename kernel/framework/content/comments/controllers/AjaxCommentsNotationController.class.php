<?php
/**
 * @package     Content
 * @subpackage  Comments\controllers
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2011 08 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AjaxCommentsNotationController extends AbstractCommentsController
{
	const LESS_NOTE = 'less';
	const PLUS_NOTE = 'plus';

	public function execute(HTTPRequestCustom $request)
	{
		parent::execute($request);

		$note_type = $request->get_poststring('note_type', '');
		$comment_id = $request->get_poststring('comment_id', '');
		$lang = LangLoader::get_all_langs();
		if ($this->verificate_note_type($note_type) && $this->is_access_authorizations() && !empty($comment_id))
		{
			$this->register_note($note_type, $comment_id);

			$object = array(
				'success' => true,
				'message' => $lang['comment.note.success']
			);
		}
		else if (!$this->is_access_authorizations())
		{
			$object = array(
				'success' => false,
				'message' => $lang['comment.note.unauthorized']
			);
		}
		else
		{
			$object = array(
				'success' => false,
				'message' => $lang['comment.note.error']
			);
		}

		return new JSONResponse($object);
	}

	private function register_note($note_type, $comment_id)
	{
		$comment = CommentsCache::load()->get_comment($comment_id);
		$current_note = $comment['note'];

		switch ($note_type) {
			case self::LESS_NOTE:
				$note = $current_note - 1;
			break;
			case self::PLUS_NOTE:
				$note = $current_note + 1;
			break;
		}

		$columns = array('note' => $note);
		$condition = "WHERE id = :id";
		$parameters = array('id' => $comment_id);
		PersistenceContext::get_querier()->update(DB_TABLE_COMMENTS, $columns, $condition, $parameters);

		$this->regenerate_cache();
	}

	private function verificate_note_type($type)
	{
		switch ($type) {
			case self::LESS_NOTE:
			case self::PLUS_NOTE:
				return true;
				break;
			default:
				return false;
				break;
		}
	}

	private function is_access_authorizations()
	{
		return $this->is_authorized_note() && $this->is_displayed() && $this->is_authorized_access();
	}

	private function is_authorized_note()
	{
		return $this->get_authorizations()->is_authorized_note();
	}

	private function is_authorized_access()
	{
		return $this->get_authorizations()->is_authorized_access_module();
	}

	private function regenerate_cache()
	{
		CommentsCache::invalidate();
	}
}
?>
