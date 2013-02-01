<?php
/*##################################################
 *		                   GuestbookConfig.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kevin MASSY <soldier.weasel@gmail.com>
 * @desc Configuration of the guestbook module
 */
class GuestbookConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const CAPTCHA_ENABLED = 'captcha_enabled';
	const CAPTCHA_DIFFICULTY_LEVEL = 'captcha_difficulty_level';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const MAXIMUM_LINKS_MESSAGE = 'maximum_links_message';
	const AUTHORIZATIONS = 'authorizations';
	
	const GUESTBOOK_WRITE_AUTH_BIT = 2;
	const GUESTBOOK_MODO_AUTH_BIT = 4;
	
	 /**
	 * @method Get items per page
	 */
	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}
	
	 /**
	 * @method Set items per page
	 * @params int $value Number of items to display per page
	 */
	public function set_items_per_page($value) 
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}
	
	 /**
	 * @method Enable captcha
	 */
	public function enable_captcha()
	{
		$this->set_property(self::CAPTCHA_ENABLED, true);
	}
	
	 /**
	 * @method Disable captcha
	 */
	public function disable_captcha()
	{
		$this->set_property(self::CAPTCHA_ENABLED, false);
	}
	
	 /**
	 * @method Check if the captcha is enabled
	 */
	public function is_captcha_enabled()
	{
		return $this->get_property(self::CAPTCHA_ENABLED);
	}
	
	 /**
	 * @method Get captcha difficulty level
	 */
	public function get_captcha_difficulty_level()
	{
		return $this->get_property(self::CAPTCHA_DIFFICULTY_LEVEL);
	}
	
	 /**
	 * @method Set captcha difficulty level
	 * @params int $value Difficulty level of the captcha
	 */
	public function set_captcha_difficulty_level($value) 
	{
		$this->set_property(self::CAPTCHA_DIFFICULTY_LEVEL, $value);
	}
	
	 /**
	 * @method Get forbidden tags
	 */
	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}
	
	 /**
	 * @method Set forbidden tags
	 * @params string[] $array Array of forbidden tags
	 */
	public function set_forbidden_tags(Array $array)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $array);
	}
	
	 /**
	 * @method Get maximum links number in a message
	 */
	public function get_maximum_links_message()
	{
		return $this->get_property(self::MAXIMUM_LINKS_MESSAGE);
	}
	
	 /**
	 * @method Set maximum links number in a message
	 * @params int $value Links number
	 */
	public function set_maximum_links_message($value)
	{
		$this->set_property(self::MAXIMUM_LINKS_MESSAGE, $value);
	}
	
	 /**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	 /**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	 /**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 10,
			self::CAPTCHA_ENABLED => true,
			self::CAPTCHA_DIFFICULTY_LEVEL => 2,
			self::FORBIDDEN_TAGS => array('swf', 'movie', 'sound', 'code', 'math', 'mail', 'html', 'feed'),
			self::MAXIMUM_LINKS_MESSAGE => -1,
			self::AUTHORIZATIONS => array('r-1' => 2, 'r0' => 2, 'r1' => 6)
		);
	}

	/**
	 * @method Load the guestbook configuration.
	 * @return GuestbookConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'guestbook', 'config');
	}

	/**
	 * Saves the guestbook configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('guestbook', self::load(), 'config');
	}
}
?>