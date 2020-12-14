<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 06 30
 * @since       PHPBoost 3.0 - 2012 04 25
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PagesCommentsTopic extends CommentsTopic
{
	private $item;

	public function __construct(PagesItem $item = null)
	{
		parent::__construct('pages');
		$this->item = $item;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->read());
		return $authorizations;
	}

	public function is_display()
	{
		return $this->get_item()->is_published();
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$this->item = PagesService::get_item('WHERE pages.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->item;
	}
}
?>
