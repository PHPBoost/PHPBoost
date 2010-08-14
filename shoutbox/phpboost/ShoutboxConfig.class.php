<?php
/*##################################################
 *		             ShoutboxConfig.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class ShoutboxConfig extends AbstractConfigData
{
	const MAX_MESSAGES = 'max_messages';
	const AUTHORIZATION = 'authorization';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const MAX_LINKS = 'max_links';
	const REFRESH_DELAY = 'refresh_delay';
	
	public function get_max_messages()
	{
		return $this->get_property(self::MAX_MESSAGES);
	}
	
	public function set_max_messages($nbr_messages)
	{
		$this->set_property(self::MAX_MESSAGES, $nbr_messages);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}
	
	public function set_forbidden_tags(Array $array)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $array);
	}
	
	public function get_max_links()
	{
		return $this->get_property(self::MAX_LINKS);
	}
	
	public function set_max_links($nbr_links)
	{
		$this->set_property(self::MAX_LINKS, $nbr_links);
	}
	
	public function get_refresh_delay()
	{
		return $this->get_property(self::REFRESH_DELAY);
	}
	
	public function set_refresh_delay($delay)
	{
		$this->set_property(self::REFRESH_DELAY, $$delay);
	}
	
	public function get_default_values()
	{
		return array(
			self::MAX_MESSAGES => 100,
			self::AUTHORIZATION => array ( 'r-1' => 3, 'r0' => 3, '[r1]' => 3 ) ,
			self::FORBIDDEN_TAGS => array(
				'title', 'style', 'url', 'img','quote',
				'hide', 'list', 'color', 'bgcolor', 'font',
				'size', 'align', 'float', 'sup', 'sub',
				'indent', 'pre',' table', 'swf', 'movie',
				'sound', 'code', 'math', 'anchor', 'acronym'
			),
			self::MAX_LINKS => 2,
			self::REFRESH_DELAY => 60000
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ShoutboxConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'main', 'shoutbox');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('main', self::load(), 'shoutbox');
	}
}
?>