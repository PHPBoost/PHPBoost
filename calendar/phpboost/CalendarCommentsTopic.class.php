<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 11
 * @since       PHPBoost 3.0 - 2011 05 06
*/

class CalendarCommentsTopic extends CommentsTopic
{
	private $event;

	public function __construct(CalendarEvent $event = null)
	{
		parent::__construct('calendar');
		$this->event = $event;
	}

	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(CategoriesAuthorizationsService::check_authorizations($this->get_event()->get_content()->get_category_id(), 'calendar')->read());
		return $authorizations;
	}

	public function is_display()
	{
		return true;
	}

	private function get_event()
	{
		if ($this->event === null)
		{
			$this->event = CalendarService::get_event('WHERE id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->event;
	}
}
?>
