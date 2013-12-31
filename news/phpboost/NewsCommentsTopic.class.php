<?php
/*##################################################
 *                           NewsCommentsTopic.class.php
 *                            -------------------
 *   begin                : May 30, 2013
 *   copyright            : (C) 2013 Kevin MASSY
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

class NewsCommentsTopic extends CommentsTopic
{
	private $news;
	
	public function __construct(News $news = null)
	{
		parent::__construct('news');
		$this->news = $news;
	}
	
	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(NewsAuthorizationsService::check_authorizations($this->get_news()->get_id_cat())->read());
		return $authorizations;
	}
	
	public function is_display()
	{
		return $this->get_news()->is_visible();
	}

	private function get_news()
	{
		if ($this->news === null)
		{
			$this->news = NewsService::get_news('WHERE id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->news;
	}
}
?>