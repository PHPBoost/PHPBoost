<?php
/*##################################################
 *		                   GuestbookConfig.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class GuestbookConfig extends AbstractConfigData
{
	const DISPLAY_CAPTCHA = 'display_captcha';
	const CAPTCHA_DIFFICULTY = 'captcha_difficulty';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const MAXIMUM_LINKS_MESSAGE = 'maximum_links_message';
	const AUTHORIZATION = 'authorizations';
	
	const AUTH_READ = 1;
	const AUTH_WRITE= 2;
	const AUTH_MODO = 4;
	
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
	
	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}

	public function set_forbidden_tags(Array $tags)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $tags);
	}
	
	public function get_maximum_links_message()
	{
		return $this->get_property(self::MAXIMUM_LINKS_MESSAGE);
	}

	public function set_maximum_links_message($nbr_links)
	{
		$this->set_property(self::MAXIMUM_LINKS_MESSAGE, $nbr_links);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATION);
	}

	public function set_authorizations(Array $auth)
	{
		$this->set_property(self::AUTHORIZATION, $auth);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::DISPLAY_CAPTCHA => 1,
			self::CAPTCHA_DIFFICULTY => 2,
			self::FORBIDDEN_TAGS => array('swf', 'movie', 'sound', 'code', 'math', 'mail', 'html', 'feed'),
			self::MAXIMUM_LINKS_MESSAGE => -1,
			self::AUTHORIZATION => array('r-1' => 3, 'r0' => 3, 'r1' => 7) 
		);
	}

	/**
	 * Returns the configuration.
	 * @return GuestbookConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'module', 'guestbook-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('module', self::load(), 'guestbook-config');
	}
}
?>