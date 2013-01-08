<?php
/*##################################################
 *                           CommentsTopics.class.php
 *                            -------------------
 *   begin                : May 22, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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