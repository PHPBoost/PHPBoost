<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 03 15
 * @since       PHPBoost 3.0 - 2011 09 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UserEventsCommentsTopic extends CommentsTopic
{
	const TOPIC_IDENTIFIER = 'events';

	public function __construct()
	{
		parent::__construct('user', self::TOPIC_IDENTIFIER);
	}

	/**
	 * @method Get comments events
	 */
	public function get_events()
	{
		return new UserEventsCommentsTopicEvents($this);
	}

	/**
	 * @method Display comments
	 */
	public function is_displayed()
	{
		return true;
	}
}
?>
