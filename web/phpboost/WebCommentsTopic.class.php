<?php
 /**
  * @copyright   &copy; 2005-2020 PHPBoost
  * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
  * @author      Julien BRISWALTER <j1.seth@phpboost.com>
  * @version     PHPBoost 5.3 - last update: 2019 11 09
  * @since       PHPBoost 4.1 - 2014 08 21
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
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_weblink()->get_id_category(), 'web')->read());
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
