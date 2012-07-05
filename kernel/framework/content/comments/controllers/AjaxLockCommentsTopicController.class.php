<?php
/*##################################################
 *                       AjaxLockCommentsTopicController.class.php
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

class AjaxLockCommentsTopicController extends AbstractCommentsController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::execute($request);
		
		$comments_lang = LangLoader::get('comments-common');
		if ($this->get_authorizations()->is_authorized_moderation())
		{
			CommentsManager::lock_topic($this->module_id, $this->id_in_module, $this->topic_identifier);
			
			$object = array(
				'success' => true,
				'message' => $comments_lang['comment.lock.success']
			);
		}
		else
		{
			$object = array(
				'success' => false,
				'message' => $comments_lang['comment.lock.not-authorized']
			);
		}
		
		return new JSONResponse($object);
	}
}
?>