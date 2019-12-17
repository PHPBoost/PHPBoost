<?php
/**
 * @package     Content
 * @subpackage  Comments
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 09 20
*/

class CommentsTopicEvents
{
	protected $comments_topic;

	public function __construct(CommentsTopic $comments_topic)
	{
		$this->comments_topic = $comments_topic;
	}

	public function execute_add_comment_event()	{}

	protected function get_comments_topic()
	{
		return $this->comments_topic;
	}
}
?>
