<?php
/*##################################################
 *                              CommentsTopic.class.php
 *                            -------------------
 *   begin                : March 31, 2011
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class represents the comments topic
 * @abstract Do not use this class, but one of its children like for your module
 * @package {@package}
 */
class CommentsTopic
{
	protected $module_id;
	protected $topic_identifier = '';
	protected $id_in_module;
	protected $url;
	
	const DEFAULT_TOPIC_IDENTIFIER = 'default';
	
	public function __construct($module_id, $topic_identifier = self::DEFAULT_TOPIC_IDENTIFIER)
	{
		$this->module_id = $module_id;
		$this->topic_identifier = $topic_identifier;
	}
	
	/**
	 * @return class CommentsAuthorizations
	 */
	public function get_authorizations()
	{
		return new CommentsAuthorizations();
	}
	
	/**
	 * @return boolean display
	 */
	public function is_display()
	{
		return false;
	}
	
	/**
	 * @return int number comments display default
	 */
	public function get_number_comments_display()
	{
		return CommentsConfig::load()->get_number_comments_display();
	}
	
	/**
	 * @return class CommentsTopicEvents
	 */
	public function get_events()
	{
		return new CommentsTopicEvents($this);
	}
	
	public function display()
	{
		return CommentsService::display($this);
	}
		
	public function get_topic_identifier()
	{
		return $this->topic_identifier;
	}

	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function set_id_in_module($id_in_module)
	{
		$this->id_in_module = $id_in_module;
	}
	
	public function get_id_in_module()
	{
		return $this->id_in_module;
	}
	
	public function set_url(Url $url)
	{
		$this->url = $url;
	}
	
	public function get_url()
	{
		return $this->url->rel();
	}
	
	public function get_path()
	{
		return $this->url->relative();
	}
}
?>