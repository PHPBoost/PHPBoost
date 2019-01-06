<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2014 12 22
 * @since   	PHPBoost 4.0 - 2013 03 14
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
