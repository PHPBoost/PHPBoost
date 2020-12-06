<?php
 /**
  * @copyright   &copy; 2005-2020 PHPBoost
  * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
  * @author      Julien BRISWALTER <j1.seth@phpboost.com>
  * @version     PHPBoost 6.0 - last update: 2020 12 06
  * @since       PHPBoost 4.1 - 2014 08 21
  * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WebCommentsTopic extends CommentsTopic
{
	private $item;

	public function __construct(WebLink $item = null)
	{
		parent::__construct('web');
		$this->item = $item;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_weblink()->get_id_category(), 'web')->read());
		return $authorizations;
	}

	public function is_display()
	{
		return $this->get_weblink()->is_published();
	}

	private function get_weblink()
	{
		if ($this->item === null)
		{
			$this->item = WebService::get_weblink('WHERE web.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->item;
	}
}
?>
