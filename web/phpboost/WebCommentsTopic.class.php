<?php
 /**
  * @copyright   &copy; 2005-2023 PHPBoost
  * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
  * @author      Julien BRISWALTER <j1.seth@phpboost.com>
  * @version     PHPBoost 6.0 - last update: 2023 01 16
  * @since       PHPBoost 4.1 - 2014 08 21
  * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WebCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(WebItem $item = null)
	{
		parent::__construct('web');
		$this->item = $item;
	}

	protected function get_item_from_manager()
	{
		return WebService::get_item($this->get_id_in_module());
	}
}
?>
