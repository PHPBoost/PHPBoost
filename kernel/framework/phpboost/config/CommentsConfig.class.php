<?php
/*##################################################
 *		             CommentsConfig.class.php
 *                            -------------------
 *   begin                : July 8, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class CommentsConfig extends AbstractConfigData
{
	const AUTH_POST_COMMENTS = 'auth_post_comments';
	const DISPLAY_COMMENTS_IN_POPUP = 'display_comments_popup';
	const DISPLAY_CAPTCHA = 'display_captcha';
	const CAPTCHA_DIFFICULTY = 'captcha_difficulty';
	const NUMBER_COMMENTS_PER_PAGE = 'number_comments_per_page';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const MAX_LINKS_COMMENT = 'max_links_comment';
	
	public function get_auth_post_comments()
	{
		return $this->get_property(self::AUTH_POST_COMMENTS);
	}
	
	public function set_auth_post_comments(Array $array)
	{
		$this->set_property(self::AUTH_POST_COMMENTS, $array);
	}
	
	public function get_display_comments_in_popup()
	{
		return $this->get_property(self::DISPLAY_COMMENTS_IN_POPUP);
	}
	
	public function set_display_comments_in_popup($display)
	{
		$this->set_property(self::DISPLAY_COMMENTS_IN_POPUP, $display);
	}
	
	public function get_display_captcha()
	{
		return $this->get_property(self::DISPLAY_CAPTCHA);
	}
	
	public function set_display_captcha($display)
	{
		$this->set_property(self::DISPLAY_CAPTCHA, $display);
	}
	
	public function get_captcha_difficulty()
	{
		return $this->get_property(self::CAPTCHA_DIFFICULTY);
	}
	
	public function set_captcha_difficulty($difficulty)
	{
		$this->set_property(self::CAPTCHA_DIFFICULTY, $difficulty);
	}
	
	public function get_number_comments_per_page()
	{
		return $this->get_property(self::NUMBER_COMMENTS_PER_PAGE);
	}
	
	public function set_number_comments_per_page($number)
	{
		$this->set_property(self::NUMBER_COMMENTS_PER_PAGE, $number);
	}
	
	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}
	
	public function set_forbidden_tags(array $forbidden_tags)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $forbidden_tags);
	}
	
	public function get_max_links_comment()
	{
		return $this->get_property(self::MAX_LINKS_COMMENT);
	}
	
	public function set_max_links_comment($number)
	{
		$this->set_property(self::MAX_LINKS_COMMENT, $number);
	}
	
	public function get_default_values()
	{
		$server_configuration = new ServerConfiguration();
		
		return array(
			self::AUTH_POST_COMMENTS => array('r-1' => '1', 'r0' => '1', 'r1' => '1'),
			self::DISPLAY_COMMENTS_IN_POPUP => false,
			self::DISPLAY_CAPTCHA => $server_configuration->has_gd_libray() ? true : false,
			self::CAPTCHA_DIFFICULTY => '2',
			self::NUMBER_COMMENTS_PER_PAGE => '10',
			self::FORBIDDEN_TAGS => array(),
			self::MAX_LINKS_COMMENT => '2',
			
		);
	}

	/**
	 * Returns the configuration.
	 * @return CommentsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'comments-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'comments-config');
	}
}
?>