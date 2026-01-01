<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 06
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(?DownloadItem $item = null)
	{
		parent::__construct('download');
		$this->item = $item;
	}

	protected function get_item_from_manager()
	{
		return DownloadService::get_item($this->get_id_in_module());
	}
}
?>
