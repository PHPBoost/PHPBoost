<?php
/*##################################################
 *                       AjaxCommentsDisplayController.class.php
 *                            -------------------
 *   begin                : September 23, 2011
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

class AjaxCommentsDisplayController extends AbstractCommentsController
{
	public function execute(HTTPRequestCustom $request)
	{
		parent::execute($request);
		
		$view = CommentsService::display_comments($this->get_module_id(), $this->get_id_in_module(), $this->get_topic_identifier(),
		$this->get_number_comments_display(), $this->get_authorizations(), true);
		
		return new SiteNodisplayResponse($view);
	}
	
	private function get_number_comments_display()
	{
		return $this->provider->get_number_comments_display();
	}
}
?>