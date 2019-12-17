<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 04
 * @since       PHPBoost 4.0 - 2013 05 30
*/

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
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_news()->get_id_cat(), 'news')->read());
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
