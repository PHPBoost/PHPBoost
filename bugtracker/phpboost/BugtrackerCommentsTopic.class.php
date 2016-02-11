<?php
/*##################################################
 *                              BugtrackerCommentsTopic.class.php
 *                            -------------------
 *   begin                : April 27, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
