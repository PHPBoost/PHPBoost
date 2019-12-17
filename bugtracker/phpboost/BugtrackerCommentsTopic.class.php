<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 04 27
*/

class BugtrackerCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('bugtracker');
	}

	 /**
	 * @method Get comments authorizations
	 */
	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(BugtrackerAuthorizationsService::check_authorizations()->read());
		return $authorizations;
	}

	 /**
	 * @method Get comments events
	 */
	public function get_events()
	{
		return new BugtrackerCommentsTopicEvents($this);
	}

	 /**
	 * @method Display comments
	 */
	public function is_display()
	{
		return true;
	}
}
?>
