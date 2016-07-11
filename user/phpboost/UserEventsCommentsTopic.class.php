<?php
/*##################################################
 *                           UserEventsCommentsTopic.class.php
 *                            -------------------
 *   begin                : September 30, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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
	public function is_display()
	{
		return true;
	}
}
?>