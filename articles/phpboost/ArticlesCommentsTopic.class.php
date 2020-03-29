<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 02
 * @since       PHPBoost 4.0 - 2013 03 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_article()->get_id_category(), 'articles')->read());
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
