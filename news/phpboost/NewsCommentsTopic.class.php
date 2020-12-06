<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 06
 * @since       PHPBoost 4.0 - 2013 05 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsCommentsTopic extends CommentsTopic
{
	private $item;

	public function __construct(News $item = null)
	{
		parent::__construct('news');
		$this->item = $item;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_news()->get_id_category(), 'news')->read());
		return $authorizations;
	}

	public function is_display()
	{
		return $this->get_news()->is_published();
	}

	private function get_news()
	{
		if ($this->item === null)
		{
			$this->item = NewsService::get_news('WHERE id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->item;
	}
}
?>
