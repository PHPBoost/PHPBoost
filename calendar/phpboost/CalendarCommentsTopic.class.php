<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 01 16
 * @since       PHPBoost 3.0 - 2011 05 06
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarCommentsTopic extends DefaultCommentsTopic
{
	public function __construct(CalendarItem $item = null)
	{
		parent::__construct('calendar');
		$this->item = $item;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_content()->get_id_category(), 'calendar')->read());
		return $authorizations;
	}

	public function is_displayed()
	{
		return true;
	}

	protected function get_item_from_manager()
	{
		return CalendarService::get_item($this->get_id_in_module());
	}
}
?>
