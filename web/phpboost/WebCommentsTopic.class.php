<?php
/*##################################################
 *                               WebCommentsTopic.class.php
 *                            -------------------
 *   begin                : August 21, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class WebCommentsTopic extends CommentsTopic
{
	private $weblink;
	
	public function __construct(WebLink $weblink = null)
	{
		parent::__construct('web');
		$this->weblink = $weblink;
	}
	
	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(WebAuthorizationsService::check_authorizations($this->get_weblink()->get_id_category())->read());
		return $authorizations;
	}
	
	public function is_display()
	{
		return $this->get_weblink()->is_visible();
	}
	
	private function get_weblink()
	{
		if ($this->weblink === null)
		{
			$this->weblink = WebService::get_weblink('WHERE web.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->weblink;
	}
}
?>
