<?php
/**
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 08
 * @since       PHPBoost 3.0 - 2012 09 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CommentsTopicEvents
{
	protected ?CommentsTopic $comments_topic = null;

	public function __construct(CommentsTopic $comments_topic)
	{
		$this->comments_topic = $comments_topic;
	}

	public function execute_add_comment_event(): mixed
    {
        return null;
    }

	protected function get_comments_topic(): CommentsTopic
	{
		return $this->comments_topic;
	}
}
?>
