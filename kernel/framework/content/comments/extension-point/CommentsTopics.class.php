<?php
/**
 * @package     Content
 * @subpackage  Comments\extension-point
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2012 05 22
*/

class CommentsTopics implements CommentsExtensionPoint
{
	private $comments_topics = array();

	public function __construct(Array $comments_topics)
	{
		if (is_array($comments_topics))
		{
			foreach ($comments_topics as $topic)
			{
				if (!$this->topic_exists($topic->get_topic_identifier()))
				{
					$this->comments_topics[$topic->get_topic_identifier()] = $topic;
				}
				else
				{
					throw new Exception($topic->get_topic_identifier() . ' already exists');
				}
			}
		}
	}

	public function get_comments_topics()
	{
		return $this->comments_topics;
	}

	public function get_comments_topic($identifier = CommentsTopic::DEFAULT_TOPIC_IDENTIFIER)
	{
		if ($this->topic_exists($identifier))
		{
			return $this->comments_topics[$identifier];
		}
		throw new Exception($identifier . ' not exists');
	}

	public function topic_exists($identifier)
	{
		return array_key_exists($identifier, $this->comments_topics);
	}
}
?>
