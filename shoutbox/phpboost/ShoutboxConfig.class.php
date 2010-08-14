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
	const MAX_MESSAGES_NUMBER = 'max_messages_number';
	const AUTHORIZATION = 'authorization';
	const FORBIDDEN_FORMATTING_TAGS = 'forbidden_formatting_tags';
	const MAX_LINKS_NUMBER_PER_MESSAGE = 'max_links_number_per_message';
	const REFRESH_DELAY = 'refresh_delay';
	
	public function get_max_messages_number()
	{
		return $this->get_property(self::MAX_MESSAGES_NUMBER);
	}
	
	public function set_max_messages_number($nbr_messages)
	{
		$this->set_property(self::MAX_MESSAGES_NUMBER, $nbr_messages);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_forbidden_formatting_tags()
	{
		return $this->get_property(self::FORBIDDEN_FORMATTING_TAGS);
	}
	
	public function set_forbidden_formatting_tags(Array $array)
	{
		$this->set_property(self::FORBIDDEN_FORMATTING_TAGS, $array);
	}
	
	public function get_max_links_number_per_message()
	{
		return $this->get_property(self::MAX_LINKS_NUMBER_PER_MESSAGE);
	}
	
	public function set_max_links_number_per_message($nbr_links)
	{
		$this->set_property(self::MAX_LINKS_NUMBER_PER_MESSAGE, $nbr_links);
	}
	
	public function get_refresh_delay()
	{
		return $this->get_property(self::REFRESH_DELAY);
	}
	
	/*
	 * Param Refresh shoutbox delay in minute
	*/
	public function set_refresh_delay($delay)
	{
		$this->set_property(self::REFRESH_DELAY, $delay);
	}
	
	public function get_default_values()
	{
		return array(
			self::MAX_MESSAGES_NUMBER => 100,
			self::AUTHORIZATION => array ( 'r-1' => 3, 'r0' => 3, '[r1]' => 3 ) ,
			self::FORBIDDEN_FORMATTING_TAGS => array(
				'title', 'style', 'url', 'img','quote',
				'hide', 'list', 'color', 'bgcolor', 'font',
				'size', 'align', 'float', 'sup', 'sub',
				'indent', 'pre',' table', 'swf', 'movie',
				'sound', 'code', 'math', 'anchor', 'acronym'
			),
			self::MAX_LINKS_NUMBER_PER_MESSAGE => 2,
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