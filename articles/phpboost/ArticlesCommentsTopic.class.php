<?php
/*##################################################
 *                    ArticlesCommentsTopic.class.php
 *                            -------------------
 *   begin                : March 14, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesCommentsTopic extends CommentsTopic
{
	private $article;
	
	public function __construct(Article $article = null)
	{
		parent::__construct('articles');
		$this->article = $article;
	}
	
	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(ArticlesAuthorizationsService::check_authorizations($this->get_article()->get_id_category())->read());
		return $authorizations;
	}
	
	public function is_display()
	{
		return $this->get_article()->is_published();
	}
	
	private function get_article()
	{
		if ($this->article === null)
		{
			$this->article = ArticlesService::get_article('WHERE articles.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->article;
	}
}
?>