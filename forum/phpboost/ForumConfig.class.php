<?php
/*##################################################
 *		                  ForumConfig.class.php
 *                            -------------------
 *   begin                : September 11, 2014
 *   copyright            : (C) 2014 j1.seth
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

class ForumConfig extends AbstractConfigData
{
	const FORUM_NAME = 'forum_name';
	const NUMBER_TOPICS_PER_PAGE = 'number_topics_per_page';
	const NUMBER_MESSAGES_PER_PAGE = 'number_messages_per_page';
	const READ_MESSAGES_STORAGE_DURATION = 'read_messages_storage_duration';
	const MAX_TOPIC_NUMBER_IN_FAVORITE = 'max_topic_number_in_favorite';
	const EDIT_MARK_ENABLED = 'edit_mark_enabled';
	const MULTIPLE_POSTS_ALLOWED = 'multiple_posts_allowed';
	const CONNEXION_FORM_DISPLAYED = 'connexion_form_displayed';
	const LEFT_COLUMN_DISABLED = 'left_column_disabled';
	const RIGHT_COLUMN_DISABLED = 'right_column_disabled';
	const MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED = 'message_before_topic_title_displayed';
	const MESSAGE_BEFORE_TOPIC_TITLE = 'message_before_topic_title';
	const MESSAGE_WHEN_TOPIC_IS_UNSOLVED = 'message_when_topic_is_unsolved';
	const MESSAGE_WHEN_TOPIC_IS_SOLVED = 'message_when_topic_is_solved';
	const MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED = 'message_before_topic_title_icon_displayed';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_forum_name()
	{
		return $this->get_property(self::FORUM_NAME);
	}
	
	public function set_forum_name($value) 
	{
		$this->set_property(self::FORUM_NAME, $value);
	}
	
	public function get_number_topics_per_page()
	{
		return $this->get_property(self::NUMBER_TOPICS_PER_PAGE);
	}
	
	public function set_number_topics_per_page($value) 
	{
		$this->set_property(self::NUMBER_TOPICS_PER_PAGE, $value);
	}
	
	public function get_number_messages_per_page()
	{
		return $this->get_property(self::NUMBER_MESSAGES_PER_PAGE);
	}
	
	public function set_number_messages_per_page($value) 
	{
		$this->set_property(self::NUMBER_MESSAGES_PER_PAGE, $value);
	}
	
	public function get_read_messages_storage_duration()
	{
		return $this->get_property(self::READ_MESSAGES_STORAGE_DURATION);
	}
	
	public function set_read_messages_storage_duration($value) 
	{
		$this->set_property(self::READ_MESSAGES_STORAGE_DURATION, $value);
	}
	
	public function get_max_topic_number_in_favorite()
	{
		return $this->get_property(self::MAX_TOPIC_NUMBER_IN_FAVORITE);
	}
	
	public function set_max_topic_number_in_favorite($value) 
	{
		$this->set_property(self::MAX_TOPIC_NUMBER_IN_FAVORITE, $value);
	}
	
	public function enable_edit_mark()
	{
		$this->set_property(self::EDIT_MARK_ENABLED, true);
	}
	
	public function disable_edit_mark()
	{
		$this->set_property(self::EDIT_MARK_ENABLED, false);
	}
	
	public function is_edit_mark_enabled()
	{
		return $this->get_property(self::EDIT_MARK_ENABLED);
	}
	
	public function allow_multiple_posts()
	{
		$this->set_property(self::MULTIPLE_POSTS_ALLOWED, true);
	}
	
	public function forbid_multiple_posts()
	{
		$this->set_property(self::MULTIPLE_POSTS_ALLOWED, false);
	}
	
	public function are_multiple_posts_allowed()
	{
		return $this->get_property(self::MULTIPLE_POSTS_ALLOWED);
	}
	
	public function display_connexion_form()
	{
		$this->set_property(self::CONNEXION_FORM_DISPLAYED, true);
	}
	
	public function hide_connexion_form()
	{
		$this->set_property(self::CONNEXION_FORM_DISPLAYED, false);
	}
	
	public function is_connexion_form_displayed()
	{
		return $this->get_property(self::CONNEXION_FORM_DISPLAYED);
	}
	
	public function disable_left_column()
	{
		$this->set_property(self::LEFT_COLUMN_DISABLED, true);
	}
	
	public function enable_left_column()
	{
		$this->set_property(self::LEFT_COLUMN_DISABLED, false);
	}
	
	public function is_left_column_disabled()
	{
		return $this->get_property(self::LEFT_COLUMN_DISABLED);
	}
	
	public function disable_right_column()
	{
		$this->set_property(self::RIGHT_COLUMN_DISABLED, true);
	}
	
	public function enable_right_column()
	{
		$this->set_property(self::RIGHT_COLUMN_DISABLED, false);
	}
	
	public function is_right_column_disabled()
	{
		return $this->get_property(self::RIGHT_COLUMN_DISABLED);
	}
	
	public function display_message_before_topic_title()
	{
		$this->set_property(self::MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED, true);
	}
	
	public function hide_message_before_topic_title()
	{
		$this->set_property(self::MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED, false);
	}
	
	public function is_message_before_topic_title_displayed()
	{
		return $this->get_property(self::MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED);
	}
	
	public function get_message_before_topic_title()
	{
		return $this->get_property(self::MESSAGE_BEFORE_TOPIC_TITLE);
	}
	
	public function set_message_before_topic_title($value) 
	{
		$this->set_property(self::MESSAGE_BEFORE_TOPIC_TITLE, $value);
	}
	
	public function get_message_when_topic_is_unsolved()
	{
		return $this->get_property(self::MESSAGE_WHEN_TOPIC_IS_UNSOLVED);
	}
	
	public function set_message_when_topic_is_unsolved($value) 
	{
		$this->set_property(self::MESSAGE_WHEN_TOPIC_IS_UNSOLVED, $value);
	}
	
	public function get_message_when_topic_is_solved()
	{
		return $this->get_property(self::MESSAGE_WHEN_TOPIC_IS_SOLVED);
	}
	
	public function set_message_when_topic_is_solved($value) 
	{
		$this->set_property(self::MESSAGE_WHEN_TOPIC_IS_SOLVED, $value);
	}
	
	public function display_message_before_topic_title_icon()
	{
		$this->set_property(self::MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED, true);
	}
	
	public function hide_message_before_topic_title_icon()
	{
		$this->set_property(self::MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED, false);
	}
	
	public function is_message_before_topic_title_icon_displayed()
	{
		return $this->get_property(self::MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::FORUM_NAME => LangLoader::get_message('default.forum_name', 'config', 'forum'),
			self::NUMBER_TOPICS_PER_PAGE => 20,
			self::NUMBER_MESSAGES_PER_PAGE => 15,
			self::READ_MESSAGES_STORAGE_DURATION => 30,
			self::MAX_TOPIC_NUMBER_IN_FAVORITE => 40,
			self::EDIT_MARK_ENABLED => true,
			self::MULTIPLE_POSTS_ALLOWED => true,
			self::CONNEXION_FORM_DISPLAYED => false,
			self::LEFT_COLUMN_DISABLED => false,
			self::RIGHT_COLUMN_DISABLED => false,
			self::MESSAGE_BEFORE_TOPIC_TITLE_DISPLAYED => true,
			self::MESSAGE_BEFORE_TOPIC_TITLE => LangLoader::get_message('default.message_before_topic_title', 'config', 'forum'),
			self::MESSAGE_WHEN_TOPIC_IS_UNSOLVED => LangLoader::get_message('default.message_when_topic_is_unsolved', 'config', 'forum'),
			self::MESSAGE_WHEN_TOPIC_IS_SOLVED => LangLoader::get_message('default.message_when_topic_is_solved', 'config', 'forum'),
			self::MESSAGE_BEFORE_TOPIC_TITLE_ICON_DISPLAYED => true,
			self::AUTHORIZATIONS => array('r-1' => 129, 'r0' => 131, 'r1' => 139)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ForumConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'forum', 'config');
	}
	
	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('forum', self::load(), 'config');
	}
}
?>
