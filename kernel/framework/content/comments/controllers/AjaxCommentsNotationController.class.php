<?php
/*##################################################
 *                       AjaxCommentsNotation.class.php
 *                            -------------------
 *   begin                : August 30, 2011
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

class AjaxCommentsNotationController extends AbstractCommentsController
{
	const LESS_NOTE = 'less';
	const PLUS_NOTE = 'plus';
	
	public function execute(HTTPRequestCustom $request)
	{
		parent::execute($request);
		
		$note_type = $request->get_poststring('note_type', '');
		$comment_id = $request->get_poststring('comment_id', '');
		$comments_lang = LangLoader::get('comments-common');
		if ($this->verificate_note_type($note_type) && $this->is_access_authorizations() && !empty($comment_id))
		{
			$this->register_note($note_type, $comment_id);
			
			$object = array(
				'success' => true,
				'message' => $comments_lang['comment.note.success']
			);
		}
		else if (!$this->is_access_authorizations())
		{
			$object = array(
				'success' => false,
				'message' => $comments_lang['comments.not-authorized.note']
			);
		}
		else
		{
			$object = array(
				'success' => false,
				'message' => $comments_lang['comment.note.error']
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
		return $this->is_authorized_note() && $this->is_display() && $this->is_authorized_access();
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