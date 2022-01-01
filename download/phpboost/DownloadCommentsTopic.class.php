<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 30
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadCommentsTopic extends CommentsTopic
{
	private $item;

	public function __construct(DownloadItem $item = null)
	{
		parent::__construct('download');
		$this->item = $item;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(DownloadAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->read());
		return $authorizations;
	}

	public function is_displayed()
	{
		return $this->get_item()->is_published();
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$this->item = DownloadService::get_item($this->get_id_in_module());
		}
		return $this->item;
	}
}
?>
