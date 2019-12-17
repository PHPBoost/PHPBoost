<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 4.0 - 2014 08 24
*/

class DownloadCommentsTopic extends CommentsTopic
{
	private $downloadfile;

	public function __construct(DownloadFile $downloadfile = null)
	{
		parent::__construct('download');
		$this->downloadfile = $downloadfile;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(DownloadAuthorizationsService::check_authorizations($this->get_downloadfile()->get_id_category())->read());
		return $authorizations;
	}

	public function is_display()
	{
		return $this->get_downloadfile()->is_visible();
	}

	private function get_downloadfile()
	{
		if ($this->downloadfile === null)
		{
			$this->downloadfile = DownloadService::get_downloadfile('WHERE download.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->downloadfile;
	}
}
?>
